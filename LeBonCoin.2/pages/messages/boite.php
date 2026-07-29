<?php
/*
 * FICHIER : pages/messages/boite.php
 * RÔLE    : Afficher tous les échanges de l'utilisateur connecté
 *           BONUS : marquer les messages reçus comme lus à l'ouverture
 *                   + bouton "Répondre" sur chaque message reçu
 */

session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../auth/connection.php');
    exit;
}

$id_moi = $_SESSION['utilisateur_id'];

// BONUS : Marquer tous les messages reçus non lus comme lus
$stmt_lu = $pdo->prepare("UPDATE messages SET lu = 1 WHERE destinataire_id = ? AND lu = 0");
$stmt_lu->execute([$id_moi]);

// Récupérer tous les messages où je suis impliqué
$stmt = $pdo->prepare("
    SELECT m.*,
           exp.pseudo AS pseudo_expediteur,
           dest.pseudo AS pseudo_destinataire,
           a.titre AS titre_annonce
    FROM messages m
    JOIN utilisateurs exp  ON m.expediteur_id = exp.id
    JOIN utilisateurs dest ON m.destinataire_id = dest.id
    JOIN annonces a        ON m.annonce_id = a.id
    WHERE m.expediteur_id = ? OR m.destinataire_id = ?
    ORDER BY m.date_envoi DESC
");
$stmt->execute([$id_moi, $id_moi]);
$messages = $stmt->fetchAll();

require_once '../../includes/header.php';
?>

<main class="container">
    <h1>💬 Mes messages</h1>

    <?php if (empty($messages)): ?>
        <p class="vide">Vous n'avez aucun message pour l'instant.</p>
    <?php else: ?>
        <div class="liste-messages">
            <?php foreach ($messages as $msg): ?>
                <div class="message-item <?= ($msg['destinataire_id'] == $id_moi && !$msg['lu']) ? 'message-non-lu' : '' ?>">
                    <div class="message-header">
                        <strong>Annonce :</strong>
                        <a href="../annonces/detail.php?id=<?= $msg['annonce_id'] ?>">
                            <?= htmlspecialchars($msg['titre_annonce']) ?>
                        </a>
                    </div>
                    <div class="message-meta">
                        De <strong><?= ($msg['expediteur_id'] == $id_moi) ? 'Vous' : htmlspecialchars($msg['pseudo_expediteur']) ?></strong>
                        → à <strong><?= ($msg['destinataire_id'] == $id_moi) ? 'Vous' : htmlspecialchars($msg['pseudo_destinataire']) ?></strong>
                        <span class="date"><?= date('d/m/Y à H:i', strtotime($msg['date_envoi'])) ?></span>
                    </div>
                    <p class="message-contenu"><?= nl2br(htmlspecialchars($msg['contenu'])) ?></p>

                    <!-- BONUS : Bouton répondre (si c'est un message reçu) -->
                    <?php if ($msg['destinataire_id'] == $id_moi): ?>
                        <a href="envoyer.php?annonce_id=<?= $msg['annonce_id'] ?>" class="btn btn-secondaire btn-sm">
                            ↩️ Répondre
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once '../../includes/footer.php'; ?>