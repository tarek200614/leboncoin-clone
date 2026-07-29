<?php
require_once __DIR__ . '/../config/db.php';

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
    <meta name="description" content="Clone de Le Bon Coin - Plateforme de petites annonces sécurisée et moderne.">
    <title><?= isset($page_title) ? e($page_title) . ' | ' : '' ?>LeBonCoin Clone</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/main.js" defer></script>
</head>
<body>
    <header>
        <div class="container">
            <a href="/index.php" class="logo">LeBonCoin Clone</a>
            <nav>
                <a href="/pages/annonces/liste.php">Annonces</a>
                <?php if (isset($_SESSION['utilisateur_id'])): ?>
                    <a href="/pages/annonces/creer.php">+ Déposer une annonce</a>
                    <a href="/pages/favoris/liste.php">Favoris</a>
                    <a href="/pages/messages/boite.php">
                        Messages <?php if ($unread_count > 0): ?><span style="background:var(--danger);color:white;padding:2px 6px;border-radius:99px;font-size:0.7rem;"><?= $unread_count ?></span><?php endif; ?>
                    </a>
                    <?php if (isset($_SESSION['utilisateur_role']) && $_SESSION['utilisateur_role'] === 'admin'): ?>
                        <a href="/pages/admin/dashboard.php" style="color:var(--accent);">Admin</a>
                    <?php endif; ?>
                    <a href="/pages/auth/deconnexion.php">Déconnexion</a>
                <?php else: ?>
                    <a href="/pages/auth/connection.php">Connexion</a>
                    <a href="/pages/auth/inscription.php" class="btn btn-primary btn-sm">Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container" style="padding-top: 2rem; padding-bottom: 2rem;">
        <?php if ($flash): ?>
            <?php foreach ($flash as $type => $message): ?>
                <div class="alert alert-<?= e($type) ?>" role="alert"><?= e($message) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
 
