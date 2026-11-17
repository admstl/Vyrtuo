<?php

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_groupe = trim($_POST['nom'] ?? '');

    if (!empty($nom_groupe) && !preg_match('/^.{1,50}$/us', $nom_groupe)) {
        $_SESSION['error'] = "Le nom du groupe doit faire 50 caractères maximum.";
    } elseif (!empty($nom_groupe)) {
        try {
            $stmt = $pdo->prepare('INSERT INTO machine_groups (name) VALUES (?)');
            $stmt->execute([$nom_groupe]);
            $_SESSION['success'] = "Le groupe a été créé avec succès.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Ce nom de groupe existe déjà.";
        }
    }
    
    // REDIRECTION MODIFIÉE : On pointe vers index.php
    header('Location: index.php?page=groupes');
    exit;
} else {
    header('Location: index.php?page=groupes');
    exit;
}