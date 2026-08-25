# Documentation BDD — Projet LeBonCoin
**Université** | Licence 1 Informatique | Année 2025-2026
**Étudiant :** MEGHARI ABDERRAHMANE TAREK & YESSINE MILED
---

## 1. Présentation de la base de données

**Nom de la base :** `leboncoin`
**Encodage :** UTF-8 (utf8mb4) — supporte les accents, emojis et caractères spéciaux
**Système :** MySQL 5.7+
**Nombre de tables :** 5

---

## 2. Schéma relationnel

```
utilisateurs
+----+--------+----------+-------+------------+
| id | pseudo | email    | mdp   | created_at |
+----+--------+----------+-------+------------+
  |
  | 1..N (un utilisateur peut avoir plusieurs annonces)
  v
annonces
+----+---------------+-------+--------+------+-------+------------+
| id | utilisateur_id| titre | descr. | prix | photo | created_at |
+----+---------------+-------+--------+------+-------+------------+
        |                  |
        |                  | 1..N
        |                  v
        |              favoris
        |         +----+---------------+-----------+
        |         | id | utilisateur_id| annonce_id|
        |         +----+---------------+-----------+
        |
        | 1..N (une annonce peut avoir plusieurs messages)
        v
    messages
    +----+-----------+---------------+----------------+---------+----+
    | id | annonce_id| expediteur_id | destinataire_id| contenu | lu |
    +----+-----------+---------------+----------------+---------+----+

categories (table indépendante)
+----+-----+
| id | nom |
+----+-----+
```

---

## 3. Description détaillée des tables

### 3.1 Table `utilisateurs`

| Colonne | Type | Contrainte | Description |
|---------|------|------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identifiant unique |
| email | VARCHAR(255) | NOT NULL, UNIQUE | Email de connexion |
| mot_de_passe | VARCHAR(255) | NOT NULL | Hash bcrypt du mot de passe |
| pseudo | VARCHAR(100) | NOT NULL | Nom affiché sur le site |
| created_at | DATETIME | DEFAULT NOW() | Date d'inscription |

### 3.2 Table `categories`

| Colonne | Type | Contrainte | Description |
|---------|------|------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identifiant unique |
| nom | VARCHAR(100) | NOT NULL | Nom de la catégorie |

**Données initiales :** Véhicules, Immobilier, Informatique, Mode, Maison, Loisirs, Autres

### 3.3 Table `annonces`

| Colonne | Type | Contrainte | Description |
|---------|------|------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identifiant unique |
| utilisateur_id | INT | FK → utilisateurs(id) CASCADE | Propriétaire |
| categorie_id | INT | FK → categories(id) SET NULL | Catégorie (optionnel) |
| titre | VARCHAR(255) | NOT NULL | Titre de l'annonce |
| description | TEXT | NOT NULL | Description détaillée |
| prix | DECIMAL(10,2) | NOT NULL | Prix en euros |
| photo | VARCHAR(255) | NOT NULL | Nom du fichier image |
| created_at | DATETIME | DEFAULT NOW() | Date de publication |
| updated_at | DATETIME | ON UPDATE NOW() | Date de dernière modification |

### 3.4 Table `favoris`

| Colonne | Type | Contrainte | Description |
|---------|------|------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identifiant unique |
| utilisateur_id | INT | FK → utilisateurs(id) CASCADE | Utilisateur |
| annonce_id | INT | FK → annonces(id) CASCADE | Annonce mise en favori |
| created_at | DATETIME | DEFAULT NOW() | Date d'ajout |
| — | — | UNIQUE(utilisateur_id, annonce_id) | Pas de doublon |

### 3.5 Table `messages`

| Colonne | Type | Contrainte | Description |
|---------|------|------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identifiant unique |
| annonce_id | INT | FK → annonces(id) CASCADE | Annonce concernée |
| expediteur_id | INT | FK → utilisateurs(id) CASCADE | Qui envoie |
| destinataire_id | INT | FK → utilisateurs(id) CASCADE | Qui reçoit |
| contenu | TEXT | NOT NULL | Texte du message |
| lu | TINYINT(1) | DEFAULT 0 | 0=non lu, 1=lu |
| created_at | DATETIME | DEFAULT NOW() | Date d'envoi |

---

## 4. Relations et clés étrangères

| Relation | Type | Action suppression |
|----------|------|--------------------|
| annonces.utilisateur_id → utilisateurs.id | N:1 | CASCADE |
| annonces.categorie_id → categories.id | N:1 | SET NULL |
| favoris.utilisateur_id → utilisateurs.id | N:1 | CASCADE |
| favoris.annonce_id → annonces.id | N:1 | CASCADE |
| messages.annonce_id → annonces.id | N:1 | CASCADE |
| messages.expediteur_id → utilisateurs.id | N:1 | CASCADE |
| messages.destinataire_id → utilisateurs.id | N:1 | CASCADE |

**Explication ON DELETE CASCADE :** Quand une annonce est supprimée, MySQL supprime automatiquement tous ses favoris et messages associés. Cela garantit l'intégrité des données.

---

## 5. Requêtes SQL importantes

### Récupérer toutes les annonces avec filtres
```sql
SELECT a.*, u.pseudo, c.nom AS categorie_nom
FROM annonces a
JOIN utilisateurs u ON a.utilisateur_id = u.id
LEFT JOIN categories c ON a.categorie_id = c.id
WHERE a.prix >= 100 AND a.categorie_id = 3
ORDER BY a.created_at DESC;
```

### Compter les messages non lus
```sql
SELECT COUNT(*) FROM messages
WHERE destinataire_id = 5 AND lu = 0;
```

### Vérifier si un favori existe
```sql
SELECT id FROM favoris
WHERE utilisateur_id = 3 AND annonce_id = 12;
```

---

## 6. Instructions d'import

```bash
# Via la ligne de commande MySQL
mysql -u root -p leboncoin < sql/database.sql

# Via phpMyAdmin (MAMP)
# 1. Ouvrir http://localhost:8888/phpmyadmin
# 2. Cliquer sur "Importer"
# 3. Sélectionner sql/database.sql
# 4. Cliquer "Exécuter"
```
