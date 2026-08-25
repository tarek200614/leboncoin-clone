<?php
$page_title = 'Mes favoris';
require_once __DIR__ . '/../../includes/header.php';
require_login();

$stmt = $pdo->prepare("
    SELECT a.*, img.image_path, u.pseudo
    FROM favoris f
    JOIN annonces a ON f.annonce_id = a.id
    JOIN utilisateurs u ON a.utilisateur_id = u.id
    LEFT JOIN annonce_images img ON a.id = img.annonce_id AND img.is_primary = 1
    WHERE f.utilisateur_id = ?
    ORDER BY f.id DESC
");
$stmt->execute([$_SESSION['utilisateur_id']]);
$favoris = $stmt->fetchAll();
?>

<h1 style="margin-bottom: 1.5rem;">⭐ Mes favoris</h1>

<?php if (empty($favoris)): ?>
    <div class="empty-state">
        <h2>Aucun favori pour le moment</h2>
        <p>Vous n'avez pas encore de favoris. <a href="<?= url('/pages/annonces/liste.php') ?>">Parcourir les annonces</a>.</p>
    </div>
<?php else: ?>
    <div class="grid">
        <?php foreach ($favoris as $annonce): ?>
            <div class="card">
                <a href="<?= url('/pages/annonces/detail.php?id=' . $annonce['id']) ?>">
                    <img src="<?= url('/assets/uploads/' . e($annonce['image_path'] ?? 'placeholder.jpg')) ?>" alt="<?= e($annonce['titre']) ?>" class="card-img" loading="lazy">
                </a>
                <div class="card-body">
                    <h2 class="card-title"><a href="<?= url('/pages/annonces/detail.php?id=' . $annonce['id']) ?>"><?= e($annonce['titre']) ?></a></h2>
                    <div class="card-price"><?= number_format((float)$annonce['prix'], 2, ',', ' ') ?> €</div>
                    <div class="card-meta">Par <?= e($annonce['pseudo']) ?></div>
                    <form method="POST" action="<?= url('/pages/favoris/toggle.php') ?>" style="margin-top: 1rem;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">
                        <input type="hidden" name="redirect_to" value="favoris">
                        <button type="submit" class="btn btn-danger btn-sm">Retirer des favoris</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>