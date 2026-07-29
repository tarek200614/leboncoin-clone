<?php
require_once __DIR__ . '/../../config/db.php';
require_login();
verify_csrf();

$annonce_id = filter_var($_POST['annonce_id'] ?? 0, FILTER_VALIDATE_INT);
$user_id = $_SESSION['utilisateur_id'];

if ($annonce_id) {
    $stmt = $pdo->prepare("SELECT id FROM favoris WHERE utilisateur_id = ? AND annonce_id = ?");
    $stmt->execute([$user_id, $annonce_id]);
    
    if ($stmt->fetch()) {
        $pdo->prepare("DELETE FROM favoris WHERE utilisateur_id = ? AND annonce_id = ?")->execute([$user_id, $annonce_id]);
    } else {
        $pdo->prepare("INSERT INTO favoris (utilisateur_id, annonce_id) VALUES (?, ?)")->execute([$user_id, $annonce_id]);
    }
}

redirect("/pages/annonces/detail.php?id=$annonce_id");
