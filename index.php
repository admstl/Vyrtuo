<?php
session_start();
require_once __DIR__ . '/model/db.php';

// Pages accessibles uniquement aux administrateurs (ADM)
$admin_pages = ['groupes', 'add_groupe', 'update_groupe', 'delete_groupe',
                'utilisateurs', 'add_user', 'update_user', 'delete_user',
                'delete_machine', 'update_machine_group', 'export_csv'];

// Pages accessibles à tous les utilisateurs connectés
$user_pages = ['machines', 'dashboard', 'profil', 'machine_detail', 'update_password'];

// On détermine quelle page l'utilisateur essaie de charger
// Par défaut (s'il n'y a rien dans l'URL), on le met sur 'machines'
$page = $_GET['page'] ?? 'machines';

// --- GESTION DE LA CONNEXION ---
if ($page === 'login') {
    if (isset($_SESSION['user'])) {
        header('Location: index.php?page=machines');
        exit;
    }
    require __DIR__ . '/controller/login.php';
    exit;
}

if ($page === 'logout') {
    require __DIR__ . '/controller/logout.php';
    exit;
}

// Si l'utilisateur n'est pas connecté et essaie d'accéder à une autre page, 
// on le force à se connecter.
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

$user = $_SESSION['user'];

// --- GESTION DES DROITS (AUTORISATIONS) ---

// 1. L'utilisateur (USR) essaie d'accéder à une page admin
if (in_array($page, $admin_pages) && $user['role'] !== 'ADM') {
    // Redirection vers une 404 (Accès refusé)
    $page = '404';
}
// 2. La page demandée n'existe dans aucune de nos listes (URL bidon)
elseif (!in_array($page, $admin_pages) && !in_array($page, $user_pages)) {
    // Redirection vers une 404 (Introuvable)
    $page = '404';
}

// --- CHARGEMENT DU BON CONTRÔLEUR ---
if ($page === '404') {
    require __DIR__ . '/controller/404.php';
} else {
    // On inclut le fichier qui correspond à la demande
    require __DIR__ . '/controller/' . $page . '.php';
}