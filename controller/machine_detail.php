<?php
// session_start() et require db.php sont DÉJÀ gérés par index.php
$user = $_SESSION['user'];

$id      = 1;
$machine = getMachineById($pdo, $id);

if ($machine) {
    $disks      = getDisksByMachine($pdo, $id);
    $networks   = getNetworkAdaptersByMachine($pdo, $id);
    $ramModules = getRamModulesByMachine($pdo, $id);
    $softwares  = getSoftwareByMachine($pdo, $id);
    $report     = getLastReport($pdo, $id);
}

$page_title  = 'VYRTUO - ' . ($machine['hostname'] ?? 'Aucune machine');
$active_page = 'machine_detail';

require __DIR__ . '/../view/machine_detail_view.php';