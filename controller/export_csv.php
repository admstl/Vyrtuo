<?php
// Récupération des filtres passés dans l'URL
$filters = [
    'search' => $_GET['search'] ?? '',
    'os'     => $_GET['os'] ?? '',
    'groupe' => $_GET['groupe'] ?? '',
    'statut' => $_GET['statut'] ?? ''
];

// On récupère exactement les mêmes machines affichées à l'écran
$machines = getMachines($pdo, $filters);

// On indique au navigateur que c'est un téléchargement de fichier CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="vyrtuo_export_' . date('Y-m-d_H-i') . '.csv"');

// On ouvre un flux mémoire pour écrire le fichier
$output = fopen('php://output', 'w');

// Force l'encodage UTF-8 pour Excel (BOM)
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Écriture de la ligne d'en-tête
fputcsv($output, ['Hostname', 'IP', 'OS', 'CPU', 'RAM (Go)', 'Groupe', 'Statut'], ';');

// Écriture des données
foreach ($machines as $m) {
    $ram = $m['ram_mb'] ? round($m['ram_mb'] / 1024) : '';
    $statut = $m['en_ligne'] ? 'En ligne' : 'Hors ligne';
    
    fputcsv($output, [
        $m['hostname'],
        $m['ip'],
        $m['os'],
        $m['cpu'],
        $ram,
        $m['groupe'] ?? 'Non assigné',
        $statut
    ], ';');
}

fclose($output);
exit;