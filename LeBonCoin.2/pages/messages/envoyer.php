<?php
/*
 * FICHIER : pages/messages/envoyer.php
 * RÔLE    : Envoyer un message à un vendeur concernant une annonce
 */

session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../auth/connection.php');
    exit;
}

$annonce_id = intval($_POST['annonce_id'] ?? $_GET['annonce_id'] ?? 0);

// Récupérer l'annonce et le vendeur
$stmt = $pdo->prepare("
    SELECT a.*, u.id AS vendeur_id, u.pseudo AS vendeur_pseudo
    FROM annonces a JOIN utilisateurs u ON a.utilisateur_id = u.id
    WHERE a.id = ?
");
$stmt->execute([$annonce_id]);
$annonce = $stmt->fetch();

if (!$annonce) die("Annonce introuvable.");

// On ne peut pas se contacter soi-même
if ($annonce['vendeur_id'] === $_SESSION['utilisateur_id']) {
    die("Vous ne pouvez pas vous envoyer un message à vous-même.");
}

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $annonce['vendeur_id'],
            $contenu
        ]);
        $succes = "Message envoyé avec succès !";
    }
}

require_once '../../includes/header.php';
?>

<main class="container">
    <div class="form-box">
        <h1>Contacter le vendeur</h1>
        <p>Annonce : <strong><?= htmlspecialchars($annonce['titre']) ?></strong>
           — Vendeur : <strong><?= htmlspecialchars($annonce['vendeur_pseudo']) ?></strong></p>

        <?php if ($erreur): ?>
            <div class="alert alert-erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        <?php if ($succes): ?>
            <div class="alert alert-succes"><?= htmlspecialchars($succes) ?>
                <a href="boite.php">Voir mes messages</a></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="annonce_id" value="<?= $annonce_id ?>">
            <div class="champ">
                <label>Votre message</label>
                <textarea name="contenu" rows="5" placeholder="Bonjour, je suis intéressé par votre annonce..." required></textarea>
            </div>
            <button type="submit" class="btn btn-principal">Envoyer le message</button>
        </form>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>