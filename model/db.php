<?php

$config = require __DIR__ . '/config.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4",
        $config['user'],
        $config['pass'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT            => 5,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Connexion à la base de données impossible.');
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
function getMachines(PDO $pdo, array $filters = [])
{
    $sql = 'SELECT m.id, m.hostname, m.ip, m.os, m.cpu, m.ram_mb,
                   g.name AS groupe,
                   (m.last_seen > NOW() - INTERVAL 10 MINUTE) AS en_ligne
            FROM machines m
            LEFT JOIN machine_groups g ON g.id = m.group_id
            WHERE 1=1';
    $params = [];

    // Filtre de recherche (hostname ou ip)
    if (!empty($filters['search'])) {
        $sql .= ' AND (m.hostname LIKE ? OR m.ip LIKE ?)';
        $params[] = '%' . $filters['search'] . '%';
        $params[] = '%' . $filters['search'] . '%';
    }
    // Filtre par OS
    if (!empty($filters['os'])) {
        $sql .= ' AND m.os LIKE ?';
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
            $sql .= ' AND m.last_seen > NOW() - INTERVAL 10 MINUTE';
        } elseif ($filters['statut'] === 'offline') {
            $sql .= ' AND (m.last_seen <= NOW() - INTERVAL 10 MINUTE OR m.last_seen IS NULL)';
        }
    }

    $sql .= ' ORDER BY m.hostname';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ajoute cette fonction pour dynamiser le menu déroulant des OS
function getDistinctOS(PDO $pdo) {
    return $pdo->query('SELECT DISTINCT os FROM machines WHERE os IS NOT NULL AND os != "" ORDER BY os')->fetchAll(PDO::FETCH_COLUMN);
}
// ---------- Ajout pour les Groupes ----------
function getGroupes(PDO $pdo)
{
    $sql = 'SELECT g.id, g.name AS nom, COUNT(m.id) AS count
            FROM machine_groups g
            LEFT JOIN machines m ON m.group_id = g.id
            GROUP BY g.id ORDER BY g.name';
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// ---------- Ajout pour la liste des Utilisateurs ----------
function getAllUsers(PDO $pdo)
{
    return $pdo->query('SELECT id, username, role_id FROM users ORDER BY username')->fetchAll(PDO::FETCH_ASSOC);
}

// ---------- Ajout pour le Détail d'une Machine ----------
function getMachineById(PDO $pdo, int $id)
{
    $sql = 'SELECT m.*, g.name AS groupe, 
                   (m.last_seen > NOW() - INTERVAL 10 MINUTE) AS en_ligne
            FROM machines m
            LEFT JOIN machine_groups g ON g.id = m.group_id
            WHERE m.id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// ---------- Fiche machine : disques ----------
function getDisksByMachine(PDO $pdo, int $machineId)
{
    $stmt = $pdo->prepare('SELECT device, size_gb FROM disks WHERE machine_id = ? ORDER BY device');
    $stmt->execute([$machineId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ---------- Dashboard ----------
function getOsDistribution(PDO $pdo)
{
    return $pdo->query("SELECT COALESCE(os, 'Inconnu') AS os, COUNT(*) AS nb FROM machines GROUP BY os ORDER BY nb DESC")->fetchAll(PDO::FETCH_ASSOC);
}

function getLatestMachines(PDO $pdo, int $limit = 5)
{
    $limit = max(1, $limit);
    return $pdo->query("SELECT id, hostname, os, ram_mb FROM machines ORDER BY created_at DESC, id DESC LIMIT $limit")->fetchAll(PDO::FETCH_ASSOC);
}

// ---------- Groupes : renommer / supprimer ----------
// Retourne false si le nom existe déjà
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
    $stmt = $pdo->prepare('DELETE FROM machine_groups WHERE id = ?');   // machines.group_id passe à NULL (ON DELETE SET NULL)
    $stmt->execute([$id]);
}
