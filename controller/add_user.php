<?php

// 2. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role_id  = $_POST['role_id'] ?? 'USR';

    // Vérification de base : les champs ne doivent pas être vides
    if (!empty($username) && !empty($password)) {
        
        // Sécurité supplémentaire : s'assurer que le rôle est valide
        if (!in_array($role_id, ['ADM', 'USR'])) {
            $role_id = 'USR';
        }

        try {
            // Appel de la fonction définie dans model/db.php
            createUser($pdo, $username, $password, $role_id);
            
            $_SESSION['success'] = "L'utilisateur '$username' a été créé avec succès.";
            
        } catch (PDOException $e) {
            // Le username est UNIQUE dans la base de données.
            // Si on essaie de créer un compte avec un identifiant déjà existant, ça tombe ici.
            $_SESSION['error'] = "Cet identifiant existe déjà.";
        }
    } else {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
    }
    
    // 3. Redirection vers la liste des utilisateurs
    header('Location: index.php?page=utilisateurs');
    exit;
} else {
    // Si on accède à la page sans poster de formulaire
    header('Location: index.php?page=utilisateurs');
    exit;
}