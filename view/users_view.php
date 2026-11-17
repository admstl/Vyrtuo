<?php
// Configuration pour le header
$page_title = 'VYRTUO - Utilisateurs';
$active_page = 'utilisateurs';

require __DIR__ . '/layout/header.php';
?>

<div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold mb-8 tracking-tight">Utilisateurs</h2>
    
    <!-- Affichage des messages flash -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm font-bold uppercase tracking-wider rounded-2xl shadow-sm">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 text-sm font-bold uppercase tracking-wider rounded-2xl shadow-sm">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- Liste des comptes -->
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
                                    <!-- Bouton Modifier (déclenche la pop-up avec les données de l'utilisateur) -->
                                    <button onclick="openEditModal(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['username'])) ?>', '<?= $u['role_id'] ?>')" class="text-gray-400 hover:text-black text-xs font-bold uppercase tracking-wider mr-3 transition-colors">Modifier</button>
                                    
                                    <!-- Bouton Supprimer (déclenche la pop-up de confirmation avec l'ID) -->
                                    <button onclick="openDeleteModal(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['username'])) ?>')" class="text-gray-400 hover:text-red-600 text-xs font-bold uppercase tracking-wider transition-colors">Supprimer</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ajouter un utilisateur -->
        <div class="w-full lg:w-1/3">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8 sticky top-8">
                <h3 class="text-sm font-bold uppercase tracking-wider mb-6">Ajouter un utilisateur</h3>

                <form method="post" action="index.php?page=add_user">
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

<!-- ============================================== -->
<!-- MODALES (POP-UPS) CACHÉES PAR DÉFAUT -->
<!-- ============================================== -->

<!-- Modale : Modifier un utilisateur -->
<div id="modal-edit-user" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl">
        <h3 class="text-lg font-bold mb-2 tracking-tight">Modifier l'utilisateur</h3>
        <p id="edit-username-display" class="text-gray-500 text-sm mb-6 font-medium"></p>
        
        <form method="post" action="index.php?page=update_user">
            <input type="hidden" name="user_id" id="edit-user-id" value="">
            
            <label class="block text-xs font-bold mb-2 uppercase tracking-wide px-1 text-gray-500">Nouveau rôle</label>
            <select name="role_id" id="edit-role-id" class="w-full border border-gray-300 px-4 py-3 mb-8 focus:border-black focus:outline-none transition-colors rounded-2xl bg-gray-50 cursor-pointer appearance-none">
                <option value="USR">User (USR)</option>
                <option value="ADM">Admin (ADM)</option>
            </select>
            
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal('modal-edit-user')" class="px-5 py-3 bg-gray-100 text-black font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-gray-200 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-5 py-3 bg-black text-white font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-gray-800 transition-colors">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modale : Confirmation de suppression -->
<div id="modal-delete-user" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl text-center">
        <h3 class="text-xl font-bold mb-2 text-red-600 tracking-tight">Supprimer l'utilisateur</h3>
        <p class="text-gray-500 text-sm mb-8 font-medium">Êtes-vous sûr de vouloir supprimer le compte <strong id="delete-username-display" class="text-black"></strong> ?</p>
        
        <form method="post" action="index.php?page=delete_user">
            <input type="hidden" name="user_id" id="delete-user-id" value="">
            
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeModal('modal-delete-user')" class="flex-1 px-5 py-3 bg-gray-100 text-black font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-gray-200 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="flex-1 px-5 py-3 bg-red-600 text-white font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-red-700 transition-colors shadow-sm">
                    Oui, supprimer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script JavaScript pour dynamiser les pop-ups -->
<script>
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function openEditModal(userId, username, roleId) {
        // Pré-remplit les informations dans le formulaire de modification
        document.getElementById('edit-user-id').value = userId;
        document.getElementById('edit-username-display').textContent = username;
        document.getElementById('edit-role-id').value = roleId;
        
        // Affiche la modale
        document.getElementById('modal-edit-user').classList.remove('hidden');
    }

    function openDeleteModal(userId, username) {
        // Pré-remplit les informations dans le formulaire de suppression
        document.getElementById('delete-user-id').value = userId;
        document.getElementById('delete-username-display').textContent = username;
        
        // Affiche la modale
        document.getElementById('modal-delete-user').classList.remove('hidden');
    }
</script>

<?php require __DIR__ . '/layout/footer.php'; ?>