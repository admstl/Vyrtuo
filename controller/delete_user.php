<?php
// 1. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)($_POST['user_id'] ?? 0);

    if ($user_id > 0) {
        // Empêcher l'admin de se supprimer lui-même
        if ($user_id === $_SESSION['user']['id']) {
            $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte en cours d'utilisation.";
        } else {
            try {
                $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
                $stmt->execute([$user_id]);
                $_SESSION['success'] = "L'utilisateur a été supprimé avec succès.";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Erreur lors de la suppression. Cet utilisateur est peut-être lié à d'autres données.";
            }
        }
    } else {
        $_SESSION['error'] = "ID utilisateur invalide.";
    }
    
    // 3. Redirection vers la liste
    header('Location: index.php?page=utilisateurs');
    exit;
} else {
    header('Location: index.php?page=utilisateurs');
    exit;
}