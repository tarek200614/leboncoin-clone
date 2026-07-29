<?php
/*
 * FICHIER : pages/annonces/modifier.php
 * RÔLE    : Modifier une annonce existante (formulaire pré-rempli)
 *           Seul l'auteur de l'annonce peut la modifier
 */

session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../auth/connection.php');
    exit;
}

// Récupérer l'ID de l'annonce depuis l'URL (?id=5)
$id = intval($_GET['id'] ?? 0);

// Chercher l'annonce dans la BDD
$stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = ?");
$stmt->execute([$id]);
$annonce = $stmt->fetch();

// Vérifications de sécurité
if (!$annonce) {
    die("Annonce introuvable.");
}
// Seul le propriétaire peut modifier
if ($annonce['utilisateur_id'] !== $_SESSION['utilisateur_id']) {
    die("Vous n'êtes pas autorisé à modifier cette annonce.");
}

$erreurs    = [];
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre       = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix        = $_POST['prix'] ?? '';
    $categorie   = $_POST['categorie_id'] ?? null;

    if (empty($titre))       $erreurs[] = "Le titre est obligatoire.";
    if (empty($description)) $erreurs[] = "La description est obligatoire.";
    if (!is_numeric($prix))  $erreurs[] = "Le prix est invalide.";

    // Traitement de la nouvelle photo (optionnel lors de la modification)
    $nom_photo = $annonce['photo'];   // Garder l'ancienne photo par défaut
    if (!empty($_FILES['photo']['name'])) {
        $extensions_ok = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (in_array($extension, $extensions_ok)) {
            $nom_photo = uniqid('photo_') . '.' . $extension;
            move_uploaded_file($_FILES['photo']['tmp_name'], '../../assets/uploads/' . $nom_photo);
        }
    }

    if (empty($erreurs)) {
        $stmt = $pdo->prepare("
            UPDATE annonces
            SET titre = ?, description = ?, prix = ?, categorie_id = ?, photo = ?
            WHERE id = ?
        ");
        $stmt->execute([$titre, $description, $prix, $categorie ?: null, $nom_photo, $id]);

        header("Location: detail.php?id=$id&modified=1");
        exit;
    }
}

require_once '../../includes/header.php';
?>

<main class="container">
    <div class="form-box form-large">
        <h1>Modifier l'annonce</h1>

        <?php if (!empty($erreurs)): ?>
            <div class="alert alert-erreur">
                <ul><?php foreach ($erreurs as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="champ">
                <label>Titre *</label>
                <!-- value pré-rempli avec les données existantes -->
                <input type="text" name="titre" value="<?= htmlspecialchars($annonce['titre']) ?>" required>
            </div>

            <div class="champ">
                <label>Catégorie</label>
                <select name="categorie_id">
                    <option value="">-- Aucune --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= ($annonce['categorie_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="champ">
                <label>Prix (€) *</label>
                <input type="number" name="prix" min="0" step="0.01"
                       value="<?= htmlspecialchars($annonce['prix']) ?>" required>
            </div>

            <div class="champ">
                <label>Description *</label>
                <textarea name="description" rows="6"><?= htmlspecialchars($annonce['description']) ?></textarea>
            </div>

            <div class="champ">
                <label>Nouvelle photo <small>(laisser vide pour conserver l'actuelle)</small></label>
                <img src="../../assets/uploads/<?= htmlspecialchars($annonce['photo']) ?>"
                     alt="Photo actuelle" style="max-width:200px; display:block; margin-bottom:8px; border-radius:8px;">
                <input type="file" name="photo" accept="image/*">
            </div>

            <div class="boutons-action">
                <button type="submit" class="btn btn-principal">Enregistrer</button>
                <a href="detail.php?id=<?= $id ?>" class="btn btn-secondaire">Annuler</a>
            </div>
        </form>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>