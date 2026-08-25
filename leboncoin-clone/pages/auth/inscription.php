<?php
$page_title = 'Inscription';
require_once __DIR__ . '/../../includes/header.php';

$erreurs = [];
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    
    $pseudo = trim($_POST['pseudo'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (strlen($pseudo) < 3) $erreurs[] = "Le pseudo doit faire au moins 3 caractères.";
    if (!$email) $erreurs[] = "L'email n'est pas valide.";
    if (strlen($password) < 8) $erreurs[] = "Le mot de passe doit faire au moins 8 caractères.";
    if ($password !== $password_confirm) $erreurs[] = "Les mots de passe ne correspondent pas.";

    if (empty($erreurs)) {
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erreurs[] = "Cet email est déjà utilisé.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, pseudo, mot_de_passe) VALUES (?, ?, ?)");
            if ($stmt->execute([$email, $pseudo, $hash])) {
                set_flash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
                redirect('/pages/auth/connection.php');
            } else {
                $erreurs[] = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>

<div style="max-width: 400px; margin: 0 auto; background: var(--bg-primary); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--border);">
    <h1 style="margin-bottom: 1.5rem; font-size: 1.5rem; text-align: center;">Créer un compte</h1>
    
    <?php if (!empty($erreurs)): ?>
        <div class="alert alert-error"><ul><?php foreach ($erreurs as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <form method="POST" action="">
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label" for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" class="form-input" required value="<?= e($_POST['pseudo'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-input" required value="<?= e($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-input" required>
            <div class="strength-meter"><div class="strength-meter-fill" id="strength-meter"></div></div>
            <small id="strength-text" style="color: var(--text-secondary); font-size: 0.75rem;"></small>
        </div>
        <div class="form-group">
            <label class="form-label" for="password_confirm">Confirmer le mot de passe</label>
            <input type="password" id="password_confirm" name="password_confirm" class="form-input" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">S'inscrire</button>
    </form>
    <p style="text-align: center; margin-top: 1rem; font-size: 0.875rem;">
        Déjà un compte ? <a href="<?= url('/pages/auth/connection.php') ?>" style="color: var(--accent);">Se connecter</a>
    </p>
</div>
