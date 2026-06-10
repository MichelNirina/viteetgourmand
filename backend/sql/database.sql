-- =============================================================
-- Base de données : viteetgourmand
-- Description     : Création complète de la base de données
--                   pour l'application Vite et Gourmand
-- =============================================================

CREATE DATABASE IF NOT EXISTS viteetgourmand
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE viteetgourmand;

-- -------------------------------------------------------------
-- Table : role
-- -------------------------------------------------------------
CREATE TABLE role (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- -------------------------------------------------------------
-- Table : regime
-- -------------------------------------------------------------
CREATE TABLE regime (
    regime_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- -------------------------------------------------------------
-- Table : theme
-- -------------------------------------------------------------
CREATE TABLE theme (
    theme_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- -------------------------------------------------------------
-- Table : allergene
-- -------------------------------------------------------------
CREATE TABLE allergene (
    allergene_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- -------------------------------------------------------------
-- Table : horaire
-- -------------------------------------------------------------
CREATE TABLE horaire (
    horaire_id INT AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(50) NOT NULL,
    heure_ouverture VARCHAR(10),
    heure_fermeture VARCHAR(10)
);

-- -------------------------------------------------------------
-- Table : utilisateur
-- -------------------------------------------------------------
CREATE TABLE utilisateur (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    prenom VARCHAR(50),
    telephone VARCHAR(20),
    ville VARCHAR(50),
    pays VARCHAR(50),
    adresse_postale VARCHAR(100),
    role_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES role(role_id)
);

-- -------------------------------------------------------------
-- Table : menu
-- -------------------------------------------------------------
CREATE TABLE menu (
    menu_id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    nombre_personne INT,
    prix_par_personne DECIMAL(10,2),
    description VARCHAR(255),
    quantite_restante INT DEFAULT 0,
    regime_id INT,
    theme_id INT,
    FOREIGN KEY (regime_id) REFERENCES regime(regime_id),
    FOREIGN KEY (theme_id) REFERENCES theme(theme_id)
);

-- -------------------------------------------------------------
-- Table : plat
-- -------------------------------------------------------------
CREATE TABLE plat (
    plat_id INT AUTO_INCREMENT PRIMARY KEY,
    titre_plat VARCHAR(100) NOT NULL,
    photo VARCHAR(255),
    menu_id INT,
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE SET NULL
);

-- -------------------------------------------------------------
-- Table : contient  (plat <-> allergene)
-- -------------------------------------------------------------
CREATE TABLE contient (
    plat_id INT,
    allergene_id INT,
    PRIMARY KEY (plat_id, allergene_id),
    FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE,
    FOREIGN KEY (allergene_id) REFERENCES allergene(allergene_id) ON DELETE CASCADE
);

-- -------------------------------------------------------------
-- Table : avis
-- -------------------------------------------------------------
CREATE TABLE avis (
    avis_id INT AUTO_INCREMENT PRIMARY KEY,
    note INT,
    description VARCHAR(255),
    statut VARCHAR(50) DEFAULT 'en_attente',
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES utilisateur(user_id) ON DELETE SET NULL
);

-- -------------------------------------------------------------
-- Table : commande
-- -------------------------------------------------------------
CREATE TABLE commande (
    numero_commande VARCHAR(50) PRIMARY KEY,
    date_commande DATE,
    date_prestation DATE,
    heure_livraison VARCHAR(10),
    prix_menu DECIMAL(10,2),
    nombre_personne INT,
    prix_livraison DECIMAL(10,2),
    statut VARCHAR(50) DEFAULT 'en_attente',
    pret_materiel BOOLEAN DEFAULT FALSE,
    restitution_materiel BOOLEAN DEFAULT FALSE,
    user_id INT,
    menu_id INT,
    FOREIGN KEY (user_id) REFERENCES utilisateur(user_id) ON DELETE SET NULL,
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE SET NULL
);

-- =============================================================
-- Données de référence
-- =============================================================

-- Rôles (1=admin, 2=employe, 3=client)
INSERT INTO role (role_id, libelle) VALUES
(1, 'admin'),
(2, 'employe'),
(3, 'client');

-- Régimes alimentaires
INSERT INTO regime (libelle) VALUES
('Classique'),
('Végétarien'),
('Vegan'),
('Sans gluten'),
('Sans lactose');

-- Thèmes
INSERT INTO theme (libelle) VALUES
('Noël'),
('Anniversaire'),
('Mariage'),
('Entreprise'),
('Buffet'),
('Baptême');

-- Allergènes (14 allergènes majeurs - Règlement UE 1169/2011)
INSERT INTO allergene (libelle) VALUES
('Gluten'),
('Crustacés'),
('Œufs'),
('Poissons'),
('Arachides'),
('Soja'),
('Lait'),
('Fruits à coque'),
('Céleri'),
('Moutarde'),
('Graines de sésame'),
('Anhydride sulfureux et sulfites'),
('Lupin'),
('Mollusques');

-- Horaires d'ouverture
INSERT INTO horaire (jour, heure_ouverture, heure_fermeture) VALUES
('Lundi',    '11:30', '14:00'),
('Lundi',    '18:30', '22:00'),
('Mardi',    NULL,    NULL),
('Mercredi', '11:30', '14:00'),
('Mercredi', '18:30', '22:00'),
('Jeudi',    '11:30', '14:00'),
('Jeudi',    '18:30', '22:00'),
('Vendredi', '11:30', '14:00'),
('Vendredi', '18:30', '22:30'),
('Samedi',   '11:30', '15:00'),
('Samedi',   '18:30', '23:00'),
('Dimanche', '11:30', '15:00');

-- =============================================================
-- Comptes utilisateurs par défaut
-- mot de passe admin   : admin123
-- mot de passe employe : employe123
-- =============================================================

INSERT INTO utilisateur (email, password, prenom, telephone, ville, pays, adresse_postale, role_id) VALUES
('admin@viteetgourmand.fr',   '$2y$12$yiGvMy5IAFl4YE5QC1zwPur2bRESjjjlqUR7lW.Trfxyz0KRl1l/S', 'Administrateur', NULL, NULL, NULL, NULL, 1),
('employe@viteetgourmand.fr', '$2y$12$FmqYNJc2DRmYzc6jAb9Xse9kkMzlUbxAPmvrrX10tQRVFXYMb7zKO', 'Employe',        NULL, NULL, NULL, NULL, 2);

-- =============================================================
-- Menus exemples
-- =============================================================

INSERT INTO menu (titre, nombre_personne, prix_par_personne, description, quantite_restante, regime_id, theme_id) VALUES
('Menu Noël Prestige',        10, 45.00, 'Un menu festif pour célébrer Noël en grande pompe avec des plats raffinés.',          5, 1, 1),
('Menu Mariage Classique',    20, 55.00, 'Menu élégant pour votre jour J, avec des plats gastronomiques préparés avec soin.',    3, 1, 3),
('Menu Anniversaire Festif',   8, 35.00, 'Célébrez votre anniversaire avec ce menu festif et coloré pour petits et grands.',     8, 1, 2),
('Menu Végétarien Printemps',  6, 30.00, 'Menu 100% végétarien avec des légumes frais de saison, léger et coloré.',             4, 2, 5),
('Menu Entreprise Premium',   15, 40.00, 'Menu professionnel idéal pour séminaires, réunions et événements d\'entreprise.',      6, 1, 4);

-- Plats du Menu Noël Prestige (menu_id = 1)
INSERT INTO plat (titre_plat, photo, menu_id) VALUES
('Foie gras mi-cuit et brioche dorée',             NULL, 1),
('Pintade rôtie aux marrons et légumes de saison', NULL, 1),
('Bûche de Noël chocolat praliné',                 NULL, 1);

-- Plats du Menu Mariage Classique (menu_id = 2)
INSERT INTO plat (titre_plat, photo, menu_id) VALUES
('Velouté de champignons et crème de truffe',          NULL, 2),
('Filet de bœuf sauce Périgueux et gratin dauphinois', NULL, 2),
('Pièce montée et mignardises',                        NULL, 2);

-- Plats du Menu Anniversaire Festif (menu_id = 3)
INSERT INTO plat (titre_plat, photo, menu_id) VALUES
('Plateau de charcuteries et fromages',                   NULL, 3),
('Poulet rôti aux herbes et pommes de terre sarladaises',  NULL, 3),
('Gâteau d\'anniversaire et desserts variés',              NULL, 3);

-- Plats du Menu Végétarien Printemps (menu_id = 4)
INSERT INTO plat (titre_plat, photo, menu_id) VALUES
('Gaspacho de tomates et basilic',        NULL, 4),
('Curry de pois chiches au lait de coco', NULL, 4),
('Tarte aux fruits frais de saison',      NULL, 4);

-- Plats du Menu Entreprise Premium (menu_id = 5)
INSERT INTO plat (titre_plat, photo, menu_id) VALUES
('Verrines de saumon fumé et avocat',    NULL, 5),
('Suprême de volaille et légumes rôtis', NULL, 5),
('Assortiment de desserts individuels',  NULL, 5);
