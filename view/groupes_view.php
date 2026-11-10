<?php 
// Configuration pour le header
$page_title = 'VYRTUO - Machines';
$active_page = 'machines'; 

// On appelle la navbar et le début du HTML
require __DIR__ . '/layout/header.php'; 
?>

    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold mb-8 tracking-tight">Groupes de machines</h2>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Liste des groupes[cite: 9] -->
            <div class="flex-1 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-wider">Liste des groupes</h3>
                </div>
                <div class="overflow-x-auto p-2">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Nom</th>
                                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Machines</th>
                                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php 
                            // Exemple de données à remplacer par celles du contrôleur
                            $groupes = [
                                ['nom' => 'Salle 1', 'count' => 20],
                                ['nom' => 'Salle 2', 'count' => 20],
                                ['nom' => 'Salle 3', 'count' => 15],
                                ['nom' => 'Profs', 'count' => 5],
                            ];
                            foreach ($groupes as $g): ?>
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="py-4 px-4 font-bold"><?= htmlspecialchars($g['nom']) ?></td>
                                    <td class="py-4 px-4 text-gray-600 font-medium">
                                        <?= $g['count'] ?>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <button class="text-gray-400 hover:text-black text-xs font-bold uppercase tracking-wider mr-3 transition-colors">Renommer</button>
                                        <button class="text-gray-400 hover:text-red-600 text-xs font-bold uppercase tracking-wider transition-colors">Supprimer</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ajouter un groupe[cite: 9] -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8 sticky top-8">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Nouveau groupe</h3>
                    
                    <form method="post" action="controller/add_groupe.php">
                        <label class="block text-xs font-bold mb-2 uppercase tracking-wide px-1 text-gray-500">Nom</label>
                        <input type="text" name="nom" placeholder="ex : Salle 4" class="w-full border border-gray-300 px-4 py-3 mb-8 focus:border-black focus:outline-none transition-colors rounded-2xl bg-gray-50 placeholder-gray-400" required>
                        
                        <button type="submit" class="w-full bg-black text-white py-3 text-sm font-bold tracking-wide hover:bg-gray-800 transition-colors rounded-2xl shadow-md">
                            CRÉER
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</body>
</html>