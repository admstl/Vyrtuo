<?php
// 1. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $machine_id = (int) ($_POST['machine_id'] ?? 0);

    if ($machine_id > 0) {
        // Préparation de la requête de suppression
        $stmt = $pdo->prepare('DELETE FROM machines WHERE id = ?');
        $stmt->execute([$machine_id]);
    }

    // 3. Redirection vers la liste des machines (car la fiche détaillée n'existe plus)
    header('Location: index.php?page=machines');
    exit;
} else {
    header('Location: index.php?page=machines');
    exit;
}