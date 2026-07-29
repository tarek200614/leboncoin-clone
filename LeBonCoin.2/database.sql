CREATE DATABASE IF NOT EXISTS leboncoin_clone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE leboncoin_clone;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    pseudo VARCHAR(100) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    avatar VARCHAR(255) DEFAULT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    status ENUM('active', 'suspended') DEFAULT 'active',
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_token_expires DATETIME DEFAULT NULL,
    last_login DATETIME DEFAULT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO categories (nom, slug) VALUES
('Véhicules', 'vehicules'), ('Immobilier', 'immobilier'), ('Électronique', 'electronique'),
('Mode', 'mode'), ('Maison & Jardin', 'maison-jardin'), ('Loisirs', 'loisirs'),
('Emploi', 'emploi'), ('Autres', 'autres');

CREATE TABLE annonces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    categorie_id INT,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    location VARCHAR(255) DEFAULT NULL,
    views INT DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_categorie (categorie_id),
    INDEX idx_utilisateur (utilisateur_id),
    INDEX idx_date_creation (date_creation)
);

CREATE TABLE annonce_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    INDEX idx_annonce (annonce_id)
);

CREATE TABLE favoris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    annonce_id INT NOT NULL,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_favori (utilisateur_id, annonce_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    expediteur_id INT NOT NULL,
    destinataire_id INT NOT NULL,
    contenu TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    lu TINYINT(1) DEFAULT 0,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    FOREIGN KEY (expediteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (destinataire_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    INDEX idx_destinataire_lu (destinataire_id, lu)
);

