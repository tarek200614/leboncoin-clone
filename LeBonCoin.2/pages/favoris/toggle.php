<?php
/*
 * FICHIER : pages/favoris/toggle.php
 * RÔLE    : Ajouter ou retirer une annonce des favoris (bascule)
 */

session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../auth/connection.php');
    exit;
}

$annonce_id    = intval($_GET['annonce_id'] ?? 0);
$utilisateur_id = $_SESSION['utilisateur_id'];

// Vérifier si le favori existe déjà
$stmt = $pdo->prepare("
    SELECT id FROM favoris WHERE utilisateur_id = ? AND annonce_id = ?
");
$stmt->execute([$utilisateur_id, $annonce_id]);
$favori = $stmt->fetch();

if ($favori) {
    // Il existe déjà → on le supprime
    $stmt = $pdo->prepare("DELETE FROM favoris WHERE utilisateur_id = ? AND annonce_id = ?");
    $stmt->execute([$utilisateur_id, $annonce_id]);
} else {
    // Il n'existe pas → on l'ajoute
    $stmt = $pdo->prepare("INSERT INTO favoris (utilisateur_id, annonce_id) VALUES (?, ?)");
    $stmt->execute([$utilisateur_id, $annonce_id]);
}

// Retour à la page de l'annonce
header("Location: ../annonces/detail.php?id=$annonce_id");
exit;
?>