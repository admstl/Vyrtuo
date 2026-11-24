<?php
// Configuration pour le header
$page_title = 'VYRTUO - Détail de la machine';
$active_page = 'machines';

// On appelle la navbar et le début du HTML
require __DIR__ . '/layout/header.php';
?>

<div class="max-w-6xl mx-auto">

    <!-- En-tête -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <div class="text-sm text-gray-500 font-medium mb-1">
                <a href="index.php?page=machines" class="hover:text-black transition-colors">Machines</a>
                <span class="mx-2">></span> <?= htmlspecialchars($machine['hostname']) ?>
            </div>
            <h1 class="text-3xl font-bold flex items-center gap-3">
                <?= htmlspecialchars($machine['hostname']) ?>
                <?php if ($machine['en_ligne']): ?>
                    <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                <?php else: ?>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-300"></span>
                <?php endif; ?>
            </h1>
        </div>

        <?php if ($user['role'] === 'ADM'): ?>
            <div class="flex gap-3">
                <!-- On ajoute l'événement onclick pour ouvrir la modale Groupe -->
                <button onclick="openModal('modal-group')" class="px-5 py-2.5 bg-white border border-gray-300 text-black font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-gray-50 transition-colors shadow-sm">
                    Changer de groupe
                </button>
                <!-- On ajoute l'événement onclick pour ouvrir la modale Supprimer -->
                <button onclick="openModal('modal-delete')" class="px-5 py-2.5 bg-white border border-red-200 text-red-600 font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-red-50 transition-colors shadow-sm">
                    Supprimer
                </button>
            </div>
        <?php endif; ?>
    </div>

