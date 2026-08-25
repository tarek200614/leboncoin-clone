<?php
$page_title = 'Toutes les annonces';
$og_desc = 'Parcourez les dernières petites annonces de véhicules, immobilier, électronique et plus.';
require_once __DIR__ . '/../../includes/header.php';

// Pagination & Filter Setup
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 12;
$offset = ($page - 1) * $per_page;

$search = trim($_GET['search'] ?? '');
$categorie_id = filter_var($_GET['categorie'] ?? 0, FILTER_VALIDATE_INT);

// Build Query
$where = [];
$params = [];

if ($search !== '') {
    $where[] = "(a.titre LIKE :search OR a.description LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}
if ($categorie_id > 0) {
    $where[] = "a.categorie_id = :categorie";
    $params[':categorie'] = $categorie_id;
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Count total for pagination
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM annonces a $where_sql");
$count_stmt->execute($params);
$total_ads = (int)$count_stmt->fetchColumn();
$total_pages = ceil($total_ads / $per_page);

// Fetch ads
$query = "SELECT a.id, a.titre, a.prix, a.date_creation, a.location, img.image_path, u.pseudo, c.nom AS cat_nom
          FROM annonces a
          JOIN utilisateurs u ON a.utilisateur_id = u.id
          LEFT JOIN annonce_images img ON a.id = img.annonce_id AND img.is_primary = 1
          LEFT JOIN categories c ON a.categorie_id = c.id
          $where_sql
          ORDER BY a.date_creation DESC
          LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->execute();
$ads = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
?>

<div class="search-bar-container">
    <form method="GET" action="" class="search-form" role="search">
        <input type="text" name="search" placeholder="Rechercher une annonce..." class="form-input" value="<?= e($search) ?>" aria-label="Recherche textuelle">
        <select name="categorie" class="form-select" aria-label="Filtrer par catégorie">
            <option value="0">Toutes les catégories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $categorie_id === $cat['id'] ? 'selected' : '' ?>><?= e($cat['nom']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Rechercher</button>
    </form>
</div>

<?php if (empty($ads)): ?>
    <div class="empty-state">
        <h2>Aucune annonce trouvée</h2>
        <p>Essayez de modifier vos critères de recherche ou <a href="<?= url('/pages/annonces/creer.php') ?>">déposez la première annonce</a>.</p>
    </div>
<?php else: ?>
    <div class="grid">
        <?php foreach ($ads as $ad): ?>
            <article class="card">
                <a href="<?= url('/pages/annonces/detail.php?id=' . $ad['id']) ?>">
                    <?php if ($ad['image_path']): ?>
                        <img src="<?= url('/assets/uploads/' . e($ad['image_path'])) ?>" alt="<?= e($ad['titre']) ?>" class="card-img" loading="lazy">
                    <?php else: ?>
                        <div class="card-img card-img-placeholder" aria-hidden="true">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                        </div>
                    <?php endif; ?>
                </a>
                <div class="card-body">
                    <div class="card-category"><?= e($ad['cat_nom'] ?? 'Divers') ?></div>
                    <h2 class="card-title"><a href="<?= url('/pages/annonces/detail.php?id=' . $ad['id']) ?>"><?= e($ad['titre']) ?></a></h2>
                    <div class="card-price"><?= number_format((float)$ad['prix'], 2, ',', ' ') ?> €</div>
                    <div class="card-meta">
                        <span><?= e($ad['location'] ?: 'Non spécifié') ?></span> • 
                        <span><?= date('d/m/Y', strtotime($ad['date_creation'])) ?></span>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1): ?>
        <nav class="pagination" aria-label="Pagination des annonces">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $categorie_id ? '&categorie=' . $categorie_id : '' ?>" 
                   class="page-link <?= $i === $page ? 'active' : '' ?>" 
                   <?= $i === $page ? 'aria-current="page"' : '' ?>>
                   <?= $i ?>
                </a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>

