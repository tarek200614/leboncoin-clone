<?php
/*
 * FICHIER : pages/auth/connection.php
 * RÔLE    : Afficher le formulaire de connexion et vérifier les identifiants
 */

session_start();
require_once '../../config/db.php';

// Si l'utilisateur est déjà connecté, le rediriger vers l'accueil
if (isset($_SESSION['utilisateur_id'])) {
    header('Location: ../../index.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email        = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    // Chercher l'utilisateur par son email
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch();

    // password_verify() compare le mot de passe saisi avec le hash stocké
    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {

        // Connexion réussie : on stocke les infos en session
        $_SESSION['utilisateur_id']   = $utilisateur['id'];
        $_SESSION['utilisateur_pseudo'] = $utilisateur['pseudo'];

        // Redirection vers la page d'accueil
        header('Location: ../../index.php');
        exit;

    } else {
        // Email ou mot de passe incorrect
        $erreur = "Email ou mot de passe incorrect.";
    }
}

require_once '../../includes/header.php';
?>

<main class="container">
    <div class="form-box">
        <h1>Se connecter</h1>

        <?php if ($erreur): ?>
            <div class="alert alert-erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="champ">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="champ">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <button type="submit" class="btn btn-principal">Se connecter</button>
        </form>

        <p class="lien-bas">Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>