<?php
// session_start() et require db.php sont DÉJÀ gérés par index.php
$user = $_SESSION['user'];

require __DIR__ . '/../view/profil_view.php';
