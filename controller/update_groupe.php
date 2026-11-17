<?php
// Renommer un groupe (admin) - index.php gère la session, la BDD et les droits
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupe_id = (int) ($_POST['groupe_id'] ?? 0);
    $nom       = trim($_POST['nom'] ?? '');

    if ($groupe_id > 0 && $nom !== '' && preg_match('/^.{1,50}$/us', $nom)) {
        if (renameGroupe($pdo, $groupe_id, $nom)) {
            $_SESSION['success'] = "Le groupe a été renommé.";
        } else {
            $_SESSION['error'] = "Ce nom de groupe existe déjà.";
        }
    } else {
        $_SESSION['error'] = "Nom de groupe invalide (1 à 50 caractères).";
    }
}

header('Location: index.php?page=groupes');
exit;
