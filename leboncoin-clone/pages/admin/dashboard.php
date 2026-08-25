<?php
$page_title = 'Tableau de bord Admin';
require_once __DIR__ . '/../../includes/header.php';
require_admin();

// Stats
$total_users = $pdo->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
$total_ads = $pdo->query("SELECT COUNT(*) FROM annonces")->fetchColumn();
$total_messages = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
$recent_ads = $pdo->query("SELECT a.titre, u.pseudo, a.date_creation FROM annonces a JOIN utilisateurs u ON a.utilisateur_id = u.id ORDER BY a.date_creation DESC LIMIT 5")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Tableau de bord Administrateur</h1>
    <a href="<?= url('/pages/admin/users.php') ?>" class="btn btn-secondary">👥 Gérer les utilisateurs</a>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: var(--bg-primary); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border); text-align: center;">
        <div style="font-size: 2rem; font-weight: 700; color: var(--accent);"><?= $total_users ?></div>
        <div style="color: var(--text-secondary); font-size: 0.875rem;">Utilisateurs</div>
    </div>
    <div style="background: var(--bg-primary); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border); text-align: center;">
        <div style="font-size: 2rem; font-weight: 700; color: var(--success);"><?= $total_ads ?></div>
        <div style="color: var(--text-secondary); font-size: 0.875rem;">Annonces</div>
    </div>
    <div style="background: var(--bg-primary); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border); text-align: center;">
        <div style="font-size: 2rem; font-weight: 700; color: var(--danger);"><?= $total_messages ?></div>
        <div style="color: var(--text-secondary); font-size: 0.875rem;">Messages</div>
    </div>
</div>

<div style="background: var(--bg-primary); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border);">
    <h2 style="margin-bottom: 1rem; font-size: 1.25rem;">Dernières annonces publiées</h2>
    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
        <thead>
            <tr style="border-bottom: 1px solid var(--border); text-align: left;">
                <th style="padding: 0.75rem;">Titre</th>
                <th style="padding: 0.75rem;">Vendeur</th>
                <th style="padding: 0.75rem;">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recent_ads as $ad): ?>
            <tr style="border-bottom: 1px solid var(--border);">
                <td style="padding: 0.75rem;"><?= e($ad['titre']) ?></td>
                <td style="padding: 0.75rem;"><?= e($ad['pseudo']) ?></td>
                <td style="padding: 0.75rem; color: var(--text-secondary);"><?= date('d/m/Y H:i', strtotime($ad['date_creation'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
