<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $machine_id = (int) ($_POST['machine_id'] ?? 0);
    $group_id   = (int) ($_POST['group_id'] ?? 0);

    if ($machine_id > 0) {
        // 0 = « Aucun groupe » (NULL en base) ; sinon le groupe doit exister
        $valid = array_map('intval', array_column(getGroupes($pdo), 'id'));

        if ($group_id === 0 || in_array($group_id, $valid, true)) {
            $stmt = $pdo->prepare('UPDATE machines SET group_id = ? WHERE id = ?');
            $stmt->execute([$group_id === 0 ? null : $group_id, $machine_id]);
        }
    }

    header('Location: index.php?page=machine_detail&id=' . $machine_id);
    exit;
} else {
    header('Location: index.php?page=machines');
    exit;
}
