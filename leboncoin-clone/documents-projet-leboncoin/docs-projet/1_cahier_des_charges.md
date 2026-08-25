# Cahier des Charges — Projet LeBonCoin
**Université** | Licence 1 Informatique | Année 2025-2026
**Étudiant :** MEGHARI ABDERRAHMANE TAREK & YESSINE MILED | **Groupe :** BACHLOR 1 GRP 2 

---

## 1. Présentation du projet

### 1.1 Contexte
Dans le cadre de notre formation en Licence 1 Informatique, nous devons réaliser un projet de développement web complet. Ce projet consiste à créer un site web de petites annonces en ligne, inspiré du site LeBonCoin.fr.

### 1.2 Objectif général
Concevoir et développer une application web permettant à des utilisateurs de publier, consulter et gérer des annonces de vente ou d'achat d'articles, avec un système de messagerie intégré.

### 1.3 Technologies utilisées
- **Front-end :** HTML5, CSS3
- **Back-end :** PHP 8 (procédural + PDO)
- **Base de données :** MySQL 5.7+
- **Serveur local :** MAMP / XAMPP
- **Versionnement :** Git (optionnel)

---

## 2. Acteurs du système

| Acteur | Description |
|--------|-------------|
| **Visiteur** | Utilisateur non connecté. Peut consulter les annonces et les filtrer. |
| **Membre** | Utilisateur inscrit et connecté. Peut créer, modifier, supprimer ses annonces, gérer ses favoris et envoyer des messages. |
| **Système** | Le serveur PHP/MySQL qui gère les données et la sécurité. |

---

## 3. Fonctionnalités requises

### 3.1 Module Mon Compte (Authentification)

| ID | Fonctionnalité | Priorité | Points |
|----|---------------|----------|--------|
| F01 | Inscription avec email + mot de passe hashé (≥ 10 caractères) | Haute | 2 |
| F02 | Connexion sécurisée avec gestion de session | Haute | 2 |
| F03 | Déconnexion avec destruction de session | Haute | 0 |
| F04 | Tableau de bord utilisateur (mes annonces, favoris, messages) | Moyenne | 0 |

### 3.2 Module Annonces

| ID | Fonctionnalité | Priorité | Points |
|----|---------------|----------|--------|
| F05 | Créer une annonce (titre, prix, description, photo obligatoires) | Haute | 2 |
| F06 | Modifier une annonce avec formulaire pré-rempli | Haute | 2 |
| F07 | Supprimer une annonce avec confirmation | Haute | 2 |
| F08 | Voir la liste des annonces en format cartes | Haute | 2 |
| F09 | Voir le détail complet d'une annonce | Haute | 1 |
| F10 | Filtrer les annonces par prix, catégorie, mot-clé | Moyenne | 2 |

### 3.3 Module Favoris

| ID | Fonctionnalité | Priorité | Points |
|----|---------------|----------|--------|
| F11 | Ajouter / retirer une annonce des favoris | Moyenne | 2 |
| F12 | Consulter la liste de ses favoris | Moyenne | 0 |

### 3.4 Module Messagerie

| ID | Fonctionnalité | Priorité | Points |
|----|---------------|----------|--------|
| F13 | Envoyer un message à un vendeur concernant une annonce | Haute | 2 |
| F14 | Consulter ses conversations | Haute | 2 |
| F15 | Suppression automatique des messages si l'annonce est supprimée | Haute | 0 |

**Total des points : 21**

---

## 4. Contraintes techniques

### 4.1 Sécurité (obligatoire)
- Mots de passe hashés avec `password_hash()` (algorithme bcrypt)
- Protection contre les injections SQL via les requêtes préparées PDO
- Protection contre le XSS via `htmlspecialchars()`
- Vérification de la propriété des ressources avant modification/suppression
- Sessions PHP sécurisées

### 4.2 Upload de fichiers
- Formats acceptés : JPG, JPEG, PNG, WEBP, GIF
- Taille maximale : 5 Mo par fichier
- Nommage unique via `uniqid()` pour éviter les conflits

### 4.3 Base de données
- Utilisation de clés étrangères avec `ON DELETE CASCADE`
- Encodage UTF-8 (utf8mb4) pour supporter les accents et emojis
- Index sur les colonnes fréquemment recherchées (email UNIQUE)

---

## 5. Livrables attendus

| Livrable | Description |
|----------|-------------|
| Code source | Ensemble des fichiers PHP, HTML, CSS, SQL |
| Base de données | Fichier SQL importable (database.sql) |
| Cahier des charges | Ce document |
| Documentation technique | Architecture, schéma BDD, explications |
| Documentation utilisateur | Guide d'utilisation du site |
| Diagramme de Gantt | Planning du projet |
| README | Instructions d'installation |

---

## 6. Contraintes de rendu

- Projet individuel ou en binôme (selon consigne du professeur)
- Code commenté en français
- Respect des bonnes pratiques de développement web
- Site fonctionnel en local avec MAMP/XAMPP

---


