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
                <a href="../controller/machines.php" class="hover:text-black transition-colors">Machines</a>
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Système -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Système</h3>
            <ul class="space-y-4 text-sm">
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">OS</span> <span class="font-medium mt-1"><?= htmlspecialchars($machine['os']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">CPU</span> <span class="font-medium mt-1"><?= htmlspecialchars($machine['cpu']) ?></span></li>
                <li class="flex flex-col"><span class="text-gray-400 text-xs font-bold uppercase">RAM</span> <span class="font-medium mt-1"><?= round($machine['ram_mb'] / 1024) ?> Go</span></li>
            </ul>
        </div>

        <!-- Disques -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Disques</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl">
                    <span class="font-bold text-sm">/dev/sda1</span>
                    <span class="text-gray-500 text-sm font-medium">256 Go</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl">
                    <span class="font-bold text-sm">/dev/sdb1</span>
                    <span class="text-gray-500 text-sm font-medium">1 To</span>
                </div>
            </div>
        </div>

        <!-- Réseau -->
        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm md:col-span-2">
            <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Interfaces Réseau</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100">
                        <tr>
                            <th class="pb-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Adresse IP</th>
                            <th class="pb-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Adresse MAC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-4 font-medium"><?= htmlspecialchars($machine['ip']) ?></td>
                            <td class="py-4 px-4 text-gray-500 font-mono text-xs">AA:BB:CC:DD:EE:FF</td>
                        </tr>
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
</script>

<?php
require __DIR__ . '/layout/footer.php';
?>