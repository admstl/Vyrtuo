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
function getMachines(PDO $pdo)
{
    $sql = 'SELECT m.id, m.hostname, m.ip, m.os, m.cpu, m.ram_mb,
                   g.name AS groupe,
                   (m.last_seen > NOW() - INTERVAL 10 MINUTE) AS en_ligne
            FROM machines m
            LEFT JOIN machine_groups g ON g.id = m.group_id
            ORDER BY m.hostname';
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}