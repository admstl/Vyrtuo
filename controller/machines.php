<?php
// session_start(), vérification utilisateur et require db.php 
// sont DÉJÀ gérés par index.php ! On ne les remet pas ici.

// 1. Récupération des filtres depuis l'URL
$filters = [
    'search' => $_GET['search'] ?? '',
    'os'     => $_GET['os'] ?? '',
    'groupe' => $_GET['groupe'] ?? '',
    'statut' => $_GET['statut'] ?? ''
];

// 2. On passe les filtres à la fonction (la variable $pdo vient de index.php)
$machines = getMachines($pdo, $filters);

// Calcul des statistiques
$total = count($machines);
$enLigne = count(array_filter($machines, fn($m) => $m['en_ligne']));
$horsLigne = $total - $enLigne;

// 3. Récupération des données pour dynamiser les menus déroulants
$allGroups = getGroupes($pdo); 
$allOS     = getDistinctOS($pdo);

// 4. Configuration pour le header
$page_title = 'VYRTUO - Machines';
$active_page = 'machines';

// 5. Appelle la vue
require __DIR__ . '/../view/machines_view.php';