<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VYRTUO - Machines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark px-3">
        <span class="navbar-brand">VYRTUO</span>
        <span class="text-white">
            <?= htmlspecialchars($user['username']) ?>
            (<?= $user['role'] === 'ADM' ? 'admin' : 'user' ?>)
            <a href="controller/logout.php" class="btn btn-outline-light btn-sm ms-3">Déconnexion</a>
        </span>
    </nav>

    <div class="container mt-4">
        <h4>Liste des machines</h4>
        <p class="text-muted">
            <?= $total ?> machines | <?= $enLigne ?> en ligne | <?= $horsLigne ?> hors ligne
        </p>

        <?php if (empty($machines)): ?>
            <div class="alert alert-info">Aucune machine enregistrée.</div>
        <?php else: ?>
            <table class="table table-striped table-hover bg-white shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>Hostname</th>
                        <th>IP</th>
                        <th>OS</th>
                        <th>CPU</th>
                        <th>RAM</th>
                        <th>Groupe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($machines as $m): ?>
                        <tr>
                            <td><?= htmlspecialchars($m['hostname']) ?></td>
                            <td><?= htmlspecialchars($m['ip'] ?? '') ?></td>
                            <td><?= htmlspecialchars($m['os'] ?? '') ?></td>
                            <td><?= htmlspecialchars($m['cpu'] ?? '') ?></td>
                            <td><?= $m['ram_mb'] ? round($m['ram_mb'] / 1024) . ' Go' : '' ?></td>
                            <td><?= htmlspecialchars($m['groupe'] ?? '—') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
