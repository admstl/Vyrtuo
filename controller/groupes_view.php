<?php
// Configuration pour le header
$page_title = 'VYRTUO - Groupes';
$active_page = 'groupes';

// On appelle la navbar et le début du HTML
require __DIR__ . '/layout/header.php';
?>

<div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold mb-8 tracking-tight">Groupes de machines</h2>
    <!-- Affichage des messages flash -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm font-bold uppercase tracking-wider rounded-2xl shadow-sm">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); // On supprime le message pour qu'il ne s'affiche qu'une seule fois 
        ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 text-sm font-bold uppercase tracking-wider rounded-2xl shadow-sm">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
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
                        <?php foreach ($groupes as $g): ?>
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="py-4 px-4 font-bold"><?= htmlspecialchars($g['nom']) ?></td>
                                <td class="py-4 px-4 text-gray-600 font-medium">
                                    <?= $g['count'] ?>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <button type="button" onclick="openRenameModal(<?= (int)$g['id'] ?>, '<?= htmlspecialchars(addslashes($g['nom'])) ?>')" class="text-gray-400 hover:text-black text-xs font-bold uppercase tracking-wider mr-3 transition-colors">Renommer</button>
                                    <button type="button" onclick="openDeleteGroupModal(<?= (int)$g['id'] ?>, '<?= htmlspecialchars(addslashes($g['nom'])) ?>')" class="text-gray-400 hover:text-red-600 text-xs font-bold uppercase tracking-wider transition-colors">Supprimer</button>
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

                <form method="post" action="index.php?page=add_groupe">
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

<!-- Modale : Renommer un groupe -->
<div id="modal-rename-groupe" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl">
        <h3 class="text-lg font-bold mb-6 tracking-tight">Renommer le groupe</h3>
        <form method="post" action="index.php?page=update_groupe">
            <input type="hidden" name="groupe_id" id="rename-groupe-id" value="">
            <label class="block text-xs font-bold mb-2 uppercase tracking-wide px-1 text-gray-500">Nouveau nom</label>
            <input type="text" name="nom" id="rename-groupe-nom" maxlength="50" required class="w-full border border-gray-300 px-4 py-3 mb-8 focus:border-black focus:outline-none transition-colors rounded-2xl bg-gray-50">
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal('modal-rename-groupe')" class="px-5 py-3 bg-gray-100 text-black font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-gray-200 transition-colors">Annuler</button>
                <button type="submit" class="px-5 py-3 bg-black text-white font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-gray-800 transition-colors">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modale : Supprimer un groupe -->
<div id="modal-delete-groupe" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl text-center">
        <h3 class="text-xl font-bold mb-2 text-red-600 tracking-tight">Supprimer le groupe</h3>
        <p class="text-gray-500 text-sm mb-8 font-medium">Supprimer <strong id="delete-groupe-nom"></strong> ? Ses machines sont conservées, mais n'auront plus de groupe.</p>
        <form method="post" action="index.php?page=delete_groupe">
            <input type="hidden" name="groupe_id" id="delete-groupe-id" value="">
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeModal('modal-delete-groupe')" class="flex-1 px-5 py-3 bg-gray-100 text-black font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-gray-200 transition-colors">Annuler</button>
                <button type="submit" class="flex-1 px-5 py-3 bg-red-600 text-white font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-red-700 transition-colors shadow-sm">Oui, supprimer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    function openRenameModal(id, nom) {
        document.getElementById('rename-groupe-id').value = id;
        document.getElementById('rename-groupe-nom').value = nom;
        document.getElementById('modal-rename-groupe').classList.remove('hidden');
    }
    function openDeleteGroupModal(id, nom) {
        document.getElementById('delete-groupe-id').value = id;
        document.getElementById('delete-groupe-nom').textContent = nom;
        document.getElementById('modal-delete-groupe').classList.remove('hidden');
    }
</script>
</body>

</html>