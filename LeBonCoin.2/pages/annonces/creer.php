<?php
$page_title = 'Déposer une annonce';
require_once __DIR__ . '/../../includes/header.php';
require_login();

$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = $_POST['prix'] ?? '';
    $categorie = $_POST['categorie_id'] ?? null;
    $location = trim($_POST['location'] ?? '');

    if (strlen($titre) < 5) $erreurs[] = "Le titre doit faire au moins 5 caractères.";
    if (strlen($description) < 20) $erreurs[] = "La description doit faire au moins 20 caractères.";
    if (!is_numeric($prix) || $prix <= 0) $erreurs[] = "Le prix doit être un nombre positif.";

    // Handle Multiple Images
    $uploaded_images = [];
    if (isset($_FILES['photos']) && $_FILES['photos']['error'][0] === UPLOAD_ERR_NO_FILE) {
        $erreurs[] = "Au moins une photo est obligatoire.";
    } elseif (isset($_FILES['photos'])) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        foreach ($_FILES['photos']['name'] as $key => $name) {
            if ($_FILES['photos']['error'][$key] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $tmp_name = $_FILES['photos']['tmp_name'][$key];
                
                // Security: Validate MIME type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $tmp_name);
                finfo_close($finfo);
                
                if (!in_array($ext, $allowed_ext) || !str_starts_with($mime, 'image/')) {
                    $erreurs[] = "Format de fichier invalide : $name";
                } elseif ($_FILES['photos']['size'][$key] > $max_size) {
                    $erreurs[] = "Le fichier $name dépasse 5 Mo.";
                } else {
                    $new_name = uniqid('img_', true) . '.' . $ext;
                    $dest = __DIR__ . '/../../assets/uploads/' . $new_name;
                    if (move_uploaded_file($tmp_name, $dest)) {
                        $uploaded_images[] = $new_name;
                    }
                }
            }
        }
    }

    if (empty($erreurs) && !empty($uploaded_images)) {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO annonces (utilisateur_id, categorie_id, titre, description, prix, location) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['utilisateur_id'], $categorie ?: null, $titre, $description, $prix, $location]);
            $annonce_id = $pdo->lastInsertId();

            $stmt_img = $pdo->prepare("INSERT INTO annonce_images (annonce_id, image_path, is_primary) VALUES (?, ?, ?)");
            foreach ($uploaded_images as $index => $img_name) {
                $is_primary = ($index === 0) ? 1 : 0;
                $stmt_img->execute([$annonce_id, $img_name, $is_primary]);
            }
            
            $pdo->commit();
            set_flash('success', 'Annonce publiée avec succès !');
            redirect("/pages/annonces/detail.php?id=$annonce_id");
        } catch (Exception $e) {
            $pdo->rollBack();
            $erreurs[] = "Erreur lors de la sauvegarde : " . $e->getMessage();
        }
    }
}
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="margin-bottom: 1.5rem;">Déposer une annonce</h1>
    <?php if (!empty($erreurs)): ?>
        <div class="alert alert-error"><ul><?php foreach ($erreurs as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label" for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" class="form-input" required value="<?= e($_POST['titre'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="categorie_id">Catégorie</label>
            <select id="categorie_id" name="categorie_id" class="form-select">
                <option value="">-- Sélectionner --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (($_POST['categorie_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>><?= e($cat['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label" for="prix">Prix (€) *</label>
            <input type="number" step="0.01" id="prix" name="prix" class="form-input" required value="<?= e($_POST['prix'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="location">Localisation</label>
            <input type="text" id="location" name="location" class="form-input" value="<?= e($_POST['location'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="description">Description *</label>
            <textarea id="description" name="description" rows="5" class="form-textarea" required><?= e($_POST['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label class="form-label" for="photos">Photos (max 5) *</label>
            <input type="file" id="photos" name="photos[]" class="form-input" multiple accept="image/png, image/jpeg, image/webp" required>
            <small style="color: var(--text-secondary);">Formats acceptés : JPG, PNG, WEBP. Max 5 Mo par image.</small>
        </div>
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Publier l'annonce</button>
            <a href="/index.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
