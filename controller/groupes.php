<?php
// session_start(), vérification admin et require db.php sont DÉJÀ gérés par index.php
$user = $_SESSION['user'];

$groupes = getGroupes($pdo);

require __DIR__ . '/../view/groupes_view.php';
