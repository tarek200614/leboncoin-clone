# MonBonCoin — README
**Projet Web | Licence 1 Informatique | 2025-2026**

---

## Description

MonBonCoin est un site web de petites annonces développé en PHP/MySQL, inspiré de LeBonCoin.fr. Il permet aux utilisateurs de publier, consulter et gérer des annonces, de mettre des favoris et d'échanger des messages avec les vendeurs.

---

## Fonctionnalités

- Inscription et connexion sécurisées (bcrypt + sessions PHP)
- Création, modification et suppression d'annonces avec photo
- Filtrage des annonces par prix, catégorie et mot-clé
- Système de favoris
- Messagerie interne entre acheteurs et vendeurs
- Tableau de bord utilisateur

---

## Technologies

| Technologie | Usage |
|-------------|-------|
| PHP 8.0+ (PDO) | Back-end et accès base de données |
| MySQL 5.7+ | Stockage des données |
| HTML5 / CSS3 | Interface utilisateur responsive |
| MAMP | Serveur local de développement |

---

## Installation rapide

### Prérequis
- MAMP installé et lancé (Apache + MySQL verts)

### Étapes

**1. Placer le projet**
```
Copier le dossier LeBonCoin/ dans :
C:\MAMP\htdocs\LeBonCoin\
```

**2. Importer la base de données**
```
Ouvrir : http://localhost:8888/phpmyadmin
→ Cliquer "Importer"
→ Sélectionner sql/database.sql
→ Cliquer "Exécuter"
```

**3. Configurer la connexion** (si nécessaire)

Ouvrir `config/database.php` et vérifier :
```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '8889');    // Port par défaut MAMP
define('DB_USER', 'root');    // Identifiant par défaut MAMP
define('DB_PASS', 'root');    // Mot de passe par défaut MAMP
define('DB_NAME', 'leboncoin');
```

**4. Accéder au site**
```
http://localhost:8888/LeBonCoin/index.php
```

---

## Structure du projet

```
LeBonCoin/
├── config/database.php       ← Connexion PDO
├── includes/
│   ├── session.php           ← Sessions + sécurité
│   ├── header.php            ← En-tête commun
│   └── footer.php            ← Pied de page commun
├── assets/css/style.css      ← Design CSS
├── uploads/annonces/         ← Photos uploadées
├── sql/database.sql          ← Script base de données
├── index.php                 ← Accueil + filtres
├── inscription.php           ← Créer un compte
├── connexion.php             ← Se connecter
├── deconnexion.php           ← Se déconnecter
├── mon-compte.php            ← Tableau de bord
├── annonce-creer.php         ← Nouvelle annonce
├── annonce-detail.php        ← Détail + contact vendeur
├── annonce-modifier.php      ← Modifier annonce
├── annonce-supprimer.php     ← Supprimer annonce
├── favoris-toggle.php        ← Gestion favoris
├── mes-favoris.php           ← Mes favoris
├── message-envoyer.php       ← Envoyer un message
└── mes-messages.php          ← Messagerie complète
```

---

## Sécurité

- Mots de passe hashés avec `password_hash()` (bcrypt)
- Protection XSS via `htmlspecialchars()` sur toutes les sorties
- Protection injection SQL via requêtes préparées PDO
- Vérification de propriété avant modification/suppression
- Validation des fichiers uploadés (extension + taille)
- Sessions PHP sécurisées

---

## Problèmes fréquents

| Erreur | Cause | Solution |
|--------|-------|----------|
| Page blanche | Erreur PHP cachée | Ajouter `ini_set('display_errors', 1);` en haut de index.php |
| Erreur BDD | Mauvaise config | Vérifier port (8889), user/pass dans config/database.php |
| 404 | Mauvaise URL | Utiliser http://localhost:8888/LeBonCoin/index.php |
| Dossier double | Archive mal extraite | Vérifier que index.php est dans htdocs/LeBonCoin/ directement |
| Photo refusée | Fichier trop lourd | Max 5 Mo, formats JPG/PNG/WEBP uniquement |

---

## Auteur

**Nom :** _____________________
**Email :** _____________________
**Formation :** Licence 1 Informatique
**Année :** 2025-2026

---

*Projet réalisé dans le cadre du cours de Développement Web — L1 Informatique*
