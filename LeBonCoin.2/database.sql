-- =============================================
-- Base de données : monsite
-- =============================================

CREATE DATABASE IF NOT EXISTS monsite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE monsite;

-- =============================================
-- TABLE : utilisateurs
-- Stocke les comptes des membres
-- =============================================
CREATE TABLE utilisateurs (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    email       VARCHAR(255) NOT NULL UNIQUE,   -- email unique pour chaque utilisateur
    mot_de_passe VARCHAR(255) NOT NULL,          -- mot de passe hashé (jamais en clair !)
    pseudo      VARCHAR(100) NOT NULL,
    date_inscription DATETIME DEFAULT NOW()
);

-- =============================================
-- TABLE : categories
-- Les catégories d'annonces (voiture, maison...)
-- =============================================
CREATE TABLE categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    nom  VARCHAR(100) NOT NULL
);

-- Quelques catégories de départ
INSERT INTO categories (nom) VALUES
    ('Véhicules'),
    ('Immobilier'),
    ('Électronique'),
    ('Mode'),
    ('Maison & Jardin'),
    ('Loisirs'),
    ('Emploi'),
    ('Autres');

-- =============================================
-- TABLE : annonces
-- Les annonces publiées par les utilisateurs
-- =============================================
CREATE TABLE annonces (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    categorie_id INT,
    titre        VARCHAR(255) NOT NULL,
    description  TEXT NOT NULL,
    prix         DECIMAL(10,2) NOT NULL,
    photo        VARCHAR(255) NOT NULL,          -- nom du fichier uploadé
    date_creation DATETIME DEFAULT NOW(),
    -- Lien vers la table utilisateurs (suppression en cascade)
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- =============================================
-- TABLE : favoris
-- Les annonces mises en favori par les utilisateurs
-- =============================================
CREATE TABLE favoris (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    annonce_id     INT NOT NULL,
    -- Un utilisateur ne peut pas mettre la même annonce en favori deux fois
    UNIQUE KEY unique_favori (utilisateur_id, annonce_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE
);

-- =============================================
-- TABLE : messages
-- Les messages entre utilisateurs concernant une annonce
-- =============================================
CREATE TABLE messages (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id   INT NOT NULL,
    expediteur_id INT NOT NULL,               -- celui qui envoie
    destinataire_id INT NOT NULL,             -- celui qui reçoit
    contenu      TEXT NOT NULL,
    date_envoi   DATETIME DEFAULT NOW(),
    lu           TINYINT(1) DEFAULT 0,        -- 0 = non lu, 1 = lu
    -- Si l'annonce est supprimée, les messages le sont aussi (CASCADE)
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    FOREIGN KEY (expediteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (destinataire_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);