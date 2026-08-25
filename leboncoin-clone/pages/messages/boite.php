<?php
$page_title = 'Mes messages';
require_once __DIR__ . '/../../includes/header.php';
require_login();

$id_moi = $_SESSION['utilisateur_id'];

// Marquer les messages reçus non lus comme lus
$stmt_lu = $pdo->prepare("UPDATE messages SET lu = 1 WHERE destinataire_id = ? AND lu = 0");
$stmt_lu->execute([$id_moi]);

// Récupérer tous les messages où l'utilisateur est impliqué
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
?>

<h1 style="margin-bottom: 1.5rem;">💬 Mes messages</h1>

<?php if (empty($messages)): ?>
    <div class="empty-state">
        <h2>Aucun message pour l'instant</h2>
        <p>Vous n'avez aucun échange. <a href="<?= url('/pages/annonces/liste.php') ?>">Parcourir les annonces</a>.</p>
    </div>
<?php else: ?>
    <div class="liste-messages">
        <?php foreach ($messages as $msg): ?>
            <div class="message-item <?= ($msg['destinataire_id'] == $id_moi && !$msg['lu']) ? 'message-non-lu' : '' ?>">
                <div class="message-header">
                    <strong>Annonce :</strong>
                    <a href="<?= url('/pages/annonces/detail.php?id=' . $msg['annonce_id']) ?>">
                        <?= e($msg['titre_annonce']) ?>
                    </a>
                </div>
                <div class="message-meta">
                    De <strong><?= ($msg['expediteur_id'] == $id_moi) ? 'Vous' : e($msg['pseudo_expediteur']) ?></strong>
                    → à <strong><?= ($msg['destinataire_id'] == $id_moi) ? 'Vous' : e($msg['pseudo_destinataire']) ?></strong>
                    <span class="date"><?= date('d/m/Y à H:i', strtotime($msg['date_envoi'])) ?></span>
                </div>
                <p class="message-contenu"><?= nl2br(e($msg['contenu'])) ?></p>

                <?php if ($msg['destinataire_id'] == $id_moi): ?>
                    <a href="<?= url('/pages/messages/envoyer.php?annonce_id=' . $msg['annonce_id'] . '&destinataire_id=' . $msg['expediteur_id']) ?>" class="btn btn-secondary btn-sm" style="margin-top: 0.5rem;">
                        ↩️ Répondre
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>