<?php
// session_start() et require db.php sont DÉJÀ gérés par index.php !
$user = $_SESSION['user'];

// Récupère l'ID depuis l'URL (ex: index.php?page=machine_detail&id=1)
$id = (int)($_GET['id'] ?? 0);
$machine = getMachineById($pdo, $id);

// Si la machine n'existe pas, on redirige vers la liste
if (!$machine) {
    header('Location: index.php?page=machines');
    exit;
}

// Données liées à la machine
$disks      = getDisksByMachine($pdo, $id);
$networks   = getNetworkAdaptersByMachine($pdo, $id);
$softwares  = getSoftwareByMachine($pdo, $id);
$ramModules = getRamModulesByMachine($pdo, $id);

// Infos sans colonne dédiée en base, lues dans le dernier rapport JSON
$report = getLatestReportData($pdo, $id);
$hw     = $report['hardware'] ?? [];
$board  = $hw['motherboard'] ?? [];
$cpuRep = $hw['cpu'] ?? [];
$gpus   = $hw['gpu'] ?? [];
if (!is_array($gpus) || ($gpus !== [] && !array_is_list($gpus))) {
    $gpus = is_array($gpus) ? [$gpus] : [];   // un seul GPU peut arriver en objet
}

// Configuration pour le header
$page_title = 'VYRTUO - Détail de la machine';
$active_page = 'machines';

require __DIR__ . '/../view/machine_detail_view.php';
