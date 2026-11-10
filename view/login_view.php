<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VYRTUO - Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-black flex flex-col items-center justify-center h-screen font-sans">

    <h1 class="text-4xl font-bold tracking-widest mb-2">VYRTUO</h1>
    <p class="text-gray-500 mb-8 text-sm">Inventaire du parc informatique</p>
    
    <div class="w-80 p-8 bg-white">
        <?php if (!empty($error)): ?>
            <div class="mb-4 p-3 border border-red-500 bg-red-50 text-red-600 text-xs font-bold text-center uppercase tracking-wide rounded-2xl">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <label class="block text-xs font-bold mb-2 uppercase tracking-wide px-1">Identifiant</label>
            <input type="text" name="username" class="w-full border border-gray-300 px-4 py-3 mb-4 focus:border-black focus:outline-none transition-colors rounded-2xl" required autofocus>
            
            <label class="block text-xs font-bold mb-2 uppercase tracking-wide px-1">Mot de passe</label>
            <input type="password" name="password" class="w-full border border-gray-300 px-4 py-3 mb-8 focus:border-black focus:outline-none transition-colors rounded-2xl" required>
            
            <button type="submit" class="w-full bg-black text-white py-3 text-sm font-bold tracking-wide hover:bg-gray-800 transition-colors rounded-2xl">
                SE CONNECTER
            </button>
        </form>
    </div>

</body>
</html>