<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($page_title ?? 'VYRTUO') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-black font-sans min-h-screen p-4 md:p-8">
    
    <!-- Navbar -->
    <nav class="bg-black text-white px-6 py-4 flex items-center justify-between text-sm rounded-2xl mb-8 shadow-sm max-w-6xl mx-auto">
        <div class="flex items-center gap-6">
            <span class="font-bold text-lg tracking-widest">VYRTUO</span>
            
            <a href="controller/machines.php" class="<?= ($active_page === 'machines') ? 'font-medium cursor-default text-white' : 'text-gray-400 hover:text-white transition-colors' ?>">Machines</a>
            <a href="controller/dashboard.php" class="<?= ($active_page === 'dashboard') ? 'font-medium cursor-default text-white' : 'text-gray-400 hover:text-white transition-colors' ?>">Dashboard</a>
            
            <?php if (isset($user) && $user['role'] === 'ADM'): ?>
                <a href="controller/groupes.php" class="<?= ($active_page === 'groupes') ? 'font-medium cursor-default text-white' : 'text-gray-400 hover:text-white transition-colors' ?>">Groupes</a>
                <a href="controller/utilisateurs.php" class="<?= ($active_page === 'utilisateurs') ? 'font-medium cursor-default text-white' : 'text-gray-400 hover:text-white transition-colors' ?>">Utilisateurs</a>
            <?php endif; ?>
        </div>
        <div class="flex items-center gap-4 text-gray-300">
            <a href="controller/profil.php" class="flex items-center gap-2 <?= ($active_page === 'profil') ? 'text-white' : 'hover:text-white transition-colors' ?>">
                <?= htmlspecialchars($user['username'] ?? '') ?> 
                <span class="text-[10px] uppercase border border-gray-600 px-2 py-0.5 rounded-full font-bold">
                    <?= (isset($user) && $user['role'] === 'ADM') ? 'admin' : 'user' ?>
                </span>
            </a>
            <a href="controller/logout.php" class="bg-white text-black px-4 py-2 rounded-2xl text-xs font-bold hover:bg-gray-200 transition-colors">
                DÉCONNEXION
            </a>
        </div>
    </nav>