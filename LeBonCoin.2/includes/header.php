<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// ---- : Compteur de messages non lus ----
$nb_non_lus = 0;
if (isset($_SESSION['utilisateur_id'])) {
    // On charge la connexion PDO seulement si elle n'est pas déjà disponible
    if (!isset($pdo)) {
        // Chemin relatif depuis includes/ vers config/
        $config_path = __DIR__ . '/../config/db.php';
        if (file_exists($config_path)) require_once $config_path;
    }
    if (isset($pdo)) {
        $stmt_nl = $pdo->prepare("
            SELECT COUNT(*) FROM messages
            WHERE destinataire_id = ? AND lu = 0
        ");
        $stmt_nl->execute([$_SESSION['utilisateur_id']]);
        $nb_non_lus = (int) $stmt_nl->fetchColumn();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeBonCoin</title>
    <link rel="stylesheet" href="/LeBonCoin/assets/css/style.css">
</head>
<body>

<header>
    <div class="container">
        <a href="/LeBonCoin/index.php" class="logo">🏷️ LeBonCoin</a>

        <!-- BONUS : Barre de recherche rapide -->
        <form method="GET" action="/LeBonCoin/pages/annonces/liste.php" class="header-search">
            <input type="text" name="q" placeholder="🔍 Rechercher une annonce..."
                   value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button type="submit" class="btn btn-search">Chercher</button>
        </form>

        <nav>
            <a href="/LeBonCoin/pages/annonces/liste.php">Annonces</a>

            <?php if (isset($_SESSION['utilisateur_id'])): ?>
                <a href="/LeBonCoin/pages/annonces/creer.php">+ Publier</a>
                <a href="/LeBonCoin/pages/favoris/liste.php">⭐ Favoris</a>
                <a href="/LeBonCoin/pages/messages/boite.php" class="nav-messages">
                    💬 Messages
                    <?php if ($nb_non_lus > 0): ?>
                        <span class="badge-notif"><?= $nb_non_lus ?></span>
                    <?php endif; ?>
                </a>
                <a href="/LeBonCoin/pages/auth/deconnexion.php">
                    Déconnexion (<?= htmlspecialchars($_SESSION['utilisateur_pseudo']) ?>)
                </a>
            <?php else: ?>
                <a href="/LeBonCoin/pages/auth/connection.php">Connexion</a>
                <a href="/LeBonCoin/pages/auth/inscription.php">S'inscrire</a>
            <?php endif; ?>
        </nav>
    </div>
</header>