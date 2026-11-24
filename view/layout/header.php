<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($page_title ?? 'VYRTUO') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-black font-sans min-h-screen p-4 md:p-8">

    <!-- Navbar globale (V0 : une seule page, donc pas de menu) -->
    <nav class="bg-black text-white px-6 py-4 flex items-center justify-between text-sm rounded-2xl mb-8 shadow-sm max-w-6xl mx-auto">
        <div class="flex items-center gap-6">
            <a href="index.php" class="font-bold text-lg tracking-widest">VYRTUO</a>
            <span class="text-gray-400 hidden md:inline">Inventaire du parc informatique</span>
            <?php /* V1 : remettre ici les liens Machines, Dashboard, Groupes, Utilisateurs */ ?>
        </div>
        <div class="flex items-center gap-4 text-gray-300">
            <span class="flex items-center gap-2">
                <?= htmlspecialchars($user['username'] ?? '') ?>
                <span class="text-[10px] uppercase border border-gray-600 px-2 py-0.5 rounded-full font-bold">
                    <?= (isset($user) && $user['role'] === 'ADM') ? 'admin' : 'user' ?>
                </span>
            </span>
            <a href="index.php?page=logout" class="bg-white text-black px-4 py-2 rounded-2xl text-xs font-bold hover:bg-gray-200 transition-colors">
                DÉCONNEXION
            </a>
        </div>
    </nav>