<?php
// Supprimer un groupe (admin) : les machines du groupe sont conservées (group_id passe à NULL)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupe_id = (int) ($_POST['groupe_id'] ?? 0);

    if ($groupe_id > 0) {
        deleteGroupe($pdo, $groupe_id);
        $_SESSION['success'] = "Le groupe a été supprimé.";
    } else {
        $_SESSION['error'] = "Groupe invalide.";
    }
}

header('Location: index.php?page=groupes');
exit;
