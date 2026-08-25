<?php
$page_title = 'Contacter le vendeur';
require_once __DIR__ . '/../../includes/header.php';
require_login();

$annonce_id = intval($_POST['annonce_id'] ?? $_GET['annonce_id'] ?? 0);
$destinataire_id = intval($_POST['destinataire_id'] ?? $_GET['destinataire_id'] ?? 0);

// Récupérer l'annonce et le vendeur
$stmt = $pdo->prepare("
    SELECT a.*, u.id AS vendeur_id, u.pseudo AS vendeur_pseudo
    FROM annonces a JOIN utilisateurs u ON a.utilisateur_id = u.id
    WHERE a.id = ?
");
$stmt->execute([$annonce_id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    set_flash('error', 'Annonce introuvable.');
    redirect('/pages/annonces/liste.php');
}

$dest_id = $destinataire_id ?: $annonce['vendeur_id'];

// Prevent sending message to oneself
if ((int)$dest_id === (int)$_SESSION['utilisateur_id']) {
    set_flash('error', 'Vous ne pouvez pas vous envoyer un message à vous-même.');
    redirect("/pages/annonces/detail.php?id=$annonce_id");
}

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $contenu = trim($_POST['contenu'] ?? '');

    if (empty($contenu)) {
        $erreur = "Le message ne peut pas être vide.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO messages (annonce_id, expediteur_id, destinataire_id, contenu)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $annonce_id,
            $_SESSION['utilisateur_id'],
            $dest_id,
            $contenu
        ]);
        set_flash('success', 'Message envoyé avec succès !');
        redirect('/pages/messages/boite.php');
    }
}
?>

<div style="max-width: 600px; margin: 0 auto; background: var(--bg-primary); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--border);">
    <h1 style="margin-bottom: 0.5rem;">Contacter le vendeur</h1>
    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Annonce : <strong><?= e($annonce['titre']) ?></strong> — Vendeur : <strong><?= e($annonce['vendeur_pseudo']) ?></strong></p>

    <?php if ($erreur): ?>
        <div class="alert alert-error"><?= e($erreur) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <?= csrf_field() ?>
        <input type="hidden" name="annonce_id" value="<?= $annonce_id ?>">
        <input type="hidden" name="destinataire_id" value="<?= $dest_id ?>">
        <div class="form-group">
            <label class="form-label" for="contenu">Votre message *</label>
            <textarea id="contenu" name="contenu" rows="5" class="form-textarea" placeholder="Bonjour, je suis intéressé par votre annonce..." required></textarea>
        </div>
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Envoyer le message</button>
            <a href="<?= url('/pages/annonces/detail.php?id=' . $annonce_id) ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>