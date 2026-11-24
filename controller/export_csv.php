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

// Empêche Excel d'exécuter une cellule qui commence par = + - @
function csvSafe($value)
{
    $value = (string) $value;
    return ($value !== '' && strpos('=+-@', $value[0]) !== false) ? "'" . $value : $value;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="vyrtuo_export_' . date('Y-m-d_H-i') . '.csv"');

$output = fopen('php://output', 'w');

// Force l'encodage UTF-8 pour Excel (BOM)
fwrite($output, "\xEF\xBB\xBF");

fputcsv($output, ['Hostname', 'IP', 'OS', 'CPU', 'RAM (Go)', 'Groupe', 'Statut', 'Domaine', 'N° de série', 'Dernier inventaire'], ';', '"', '\\');

foreach ($machines as $m) {
    $ram    = $m['ram_mb'] ? round($m['ram_mb'] / 1024) : '';
    $statut = $m['en_ligne'] ? 'En ligne' : 'Hors ligne';

    fputcsv($output, [
        csvSafe($m['hostname']),
        csvSafe($m['ip']),
        csvSafe($m['os']),
        csvSafe($m['cpu']),
        $ram,
        csvSafe($m['groupe'] ?? 'Non assigné'),
        $statut,
        csvSafe($m['domain'] ?? ''),
        csvSafe($m['serial_number'] ?? ''),
        $m['last_seen'] ? date('d/m/Y H:i', strtotime($m['last_seen'])) : ''
    ], ';', '"', '\\');
}

fclose($output);
exit;
