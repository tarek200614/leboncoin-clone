<?php
$page_title = 'Détail de l\'annonce';
require_once __DIR__ . '/../../includes/header.php';

$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('error', 'Annonce introuvable.');
    redirect('/pages/annonces/liste.php');
}

// Increment views
$pdo->prepare("UPDATE annonces SET views = views + 1 WHERE id = ?")->execute([$id]);

$stmt = $pdo->prepare("
    SELECT a.*, u.pseudo, u.id as user_id, c.nom AS categorie_nom
    FROM annonces a
    JOIN utilisateurs u ON a.utilisateur_id = u.id
    LEFT JOIN categories c ON a.categorie_id = c.id
    WHERE a.id = ?
");
$stmt->execute([$id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    set_flash('error', 'Annonce introuvable ou supprimée.');
    redirect('/pages/annonces/liste.php');
}

// Fetch images
$stmt_imgs = $pdo->prepare("SELECT image_path FROM annonce_images WHERE annonce_id = ? ORDER BY is_primary DESC, id ASC");
$stmt_imgs->execute([$id]);
$images = $stmt_imgs->fetchAll(PDO::FETCH_COLUMN);

$est_favori = false;
if (isset($_SESSION['utilisateur_id'])) {
    $stmt_fav = $pdo->prepare("SELECT id FROM favoris WHERE utilisateur_id = ? AND annonce_id = ?");
    $stmt_fav->execute([$_SESSION['utilisateur_id'], $id]);
    $est_favori = (bool)$stmt_fav->fetch();
}
?>

<div style="max-width: 900px; margin: 0 auto;">
    <a href="/pages/annonces/liste.php" style="color: var(--accent); text-decoration: none; margin-bottom: 1rem; display: inline-block;">← Retour aux annonces</a>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; background: var(--bg-primary); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <div>
            <img src="/assets/uploads/<?= e($images[0] ?? 'placeholder.jpg') ?>" alt="<?= e($annonce['titre']) ?>" style="width: 100%; border-radius: var(--radius); object-fit: cover; max-height: 400px;">
            <?php if (count($images) > 1): ?>
                <div style="display: flex; gap: 0.5rem; margin-top: 1rem; overflow-x: auto;">
                    <?php foreach ($images as $img): ?>
                        <img src="/assets/uploads/<?= e($img) ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; cursor: pointer; border: 1px solid var(--border);">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div>
            <span style="background: var(--bg-secondary); padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary);"><?= e($annonce['categorie_nom'] ?? 'Non catégorisé') ?></span>
            <h1 style="font-size: 1.75rem; font-weight: 700; margin: 1rem 0;"><?= e($annonce['titre']) ?></h1>
            <div style="font-size: 2rem; font-weight: 800; color: var(--accent); margin-bottom: 1.5rem;"><?= number_format($annonce['prix'], 2, ',', ' ') ?> €</div>
            
            <div style="border-top: 1px solid var(--border); padding-top: 1.5rem; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1rem; margin-bottom: 0.5rem;">Description</h3>
                <p style="color: var(--text-secondary); white-space: pre-line;"><?= nl2br(e($annonce['description'])) ?></p>
            </div>

            <div style="background: var(--bg-secondary); padding: 1rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
                <p style="font-weight: 600; margin-bottom: 0.25rem;">Vendu par : <?= e($annonce['pseudo']) ?></p>
                <p style="font-size: 0.875rem; color: var(--text-secondary);">Annonce publiée le <?= date('d/m/Y', strtotime($annonce['date_creation'])) ?> • <?= $annonce['views'] ?> vues</p>
            </div>

            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <?php if (isset($_SESSION['utilisateur_id']) && $_SESSION['utilisateur_id'] !== $annonce['user_id']): ?>
                    <a href="/pages/messages/envoyer.php?annonce_id=<?= $annonce['id'] ?>&destinataire_id=<?= $annonce['user_id'] ?>" class="btn btn-primary">💬 Contacter le vendeur</a>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['utilisateur_id'])): ?>
                    <form method="POST" action="/pages/favoris/toggle.php" style="display: inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">
                        <button type="submit" class="btn btn-secondary"><?= $est_favori ? '★ Retirer des favoris' : '☆ Ajouter aux favoris' ?></button>
                    </form>
                <?php endif; ?>

                <?php if (isset($_SESSION['utilisateur_id']) && ($_SESSION['utilisateur_id'] === $annonce['user_id'] || $_SESSION['utilisateur_role'] === 'admin')): ?>
                    <a href="modifier.php?id=<?= $annonce['id'] ?>" class="btn btn-secondary">✏️ Modifier</a>
                    <a href="supprimer.php?id=<?= $annonce['id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">🗑️ Supprimer</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
      
