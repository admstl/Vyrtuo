<?php
session_start();
require __DIR__ . '/../model/db.php';

if (isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = getUserByUsername($pdo, $username);

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'role'     => $user['role_id'],   // ADM ou USR
        ];
        header('Location: ../index.php');
        exit;
    }

    $error = 'Identifiant ou mot de passe incorrect.';
}

require __DIR__ . '/../view/login_view.php';