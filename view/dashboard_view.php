<?php 
// Configuration pour le header
$page_title = 'VYRTUO - Machines';
$active_page = 'machines'; 

// On appelle la navbar et le début du HTML
require __DIR__ . '/layout/header.php'; 
?>

    <div class="max-w-6xl mx-auto">
        <!-- Chiffres clés[cite: 9] -->
        <div class="flex flex-col md:flex-row gap-6 mb-8">
            <div class="p-8 bg-white border border-gray-200 rounded-2xl flex-1 shadow-sm">
                <div class="text-xs uppercase tracking-widest font-bold text-gray-500 mb-2">Total Machines</div>
                <div class="text-5xl font-bold text-black"><?= $total ?? 0 ?></div>
            </div>
            <div class="p-8 bg-white border border-gray-200 rounded-2xl flex-1 shadow-sm">
                <div class="text-xs uppercase tracking-widest font-bold text-gray-500 mb-2">En ligne</div>
                <div class="text-5xl font-bold text-black"><?= $enLigne ?? 0 ?></div>
            </div>
            <div class="p-8 bg-white border border-gray-200 rounded-2xl flex-1 shadow-sm">
                <div class="text-xs uppercase tracking-widest font-bold text-gray-500 mb-2">Hors ligne</div>
                <div class="text-5xl font-bold text-gray-400"><?= $horsLigne ?? 0 ?></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Répartition par OS[cite: 9] -->
            <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm lg:col-span-1">
                <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Répartition par OS</h3>
                <div class="space-y-4">
                    <?php 
                    $os_stats = ['Ubuntu 22.04' => 45, 'Debian 12' => 30, 'Windows 11' => 15]; // Exemple de données
                    foreach($os_stats as $os => $percent): 
                    ?>
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-600 mb-1">
                            <span><?= htmlspecialchars($os) ?></span>
                            <span><?= $percent ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-black h-2 rounded-full" style="width: <?= $percent ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Dernières machines[cite: 9] -->
            <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm lg:col-span-2">
                <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Dernières machines ajoutées</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="border-b border-gray-100">
                            <tr>
                                <th class="pb-3 px-2 font-bold uppercase tracking-wider text-xs text-gray-400">Hostname</th>
                                <th class="pb-3 px-2 font-bold uppercase tracking-wider text-xs text-gray-400">OS</th>
                                <th class="pb-3 px-2 font-bold uppercase tracking-wider text-xs text-gray-400">RAM</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php 
                            $recent_machines = []; // À remplacer par tes données depuis le controller
                            foreach ($recent_machines as $m): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-2 font-bold"><?= htmlspecialchars($m['hostname']) ?></td>
                                    <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars($m['os']) ?></td>
                                    <td class="py-3 px-2 text-gray-600"><?= round($m['ram_mb']/1024) ?> Go</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>