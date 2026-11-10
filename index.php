<?php
session_start();
 
if (!isset($_SESSION['user'])) {
    header('Location: controller/login.php');
    exit;
}
 
require __DIR__ . '/model/db.php';
 
$user     = $_SESSION['user'];
$machines = getMachines($pdo);
 
$total    = count($machines);
$enLigne  = count(array_filter($machines, fn($m) => $m['en_ligne']));
$horsLigne = $total - $enLigne;
 
require __DIR__ . '/view/machines_view.php';
 