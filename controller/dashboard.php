<?php
// On supprime session_start() et require db.php car index.php s'en charge !

$user = $_SESSION['user'];

// On réutilise getMachines pour les stats du dashboard
$machines = getMachines($pdo);
$total = count($machines);
$enLigne = count(array_filter($machines, fn($m) => $m['en_ligne']));
$horsLigne = $total - $enLigne;

$page_title = 'VYRTUO - Dashboard';
$active_page = 'dashboard';

require __DIR__ . '/../view/dashboard_view.php';