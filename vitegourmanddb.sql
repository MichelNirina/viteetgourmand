-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 10 fév. 2026 à 20:08
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `vite_gourmand`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `note` tinyint(4) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `gsm` varchar(20) NOT NULL,
  `date_livraison` date DEFAULT NULL,
  `heure_livraison` time DEFAULT NULL,
  `adresse_livraison` varchar(255) DEFAULT NULL,
  `nb_personnes` int(11) DEFAULT NULL,
  `prix_total` decimal(8,2) DEFAULT NULL,
  `statut` enum('recu','accepte','preparation','livraison','livre','termine') DEFAULT 'recu',
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `adresse_prestation` varchar(255) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `date_prestation` date NOT NULL,
  `heure_prestation` time NOT NULL,
  `prix_menu` decimal(10,2) NOT NULL,
  `prix_livraison` decimal(10,2) NOT NULL,
  `remise` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `id_utilisateur`, `id_menu`, `nom`, `prenom`, `email`, `adresse`, `gsm`, `date_livraison`, `heure_livraison`, `adresse_livraison`, `nb_personnes`, `prix_total`, `statut`, `date_modification`, `date_creation`, `adresse_prestation`, `ville`, `lieu`, `date_prestation`, `heure_prestation`, `prix_menu`, `prix_livraison`, `remise`) VALUES
