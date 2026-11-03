<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login_exe.php');
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VYRTUO - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark px-3">
        <span class="navbar-brand">VYRTUO</span>
        <span class="text-white">
            <?= htmlspecialchars($user['username']) ?>
            (<?= $user['role'] === 'ADM' ? 'admin' : 'user' ?>)
            <a href="logout.php" class="btn btn-outline-light btn-sm ms-3">Déconnexion</a>
        </span>
    </nav>

    <div class="container mt-4">
        <h4>Liste des machines</h4>
        <p class="text-muted">La liste des machines arrivera ici.</p>
    </div>
</body>
</html>
