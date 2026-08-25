<?php
$page_title = 'Modifier l\'annonce';
require_once __DIR__ . '/../../includes/header.php';
require_login();

$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('error', 'Annonce introuvable.');
    redirect('/pages/annonces/liste.php');
}

// Fetch ad
$stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = ?");
$stmt->execute([$id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    set_flash('error', 'Annonce introuvable.');
    redirect('/pages/annonces/liste.php');
}

// Authorization check: owner or admin
if ((int)$annonce['utilisateur_id'] !== (int)$_SESSION['utilisateur_id'] && ($_SESSION['utilisateur_role'] ?? '') !== 'admin') {
    set_flash('error', 'Vous n\'êtes pas autorisé à modifier cette annonce.');
    redirect("/pages/annonces/detail.php?id=$id");
}

// Fetch primary image
$stmt_img = $pdo->prepare("SELECT image_path FROM annonce_images WHERE annonce_id = ? ORDER BY is_primary DESC, id ASC LIMIT 1");
$stmt_img->execute([$id]);
$current_image = $stmt_img->fetchColumn() ?: null;

$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $titre       = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix        = $_POST['prix'] ?? '';
    $categorie   = $_POST['categorie_id'] ?? null;
    $location    = trim($_POST['location'] ?? '');

    if (strlen($titre) < 5) $erreurs[] = "Le titre doit faire au moins 5 caractères.";
    if (strlen($description) < 20) $erreurs[] = "La description doit faire au moins 20 caractères.";
    if (!is_numeric($prix) || $prix <= 0) $erreurs[] = "Le prix doit être un nombre positif.";

    // Handle optional image upload
    $new_image = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        $max_size = 5 * 1024 * 1024;
        $name = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $tmp_name = $_FILES['photo']['tmp_name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp_name);
        finfo_close($finfo);

        if (!in_array($ext, $allowed_ext) || !str_starts_with($mime, 'image/')) {
            $erreurs[] = "Format d'image invalide. Formats acceptés : JPG, PNG, WEBP.";
        } elseif ($_FILES['photo']['size'] > $max_size) {
            $erreurs[] = "L'image dépasse 5 Mo.";
        } else {
            $new_name = uniqid('img_', true) . '.' . $ext;
            $dest = __DIR__ . '/../../assets/uploads/' . $new_name;
            if (move_uploaded_file($tmp_name, $dest)) {
                $new_image = $new_name;
            } else {
                $erreurs[] = "Erreur lors du téléchargement de l'image.";
            }
        }
    }

    if (empty($erreurs)) {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("
                UPDATE annonces
                SET titre = ?, description = ?, prix = ?, categorie_id = ?, location = ?
                WHERE id = ?
            ");
            $stmt->execute([$titre, $description, $prix, $categorie ?: null, $location, $id]);

            if ($new_image) {
                // Set primary image
                $pdo->prepare("UPDATE annonce_images SET is_primary = 0 WHERE annonce_id = ?")->execute([$id]);
                $stmt_img_ins = $pdo->prepare("INSERT INTO annonce_images (annonce_id, image_path, is_primary) VALUES (?, ?, 1)");
                $stmt_img_ins->execute([$id, $new_image]);
            }

            $pdo->commit();
            set_flash('success', 'Annonce modifiée avec succès !');
            redirect("/pages/annonces/detail.php?id=$id");
        } catch (Exception $e) {
            $pdo->rollBack();
            $erreurs[] = "Erreur lors de la sauvegarde : " . $e->getMessage();
        }
    }
}
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="margin-bottom: 1.5rem;">Modifier l'annonce</h1>
    <?php if (!empty($erreurs)): ?>
        <div class="alert alert-error"><ul><?php foreach ($erreurs as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label" for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" class="form-input" required value="<?= e($_POST['titre'] ?? $annonce['titre']) ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="categorie_id">Catégorie</label>
            <select id="categorie_id" name="categorie_id" class="form-select">
                <option value="">-- Sélectionner --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (($_POST['categorie_id'] ?? $annonce['categorie_id']) == $cat['id']) ? 'selected' : '' ?>><?= e($cat['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label" for="prix">Prix (€) *</label>
            <input type="number" step="0.01" id="prix" name="prix" class="form-input" required value="<?= e($_POST['prix'] ?? $annonce['prix']) ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="location">Localisation</label>
            <input type="text" id="location" name="location" class="form-input" value="<?= e($_POST['location'] ?? $annonce['location']) ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="description">Description *</label>
            <textarea id="description" name="description" rows="5" class="form-textarea" required><?= e($_POST['description'] ?? $annonce['description']) ?></textarea>
        </div>
        <div class="form-group">
            <label class="form-label" for="photo">Nouvelle photo <small style="color: var(--text-secondary);">(laisser vide pour conserver l'actuelle)</small></label>
            <?php if ($current_image): ?>
                <div style="margin-bottom: 0.5rem;">
                    <img src="<?= url('/assets/uploads/' . e($current_image)) ?>" alt="Photo actuelle" style="max-width: 150px; border-radius: var(--radius); border: 1px solid var(--border);">
                </div>
            <?php endif; ?>
            <input type="file" id="photo" name="photo" class="form-input" accept="image/png, image/jpeg, image/webp">
        </div>
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="<?= url('/pages/annonces/detail.php?id=' . $id) ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>