<?php
// Petites aides d'affichage : valeur échappée ou tiret si vide
$val = fn($x) => ($x === null || $x === '') ? '-' : htmlspecialchars((string)$x);
$go  = fn($x) => ($x === null || $x === '') ? '-' : rtrim(rtrim(number_format((float)$x, 1, ',', ' '), '0'), ',') . ' Go';
$dt  = fn($x) => $x ? date('d/m/Y H:i', strtotime($x)) : '-';
?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Identité -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Identité</h3>
            <ul class="space-y-4 text-sm">
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Fabricant / Modèle</span> <span class="font-medium mt-1"><?= $val(trim(($machine['manufacturer'] ?? '') . ' ' . ($machine['model'] ?? ''))) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Numéro de série</span> <span class="font-medium mt-1"><?= $val($machine['serial_number']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">UUID</span> <span class="font-mono text-xs mt-1 break-all"><?= $val($machine['uuid']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Domaine</span> <span class="font-medium mt-1"><?= $val($machine['domain']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Utilisateur connecté</span> <span class="font-medium mt-1"><?= $val($machine['logged_user']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Groupe</span> <span class="font-medium mt-1"><?= $val($machine['groupe']) ?></span></li>
            </ul>
        </div>

        <!-- Système -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Système</h3>
            <ul class="space-y-4 text-sm">
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">OS</span> <span class="font-medium mt-1"><?= $val($machine['os']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Version / Build</span> <span class="font-medium mt-1"><?= $val($machine['os_version']) ?><?= $machine['os_build'] ? ' (build ' . htmlspecialchars($machine['os_build']) . ')' : '' ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Architecture</span> <span class="font-medium mt-1"><?= $val($machine['architecture']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Dernier démarrage</span> <span class="font-medium mt-1"><?= $dt($machine['last_boot']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Dernier inventaire</span> <span class="font-medium mt-1"><?= $dt($machine['last_seen']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Ajoutée au parc le</span> <span class="font-medium mt-1"><?= $dt($machine['created_at']) ?></span></li>
            </ul>
        </div>

        <!-- Processeur & carte mère -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Processeur & carte mère</h3>
            <ul class="space-y-4 text-sm">
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">CPU</span> <span class="font-medium mt-1"><?= $val($machine['cpu']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Cœurs / Threads</span> <span class="font-medium mt-1"><?= $val($machine['cpu_cores']) ?> cœurs / <?= $val($cpuRep['logical_processors'] ?? null) ?> threads</span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Fréquence max</span> <span class="font-medium mt-1"><?= !empty($cpuRep['max_clock_mhz']) ? (int)$cpuRep['max_clock_mhz'] . ' MHz' : '-' ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Carte mère</span> <span class="font-medium mt-1"><?= $val(trim(($board['manufacturer'] ?? '') . ' ' . ($board['product'] ?? ''))) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">Version BIOS</span> <span class="font-medium mt-1"><?= $val($board['bios_version'] ?? null) ?></span></li>
            </ul>
        </div>

        <!-- Mémoire vive -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-bold uppercase tracking-wider">Mémoire vive</h3>
                <span class="text-xs font-bold bg-gray-100 px-3 py-1 rounded-full">Total : <?= $go($machine['ram_total_gb']) ?></span>
            </div>
            <div class="space-y-3">
                <?php if (empty($ramModules)): ?>
                    <p class="text-gray-400 text-sm">Aucune barrette détaillée (fréquent sur une machine virtuelle).</p>
                <?php endif; ?>
                <?php foreach ($ramModules as $r): ?>
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl text-sm">
                        <div>
                            <span class="font-bold"><?= $val($r['slot']) ?></span>
                            <span class="text-gray-500"> - <?= $go($r['capacity_gb']) ?></span>
                            <div class="text-xs text-gray-400 mt-1">Fabricant : <?= $val($r['manufacturer']) ?></div>
                        </div>
                        <span class="text-xs font-mono text-gray-500"><?= $r['speed_mhz'] ? (int)$r['speed_mhz'] . ' MHz' : '-' ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Disques -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Disques</h3>
            <div class="space-y-3">
                <?php if (empty($disks)): ?>
                    <p class="text-gray-400 text-sm">Aucun disque enregistré.</p>
                <?php endif; ?>
                <?php foreach ($disks as $d):
                    $total = (float)$d['size_gb'];
                    $free  = $d['free_gb'] !== null ? (float)$d['free_gb'] : null;
                    $pct   = ($total > 0 && $free !== null) ? (int)round(($total - $free) / $total * 100) : null;
                    $color = $pct === null ? 'bg-gray-300' : ($pct >= 90 ? 'bg-red-500' : ($pct >= 75 ? 'bg-amber-500' : 'bg-green-500'));
                ?>
                    <div class="p-4 bg-gray-50 rounded-2xl text-sm">
                        <div class="flex justify-between items-center">
                            <span class="font-bold"><?= $val($d['device']) ?></span>
                            <span class="text-xs text-gray-500"><?= $val($d['filesystem']) ?></span>
                        </div>
                        <?php if (!empty($d['model'])): ?>
                            <div class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($d['model']) ?></div>
                        <?php endif; ?>
                        <?php if ($pct !== null): ?>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                                <div class="<?= $color ?> h-2 rounded-full" style="width: <?= max(0, min(100, $pct)) ?>%"></div>
                            </div>
                        <?php endif; ?>
                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span><?= $pct !== null ? 'Utilisé : ' . $pct . ' %' : '' ?></span>
                            <span>Libre : <?= $go($free) ?> / <?= $go($total) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Carte graphique -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Carte graphique</h3>
            <div class="space-y-3">
                <?php if (empty($gpus)): ?>
                    <p class="text-gray-400 text-sm">Aucune carte graphique remontée.</p>
                <?php endif; ?>
                <?php foreach ($gpus as $g): ?>
                    <div class="p-4 bg-gray-50 rounded-2xl text-sm">
                        <div class="font-bold"><?= $val($g['name'] ?? null) ?></div>
                        <div class="text-xs text-gray-400 mt-1">
                            Mémoire : <?= !empty($g['vram_mb']) ? (int)$g['vram_mb'] . ' Mo' : '-' ?>
                            &nbsp;•&nbsp; Pilote : <?= $val($g['driver_version'] ?? null) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Réseau -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm md:col-span-2">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Interfaces réseau (<?= count($networks) ?>)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100">
                        <tr>
                            <th class="pb-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Interface</th>
                            <th class="pb-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Adresse IP</th>
                            <th class="pb-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Masque</th>
                            <th class="pb-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Passerelle</th>
                            <th class="pb-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Adresse MAC</th>
                            <th class="pb-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">DHCP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if (empty($networks)): ?>
                            <tr><td colspan="6" class="py-4 px-4 text-gray-400">Aucune interface réseau enregistrée.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($networks as $n): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-4 font-medium"><?= $val($n['name']) ?></td>
                                <td class="py-4 px-4 font-medium"><?= $val($n['ipv4_address']) ?></td>
                                <td class="py-4 px-4 text-gray-500 font-mono text-xs"><?= $val($n['subnet_mask']) ?></td>
                                <td class="py-4 px-4 text-gray-500 font-mono text-xs"><?= $val($n['default_gateway']) ?></td>
                                <td class="py-4 px-4 text-gray-500 font-mono text-xs"><?= $val($n['mac_address']) ?></td>
                                <td class="py-4 px-4 text-gray-500"><?= $n['dhcp_enabled'] === null ? '-' : ($n['dhcp_enabled'] ? 'Oui' : 'Non') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Logiciels -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm md:col-span-2">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-6">
                <h3 class="text-sm font-bold uppercase tracking-wider">Logiciels installés (<?= count($softwares) ?>)</h3>
                <?php if (!empty($softwares)): ?>
                    <input type="text" id="filtre-logiciels" placeholder="Filtrer un logiciel..." oninput="filtrerLogiciels(this.value)"
                           class="border border-gray-300 px-4 py-2 focus:border-black focus:outline-none transition-colors rounded-2xl bg-white text-sm">
                <?php endif; ?>
            </div>
            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 sticky top-0 bg-white">
                        <tr>
                            <th class="pb-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Nom</th>
                            <th class="pb-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Version</th>
                            <th class="pb-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Éditeur</th>
                            <th class="pb-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Installé le</th>
                        </tr>
                    </thead>
                    <tbody id="liste-logiciels" class="divide-y divide-gray-50">
                        <?php if (empty($softwares)): ?>
                            <tr><td colspan="4" class="py-4 px-4 text-gray-400">Aucun logiciel enregistré.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($softwares as $s): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 font-medium"><?= $val($s['name']) ?></td>
                                <td class="py-3 px-4 text-gray-500 font-mono text-xs"><?= $val($s['version']) ?></td>
                                <td class="py-3 px-4 text-gray-500"><?= $val($s['publisher']) ?></td>
                                <td class="py-3 px-4 text-gray-500"><?php $t = $s['install_date'] ? strtotime($s['install_date']) : false; ?><?= $t ? date('d/m/Y', $t) : $val($s['install_date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODALES (POP-UPS) CACHÉES PAR DÉFAUT -->
<!-- ============================================== -->

<!-- Modale : Changer de groupe -->
<div id="modal-group" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl">
        <h3 class="text-lg font-bold mb-6 tracking-tight">Changer de groupe</h3>

        <form method="post" action="index.php?page=update_machine_group">
            <input type="hidden" name="machine_id" value="<?= $machine['id'] ?>">

            <label class="block text-xs font-bold mb-2 uppercase tracking-wide px-1 text-gray-500">Nouveau groupe</label>
            <!-- Ici tu pourras faire une boucle PHP foreach pour lister tes groupes dynamiquement -->
            <select name="group_id" class="w-full border border-gray-300 px-4 py-3 mb-8 focus:border-black focus:outline-none transition-colors rounded-2xl bg-gray-50 cursor-pointer">
                <option value="0">Aucun groupe</option>
                <!-- Boucle PHP pour générer les options à partir de la BDD -->
                <?php
                // On appelle tous les groupes (la fonction getGroupes doit être dans db.php)
                $allGroups = getGroupes($pdo);
                foreach ($allGroups as $g):
                    // On sélectionne par défaut le groupe actuel de la machine
                    $selected = ($g['nom'] === $machine['groupe']) ? 'selected' : '';
                ?>
                    <option value="<?= $g['id'] ?>" <?= $selected ?>><?= htmlspecialchars($g['nom']) ?></option>
                <?php endforeach; ?>
            </select>

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal('modal-group')" class="px-5 py-3 bg-gray-100 text-black font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-gray-200 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-5 py-3 bg-black text-white font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-gray-800 transition-colors">
                    Confirmer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modale : Confirmation de suppression -->
<div id="modal-delete" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl text-center">
        <h3 class="text-xl font-bold mb-2 text-red-600 tracking-tight">Supprimer la machine</h3>
        <p class="text-gray-500 text-sm mb-8 font-medium">Êtes-vous sûr de vouloir supprimer cette machine ? Cette action est irréversible.</p>

        <form method="post" action="index.php?page=delete_machine">
            <input type="hidden" name="machine_id" value="<?= $machine['id'] ?>">

            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeModal('modal-delete')" class="flex-1 px-5 py-3 bg-gray-100 text-black font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-gray-200 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="flex-1 px-5 py-3 bg-red-600 text-white font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-red-700 transition-colors shadow-sm">
                    Oui, supprimer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script JavaScript pour faire fonctionner les pop-ups -->
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Filtre la liste des logiciels pendant la frappe (nom, version ou éditeur)
    function filtrerLogiciels(texte) {
        const t = texte.toLowerCase();
        document.querySelectorAll('#liste-logiciels tr').forEach(function (ligne) {
            ligne.style.display = ligne.textContent.toLowerCase().includes(t) ? '' : 'none';
        });
    }
</script>

<?php
require __DIR__ . '/layout/footer.php';
?>