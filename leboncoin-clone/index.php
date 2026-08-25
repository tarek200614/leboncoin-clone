<?php
$page_title = 'Accueil';
require_once __DIR__ . '/includes/header.php';

// Fetch featured recent ads
$stmt = $pdo->prepare("
    SELECT a.id, a.titre, a.prix, a.date_creation, img.image_path, u.pseudo
    FROM annonces a
    JOIN utilisateurs u ON a.utilisateur_id = u.id
    LEFT JOIN annonce_images img ON a.id = img.annonce_id AND img.is_primary = 1
    ORDER BY a.date_creation DESC LIMIT 6
");
$stmt->execute();
$featured_ads = $stmt->fetchAll();
?>

<div style="text-align: center; padding: 3rem 0;">
    <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; color: var(--text-primary);">
        Achetez et vendez près de chez vous
    </h1>
    <p style="font-size: 1.125rem; color: var(--text-secondary); max-width: 600px; margin: 0 auto 2rem;">
        La plateforme de petites annonces moderne, sécurisée et rapide.
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="<?= url('/pages/annonces/liste.php') ?>" class="btn btn-primary">Parcourir les annonces</a>
        <?php if (isset($_SESSION['utilisateur_id'])): ?>
            <a href="<?= url('/pages/annonces/creer.php') ?>" class="btn btn-secondary">+ Déposer une annonce</a>
        <?php else: ?>
            <a href="<?= url('/pages/auth/inscription.php') ?>" class="btn btn-secondary">Créer un compte</a>
        <?php endif; ?>
    </div>
</div>

<h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Annonces récentes</h2>
<div class="grid">
    <?php if (empty($featured_ads)): ?>
        <p style="grid-column: 1 / -1; text-align: center; color: var(--text-secondary);">Aucune annonce pour le moment.</p>
    <?php else: ?>
        <?php foreach ($featured_ads as $ad): ?>
            <div class="card">
                <img src="<?= url('/assets/uploads/' . e($ad['image_path'] ?? 'placeholder.jpg')) ?>" alt="<?= e($ad['titre']) ?>" class="card-img" loading="lazy">
                <div class="card-body">
                    <div class="card-title"><a href="<?= url('/pages/annonces/detail.php?id=' . $ad['id']) ?>"><?= e($ad['titre']) ?></a></div>
                    <div class="card-price"><?= number_format((float)$ad['prix'], 2, ',', ' ') ?> €</div>
                    <div class="card-meta">Par <?= e($ad['pseudo']) ?> • <?= date('d/m/Y', strtotime($ad['date_creation'])) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
