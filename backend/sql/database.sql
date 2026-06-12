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
('Lundi',    '10:30', '22:30'),
('Mardi',    '10:30', '22:30'),
('Mercredi', '10:30', '22:30'),
('Jeudi',    '10:30', '22:30'),
('Vendredi', '10:30', '22:30'),
('Samedi',   '10:30', '22:30'),
('Dimanche', '10:30', '22:30');

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
('Foie gras mi-cuit et brioche dorée',             'assets/images/plats/foie_gras_mi_cuit_et_brioche_dor_e.png',    1),
('Pintade rôtie aux marrons et légumes de saison', 'assets/images/plats/pintade_r_tie_aux_marrons_et_l_gumes.png',  1),
('Bûche de Noël chocolat praliné',                 'assets/images/plats/b_che_de_no_l_chocolat_pralin_.png',        1);

-- Plats du Menu Mariage Classique (menu_id = 2)
INSERT INTO plat (titre_plat, photo, menu_id) VALUES
('Velouté de courge et crème fraîche',             'assets/images/plats/velout_de_courge_et_cr_me_fra_che.png',     2),
('Filet de bœuf sauce Périgueux et gratin dauphinois', 'assets/images/plats/filet_de_b_uf_sauce_p_rigueux.png',    2),
('Pièce montée choux et caramel',                  'assets/images/plats/pi_ce_mont_e_choux_et_caramel.png',         2);

-- Plats du Menu Anniversaire Festif (menu_id = 3)
INSERT INTO plat (titre_plat, photo, menu_id) VALUES
('Saumon fumé, blinis et crème citronnée',         'assets/images/plats/saumon_fum_blinis_et_cr_me_citronn_e.png', 3),
('Tartare de saumon, avocat et citron vert',        'assets/images/plats/tartare_de_saumon_avocat_et_citron_vert.png', 3),
('Tarte tatin aux pommes',                          'assets/images/plats/tarte_tatin_aux_pommes.png',                3);

-- Plats du Menu Végétarien Printemps (menu_id = 4)
INSERT INTO plat (titre_plat, photo, menu_id) VALUES
('Gaspacho de tomates et basilic',                 'assets/images/plats/gaspacho_de_tomates_et_basilic.png',        4),
('Curry de pois chiches au lait de coco',          'assets/images/plats/curry_de_pois_chiches_lait_de_coco.png',    4),
('Tarte aux fruits rouges, pâte amandes',          'assets/images/plats/tarte_aux_fruits_rouges_p_te_amandes.png',  4);

-- Plats du Menu Entreprise Premium (menu_id = 5)
INSERT INTO plat (titre_plat, photo, menu_id) VALUES
('Risotto aux champignons et parmesan',            'assets/images/plats/risotto_aux_champignons_et_parmesan.png',   5),
('Suprême de volaille sauce morilles',             'assets/images/plats/supr_me_de_volaille_sauce_morilles.png',    5),
('Fondant chocolat cœur coulant',                  'assets/images/plats/fondant_chocolat_c_ur_coulant.png',         5);

-- =============================================================
-- Allergènes par plat
-- Référence allergene_id : 1=Gluten 2=Crustacés 3=Œufs 4=Poissons
--   5=Arachides 6=Soja 7=Lait 8=Fruits à coque 9=Céleri
--   10=Moutarde 11=Sésame 12=Sulfites 13=Lupin 14=Mollusques
-- Référence plat_id (dans l'ordre d'insertion) :
--   1=Foie gras brioche  2=Pintade marrons  3=Bûche Noël
--   4=Velouté courge     5=Filet bœuf       6=Pièce montée
--   7=Saumon blinis      8=Tartare saumon   9=Tarte tatin
--  10=Gaspacho          11=Curry pois       12=Tarte fruits rouges
--  13=Risotto           14=Suprême volaille 15=Fondant choco
-- =============================================================

INSERT INTO contient (plat_id, allergene_id) VALUES
-- Foie gras mi-cuit et brioche dorée : gluten, œufs, lait
(1, 1), (1, 3), (1, 7),
-- Pintade rôtie aux marrons : fruits à coque
(2, 8),
-- Bûche de Noël chocolat praliné : gluten, œufs, lait, fruits à coque
(3, 1), (3, 3), (3, 7), (3, 8),
-- Velouté de courge et crème fraîche : lait, céleri
(4, 7), (4, 9),
-- Filet de bœuf sauce Périgueux et gratin dauphinois : gluten, lait
(5, 1), (5, 7),
-- Pièce montée choux et caramel : gluten, œufs, lait
(6, 1), (6, 3), (6, 7),
-- Saumon fumé, blinis et crème citronnée : gluten, œufs, poissons, lait
(7, 1), (7, 3), (7, 4), (7, 7),
-- Tartare de saumon, avocat et citron vert : poissons
(8, 4),
-- Tarte tatin aux pommes : gluten, œufs, lait
(9, 1), (9, 3), (9, 7),
-- Gaspacho de tomates et basilic : céleri
(10, 9),
-- Curry de pois chiches au lait de coco : soja
(11, 6),
-- Tarte aux fruits rouges, pâte amandes : gluten, œufs, lait, fruits à coque
(12, 1), (12, 3), (12, 7), (12, 8),
-- Risotto aux champignons et parmesan : lait
(13, 7),
-- Suprême de volaille sauce morilles : gluten, lait
(14, 1), (14, 7),
-- Fondant chocolat cœur coulant : gluten, œufs, lait
(15, 1), (15, 3), (15, 7);
