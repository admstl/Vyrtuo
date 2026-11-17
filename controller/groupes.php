<?php
session_start();
// Protection stricte : Seul l'admin (ADM) peut voir ça
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADM') {
    header('Location: machines.php');
    exit;
}

require __DIR__ . '/../model/db.php';
$user = $_SESSION['user'];

// Récupération des groupes avec la nouvelle fonction
$groupes = getGroupes($pdo);

require __DIR__ . '/../view/groupes_view.php';
