# Documentation Technique — Projet LeBonCoin
**Université** | Licence 1 Informatique | Année 2025-2026
**Étudiant :**MEGHARI ABDERRAHMANE TAREK & YESSINE MILED | **Date :** 14/04/2026

---

## 1. Architecture du projet

### 1.1 Structure des fichiers

```
LeBonCoin/
│
├── config/
│   └── database.php          ← Connexion PDO à MySQL
│
├── includes/
│   ├── session.php           ← Gestion sessions + fonctions sécurité
│   ├── header.php            ← En-tête HTML commun (navigation)
│   └── footer.php            ← Pied de page HTML commun
│
├── assets/
│   └── css/
│       └── style.css         ← Design CSS responsive complet
│
├── uploads/
│   └── annonces/             ← Photos uploadées par les utilisateurs
│
├── sql/
│   └── database.sql          ← Script de création de la base
│
├── index.php                 ← Page d'accueil + liste + filtres
├── inscription.php           ← Création de compte
├── connexion.php             ← Authentification
├── deconnexion.php           ← Destruction de session
├── mon-compte.php            ← Tableau de bord utilisateur
├── annonce-creer.php         ← Création d'annonce
├── annonce-detail.php        ← Page détail + messagerie
├── annonce-modifier.php      ← Modification d'annonce
├── annonce-supprimer.php     ← Suppression avec confirmation
├── favoris-toggle.php        ← Action ajout/retrait favori
├── mes-favoris.php           ← Liste des favoris
├── message-envoyer.php       ← Action envoi de message
└── mes-messages.php          ← Interface messagerie
```

### 1.2 Technologies

| Couche | Technologie | Version |
|--------|-------------|---------|
| Front-end | HTML5 + CSS3 | — |
| Back-end | PHP procédural + PDO | 8.0+ |
| Base de données | MySQL | 5.7+ |
| Serveur local | MAMP | 6+ |

### 1.3 Pattern architectural

Le projet suit le pattern **sans framework** (PHP natif) avec une séparation logique :
- **config/** : configuration (connexion BDD)
- **includes/** : composants réutilisables (header, footer, session)
- **Fichiers racine** : pages PHP (1 fichier = 1 fonctionnalité)
- **assets/** : ressources statiques (CSS)
- **uploads/** : fichiers uploadés par les utilisateurs

---

## 2. Connexion à la base de données

### 2.1 Méthode utilisée : PDO

PDO (PHP Data Objects) est utilisé pour toutes les interactions avec MySQL. Avantages :
- Compatible avec plusieurs bases de données
- Requêtes préparées natives (protection injection SQL)
- Gestion des erreurs via exceptions

### 2.2 Configuration (config/database.php)

```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '8889');      // Port MAMP
define('DB_NAME', 'leboncoin');
define('DB_USER', 'root');
define('DB_PASS', 'root');

function getPDO(): PDO {
    static $pdo = null;         // Singleton : 1 seule connexion
    if ($pdo === null) {
        $dsn = "mysql:host=...;port=...;dbname=...;charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}
```

### 2.3 Utilisation type dans les pages

```php
$pdo  = getPDO();
$stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = ?");
$stmt->execute([$id]);
$annonce = $stmt->fetch();
```

---

## 3. Système d'authentification

### 3.1 Inscription

1. Validation côté PHP : email valide, MDP ≥ 10 caractères, confirmation identique
2. Vérification unicité email en base (`SELECT id WHERE email = ?`)
3. Hashage du mot de passe : `password_hash($mdp, PASSWORD_DEFAULT)` → bcrypt
4. Insertion en base avec requête préparée

### 3.2 Connexion

1. Recherche de l'utilisateur par email (`SELECT * WHERE email = ?`)
2. Vérification du mot de passe : `password_verify($mdp_saisi, $hash_bdd)`
3. Si OK : création de session `$_SESSION['utilisateur_id']` + `$_SESSION['pseudo']`

### 3.3 Protection des pages

```php
// En haut de chaque page nécessitant une connexion :
requireConnexion();
// → si non connecté, redirige vers /connexion.php
```

---

## 4. Mesures de sécurité implémentées

### 4.1 Protection XSS (Cross-Site Scripting)

Toute variable affichée dans le HTML passe par la fonction `h()` :
```php
function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
// Utilisation : echo h($_GET['titre']);
// < devient &lt; → le script ne s'exécute pas
```

### 4.2 Protection injection SQL

Toutes les requêtes SQL utilisent des paramètres liés `?` :
```php
// SÉCURISÉ
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

// DANGEREUX — jamais faire ça
$pdo->query("SELECT * FROM users WHERE email = '$email'");
```

### 4.3 Hashage des mots de passe

```php
// À l'inscription : hashage bcrypt
$hash = password_hash($mdp, PASSWORD_DEFAULT);

// À la connexion : vérification sans déchiffrement
if (password_verify($mdp_saisi, $hash)) { ... }
```

### 4.4 Vérification de propriété (IDOR)

Avant toute modification ou suppression, on vérifie que l'utilisateur connecté est le propriétaire :
```php
if ((int)$annonce['utilisateur_id'] !== getUserId()) {
    header('Location: /index.php');
    exit;
}
```

### 4.5 Sécurité des uploads

```php
// 1. Vérification de l'extension
$ext = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
$autorisees = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
if (!in_array($ext, $autorisees)) { /* erreur */ }

