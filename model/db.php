<?php

// ---------- Configuration BDD ----------
$configFile = __DIR__ . '/../config.ini';

if (!is_readable($configFile)) {
    die('Fichier config.ini introuvable.');
}

$ini = parse_ini_file($configFile, true, INI_SCANNER_TYPED);
$config = $ini['database'] ?? null;

if (!$config) {
    die('Configuration BDD invalide.');
}

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4",
        $config['user'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT            => 5,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Erreur BDD : ' . $e->getMessage());
}

// ---------- Utilisateurs ----------
function getUserByUsername(PDO $pdo, string $username)
{
    $stmt = $pdo->prepare('SELECT id, username, password_hash, role_id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    return $stmt->fetch();   // false si introuvable
}

function createUser(PDO $pdo, string $username, string $password, string $roleId = 'USR')
{
    $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, role_id) VALUES (?, ?, ?)');
    $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $roleId]);
}

// ---------- Machines ----------
// Une machine est "en ligne" si son dernier inventaire date de moins de ONLINE_MINUTES.
// Le collecteur passe toutes les 30 min : on laisse 15 min de marge.
const ONLINE_MINUTES = 45;

function getMachines(PDO $pdo, array $filters = [])
{
    // Adaptation à la BDD : 
    // - os_name -> os
    // - cpu_name -> cpu
    // - ram_total_gb multiplié par 1024 -> ram_mb
    // - Récupération de la première adresse IP depuis network_adapters
    $sql = 'SELECT m.id, m.hostname, m.os_name AS os, m.cpu_name AS cpu, (m.ram_total_gb * 1024) AS ram_mb,
                   m.serial_number, m.domain, m.last_seen,
                   g.name AS groupe,
                   (SELECT ipv4_address FROM network_adapters WHERE machine_id = m.id LIMIT 1) AS ip,
                   (m.last_seen > NOW() - INTERVAL ' . ONLINE_MINUTES . ' MINUTE) AS en_ligne
            FROM machines m
            LEFT JOIN machine_groups g ON g.id = m.group_id
            WHERE 1=1';
    $params = [];

    // Filtre de recherche (hostname ou ip)
    if (!empty($filters['search'])) {
        $sql .= ' AND (m.hostname LIKE ? OR (SELECT ipv4_address FROM network_adapters WHERE machine_id = m.id LIMIT 1) LIKE ?)';
        $params[] = '%' . $filters['search'] . '%';
        $params[] = '%' . $filters['search'] . '%';
    }
    // Filtre par OS
    if (!empty($filters['os'])) {
        $sql .= ' AND m.os_name LIKE ?';
        $params[] = '%' . $filters['os'] . '%';
    }
    // Filtre par Groupe
    if (!empty($filters['groupe'])) {
        $sql .= ' AND g.id = ?';
        $params[] = $filters['groupe'];
    }
    // Filtre par Statut
    if (!empty($filters['statut'])) {
        if ($filters['statut'] === 'online') {
            $sql .= ' AND m.last_seen > NOW() - INTERVAL ' . ONLINE_MINUTES . ' MINUTE';
        } elseif ($filters['statut'] === 'offline') {
            $sql .= ' AND (m.last_seen <= NOW() - INTERVAL ' . ONLINE_MINUTES . ' MINUTE OR m.last_seen IS NULL)';
        }
    }

    $sql .= ' ORDER BY m.hostname';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Dynamisation du menu déroulant des OS
function getDistinctOS(PDO $pdo) {
    return $pdo->query('SELECT DISTINCT os_name FROM machines WHERE os_name IS NOT NULL AND os_name != "" ORDER BY os_name')->fetchAll(PDO::FETCH_COLUMN);
}

// ---------- Groupes ----------
function getGroupes(PDO $pdo)
{
    $sql = 'SELECT g.id, g.name AS nom, COUNT(m.id) AS count
            FROM machine_groups g
            LEFT JOIN machines m ON m.group_id = g.id
            GROUP BY g.id ORDER BY g.name';
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function renameGroupe(PDO $pdo, int $id, string $nom)
{
    try {
        $stmt = $pdo->prepare('UPDATE machine_groups SET name = ? WHERE id = ?');
        $stmt->execute([$nom, $id]);
        return true;
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            return false;
        }
        throw $e;
    }
}

function deleteGroupe(PDO $pdo, int $id)
{
    $stmt = $pdo->prepare('DELETE FROM machine_groups WHERE id = ?');
    $stmt->execute([$id]);
}

// ---------- Utilisateurs ----------
function getAllUsers(PDO $pdo)
{
    return $pdo->query('SELECT id, username, role_id FROM users ORDER BY username')->fetchAll(PDO::FETCH_ASSOC);
}

// ---------- Fiche Machine ----------
function getMachineById(PDO $pdo, int $id)
{
    $sql = 'SELECT m.*, m.os_name AS os, m.cpu_name AS cpu, (m.ram_total_gb * 1024) AS ram_mb,
                   g.name AS groupe, 
                   (SELECT ipv4_address FROM network_adapters WHERE machine_id = m.id LIMIT 1) AS ip,
                   (m.last_seen > NOW() - INTERVAL ' . ONLINE_MINUTES . ' MINUTE) AS en_ligne
            FROM machines m
            LEFT JOIN machine_groups g ON g.id = m.group_id
            WHERE m.id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// ---------- Disques ----------
function getDisksByMachine(PDO $pdo, int $machineId)
{
    // device_id -> device, total_gb -> size_gb (noms attendus par la vue)
    $stmt = $pdo->prepare('SELECT device_id AS device, model, filesystem, total_gb AS size_gb, free_gb
                           FROM disks WHERE machine_id = ? ORDER BY device_id');
    $stmt->execute([$machineId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ---------- Dashboard ----------
function getOsDistribution(PDO $pdo)
{
    return $pdo->query("SELECT COALESCE(os_name, 'Inconnu') AS os, COUNT(*) AS nb FROM machines GROUP BY os_name ORDER BY nb DESC")->fetchAll(PDO::FETCH_ASSOC);
}

function getLatestMachines(PDO $pdo, int $limit = 5)
{
    $limit = max(1, $limit);
    return $pdo->query("SELECT id, hostname, os_name AS os, (ram_total_gb * 1024) AS ram_mb FROM machines ORDER BY created_at DESC, id DESC LIMIT $limit")->fetchAll(PDO::FETCH_ASSOC);
}

// ---------- Fiche Machine : Modules RAM ----------
function getRamModulesByMachine(PDO $pdo, int $machineId)
{
    $stmt = $pdo->prepare('SELECT slot, capacity_gb, speed_mhz, manufacturer FROM ram_modules WHERE machine_id = ? ORDER BY slot');
    $stmt->execute([$machineId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ---------- Fiche Machine : Cartes réseau ----------
function getNetworkAdaptersByMachine(PDO $pdo, int $machineId)
{
    $stmt = $pdo->prepare('SELECT name, mac_address, ipv4_address, subnet_mask, default_gateway, dhcp_enabled
                           FROM network_adapters WHERE machine_id = ? ORDER BY id');
    $stmt->execute([$machineId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ---------- Fiche Machine : Logiciels ----------
function getSoftwareByMachine(PDO $pdo, int $machineId)
{
    $stmt = $pdo->prepare('SELECT name, version, publisher, install_date
                           FROM software WHERE machine_id = ? ORDER BY name');
    $stmt->execute([$machineId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ---------- Fiche Machine : dernier rapport complet ----------
// La table reports garde le JSON complet de chaque envoi. On y lit ce qui n'a pas
// de colonne dédiée en base : carte mère, BIOS, threads et fréquence du CPU, GPU.
function getLatestReportData(PDO $pdo, int $machineId)
{
    $stmt = $pdo->prepare('SELECT data_json FROM reports WHERE machine_id = ?
                           ORDER BY received_at DESC, id DESC LIMIT 1');
    $stmt->execute([$machineId]);
    $json = $stmt->fetchColumn();
    if ($json === false) {
        return [];
    }
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}
