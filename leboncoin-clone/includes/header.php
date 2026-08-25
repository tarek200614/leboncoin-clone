<?php
require_once __DIR__ . '/../config/db.php';

$page_title = $page_title ?? 'LeBonCoin Clone';
$og_title = $og_title ?? $page_title;
$og_desc = $og_desc ?? 'Plateforme de petites annonces moderne, sécurisée et rapide.';
$og_image = $og_image ?? url('/assets/img/og-default.jpg');

$flash = get_flash();
$unread_count = 0;
if (isset($_SESSION['utilisateur_id'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE destinataire_id = ? AND lu = 0");
    $stmt->execute([$_SESSION['utilisateur_id']]);
    $unread_count = (int)$stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($og_desc) ?>">
    
    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($og_title) ?>">
    <meta property="og:description" content="<?= e($og_desc) ?>">
    <meta property="og:image" content="<?= e($og_image) ?>">
    <meta property="og:url" content="<?= e((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '')) ?>">
    
    <title><?= e($page_title) ?> | LeBonCoin Clone</title>
    <link rel="stylesheet" href="<?= url('/assets/css/style.css') ?>">
    <script src="<?= url('/assets/js/main.js') ?>" defer></script>
</head>
<body>
    <header>
        <div class="container header-container">
            <a href="<?= url('/index.php') ?>" class="logo" aria-label="Retour à l'accueil">LeBonCoin Clone</a>
            <nav aria-label="Navigation principale">
                <a href="<?= url('/pages/annonces/liste.php') ?>">Annonces</a>
                <?php if (isset($_SESSION['utilisateur_id'])): ?>
                    <a href="<?= url('/pages/annonces/creer.php') ?>">+ Déposer</a>
                    <a href="<?= url('/pages/favoris/liste.php') ?>">Favoris</a>
                    <a href="<?= url('/pages/messages/boite.php') ?>" aria-label="Messages, <?= $unread_count ?> non lus">
                        Messages <?php if ($unread_count > 0): ?><span class="badge"><?= $unread_count ?></span><?php endif; ?>
                    </a>
                    <?php if (isset($_SESSION['utilisateur_role']) && $_SESSION['utilisateur_role'] === 'admin'): ?>
                        <a href="<?= url('/pages/admin/dashboard.php') ?>" class="admin-link">Admin</a>
                    <?php endif; ?>
                    <a href="<?= url('/pages/auth/deconnexion.php') ?>">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= url('/pages/auth/connection.php') ?>">Connexion</a>
                    <a href="<?= url('/pages/auth/inscription.php') ?>" class="btn btn-primary btn-sm">Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container main-content">
        <?php if ($flash): ?>
            <?php foreach ($flash as $type => $message): ?>
                <div class="alert alert-<?= e($type) ?>" role="alert" aria-live="polite">
                    <?= e($message) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

