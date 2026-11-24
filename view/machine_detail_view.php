<?php
// Variables fournies par controller/machine_detail.php :
// $user, $machines, $machine, $disks, $networks, $ramModules, $softwares, $report

require __DIR__ . '/layout/header.php';

// ---------- Petites fonctions d'affichage ----------
if (!function_exists('e')) {
    function e($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
}
// Valeur vide -> tiret
function show($v) { return ($v === null || $v === '') ? '-' : e($v); }
// Taille en Go (ou To au-delà de 1000 Go), format français
function fmt_gb($gb) {
    if ($gb === null || $gb === '') return '-';
    $gb = (float) $gb;
    return $gb >= 1000 ? number_format($gb / 1000, 2, ',', ' ') . ' To' : number_format($gb, 1, ',', ' ') . ' Go';
}
function fmt_datetime($d) { return $d ? date('d/m/Y H:i', strtotime($d)) : '-'; }
// Date d'installation : accepte 20261105 ou 2026-11-05
function fmt_install($v) {
    if ($v === null || $v === '') return '-';
    if (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $v, $m) || preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $v, $m)) {
        return $m[3] . '/' . $m[2] . '/' . $m[1];
    }
    return e($v);
}
?>

<div class="max-w-6xl mx-auto">

<?php if (!$machine): ?>
    <!-- Aucune machine en base -->
    <div class="p-12 bg-white border border-gray-200 rounded-2xl shadow-sm text-center">
        <h1 class="text-xl font-bold mb-2">Aucune machine inventoriée</h1>
        <p class="text-gray-500 text-sm">Les ordinateurs apparaîtront ici dès que le script de collecte aura envoyé son premier rapport.</p>
    </div>

<?php else: ?>
    <?php
    // Totaux de stockage
    $totalDisk = 0; $freeDisk = 0; $hasFree = false;
    foreach ($disks as $d) {
        $totalDisk += (float) $d['size_gb'];
        if ($d['free_gb'] !== null) { $freeDisk += (float) $d['free_gb']; $hasFree = true; }
    }
    ?>