// 2. Limitation de la taille (5 Mo)
if ($fichier['size'] > 5 * 1024 * 1024) { /* erreur */ }

// 3. Nom de fichier aléatoire
$nom = uniqid('photo_', true) . '.' . $ext;

// 4. Déplacement sécurisé
move_uploaded_file($fichier['tmp_name'], $destination);
```

---

## 5. Gestion des sessions

```php
// Démarrage (includes/session.php, inclus partout)
session_start();

// Connexion → stockage
$_SESSION['utilisateur_id'] = $user['id'];
$_SESSION['pseudo']         = $user['pseudo'];

// Déconnexion → destruction complète
$_SESSION = [];
session_destroy();

// Vérification sur chaque page protégée
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: /connexion.php');
    exit; // Toujours exit après header()
}
```

---

## 6. Gestion des fichiers uploadés

Les photos sont stockées dans `uploads/annonces/` avec un nom unique généré par `uniqid()`. Lors de la suppression d'une annonce, le fichier physique est supprimé avec `unlink()` avant de supprimer l'entrée en base.

---

## 7. Requêtes SQL dynamiques (filtres)

Pour les filtres, la requête est construite dynamiquement :

```php
$sql    = "SELECT a.*, u.pseudo FROM annonces a
           JOIN utilisateurs u ON a.utilisateur_id = u.id
           WHERE 1=1";   // 1=1 permet d'ajouter des AND sans condition initiale
$params = [];

if ($prix_max !== null) {
    $sql .= " AND a.prix <= ?";
    $params[] = $prix_max;
}
if ($categorie_id !== null) {
    $sql .= " AND a.categorie_id = ?";
    $params[] = $categorie_id;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
```

---

## 8. Intégrité référentielle

La suppression en cascade est gérée par MySQL via `ON DELETE CASCADE` :
- Supprimer un utilisateur → supprime ses annonces, favoris, messages
- Supprimer une annonce → supprime ses favoris et messages associés

Cela évite les données orphelines sans code PHP supplémentaire.

---

## 9. Prérequis d'installation

| Logiciel | Version minimale | Rôle |
|----------|-----------------|------|
| MAMP / XAMPP | MAMP 6+ | Serveur Apache + MySQL |
| PHP | 8.0 | Traitement back-end |
| MySQL | 5.7 | Base de données |
| Navigateur | Chrome/Firefox récent | Client web |

### Procédure d'installation

```
1. Copier le dossier LeBonCoin/ dans C:\MAMP\htdocs\
2. Démarrer MAMP (Apache + MySQL verts)
3. Ouvrir phpMyAdmin : http://localhost:8888/phpmyadmin
4. Importer sql/database.sql
5. Vérifier config/database.php (port 8889 pour MAMP)
6. Ouvrir http://localhost:8888/LeBonCoin/index.php
```

---

