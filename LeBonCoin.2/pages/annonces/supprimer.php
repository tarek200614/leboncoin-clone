<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
require_login();
verify_csrf();

$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('error', 'ID d\'annonce invalide.');
    redirect('/pages/annonces/liste.php');
}

// Verify ownership or admin role
$stmt = $pdo->prepare("SELECT utilisateur_id FROM annonces WHERE id = ?");
$stmt->execute([$id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    set_flash('error', 'Annonce introuvable.');
    redirect('/pages/annonces/liste.php');
}

if ($annonce['utilisateur_id'] !== $_SESSION['utilisateur_id'] && $_SESSION['utilisateur_role'] !== 'admin') {
    set_flash('error', 'Vous n\'êtes pas autorisé à supprimer cette annonce.');
    redirect('/pages/annonces/detail.php?id=' . $id);
}

// Delete (Cascade will handle images and favorites)
$pdo->prepare("DELETE FROM annonces WHERE id = ?")->execute([$id]);

set_flash('success', 'Annonce supprimée avec succès.');
redirect('/pages/annonces/liste.php');
