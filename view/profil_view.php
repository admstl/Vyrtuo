<?php 
// Configuration pour le header
$page_title = 'VYRTUO - Machines';
$active_page = 'machines'; 

// On appelle la navbar et le début du HTML
require __DIR__ . '/layout/header.php'; 
?>

    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold mb-8 tracking-tight">Mon profil</h2>

        <div class="flex flex-col md:flex-row gap-8">
            
            <!-- Informations du compte[cite: 9] -->
            <div class="w-full md:w-1/3">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Informations</h3>
                    <ul class="space-y-4 text-sm">
                        <li class="flex flex-col">
                            <span class="text-gray-400 text-xs font-bold uppercase">Identifiant</span> 
                            <span class="font-medium mt-1 text-lg"><?= htmlspecialchars($user['username']) ?></span>
                        </li>
                        <li class="flex flex-col mt-4">
                            <span class="text-gray-400 text-xs font-bold uppercase">Rôle</span> 
                            <span class="font-medium mt-1">
                                <?= $user['role'] === 'ADM' ? 'Administrateur (ADM)' : 'Utilisateur (USR)' ?>
                            </span>
                        </li>
                        <li class="flex flex-col mt-4">
                            <span class="text-gray-400 text-xs font-bold uppercase">Dernière connexion</span> 
                            <span class="font-medium mt-1">10/11/2026 09:14</span> <!-- À dynamiser via la BDD -->
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Changer le mot de passe[cite: 9] -->
            <div class="flex-1">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Changer mon mot de passe</h3>
                    
                    <form method="post" action="controller/update_password.php" class="max-w-md">
                        <label class="block text-xs font-bold mb-2 uppercase tracking-wide px-1 text-gray-500">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="w-full border border-gray-300 px-4 py-3 mb-6 focus:border-black focus:outline-none transition-colors rounded-2xl bg-gray-50" required>
                        
                        <label class="block text-xs font-bold mb-2 uppercase tracking-wide px-1 text-gray-500">Nouveau mot de passe</label>
                        <input type="password" name="new_password" class="w-full border border-gray-300 px-4 py-3 mb-4 focus:border-black focus:outline-none transition-colors rounded-2xl bg-gray-50" required>
                        
                        <label class="block text-xs font-bold mb-2 uppercase tracking-wide px-1 text-gray-500">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="confirm_password" class="w-full border border-gray-300 px-4 py-3 mb-8 focus:border-black focus:outline-none transition-colors rounded-2xl bg-gray-50" required>
                        
                        <button type="submit" class="w-full bg-black text-white py-3 text-sm font-bold tracking-wide hover:bg-gray-800 transition-colors rounded-2xl shadow-md">
                            ENREGISTRER
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</body>
</html>