<?php
// Envoie le vrai code d'erreur HTTP 404 au navigateur
http_response_code(404);

// Configuration pour le layout
$page_title = 'VYRTUO - Page introuvable';
$active_page = ''; // Aucun onglet en surbrillance

require __DIR__ . '/../view/404_view.php';