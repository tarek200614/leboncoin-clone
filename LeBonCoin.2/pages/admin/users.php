<?php
$page_title = 'Gestion des Utilisateurs';
require_once __DIR__ . '/../../includes/header.php';
require_admin();

// Handle Suspend/Activate
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['user_id'])) {
    verify_csrf();
    $user_id = (int)$_POST['user_id'];
    $action = $_POST['action'];
    
    if ($user_id === (int)$_SESSION['utilisateur_id']) {
        set_flash('error', 'Vous ne pouvez pas modifier votre propre statut.');
    } else {
        $new_status = ($action === 'suspend') ? 'suspended' : 'active';
        $stmt = $pdo->prepare("UPDATE utilisateurs SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $user_id]);
        set_flash('success', "Statut de l'utilisateur mis à jour.");
    }
    redirect('/pages/admin/users.php');
}

$users = $pdo->query("SELECT id, pseudo, email, role, status, date_inscription FROM utilisateurs ORDER BY date_inscription DESC")->fetchAll();
?>

<h1 style="margin-bottom: 1.5rem;">Gestion des Utilisateurs</h1>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Inscription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><strong><?= e($user['pseudo']) ?></strong></td>
                <td><?= e($user['email']) ?></td>
                <td><span class="badge badge-<?= $user['role'] === 'admin' ? 'admin' : 'user' ?>"><?= ucfirst($user['role']) ?></span></td>
                <td>
                    <span class="badge badge-<?= $user['status'] === 'active' ? 'success' : 'danger' ?>">
                        <?= $user['status'] === 'active' ? 'Actif' : 'Suspendu' ?>
                    </span>
                </td>
                <td><?= date('d/m/Y', strtotime($user['date_inscription'])) ?></td>
                <td>
                    <?php if ($user['id'] !== $_SESSION['utilisateur_id']): ?>
                        <form method="POST" action="" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <?php if ($user['status'] === 'active'): ?>
                                <input type="hidden" name="action" value="suspend">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Suspendre ce compte ?')">Suspendre</button>
                            <?php else: ?>
                                <input type="hidden" name="action" value="activate">
                                <button type="submit" class="btn btn-sm btn-secondary">Réactiver</button>
                            <?php endif; ?>
                        </form>
                    <?php else: ?>
                        <span style="color: var(--text-secondary); font-size: 0.75rem;">Vous</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
