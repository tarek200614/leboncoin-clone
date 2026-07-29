<?php
/*
 * FICHIER : pages/annonces/liste.php
 * RÔLE    : Afficher toutes les annonces sous forme de cartes
 *           avec possibilité de filtrer par catégorie, prix, mot-clé
 *           BONUS : pagination + recherche full-text
 */

session_start();
require_once '../../config/db.php';

// ---- RÉCUPÉRATION DES CATÉGORIES (pour le menu de filtre) ----
$stmt_cats = $pdo->query("SELECT * FROM categories ORDER BY nom");
$categories = $stmt_cats->fetchAll();

// ---- CONSTRUCTION DE LA REQUÊTE AVEC FILTRES ----
$sql    = "SELECT a.*, u.pseudo, c.nom AS categorie_nom
           FROM annonces a
           JOIN utilisateurs u ON a.utilisateur_id = u.id
           LEFT JOIN categories c ON a.categorie_id = c.id
           WHERE 1=1";

$sql_count = "SELECT COUNT(*) FROM annonces a
              JOIN utilisateurs u ON a.utilisateur_id = u.id
              LEFT JOIN categories c ON a.categorie_id = c.id
              WHERE 1=1";

$params = [];

// Filtre par catégorie
$filtre_categorie = $_GET['categorie'] ?? '';
if (!empty($filtre_categorie)) {
    $sql .= " AND a.categorie_id = ?";
    $sql_count .= " AND a.categorie_id = ?";
    $params[] = $filtre_categorie;
}

// Filtre par prix minimum
$filtre_prix_min = $_GET['prix_min'] ?? '';
if (is_numeric($filtre_prix_min)) {
    $sql .= " AND a.prix >= ?";
    $sql_count .= " AND a.prix >= ?";
    $params[] = $filtre_prix_min;
}

// Filtre par prix maximum
$filtre_prix_max = $_GET['prix_max'] ?? '';
if (is_numeric($filtre_prix_max)) {
    $sql .= " AND a.prix <= ?";
    $sql_count .= " AND a.prix <= ?";
    $params[] = $filtre_prix_max;
}

// BONUS : Filtre par mot-clé (titre ou description)
$filtre_q = trim($_GET['q'] ?? '');
if (!empty($filtre_q)) {
    $sql .= " AND (a.titre LIKE ? OR a.description LIKE ?)";
    $sql_count .= " AND (a.titre LIKE ? OR a.description LIKE ?)";
    $params[] = '%' . $filtre_q . '%';
    $params[] = '%' . $filtre_q . '%';
}

// BONUS : Pagination
$par_page   = 9;
$page       = max(1, intval($_GET['page'] ?? 1));
$total_stmt = $pdo->prepare($sql_count);
$total_stmt->execute($params);
$total      = (int) $total_stmt->fetchColumn();
$nb_pages   = max(1, ceil($total / $par_page));
$page       = min($page, $nb_pages);
$offset     = ($page - 1) * $par_page;

// Tri : les plus récentes en premier
$sql .= " ORDER BY a.date_creation DESC LIMIT ? OFFSET ?";

$stmt = $pdo->prepare($sql);
// Binder les paramètres normaux (catégorie, prix, mot-clé)
$i = 1;
foreach ($params as $val) {
    $stmt->bindValue($i++, $val);
}
// LIMIT et OFFSET doivent être bindés comme PDO::PARAM_INT (sinon MySQL les met entre quotes)
$stmt->bindValue($i++, (int) $par_page, PDO::PARAM_INT);
$stmt->bindValue($i++, (int) $offset,   PDO::PARAM_INT);
$stmt->execute();
$annonces = $stmt->fetchAll();

require_once '../../includes/header.php';
?>

<main class="container">
    <h1>Toutes les annonces
        <?php if ($total > 0): ?>
            <small style="font-size:0.85rem;color:var(--gris)">(<?= $total ?> résultat<?= $total > 1 ? 's' : '' ?>)</small>
        <?php endif; ?>
    </h1>

    <!-- FORMULAIRE DE FILTRAGE -->
    <form method="GET" action="" class="filtres">
        <!-- BONUS : Champ recherche par mot-clé -->
        <input type="text" name="q" placeholder="🔍 Mot-clé..." value="<?= htmlspecialchars($filtre_q) ?>">

        <select name="categorie">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"
                    <?= ($filtre_categorie == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="number" name="prix_min" placeholder="Prix min (€)"
               value="<?= htmlspecialchars($filtre_prix_min) ?>">

        <input type="number" name="prix_max" placeholder="Prix max (€)"
               value="<?= htmlspecialchars($filtre_prix_max) ?>">

        <button type="submit" class="btn btn-filtre">Filtrer</button>
        <a href="liste.php" class="btn btn-secondaire">Réinitialiser</a>
    </form>

    <!-- GRILLE DE CARTES D'ANNONCES -->
    <?php if (empty($annonces)): ?>
        <p class="vide">Aucune annonce trouvée.</p>
    <?php else: ?>
        <div class="grille-annonces">
            <?php foreach ($annonces as $annonce): ?>
                <div class="carte-annonce">
                    <a href="detail.php?id=<?= $annonce['id'] ?>">
                        <img src="../../assets/uploads/<?= htmlspecialchars($annonce['photo']) ?>"
                             alt="<?= htmlspecialchars($annonce['titre']) ?>"
                             class="carte-photo">
                    </a>

                    <div class="carte-corps">
                        <?php if ($annonce['categorie_nom']): ?>
                            <span class="badge"><?= htmlspecialchars($annonce['categorie_nom']) ?></span>
                        <?php endif; ?>

                        <h2 class="carte-titre">
                            <a href="detail.php?id=<?= $annonce['id'] ?>">
                                <?= htmlspecialchars($annonce['titre']) ?>
                            </a>
                        </h2>

                        <p class="carte-prix"><?= number_format($annonce['prix'], 2, ',', ' ') ?> €</p>
                        <p class="carte-vendeur">Par <?= htmlspecialchars($annonce['pseudo']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- BONUS : Pagination -->
        <?php if ($nb_pages > 1): ?>
            <div class="pagination">
                <?php
                // Construire les paramètres GET sans 'page'
                $get_params = $_GET;
                unset($get_params['page']);
                $base_url = '?' . http_build_query($get_params) . '&page=';
                ?>
                <?php if ($page > 1): ?>
                    <a href="<?= $base_url . ($page - 1) ?>" class="btn btn-secondaire">← Précédent</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $nb_pages; $i++): ?>
                    <a href="<?= $base_url . $i ?>"
                       class="btn <?= ($i === $page) ? 'btn-principal' : 'btn-secondaire' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $nb_pages): ?>
                    <a href="<?= $base_url . ($page + 1) ?>" class="btn btn-secondaire">Suivant →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php require_once '../../includes/footer.php'; ?>