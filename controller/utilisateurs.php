<?php
$user = $_SESSION['user'];

$utilisateurs = getAllUsers($pdo);

require __DIR__ . '/../view/users_view.php';
