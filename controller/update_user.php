<?php
// 1. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)($_POST['user_id'] ?? 0);
    $role_id = $_POST['role_id'] ?? 'USR';
    
    // Vérification de sécurité du rôle
    if (!in_array($role_id, ['ADM', 'USR'])) {
        $role_id = 'USR';
    }

    if ($user_id > 0) {
        // Empêcher l'admin de se modifier lui-même (facultatif mais recommandé pour éviter de se bloquer)
        if ($user_id === (int)$_SESSION['user']['id']) {
            $_SESSION['error'] = "Vous ne pouvez pas modifier votre propre rôle ici. Allez dans Mon Profil.";
        } else {
            try {
                $stmt = $pdo->prepare('UPDATE users SET role_id = ? WHERE id = ?');
                $stmt->execute([$role_id, $user_id]);
                $_SESSION['success'] = "L'utilisateur a été mis à jour avec succès.";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Erreur lors de la mise à jour de l'utilisateur.";
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