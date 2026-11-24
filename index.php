<?php
session_start();
require_once __DIR__ . '/model/db.php';

// ===== V0 : l'application n'affiche que la fiche détaillée d'une machine =====
// Les autres pages (liste, dashboard, groupes, utilisateurs, profil, export...) existent
// toujours dans controller/ et view/, mais ne sont pas encore routées : on les ajoutera en V1.

// Pages accessibles uniquement aux administrateurs (ADM)
$admin_pages = [
    // V1 : 'groupes', 'add_groupe', 'update_groupe', 'delete_groupe',
    //      'utilisateurs', 'add_user', 'update_user', 'delete_user',
    //      'delete_machine', 'update_machine_group', 'export_csv'
];

// Pages accessibles à tous les utilisateurs connectés
$user_pages = ['machine_detail'];   // V1 : 'machines', 'dashboard', 'profil', 'update_password'

// Page par défaut = la fiche d'une machine
$page = $_GET['page'] ?? 'machine_detail';

// --- GESTION DE LA CONNEXION ---
if ($page === 'login') {
    if (isset($_SESSION['user'])) {
        header('Location: index.php');
        exit;
    }
    require __DIR__ . '/controller/login.php';
    exit;
}

if ($page === 'logout') {
    require __DIR__ . '/controller/logout.php';
    exit;
}

// Pas connecté : on force la connexion
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

$user = $_SESSION['user'];

// --- GESTION DES DROITS ---
if (in_array($page, $admin_pages, true) && $user['role'] !== 'ADM') {
    $page = '404';                                   // page admin demandée par un USR
} elseif (!in_array($page, $admin_pages, true) && !in_array($page, $user_pages, true)) {
    $page = '404';                                   // page inconnue
}

// --- CHARGEMENT DU CONTRÔLEUR ---
require __DIR__ . '/controller/' . $page . '.php';