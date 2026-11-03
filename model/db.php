<?php

$host = '10.29.60.7';
$port = '3306';
$dbname = 'la-zone';
$username = 'dev';
$password = 'dev123';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    $mon_pdo = $pdo;

    $connexion = $pdo;
    $bdd = $pdo;
    $db = $pdo;
    $conn = $pdo;

} catch (PDOException $e) {
    die("Connexion impossible : " . $e->getMessage());
}

function getUserByUsername(PDO $pdo, string $username)
{
    $stmt = $pdo->prepare('SELECT id, username, password_hash, role_id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);   
}

function createUser(PDO $pdo, string $username, string $password, string $roleId = 'USR')
{
    $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, role_id) VALUES (?, ?, ?)');
    $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $roleId]);
}