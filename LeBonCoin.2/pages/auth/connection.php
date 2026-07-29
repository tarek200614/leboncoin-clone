<?php
$page_title = 'Connexion';
require_once __DIR__ . '/../../includes/header.php';

if (isset($_SESSION['utilisateur_id'])) {
    redirect('/index.php');
}

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT id, pseudo, mot_de_passe, role, status FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            if ($user['status'] === 'suspended') {
                $erreur = "Ce compte a été suspendu. Contactez l'administration.";
            } else {
                // Security: Regenerate session ID to prevent fixation
                session_regenerate_id(true);
                $_SESSION['utilisateur_id'] = $user['id'];
                $_SESSION['utilisateur_pseudo'] = $user['pseudo'];
                $_SESSION['utilisateur_role'] = $user['role'];
                
                // Update last login
                $update = $pdo->prepare("UPDATE utilisateurs SET last_login = NOW() WHERE id = ?");
                $update->execute([$user['id']]);

                set_flash('success', 'Connexion réussie !');
                redirect('/index.php');
            }
        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs correctement.";
    }
}
?>

<div style="max-width: 400px; margin: 0 auto; background: var(--bg-primary); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--border);">
    <h1 style="margin-bottom: 1.5rem; font-size: 1.5rem; text-align: center;">Se connecter</h1>
    <?php if ($erreur): ?><div class="alert alert-error"><?= e($erreur) ?></div><?php endif; ?>
    
    <form method="POST" action="">
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-input" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Se connecter</button>
    </form>
    <p style="text-align: center; margin-top: 1rem; font-size: 0.875rem;">
        Pas encore de compte ? <a href="inscription.php" style="color: var(--accent);">S'inscrire</a>
    </p>
</div>


    
