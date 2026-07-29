<?php
/*
 * FICHIER : pages/annonces/supprimer.php
 * RÔLE    : Supprimer une annonce après confirmation
 *           Un message d'avertissement est affiché avant la suppression
 */

session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../auth/connection.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = ?");
$stmt->execute([$id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    die("Annonce introuvable.");
}
if ($annonce['utilisateur_id'] !== $_SESSION['utilisateur_id']) {
    die("Action non autorisée.");
}

// L'utilisateur a confirmé la suppression (bouton "Oui, supprimer")
if (isset($_POST['confirmer'])) {
    // Supprimer la photo du serveur
    $chemin_photo = '../../assets/uploads/' . $annonce['photo'];
    if (file_exists($chemin_photo)) {
        unlink($chemin_photo);   // unlink() supprime un fichier
    }

    // Supprimer l'annonce (les messages et favoris liés seront supprimés automatiquement
    // grâce aux FOREIGN KEY avec ON DELETE CASCADE dans la BDD)
    $stmt = $pdo->prepare("DELETE FROM annonces WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: liste.php?deleted=1');
    exit;
}

require_once '../../includes/header.php';
?>

<main class="container">
    <div class="form-box">
        <h1>Supprimer l'annonce</h1>

        <!-- Message d'avertissement avant suppression -->
        <div class="alert alert-avertissement">
            ⚠️ Êtes-vous sûr de vouloir supprimer l'annonce
            <strong>"<?= htmlspecialchars($annonce['titre']) ?>"</strong> ?
            <br>Cette action est <strong>irréversible</strong>.
            Tous les messages liés seront également supprimés.
        </div>

        <form method="POST" action="">
            <div class="boutons-action">
                <!-- Le bouton "confirmer" déclenche la suppression -->
                <button type="submit" name="confirmer" class="btn btn-danger">
                    Oui, supprimer définitivement
                </button>
                <a href="detail.php?id=<?= $id ?>" class="btn btn-secondaire">
                    Non, annuler
                </a>
            </div>
        </form>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>