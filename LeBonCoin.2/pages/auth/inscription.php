<?php
/*
 * FICHIER : pages/auth/inscription.php
 * RÔLE    : Afficher le formulaire d'inscription et traiter les données
 */

session_start();                          // Démarre la session PHP
require_once '../../config/db.php';       // Connexion BDD

$erreurs = [];   // Tableau pour stocker les messages d'erreur
$succes  = '';   // Message de succès

// On traite le formulaire seulement si l'utilisateur a cliqué sur "S'inscrire"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupérer et nettoyer les données du formulaire
    // trim() supprime les espaces en début/fin de chaîne
    $email       = trim($_POST['email'] ?? '');
    $pseudo      = trim($_POST['pseudo'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    // ---- VALIDATIONS ----

    // L'email est-il rempli ?
    if (empty($email)) {
        $erreurs[] = "L'email est obligatoire.";
    }
    // L'email est-il valide ? (ex: test@gmail.com)
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'email n'est pas valide.";
    }

    // Le pseudo est-il rempli ?
    if (empty($pseudo)) {
        $erreurs[] = "Le pseudo est obligatoire.";
    }

    // Le mot de passe fait-il au moins 10 caractères ? (requis dans le cahier des charges)
    if (strlen($mot_de_passe) < 10) {
        $erreurs[] = "Le mot de passe doit contenir au moins 10 caractères.";
    }

    // Si aucune erreur, on peut créer le compte
    if (empty($erreurs)) {

        // Vérifier si l'email existe déjà en base de données
        // REQUÊTE PRÉPARÉE : on n'insère jamais directement les variables dans le SQL !
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            // L'email est déjà utilisé
            $erreurs[] = "Cet email est déjà utilisé.";
        } else {
            // Hasher le mot de passe avant de le stocker
            // password_hash() est la fonction sécurisée recommandée
            $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            // Insérer le nouvel utilisateur
            $stmt = $pdo->prepare("
                INSERT INTO utilisateurs (email, pseudo, mot_de_passe)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$email, $pseudo, $hash]);

            $succes = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        }
    }
}

require_once '../../includes/header.php';
?>

<main class="container">
    <div class="form-box">
        <h1>Créer un compte</h1>

        <!-- Affichage des erreurs -->
        <?php if (!empty($erreurs)): ?>
            <div class="alert alert-erreur">
                <ul>
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?= htmlspecialchars($erreur) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Affichage du succès -->
        <?php if ($succes): ?>
            <div class="alert alert-succes">
                <?= htmlspecialchars($succes) ?>
                <a href="connection.php">→ Se connecter</a>
            </div>
        <?php endif; ?>

        <!-- Formulaire POST -->
        <form method="POST" action="">
            <div class="champ">
                <label for="pseudo">Pseudo</label>
                <!-- htmlspecialchars() pour éviter les injections XSS -->
                <input type="text" id="pseudo" name="pseudo"
                       value="<?= htmlspecialchars($pseudo ?? '') ?>"
                       placeholder="VotreNom123" required>
            </div>

            <div class="champ">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($email ?? '') ?>"
                       placeholder="exemple@gmail.com" required>
            </div>

            <div class="champ">
                <label for="mot_de_passe">Mot de passe <small>(10 caractères minimum)</small></label>
                <input type="password" id="mot_de_passe" name="mot_de_passe"
                       minlength="10" required>
            </div>

            <button type="submit" class="btn btn-principal">S'inscrire</button>
        </form>

        <p class="lien-bas">Déjà un compte ? <a href="connection.php">Se connecter</a></p>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>