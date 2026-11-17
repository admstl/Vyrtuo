<?php
// session_start(), et require db.php sont DÉJÀ gérés par index.php !
$user = $_SESSION['user'];

// Récupère l'ID depuis l'URL (ex: index.php?page=machine_detail&id=1)
$id = $_GET['id'] ?? 0;
$machine = getMachineById($pdo, (int)$id);

// Si la machine n'existe pas, on redirige vers la liste
if (!$machine) {
    header('Location: index.php?page=machines');
    exit;
}

// Configuration pour le header
$page_title = 'VYRTUO - Détail de la machine';
$active_page = 'machines';

require __DIR__ . '/../view/machine_detail_view.php';