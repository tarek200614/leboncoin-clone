<?php
/*
 * FICHIER : pages/annonces/creer.php
 * RÔLE    : Permettre à un utilisateur connecté de publier une annonce
 *           avec titre, prix, description et photo obligatoires
 */

session_start();
require_once '../../config/db.php';

// Seul un utilisateur connecté peut créer une annonce
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../auth/connection.php');
    exit;
}

$erreurs = [];
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre       = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix        = $_POST['prix'] ?? '';
    $categorie   = $_POST['categorie_id'] ?? null;

    // Validations
    if (empty($titre))       $erreurs[] = "Le titre est obligatoire.";
    if (empty($description)) $erreurs[] = "La description est obligatoire.";
    if (!is_numeric($prix) || $prix < 0) $erreurs[] = "Le prix doit être un nombre positif.";

    // ---- TRAITEMENT DE LA PHOTO ----
    $nom_photo = '';
    if (empty($_FILES['photo']['name'])) {
        $erreurs[] = "La photo est obligatoire.";
    } else {
        // Extensions autorisées
        $extensions_ok = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensions_ok)) {
            $erreurs[] = "Format de photo non autorisé (jpg, png, gif, webp uniquement).";
        } elseif ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
            // Limite à 5 Mo
            $erreurs[] = "La photo ne doit pas dépasser 5 Mo.";
        } else {
            // Générer un nom unique pour éviter les doublons
            $nom_photo = uniqid('photo_') . '.' . $extension;
            $dossier   = '../../assets/uploads/';
            move_uploaded_file($_FILES['photo']['tmp_name'], $dossier . $nom_photo);
        }
    }

    if (empty($erreurs)) {
        $stmt = $pdo->prepare("
            INSERT INTO annonces (utilisateur_id, categorie_id, titre, description, prix, photo)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_SESSION['utilisateur_id'],
            $categorie ?: null,
            $titre,
            $description,
            $prix,
            $nom_photo
        ]);

        // Récupérer l'ID de l'annonce créée et rediriger vers elle
        $id = $pdo->lastInsertId();
        header("Location: detail.php?id=$id&created=1");
        exit;
    }
}

require_once '../../includes/header.php';
?>

<main class="container">
    <div class="form-box form-large">
        <h1>Publier une annonce</h1>

        <?php if (!empty($erreurs)): ?>
            <div class="alert alert-erreur">
                <ul><?php foreach ($erreurs as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <!-- enctype obligatoire pour l'upload de fichier -->
        <form method="POST" action="" enctype="multipart/form-data">

            <div class="champ">
                <label>Titre de l'annonce *</label>
                <input type="text" name="titre" value="<?= htmlspecialchars($titre ?? '') ?>" required>
            </div>

            <div class="champ">
                <label>Catégorie</label>
                <select name="categorie_id">
                    <option value="">-- Choisir --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="champ">
                <label>Prix (€) *</label>
                <input type="number" name="prix" min="0" step="0.01"
                       value="<?= htmlspecialchars($prix ?? '') ?>" required>
            </div>

            <div class="champ">
                <label>Description *</label>
                <textarea name="description" rows="6" required><?= htmlspecialchars($description ?? '') ?></textarea>
            </div>

            <div class="champ">
                <label>Photo * <small>(jpg, png — max 5 Mo)</small></label>
                <input type="file" name="photo" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-principal">Publier l'annonce</button>
        </form>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>