<!-- ============ En-tête ============ -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold flex items-center gap-3">
                <?= e($machine['hostname']) ?>
                <?php if ($machine['en_ligne']): ?>
                    <span class="flex h-3 w-3 relative" title="En ligne">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                <?php else: ?>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-300" title="Hors ligne"></span>
                <?php endif; ?>
            </h1>
            <p class="text-sm text-gray-500 mt-2">
                <?= e(trim(($machine['manufacturer'] ?? '') . ' ' . ($machine['model'] ?? ''))) ?: 'Matériel non renseigné' ?>
                &middot;
                <?= $machine['en_ligne'] ? 'En ligne' : ($machine['last_seen'] ? 'Hors ligne' : 'Jamais inventoriée') ?>
                <?php if ($machine['last_seen']): ?>
                    &middot; dernier inventaire le <?= fmt_datetime($machine['last_seen']) ?>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- ============ Chiffres clés ============ -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="text-gray-400 text-xs font-bold uppercase mb-2">Mémoire</div>
            <div class="text-2xl font-bold"><?= $machine['ram_total_gb'] !== null ? number_format((float) $machine['ram_total_gb'], 1, ',', ' ') . ' Go' : '-' ?></div>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="text-gray-400 text-xs font-bold uppercase mb-2">Processeur</div>
            <div class="text-2xl font-bold"><?= $machine['cpu_cores'] !== null ? (int) $machine['cpu_cores'] . ' cœurs' : '-' ?></div>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="text-gray-400 text-xs font-bold uppercase mb-2">Stockage libre</div>
            <div class="text-2xl font-bold"><?= $hasFree ? fmt_gb($freeDisk) : '-' ?></div>
            <?php if ($totalDisk > 0): ?><div class="text-xs text-gray-400 mt-1">sur <?= fmt_gb($totalDisk) ?></div><?php endif; ?>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="text-gray-400 text-xs font-bold uppercase mb-2">Logiciels</div>
            <div class="text-2xl font-bold"><?= count($softwares) ?></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- ============ Système ============ -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Système</h3>
            <ul class="space-y-4 text-sm">
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Système d'exploitation</span>
                    <span class="font-medium mt-1"><?= show($machine['os']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Version</span>
                    <span class="font-medium mt-1"><?= show($machine['os_version']) ?><?= !empty($machine['os_build']) ? ' (build ' . e($machine['os_build']) . ')' : '' ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Architecture</span>
                    <span class="font-medium mt-1"><?= show($machine['architecture']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Domaine</span>
                    <span class="font-medium mt-1"><?= show($machine['domain']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Utilisateur connecté</span>
                    <span class="font-medium mt-1"><?= show($machine['logged_user']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Dernier démarrage</span>
                    <span class="font-medium mt-1"><?= fmt_datetime($machine['last_boot']) ?></span></li>
            </ul>
        </div>

        <!-- ============ Matériel ============ -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Matériel</h3>
            <ul class="space-y-4 text-sm">
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Fabricant et modèle</span>
                    <span class="font-medium mt-1"><?= show(trim(($machine['manufacturer'] ?? '') . ' ' . ($machine['model'] ?? ''))) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Processeur</span>
                    <span class="font-medium mt-1"><?= show($machine['cpu']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Numéro de série</span>
                    <span class="font-medium mt-1 font-mono text-xs"><?= show($machine['serial_number']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">UUID</span>
                    <span class="font-medium mt-1 font-mono text-xs break-all"><?= show($machine['uuid']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Dernier rapport reçu</span>
                    <span class="font-medium mt-1">
                        <?php if ($report): ?>
                            <?= fmt_datetime($report['collected_at'] ?: $report['received_at']) ?>
                            <?= !empty($report['agent_version']) ? '<span class="text-gray-400">(agent ' . e($report['agent_version']) . ')</span>' : '' ?>
                        <?php else: ?>-<?php endif; ?>
                    </span></li>
            </ul>
        </div>

        <!-- ============ Mémoire ============ -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Modules de mémoire</h3>
            <?php if (empty($ramModules)): ?>
                <p class="text-gray-400 text-sm">Aucun module enregistré.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-gray-100">
                            <tr>
                                <th class="pb-3 pr-4 font-bold uppercase tracking-wider text-xs text-gray-400">Emplacement</th>
                                <th class="pb-3 pr-4 font-bold uppercase tracking-wider text-xs text-gray-400">Capacité</th>
                                <th class="pb-3 pr-4 font-bold uppercase tracking-wider text-xs text-gray-400">Vitesse</th>
                                <th class="pb-3 font-bold uppercase tracking-wider text-xs text-gray-400">Fabricant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php foreach ($ramModules as $r): ?>
                                <tr>
                                    <td class="py-3 pr-4 font-medium"><?= show($r['slot']) ?></td>
                                    <td class="py-3 pr-4"><?= fmt_gb($r['capacity_gb']) ?></td>
                                    <td class="py-3 pr-4"><?= $r['speed_mhz'] ? (int) $r['speed_mhz'] . ' MHz' : '-' ?></td>
                                    <td class="py-3 text-gray-500"><?= show($r['manufacturer']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- ============ Disques ============ -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Disques</h3>
            <div class="space-y-3">
                <?php if (empty($disks)): ?>
                    <p class="text-gray-400 text-sm">Aucun disque enregistré.</p>
                <?php endif; ?>
                <?php foreach ($disks as $d): ?>
                    <?php
                    $total = (float) $d['size_gb'];
                    $hasUsage = ($d['free_gb'] !== null && $total > 0);
                    $pct = $hasUsage ? max(0, min(100, (int) round(($total - (float) $d['free_gb']) / $total * 100))) : 0;
                    $barColor = $pct >= 90 ? 'bg-red-500' : ($pct >= 75 ? 'bg-amber-500' : 'bg-black');
                    ?>
                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-sm"><?= show($d['device']) ?><?= !empty($d['filesystem']) ? ' <span class="text-gray-400 font-medium">· ' . e($d['filesystem']) . '</span>' : '' ?></span>
                            <span class="text-gray-500 text-sm font-medium"><?= fmt_gb($d['size_gb']) ?></span>
                        </div>
                        <?php if (!empty($d['model'])): ?>
                            <div class="text-xs text-gray-400 mt-1"><?= e($d['model']) ?></div>
                        <?php endif; ?>
                        <?php if ($hasUsage): ?>
                            <div class="w-full h-2 bg-gray-200 rounded-full mt-3 overflow-hidden">
                                <div class="h-2 rounded-full <?= $barColor ?>" style="width: <?= $pct ?>%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-2">
                                <span><?= $pct ?> % utilisé</span>
                                <span><?= fmt_gb($d['free_gb']) ?> libres</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ============ Réseau ============ -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm md:col-span-2">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Interfaces réseau</h3>
            <?php if (empty($networks)): ?>
                <p class="text-gray-400 text-sm">Aucune interface enregistrée.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-gray-100">
                            <tr>
                                <?php foreach (['Interface', 'Adresse IP', 'Masque', 'Passerelle', 'Adresse MAC', 'DHCP'] as $h): ?>
                                    <th class="pb-3 pr-4 font-bold uppercase tracking-wider text-xs text-gray-400"><?= $h ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php foreach ($networks as $n): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 pr-4 font-medium"><?= show($n['name']) ?></td>
                                    <td class="py-3 pr-4"><?= show($n['ipv4_address']) ?></td>
                                    <td class="py-3 pr-4"><?= show($n['subnet_mask']) ?></td>
                                    <td class="py-3 pr-4"><?= show($n['default_gateway']) ?></td>
                                    <td class="py-3 pr-4 text-gray-500 font-mono text-xs"><?= show($n['mac_address']) ?></td>
                                    <td class="py-3"><?= $n['dhcp_enabled'] === null ? '-' : ($n['dhcp_enabled'] ? 'Oui' : 'Non') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- ============ Logiciels ============ -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm md:col-span-2">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-6">
                <h3 class="text-sm font-bold uppercase tracking-wider">
                    Logiciels installés
                    <span id="soft-count" class="text-gray-400 font-medium normal-case tracking-normal ml-2"><?= count($softwares) ?></span>
                </h3>
                <?php if (!empty($softwares)): ?>
                    <input id="soft-search" type="text" placeholder="Rechercher un logiciel ou un éditeur"
                           class="w-full md:w-80 border border-gray-300 px-4 py-2 text-sm focus:border-black focus:outline-none rounded-2xl bg-gray-50">
                <?php endif; ?>
            </div>

            <?php if (empty($softwares)): ?>
                <p class="text-gray-400 text-sm">Aucun logiciel enregistré.</p>
            <?php else: ?>
                <div class="overflow-auto max-h-96 border border-gray-100 rounded-2xl">
                    <table class="w-full text-left text-sm">
                        <thead class="sticky top-0 bg-white border-b border-gray-100">
                            <tr>
                                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Nom</th>
                                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Version</th>
                                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Éditeur</th>
                                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Installé le</th>
                            </tr>
                        </thead>
                        <tbody id="soft-body" class="divide-y divide-gray-50">
                            <?php foreach ($softwares as $s): ?>
                                <tr data-search="<?= e($s['name'] . ' ' . $s['publisher']) ?>">
                                    <td class="py-2 px-4 font-medium"><?= show($s['name']) ?></td>
                                    <td class="py-2 px-4 text-gray-500"><?= show($s['version']) ?></td>
                                    <td class="py-2 px-4 text-gray-500"><?= show($s['publisher']) ?></td>
                                    <td class="py-2 px-4 text-gray-500"><?= fmt_install($s['install_date']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p id="soft-empty" class="hidden p-6 text-center text-gray-400 text-sm">Aucun logiciel ne correspond à la recherche.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Recherche instantanée dans la liste des logiciels
        (function () {
            var input = document.getElementById('soft-search');
            if (!input) return;
            var rows = document.querySelectorAll('#soft-body tr');
            var count = document.getElementById('soft-count');
            var empty = document.getElementById('soft-empty');
            var total = rows.length;
            input.addEventListener('input', function () {
                var q = input.value.trim().toLowerCase(), visible = 0;
                rows.forEach(function (tr) {
                    var show = q === '' || tr.getAttribute('data-search').toLowerCase().indexOf(q) !== -1;
                    tr.classList.toggle('hidden', !show);
                    if (show) visible++;
                });
                count.textContent = (q === '') ? total : visible + ' / ' + total;
                empty.classList.toggle('hidden', visible !== 0);
            });
        })();
    </script>
<?php endif; ?>

</div>

<?php require __DIR__ . '/layout/footer.php'; ?>