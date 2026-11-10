<?php 
// Configuration pour le header
$page_title = 'VYRTUO - Machines';
$active_page = 'machines'; 

// On appelle la navbar et le début du HTML
require __DIR__ . '/layout/header.php'; 
?>

    <div class="max-w-6xl mx-auto">
        
        <!-- En-tête : Titre, sous-titre et bouton d'export[cite: 9] -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight mb-2">Liste des machines</h1>
                <p class="text-gray-500 text-sm font-medium">
                    <?= $total ?> machines <span class="mx-2">|</span> <?= $enLigne ?> en ligne <span class="mx-2">|</span> <?= $horsLigne ?> hors ligne
                </p>
            </div>
            <?php if ($user['role'] === 'ADM'): ?>
            <button class="px-5 py-2.5 bg-gray-500 text-white font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                Exporter CSV
            </button>
            <?php endif; ?>
        </div>

        <!-- Barre de recherche et filtres[cite: 9] -->
        <div class="flex flex-col lg:flex-row gap-4 mb-6">
            <!-- Recherche[cite: 9] -->
            <div class="flex-1">
                <input type="text" placeholder="Rechercher hostname / IP" class="w-full border border-gray-300 px-4 py-3 focus:border-black focus:outline-none transition-colors rounded-2xl bg-white text-sm shadow-sm">
            </div>
            
            <!-- Filtres[cite: 9] -->
            <div class="flex flex-col sm:flex-row gap-4">
                <select class="border border-gray-300 px-4 py-3 focus:border-black focus:outline-none transition-colors rounded-2xl bg-white text-sm shadow-sm appearance-none cursor-pointer pr-10 min-w-[140px]">
                    <option value="">OS : tous</option>
                    <option value="ubuntu">Ubuntu</option>
                    <option value="debian">Debian</option>
                    <option value="windows">Windows</option>
                </select>
                
                <select class="border border-gray-300 px-4 py-3 focus:border-black focus:outline-none transition-colors rounded-2xl bg-white text-sm shadow-sm appearance-none cursor-pointer pr-10 min-w-[140px]">
                    <option value="">Groupe : tous</option>
                    <option value="salle1">Salle 1</option>
                    <option value="salle2">Salle 2</option>
                    <option value="profs">Profs</option>
                </select>
                
                <select class="border border-gray-300 px-4 py-3 focus:border-black focus:outline-none transition-colors rounded-2xl bg-white text-sm shadow-sm appearance-none cursor-pointer pr-10 min-w-[140px]">
                    <option value="">Statut : tous</option>
                    <option value="online">En ligne</option>
                    <option value="offline">Hors ligne</option>
                </select>
            </div>
        </div>

        <!-- Tableau[cite: 9] -->
        <?php if (empty($machines)): ?>
            <div class="p-8 bg-white border border-gray-200 rounded-2xl text-gray-500 text-sm font-medium shadow-sm text-center">
                Aucune machine enregistrée.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto bg-white border border-gray-200 rounded-2xl shadow-sm">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-4 px-6 font-bold uppercase tracking-wider text-xs text-gray-500">Hostname</th>
                            <th class="py-4 px-6 font-bold uppercase tracking-wider text-xs text-gray-500">IP</th>
                            <th class="py-4 px-6 font-bold uppercase tracking-wider text-xs text-gray-500">OS</th>
                            <th class="py-4 px-6 font-bold uppercase tracking-wider text-xs text-gray-500">CPU</th>
                            <th class="py-4 px-6 font-bold uppercase tracking-wider text-xs text-gray-500">RAM</th>
                            <th class="py-4 px-6 font-bold uppercase tracking-wider text-xs text-gray-500">Groupe</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($machines as $m): ?>
                            <!-- Un clic sur la ligne redirigera vers la fiche de la machine[cite: 9] -->
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer group" onclick="window.location='controller/machine_detail.php?id=<?= $m['id'] ?>'">
                                <td class="py-4 px-6 font-bold text-black flex items-center gap-2">
                                    <?php if($m['en_ligne']): ?>
                                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                    <?php else: ?>
                                        <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($m['hostname']) ?>
                                </td>
                                <td class="py-4 px-6 text-gray-600 font-medium"><?= htmlspecialchars($m['ip'] ?? '') ?></td>
                                <td class="py-4 px-6 text-gray-600"><?= htmlspecialchars($m['os'] ?? '') ?></td>
                                <td class="py-4 px-6 text-gray-600"><?= htmlspecialchars($m['cpu'] ?? '') ?></td>
                                <td class="py-4 px-6 text-gray-600 font-medium">
                                    <?= $m['ram_mb'] ? round($m['ram_mb'] / 1024) . ' Go' : '' ?>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold group-hover:bg-white group-hover:border group-hover:border-gray-200 transition-colors">
                                        <?= htmlspecialchars($m['groupe'] ?? '—') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>