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
-- Insert realistic French users
INSERT INTO utilisateurs (email, pseudo, mot_de_passe, role, status, last_login) VALUES
('jean.martin@email.fr', 'JeanMartin75', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', NOW()),
('sophie.bernard@email.fr', 'SophieB_Lyon', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', NOW()),
('lucas.moreau@email.fr', 'LucasMoreau', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', NOW()),
('emma.dubois@email.fr', 'EmmaDubois33', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', NOW()),
('thomas.petit@email.fr', 'ThomasPetit', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', NOW()),
('marie.rousseau@email.fr', 'MarieR_Paris', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', NOW()),
('nicolas.laurent@email.fr', 'NicolasLaurent', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', NOW()),
('camille.simon@email.fr', 'CamilleSimon', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', NOW()),
('admin@leboncoin-clone.fr', 'AdminLBC', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NOW());
-- 1. VÉHICULES - Renault Clio 2019
INSERT INTO annonces (utilisateur_id, categorie_id, titre, description, prix, location, date_creation, views) VALUES
(1, 1, 'Renault Clio IV 1.5 dCi - Excellent état', 
'Renault Clio IV de 2019 en excellent état, première main. Entretien régulier chez concessionnaire Renault. Carnet d''entretien à jour. Climatisation, GPS intégré, régulateur de vitesse, radar de recul. Très économique (4.2L/100km). Contrôle technique OK. Visible sur rendez-vous. Possibilité essai.',
12500, 'Lyon (69000)', DATE_SUB(NOW(), INTERVAL 3 DAY), 47);

-- 2. ÉLECTRONIQUE - MacBook Pro
INSERT INTO annonces (utilisateur_id, categorie_id, titre, description, prix, location, date_creation, views) VALUES
(2, 3, 'MacBook Pro 13" 2020 - M1 - 8GB/256GB',
'MacBook Pro 13 pouces avec puce Apple M1, 8GB RAM, SSD 256GB. Acheté en mars 2021, encore sous garantie Apple Care jusqu''en mars 2024. Très peu utilisé (télétravail). État impeccable, aucune rayure. Batterie à 96% de capacité. Vendu avec chargeur MagSafe, boîte d''origine et facture. Cause déménagement à l''étranger.',
1250, 'Paris 11ème (75011)', DATE_SUB(NOW(), INTERVAL 1 DAY), 128);

-- 3. IMMOBILIER - Appartement T3
INSERT INTO annonces (utilisateur_id, categorie_id, titre, description, prix, location, date_creation, views) VALUES
(3, 2, 'Appartement T3 lumineux - 65m² - Bordeaux Centre',
'Bel appartement T3 de 65m² situé au 3ème étage avec ascenseur, proche tramway ligne B. Composé d''un séjour lumineux de 28m² avec balcon, 2 chambres, cuisine équipée, salle de bain avec baignoire, WC séparés. Cave et parking inclus. Chauffage gaz individuel. Charges de copropriété: 120€/mois. Idéal investissement locatif ou premier achat. DPE: C. Disponible immédiatement.',
185000, 'Bordeaux (33000)', DATE_SUB(NOW(), INTERVAL 5 DAY), 234);

-- 4. MODE - Nike Air Jordan
INSERT INTO annonces (utilisateur_id, categorie_id, titre, description, prix, location, date_creation, views) VALUES
(4, 4, 'Nike Air Jordan 1 Mid - Taille 42 - Neuves',
'Nike Air Jordan 1 Mid coloris "Shadow Grey" taille 42 (US 8.5). Neuves, jamais portées, encore dans la boîte d''origine avec étiquettes. Achetées sur le site Nike en octobre 2023 (facture disponible). Coloris gris/anthracite/blanc très polyvalent. Pointure parfaite, je les vends car doublon. Possibilité envoi Mondial Relay ou remise en main propre sur Bordeaux.',
135, 'Bordeaux (33000)', DATE_SUB(NOW(), INTERVAL 2 DAY), 89);

-- 5. MAISON & JARDIN - Canapé d'angle
INSERT INTO annonces (utilisateur_id, categorie_id, titre, description, prix, location, date_creation, views) VALUES
(5, 5, 'Canapé d''angle convertible gris anthracite - Comme neuf',
'Magnifique canapé d''angle convertible en tissu gris anthracite, dimensions: 280x180cm. Acheté 1200€ chez Conforama en 2022. Très bon état, aucune tache, structure solide. Coffre de rangement intégré, couchage 140x190cm avec matelas inclus. Pieds en métal chromé. À venir chercher sur place (appartement au RDC, facile d''accès). Cause déménagement.',
450, 'Nantes (44000)', DATE_SUB(NOW(), INTERVAL 4 DAY), 156);

-- 6. LOISIRS - Vélo de route
INSERT INTO annonces (utilisateur_id, categorie_id, titre, description, prix, location, date_creation, views) VALUES
(6, 6, 'Vélo de route Triban RC520 - Taille M - Excellent rapport qualité/prix',
'Vélo de route Triban RC520 taille M (convient pour 1m70-1m80). Cadre aluminium, fourche carbone, groupe Shimano 105 11 vitesses. Freins à disque hydrauliques. Poids: 9.2kg. Acheté chez Decathlon en juin 2022, très peu utilisé (environ 500km). Révision complète effectuée en mars 2024 (facture 120€). Vendu avec pédales automatiques Look, porte-bidon et compteur GPS. Parfait pour débuter ou progresser.',
680, 'Toulouse (31000)', DATE_SUB(NOW(), INTERVAL 6 DAY), 92);

-- 7. EMPLOI - Développeur Web Junior
INSERT INTO annonces (utilisateur_id, categorie_id, titre, description, prix, location, date_creation, views) VALUES
(7, 7, 'Développeur Web Full-Stack Junior - Disponible immédiatement',
'Jeune développeur passionné, formation OpenClassrooms (titre RNCP niveau 6), recherche mission freelance ou CDI. Compétences: PHP/Symfony, JavaScript/React, MySQL, Git. Projets personnels: marketplace, API REST, application mobile React Native. Disponible sur Toulouse et remote. Taux journalier: 250-300€ selon mission. Portfolio et références sur demande. Permis B, véhiculé.',
0, 'Toulouse (31000)', DATE_SUB(NOW(), INTERVAL 7 DAY), 67);

-- 8. SERVICES - Cours de guitare
INSERT INTO annonces (utilisateur_id, categorie_id, titre, description, prix, location, date_creation, views) VALUES
(8, 8, 'Cours de guitare tous niveaux - Professeur diplômé',
'Professeur de guitare diplômé du CIM (Centre d''Informations Musicales) propose cours particuliers tous niveaux et tous styles (classique, folk, rock, jazz, blues). 10 ans d''expérience pédagogique. Approche personnalisée selon vos objectifs: technique, théorie, improvisation, composition. Cours à domicile ou dans mon studio équipé (quartier Gambetta). Tarif: 30€/h (débutant), 35€/h (avancé). Premier cours d''essai gratuit!',
30, 'Paris 11ème (75011)', DATE_SUB(NOW(), INTERVAL 2 DAY), 143);

-- 9. AUTRES - Livres universitaires
INSERT INTO annonces (utilisateur_id, categorie_id, titre, description, prix, location, date_creation, views) VALUES
(1, 9, 'Lot de 15 livres universitaires - Économie/Gestion',
'Vends lot complet de manuels universitaires licence Économie-Gestion. Livres en très bon état, peu utilisés. Au programme: Microéconomie (Varian), Macroéconomie (Mankiw), Statistiques, Comptabilité, Droit des sociétés, Marketing, Finance d''entreprise. Valeur neuve: environ 600€. Idéal étudiants L1/L2/L3. Remise en main propre Lyon ou envoi possible (frais de port en sus). Possibilité vente à l''unité, me contacter.',
120, 'Lyon (69000)', DATE_SUB(NOW(), INTERVAL 8 DAY), 38);
