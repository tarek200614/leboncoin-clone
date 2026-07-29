<?php
/*
 * FICHIER : pages/annonces/detail.php
 * RÔLE    : Afficher tous les détails d'une annonce
 *           et le formulaire pour envoyer un message au vendeur
 */

session_start();
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

// Récupérer l'annonce avec les infos du vendeur et de la catégorie
$stmt = $pdo->prepare("
    SELECT a.*, u.pseudo, u.email AS vendeur_email, c.nom AS categorie_nom
    FROM annonces a
    JOIN utilisateurs u ON a.utilisateur_id = u.id
    LEFT JOIN categories c ON a.categorie_id = c.id
    WHERE a.id = ?
");
$stmt->execute([$id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    die("Annonce introuvable.");
}

// Vérifier si l'annonce est dans les favoris de l'utilisateur connecté
$est_favori = false;
if (isset($_SESSION['utilisateur_id'])) {
    $stmt = $pdo->prepare("
        SELECT id FROM favoris
        WHERE utilisateur_id = ? AND annonce_id = ?
    ");
    $stmt->execute([$_SESSION['utilisateur_id'], $id]);
    $est_favori = (bool) $stmt->fetch();
}

require_once '../../includes/header.php';
?>

<main class="container">
    <!-- Messages flash -->
    <?php if (isset($_GET['created'])): ?>
        <div class="alert alert-succes">Annonce publiée avec succès !</div>
    <?php endif; ?>

    <div class="detail-annonce">
        <!-- Grande photo -->
        <img src="../../assets/uploads/<?= htmlspecialchars($annonce['photo']) ?>"
             alt="<?= htmlspecialchars($annonce['titre']) ?>"
             class="detail-photo">

        <div class="detail-info">
            <?php if ($annonce['categorie_nom']): ?>
                <span class="badge"><?= htmlspecialchars($annonce['categorie_nom']) ?></span>
            <?php endif; ?>

            <h1><?= htmlspecialchars($annonce['titre']) ?></h1>
            <p class="detail-prix"><?= number_format($annonce['prix'], 2, ',', ' ') ?> €</p>
            <p class="detail-description"><?= nl2br(htmlspecialchars($annonce['description'])) ?></p>

            <p class="detail-meta">
                Publié par <strong><?= htmlspecialchars($annonce['pseudo']) ?></strong>
                le <?= date('d/m/Y', strtotime($annonce['date_creation'])) ?>
            </p>

            <!-- Boutons pour le propriétaire -->
            <?php if (isset($_SESSION['utilisateur_id']) && $_SESSION['utilisateur_id'] == $annonce['utilisateur_id']): ?>
                <div class="boutons-action">
                    <a href="modifier.php?id=<?= $id ?>" class="btn btn-principal">✏️ Modifier</a>
                    <a href="supprimer.php?id=<?= $id ?>" class="btn btn-danger">🗑️ Supprimer</a>
                </div>
            <?php endif; ?>

            <!-- Bouton favori + contacter pour les autres utilisateurs -->
            <?php if (isset($_SESSION['utilisateur_id']) && $_SESSION['utilisateur_id'] != $annonce['utilisateur_id']): ?>
                <div class="boutons-action">
                    <a href="../favoris/toggle.php?annonce_id=<?= $id ?>" class="btn btn-secondaire">
                        <?= $est_favori ? '💔 Retirer des favoris' : '❤️ Ajouter aux favoris' ?>
                    </a>
                    <a href="../messages/envoyer.php?annonce_id=<?= $id ?>" class="btn btn-principal">
                        💬 Contacter le vendeur
                    </a>
                </div>
            <?php elseif (!isset($_SESSION['utilisateur_id'])): ?>
                <p class="detail-meta">
                    <a href="../auth/connection.php">Connectez-vous</a> pour contacter le vendeur ou ajouter aux favoris.
                </p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>