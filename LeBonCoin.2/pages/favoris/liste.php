<?php
/*
 * FICHIER : pages/favoris/liste.php
 * RÔLE    : Afficher les annonces favorites de l'utilisateur connecté
 */

session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../auth/connection.php');
    exit;
}

// Récupérer toutes les annonces en favori de l'utilisateur
// On fait une jointure entre favoris et annonces
$stmt = $pdo->prepare("
    SELECT a.*, u.pseudo
    FROM favoris f
    JOIN annonces a ON f.annonce_id = a.id
    JOIN utilisateurs u ON a.utilisateur_id = u.id
    WHERE f.utilisateur_id = ?
    ORDER BY f.id DESC
");
$stmt->execute([$_SESSION['utilisateur_id']]);
$favoris = $stmt->fetchAll();

require_once '../../includes/header.php';
?>

<main class="container">
    <h1>⭐ Mes favoris</h1>

    <?php if (empty($favoris)): ?>
        <p class="vide">Vous n'avez pas encore de favoris. <a href="../annonces/liste.php">Parcourir les annonces</a></p>
    <?php else: ?>
        <div class="grille-annonces">
            <?php foreach ($favoris as $annonce): ?>
                <div class="carte-annonce">
                    <a href="../annonces/detail.php?id=<?= $annonce['id'] ?>">
                        <img src="../../assets/uploads/<?= htmlspecialchars($annonce['photo']) ?>"
                             alt="<?= htmlspecialchars($annonce['titre']) ?>"
                             class="carte-photo">
                    </a>
                    <div class="carte-corps">
                        <h2 class="carte-titre">
                            <a href="../annonces/detail.php?id=<?= $annonce['id'] ?>">
                                <?= htmlspecialchars($annonce['titre']) ?>
                            </a>
                        </h2>
                        <p class="carte-prix"><?= number_format($annonce['prix'], 2, ',', ' ') ?> €</p>
                        <!-- Bouton retirer des favoris -->
                        <a href="toggle.php?annonce_id=<?= $annonce['id'] ?>" class="btn btn-danger btn-petit">
                            Retirer
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once '../../includes/footer.php'; ?>