(36, 5, 2, '', '', '', '', '', NULL, NULL, NULL, 7, NULL, '', '2026-02-10 07:45:42', '2026-02-09 17:32:00', '93051, Friedrich-Ebert-Straße 19a', 'Regensburg', 'wae', '2026-02-09', '17:32:00', 157.50, 5.00, 17.50),
(37, 5, 2, '', '', '', '', '', NULL, NULL, NULL, 7, NULL, 'livre', '2026-02-09 17:36:12', '2026-02-09 17:38:34', '93051, Friedrich-Ebert-Straße 19a', 'Regensburg', 'ads', '2026-02-09', '17:38:34', 157.50, 5.00, 17.50),
(39, 5, 2, '', '', '', '', '', NULL, NULL, NULL, 5, NULL, '', '2026-02-09 21:10:54', '2026-02-09 18:42:32', 'Siegfriedstr. 9', 'Regensburg', 'wae', '2026-02-09', '18:42:32', 125.00, 5.00, 0.00),
(52, 5, 2, 'Nirina', 'Fandresena Michel', 'fandrymichela@gmail.com', 'Siegfriedstr. 9', 'sad', NULL, NULL, NULL, 2, 55.00, 'livre', '2026-02-10 13:50:43', '2026-02-10 14:28:46', '', '', '', '0000-00-00', '00:00:00', 50.00, 5.00, 0.00),
(53, 5, 1, 'Nirina', 'Fandresena Michel', 'fandrymichela@gmail.com', 'Siegfriedstr. 9', 'sad', NULL, NULL, NULL, 6, 125.00, '', '2026-02-10 13:29:56', '2026-02-10 14:29:56', '', '', '', '0000-00-00', '00:00:00', 120.00, 5.00, 0.00),
(90, 5, 2, 'Nirina', 'Fandresena Michel', 'fandrymichela@gmail.com', '', 'sad', NULL, NULL, NULL, 6, 155.00, '', '2026-02-10 15:41:32', '2026-02-10 16:41:32', '93051, Friedrich-Ebert-Straße 19a', 'Regensburg', 'wae', '0000-00-00', '00:00:00', 150.00, 5.00, 0.00),
(91, 5, 2, 'Nirina', 'Fandresena Michel', 'fandrymichela@gmail.com', '', 'sad', NULL, NULL, NULL, 12, 305.00, '', '2026-02-10 15:42:11', '2026-02-10 16:42:11', 'Siegfriedstr. 9', 'Regensburg', 'wae', '0000-00-00', '00:00:00', 300.00, 5.00, 0.00),
(92, 5, 2, 'Nirina', 'Fandresena Michel', 'fandrymichela@gmail.com', '', 'sad', NULL, NULL, NULL, 6, 155.00, 'livre', '2026-02-10 15:46:43', '2026-02-10 16:43:32', 'Siegfriedstr. 9', 'Regensburg', 'wae', '0000-00-00', '00:00:00', 150.00, 5.00, 0.00),
(93, 5, 2, 'Nirina', 'Fandresena Michel', 'fandrymichela@gmail.com', '', 'sad', NULL, NULL, NULL, 4, 105.00, '', '2026-02-10 15:49:31', '2026-02-10 16:49:31', '93051, Friedrich-Ebert-Straße 19a', 'Regensburg', 'wae', '0000-00-00', '00:00:00', 100.00, 5.00, 0.00);

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_envoi` datetime DEFAULT current_timestamp(),
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`id`, `id_utilisateur`, `email`, `titre`, `message`, `date_envoi`, `date_creation`) VALUES
(1, NULL, 'fandrymichel@gmail.com', 'Réclamation livraison', 'Bonjour, ma commande n’est pas arrivée à temps. Merci de votre retour.', '2026-02-10 11:32:15', '2026-02-10 11:32:15'),
(2, NULL, 'fandrymichel@gmail.com', 'Question sur les menus', 'Je souhaiterais savoir si vous proposez des options végétariennes et sans gluten pour vos menus.\r\nMerci d’avance pour votre réponse !', '2026-02-10 11:39:44', '2026-02-10 11:39:44');

-- --------------------------------------------------------

--
-- Structure de la table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `conditions` text DEFAULT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `regime` varchar(50) DEFAULT NULL,
  `nombre_personnes_min` int(11) DEFAULT 1,
  `prix` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `allergenes` varchar(255) DEFAULT NULL COMMENT 'Liste des allergènes séparés par des virgules'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `menus`
--

INSERT INTO `menus` (`id`, `titre`, `description`, `conditions`, `theme`, `regime`, `nombre_personnes_min`, `prix`, `stock`, `image`, `allergenes`) VALUES
(1, 'Menu du Nouvel An', 'Entrée : Salade César au poulet\r\nPlat : Filet de saumon sauce citron\r\nDessert : Tarte aux pommes maison', 'Précommande 24h à l’avance. Allergènes : gluten, œufs, poisson', 'Noel', 'Classique', 4, 20.00, 10, 'uploads/6989e7bd6b794.webp', 'arachides, fruits à coque, soja, gluten, lait, œufs'),
(2, 'Menu Noël Familial', 'Entrée : Salade César au poulet\r\nPlat : Filet de saumon sauce citron\r\nDessert : Tarte aux pommes maison', 'Précommande 24h à l’avance. Allergènes : gluten, œufs, poisson', 'Classique', 'Classique', 2, 25.00, 6, 'uploads/6989e8a7bd24c.webp', 'gluten, lait, œufs, arachides, fruits à coque, soja');

-- --------------------------------------------------------

--
-- Structure de la table `statut_historique`
--

CREATE TABLE `statut_historique` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `statut` varchar(50) NOT NULL,
  `date_modification` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `statut_historique`
--

INSERT INTO `statut_historique` (`id`, `id_commande`, `statut`, `date_modification`) VALUES
(1, 37, 'En préparation', '2026-02-09 18:17:00'),
(2, 37, 'En préparation', '2026-02-09 18:17:00'),
(3, 37, 'Livré', '2026-02-09 18:17:00'),
(4, 37, 'Livré', '2026-02-09 18:17:00'),
(5, 37, 'Annulé', '2026-02-09 18:17:00'),
(6, 37, 'En attente', '2026-02-09 18:17:00'),
(7, 37, 'En attente', '2026-02-09 18:17:00'),
(8, 37, 'En attente', '2026-02-09 18:17:00'),
(9, 37, 'En attente', '2026-02-09 18:17:00'),
(10, 37, 'En préparation', '2026-02-09 18:22:17'),
(11, 37, 'Livré', '2026-02-09 18:22:21'),
(12, 37, 'Livré', '2026-02-09 18:22:29'),
(13, 37, 'En préparation', '2026-02-09 18:26:12'),
(14, 37, 'Livré', '2026-02-09 18:26:28'),
(15, 37, 'En préparation', '2026-02-09 18:31:27'),
(16, 37, 'Livré', '2026-02-09 18:36:12'),
(17, 37, 'Livré', '2026-02-09 18:39:34'),
(18, 36, 'En préparation', '2026-02-09 18:39:40'),
(19, 36, 'En préparation', '2026-02-09 22:10:43'),
(20, 39, 'En préparation', '2026-02-09 22:10:54'),
(21, 36, 'annule', '2026-02-10 08:44:47'),
(22, 36, 'livre', '2026-02-10 08:45:01'),
(23, 36, 'livre', '2026-02-10 08:45:18'),
(24, 36, 'Livré', '2026-02-10 08:45:27'),
(25, 39, 'En attente', '2026-02-10 08:45:32'),
(26, 39, 'En préparation', '2026-02-10 08:45:36'),
(27, 37, 'Livré', '2026-02-10 08:45:37'),
(28, 36, 'Annulé', '2026-02-10 08:45:42'),
(29, 36, 'Annulé', '2026-02-10 08:50:06'),
(30, 39, 'En préparation', '2026-02-10 08:50:07'),
(31, 53, 'En préparation', '2026-02-10 14:49:29'),
(32, 52, 'En attente', '2026-02-10 14:49:30'),
(33, 37, 'Livré', '2026-02-10 14:49:31'),
(34, 39, 'En préparation', '2026-02-10 14:49:32'),
(35, 36, 'Annulé', '2026-02-10 14:49:33'),
(36, 52, 'Livré', '2026-02-10 14:50:43'),
(37, 92, 'Livré', '2026-02-10 16:46:43'),
(38, 91, 'En attente', '2026-02-10 16:46:43'),
(39, 90, 'En attente', '2026-02-10 16:46:44'),
(40, 90, 'En préparation', '2026-02-10 16:47:42'),
(41, 91, 'En préparation', '2026-02-10 16:47:46');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `gsm` varchar(20) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `role` enum('utilisateur','employe','admin') DEFAULT 'utilisateur',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `gsm`, `adresse`, `role`, `date_creation`) VALUES
(4, 'Nirina', 'Fandresena Michel', 'fandrymichel@gmail.com', '$2y$10$X19JAkMlNbOJZcURtqjgtOpU0inXasgYQVsOIwdiROcnk3q.RNljW', 'fdggd', '93051, Friedrich-Ebert-Straße 19a', 'utilisateur', '2026-01-23 17:45:30'),
(5, 'Nirina', 'Fandresena Michel', 'fandrymichela@gmail.com', '$2y$10$XwmYmyuvHPVaNvB3BdUU2.nFWbdgKqt.8vTRkaihA/PKGJ9gpV/Qu', 'sad', '93051, Friedrich-Ebert-Straße 19a', 'utilisateur', '2026-01-23 17:58:32'),
(6, 'Jean', 'Dupont', 'employe1@vite.com', '$2y$10$SqdZ82nrgGDTC4Ne5UIlFOHKMu2yR4kUzcLJnrkN4cphj2eJkByma', '0123456789', 'Bureau', 'employe', '2026-01-25 16:20:21'),
(7, 'Rakoto', 'Chef', 'admin1@vite.com', '$2y$10$B5l6zz8..j3FWpnZW7zj.eAozimwnx9TTIIh5ZZzBW2EyDEDpK6Ca', NULL, 'Bureau', 'admin', '2026-01-25 19:12:37'),
(8, 'Rajao ', 'Francois', 'admin2@vite.com', '$2y$10$5eUKj5hOiwn87bzys7ENLeKkT8RsUHS1B.aCFzeY66ts.FqzxWXXu', NULL, NULL, 'admin', '2026-01-25 21:38:49');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `fk_commande_menu` (`id_menu`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `statut_historique`
--
ALTER TABLE `statut_historique`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `statut_historique`
--
ALTER TABLE `statut_historique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `fk_commande_menu` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
