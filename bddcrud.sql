-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 16 nov. 2023 à 09:00
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bddcrud`
--

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

DROP TABLE IF EXISTS `cours`;
CREATE TABLE IF NOT EXISTS `cours` (
  `id_cours` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `sujet` varchar(255) NOT NULL,
  `description` text,
  `intervenant` varchar(255) DEFAULT NULL,
  `duree_cours` time DEFAULT NULL,
  `heure_cours` time DEFAULT NULL,
  PRIMARY KEY (`id_cours`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `cours`
--

INSERT INTO `cours` (`id_cours`, `date`, `sujet`, `description`, `intervenant`, `duree_cours`, `heure_cours`) VALUES
(1, '2023-11-06', 'HTML / CSS', '1er cours html / css', 'Alex', '03:00:00', '18:00:00'),
(2, '2023-11-18', 'JAVA', 'JAVA initiation / Algo', 'Sacha', '03:00:00', '17:00:00'),
(5, '2023-11-16', 'test', 'qqq', 'Sacha', '03:00:00', '03:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `inscription_cours`
--

DROP TABLE IF EXISTS `inscription_cours`;
CREATE TABLE IF NOT EXISTS `inscription_cours` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_cours` int NOT NULL,
  `date_inscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(10) DEFAULT 'inscrit',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_inscription` (`id_utilisateur`,`id_cours`),
  KEY `id_cours` (`id_cours`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `inscription_cours`
--

INSERT INTO `inscription_cours` (`id`, `id_utilisateur`, `id_cours`, `date_inscription`, `statut`) VALUES
(28, 7, 2, '2023-11-15 12:02:12', 'inscrit');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiration` datetime DEFAULT NULL,
  `Admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `email`, `mot_de_passe`, `first_name`, `last_name`, `reset_token`, `reset_token_expiration`, `Admin`) VALUES
(5, 'ttt@gmail.com', '$2y$10$S2DD9Fbdx1TQJSgQQYyW0unylIscysWtN4UcyTR3I/BRLW.rgF7Ny', 'Sacha', 'azerty', NULL, NULL, 0),
(6, 'sacha@studioricom.com', '$2y$10$u9dUlaLwYQAA5nz0cEjc/.LWa0sG/SHa8daC1UGUBbFiVn.R49NBm', 'SACHA450', 'Thibault', NULL, NULL, 0),
(7, 'sachathibault45@gmail.com', '$2y$10$AIKGl7XvNvTMvY1s5ezZMuAauhxxSHfXF0cC7J5pOX3gGw1IcJVaO', 'Sacha', 'Thibault', '748b493408d6af3cf4f94ee67f9706ee1637f142a70032574e56b8f7157e47d7', '2023-11-16 00:54:25', 1),
(8, 'Yug@gmail.com', '$2y$10$12PLbr/LuMlsCrhDaQAl8.vJkIoZ1Yu/krkUnACtLrwdr/R5alCJK', 'Yug', 'TTT', NULL, NULL, 0),
(9, '', '$2y$10$Z8pQme5GoxoI5TH0QtbSdOTL6dQJgEihl1GNrJ/uD1bm4IXX3Mzbm', '', '', NULL, NULL, 0),
(17, 'sachathibault450@gmail.com', '$2y$10$GvN3ZJqYyXCkwMoMu6QkiuBNV7IR0A9VMQ7MSXXDqGYWi/v9qWn9S', 'SACHA450', 'Thibault', '50ea5100968f3e674ac05a5bb942eabbc02bf6de45b39cc78eb66035f1dc2894', NULL, 0),
(18, 'devsarealsohumans@outlook.fr', '$2y$10$oGUInuXWvfqr.phtdWj6quvOkMQ.H7uRnbDQBY2T6YqmntGPfH4CG', 'BBB', 'AAA', 'f14b560f987807e120fd14dba4b0973ace243108362fc1ada04e9578f6d6785c', NULL, 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `inscription_cours`
--
ALTER TABLE `inscription_cours`
  ADD CONSTRAINT `inscription_cours_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inscription_cours_ibfk_2` FOREIGN KEY (`id_cours`) REFERENCES `cours` (`id_cours`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
