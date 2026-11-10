<?php 
// Configuration pour le header
$page_title = 'VYRTUO - Machines';
$active_page = 'machines'; 

// On appelle la navbar et le début du HTML
require __DIR__ . '/layout/header.php'; 
?>

    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold mb-8 tracking-tight">Utilisateurs</h2>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Liste des comptes[cite: 9] -->
            <div class="flex-1 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-wider">Liste des comptes</h3>
                </div>
                <div class="overflow-x-auto p-2">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-white">
                            <tr>
                                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Identifiant</th>
                                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400">Rôle</th>
                                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-gray-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php foreach ($utilisateurs as $u): ?>
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="py-4 px-4 font-bold"><?= htmlspecialchars($u['username']) ?></td>
                                    <td class="py-4 px-4">
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">
                                            <?= htmlspecialchars($u['role_id']) ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <button class="text-gray-400 hover:text-black text-xs font-bold uppercase tracking-wider mr-3 transition-colors">Modifier</button>
                                        <button class="text-gray-400 hover:text-red-600 text-xs font-bold uppercase tracking-wider transition-colors">Supprimer</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ajouter un utilisateur[cite: 9] -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8 sticky top-8">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Ajouter un utilisateur</h3>
                    
                    <form method="post" action="controller/add_user.php">
                        <label class="block text-xs font-bold mb-2 uppercase tracking-wide px-1 text-gray-500">Identifiant</label>
                        <input type="text" name="username" class="w-full border border-gray-300 px-4 py-3 mb-4 focus:border-black focus:outline-none transition-colors rounded-2xl bg-gray-50" required>
                        
                        <label class="block text-xs font-bold mb-2 uppercase tracking-wide px-1 text-gray-500">Mot de passe</label>
                        <input type="password" name="password" class="w-full border border-gray-300 px-4 py-3 mb-4 focus:border-black focus:outline-none transition-colors rounded-2xl bg-gray-50" required>
                        
                        <label class="block text-xs font-bold mb-2 uppercase tracking-wide px-1 text-gray-500">Rôle</label>
                        <select name="role_id" class="w-full border border-gray-300 px-4 py-3 mb-8 focus:border-black focus:outline-none transition-colors rounded-2xl bg-gray-50 appearance-none">
                            <option value="USR">User (USR)</option>
                            <option value="ADM">Admin (ADM)</option>
                        </select>
                        
                        <button type="submit" class="w-full bg-black text-white py-3 text-sm font-bold tracking-wide hover:bg-gray-800 transition-colors rounded-2xl shadow-md">
                            CRÉER L'UTILISATEUR
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</body>
</html>