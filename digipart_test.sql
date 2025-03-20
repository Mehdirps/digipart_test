-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 20 mars 2025 à 10:53
-- Version du serveur : 8.0.40
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `digipart_test`
--

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `reference`, `title`, `image`) VALUES
(6, 'Réf 1', 'Produit 1', '67dbe9c1a59607.06402973.png'),
(9, 'Réf 8', 'Produit 8', '67dbed55b5ca48.81718991.png'),
(10, 'Réf 10', 'Produit 10', '67dbf103e16414.43836899.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `product_infos`
--

CREATE TABLE `product_infos` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `price` float NOT NULL,
  `priceTaxIncl` float DEFAULT NULL,
  `priceTaxExcl` float DEFAULT NULL,
  `idLang` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `product_infos`
--

INSERT INTO `product_infos` (`id`, `product_id`, `description`, `price`, `priceTaxIncl`, `priceTaxExcl`, `idLang`, `quantity`) VALUES
(6, 6, 'Description Produit 1', 23, NULL, NULL, NULL, 0),
(9, 9, 'Description du produit 8', 746, NULL, NULL, NULL, 0),
(10, 10, 'Description du produit 10', 3640, 4670, 5000, 'FRA', 980);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `product_infos`
--
ALTER TABLE `product_infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `product_infos`
--
ALTER TABLE `product_infos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
