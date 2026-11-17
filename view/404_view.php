<?php 
// On appelle la navbar et le début du HTML
require __DIR__ . '/layout/header.php'; 
?>

    <div class="max-w-6xl mx-auto flex flex-col items-center justify-center py-20 text-center">
        <!-- Chiffre 404 énorme et très fin -->
        <h1 class="text-9xl font-light text-gray-200 mb-4 tracking-tighter">404</h1>
        
        <h2 class="text-2xl font-bold mb-4 tracking-tight">Oups ! Page introuvable ou accès refusé.</h2>
        
        <p class="text-gray-500 mb-10 max-w-md">
            L'URL que vous essayez d'atteindre n'existe pas, a été déplacée, ou vous n'avez pas les droits d'administration nécessaires pour la consulter.
        </p>

        <!-- Bouton de retour -->
        <a href="index.php?page=machines" class="px-8 py-4 bg-black text-white font-bold text-xs uppercase tracking-wider rounded-2xl hover:bg-gray-800 transition-colors shadow-lg">
            Retourner à l'accueil
        </a>
    </div>

<?php 
require __DIR__ . '/layout/footer.php'; 
?>