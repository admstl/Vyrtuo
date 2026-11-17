<?php
// 1. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération sécurisée des données du formulaire
    $machine_id = (int) ($_POST['machine_id'] ?? 0);
    $group_id = (int) ($_POST['group_id'] ?? 0);

    if ($machine_id > 0) {
        // Préparation de la requête de mise à jour
        // (Si le group_id envoyé est 0 ou invalide, on pourrait gérer ça, mais ici on suppose que la liste déroulante renvoie un vrai ID)
        $stmt = $pdo->prepare('UPDATE machines SET group_id = ? WHERE id = ?');
        $stmt->execute([$group_id, $machine_id]);
    }

    // 3. Redirection vers la fiche de la machine
    header('Location: machine_detail.php?id=' . $machine_id);
    exit;
} else {
    // Si on accède à cette page sans poster de formulaire
    header('Location: ../index.php');
    exit;
}
