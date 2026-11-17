<?php
// 1. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des mots de passe
    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Vérification que tous les champs sont remplis
    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        
        // Vérification que les nouveaux mots de passe correspondent
        if ($new_password === $confirm_password) {
            
            // Récupération des infos de l'utilisateur actuel depuis la BDD (pour avoir son hash)
            $username = $_SESSION['user']['username'];
            $user_db = getUserByUsername($pdo, $username); // Fonction déjà présente dans model/db.php
            
            // Vérification du mot de passe actuel
            if ($user_db && password_verify($current_password, $user_db['password_hash'])) {
                
                // Tout est bon : on hache le nouveau mot de passe
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Mise à jour dans la base de données
                $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
                $stmt->execute([$new_hash, $_SESSION['user']['id']]);
                
                $_SESSION['success'] = "Votre mot de passe a été mis à jour avec succès.";
                
            } else {
                $_SESSION['error'] = "Le mot de passe actuel est incorrect.";
            }
        } else {
            $_SESSION['error'] = "Les nouveaux mots de passe ne correspondent pas.";
        }
    } else {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
    }
    
    // 3. Redirection vers la page de profil
    header('Location: index.php?page=profil');
    exit;
} else {
    // Si on accède à la page sans poster de formulaire
    header('Location: index.php?page=profil');
    exit;
}