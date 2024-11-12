-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 11 oct. 2024 à 00:27
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
-- Base de données : `client_management`
--

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `plat_id` int(11) DEFAULT NULL,
  `plat_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Validated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cart`
--

INSERT INTO `cart` (`cart_id`, `client_id`, `order_date`, `plat_id`, `plat_name`, `quantity`, `price`, `total_amount`, `status`) VALUES
(1, '1', '2024-10-10 21:53:23', 6, 'Borscht', 2, 9.99, 19.98, 'Validated'),
(2, '3', '2024-10-10 22:21:36', 5, 'Beef Stroganoff', 3, 16.99, 50.97, 'Validated');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `client_id` varchar(50) NOT NULL,
  `code_client` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`client_id`, `code_client`, `password`) VALUES
('1', 'B8585', 'ensem123'),
('2', 'Q789', 'ensem12'),
('3', 'Y852', 'yasmine'),
('4', 'uj589', 'ouazzine');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `commande_id` int(11) NOT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  `ingredients` text NOT NULL,
  `plat_name` varchar(100) NOT NULL,
  `min_price` decimal(10,2) NOT NULL,
  `max_price` decimal(10,2) NOT NULL,
  `origine` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`commande_id`, `client_id`, `ingredients`, `plat_name`, `min_price`, `max_price`, `origine`, `created_at`) VALUES
(1, '1', 'Eggs, milk and milk products.\r\n', 'Scrambled Eggs.', 20.00, 30.00, 'Japan', '2024-10-01 20:08:34'),
(2, '1', 'Fats and oils', 'yasmine', 20.00, 80.00, 'Irish', '2024-10-10 20:30:17'),
(3, '1', 'Fats and oils', 'yasmine', 20.00, 80.00, 'Irish', '2024-10-10 20:45:01'),
(4, '1', 'Fruits', 'sushi', 40.00, 20.00, 'Japan', '2024-10-10 21:16:54'),
(5, '3', 'Grain, nuts and baking products', 'curry', 12.00, 13.00, 'Japan', '2024-10-10 22:05:31');

-- --------------------------------------------------------

--
-- Structure de la table `plat`
--

CREATE TABLE `plat` (
  `plat_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `origin` varchar(50) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `plat`
--

INSERT INTO `plat` (`plat_id`, `name`, `description`, `price`, `origin`, `image_url`) VALUES
(1, 'Couscous Royal', 'Traditional Moroccan dish with semolina, vegetables, and meat', 15.99, 'Morocco', 'https://www.thespruceeats.com/thmb/A7dwS7Y3yh3IeQ1NZEWZ45KGH4c=/750x0/filters:no_upscale():max_bytes(150000):strip_icc():format(webp)/couscous-getty-3890-x-2562-56a645de5f9b58b7d0e0c3b1-af93f1c24efb43b9a3c143eba611aabd.jpg'),
(2, 'Tajine de Poulet', 'Slow-cooked chicken with preserved lemons and olives', 14.99, 'Morocco', 'https://www.la-cuisine-marocaine.com/photos-recettes/02-tajine-de-poulet-aux-legumes.jpg'),
(3, 'Sushi Platter', 'Assorted fresh sushi including nigiri and maki rolls', 22.99, 'Japan', 'https://static.blog.bolt.eu/LIVE/wp-content/uploads/2024/05/10110345/different-types-of-sushi-1024x536.jpg'),
(4, 'Ramen', 'Rich broth with noodles, pork, egg, and vegetables', 12.99, 'Japan', 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/Shoyu_ramen%2C_at_Kasukabe_Station_%282014.05.05%29_1.jpg/375px-Shoyu_ramen%2C_at_Kasukabe_Station_%282014.05.05%29_1.jpg'),
(5, 'Beef Stroganoff', 'Tender beef in a creamy mushroom sauce with noodles', 16.99, 'Russian', 'https://kitskitchen.com/wp-content/uploads/2021/10/roastbeef2-768x1152.jpg'),
(6, 'Borscht', 'Traditional beet soup with sour cream', 9.99, 'Russian', 'https://natashaskitchen.com/wp-content/uploads/2018/10/Borscht-Recipe-2-768x1152.jpg');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `plat_id` (`plat_id`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `code_client` (`code_client`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`commande_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Index pour la table `plat`
--
ALTER TABLE `plat`
  ADD PRIMARY KEY (`plat_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `commande_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `plat`
--
ALTER TABLE `plat`
  MODIFY `plat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`plat_id`) REFERENCES `plat` (`plat_id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
