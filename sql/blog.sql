-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le :  Dim 14 avr. 2019 à 21:40
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `published_at` date NOT NULL,
  `summary` text,
  `content` longtext,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `image` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `title`, `published_at`, `summary`, `content`, `is_published`, `image`) VALUES
(1, 'Hellfest 2018, l\'affiche quasi-complète', '2017-01-06', 'Résumé de l\'article Hellfest', '<p>Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. </p>', 1, NULL),
(2, 'Critique « Star Wars 8 – Les derniers Jedi » de Rian Johnson : le renouveau de la saga ?', '2017-01-07', 'Résumé de l\'article Star Wars 8', '<p>Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim. Pellentesque congue.</p>', 1, NULL),
(3, 'Revue - The Ramones', '2017-01-01', 'Résumé de l\'article The Ramones', '<p>Pellentesque sed dui ut augue blandit sodales. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam nibh.</p>', 1, NULL),
(4, 'De “Skyrim” à “L.A. Noire” ou “Doom” : pourquoi les vieux jeux sont meilleurs sur la Switch', '2017-01-03', 'Résumé de l\'article Switch', '<p>Mauris ac mauris sed pede pellentesque fermentum. Maecenas adipiscing ante non diam sodales hendrerit.</p>', 1, NULL),
(5, 'Comment “Assassin’s Creed” trouve un nouveau souffle en Egypte', '2017-01-04', 'Résumé de l\'article Assassin’s Creed', '<p>Ut velit mauris, egestas sed, gravida nec, ornare ut, mi. Aenean ut orci vel massa suscipit pulvinar.</p>', 1, NULL),
(6, 'BO de « Les seigneurs de Dogtown » : l’époque bénie du rock.', '2017-01-05', 'Résumé de l\'article Les seigneurs de Dogtown', '<p>Nulla sollicitudin. Fusce varius, ligula non tempus aliquam, nunc turpis ullamcorper nibh, in tempus sapien eros vitae ligula.</p>', 1, NULL),
(7, 'Pourquoi \"Destiny 2\" est un remède à l’ultra-moderne solitude', '2019-04-01', 'Résumé de l\'article Destiny 2', '<p>Pellentesque rhoncus nunc et augue. Integer id felis. Curabitur aliquet pellentesque diam.</p>', 1, NULL),
(8, 'Pourquoi \"Mario + Lapins Crétins : Kingdom Battle\" est le jeu de la rentrée', '2017-01-08', 'Résumé de l\'article Mario + Lapins Crétins', '<p>Integer quis metus vitae elit lobortis egestas. Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>', 1, NULL),
(9, '« Le Crime de l’Orient Express » : rencontre avec Kenneth Branagh', '2017-01-17', 'Résumé de l\'article Le Crime de l’Orient Express', '<p>Morbi vel erat non mauris convallis vehicula. Nulla et sapien. Integer tortor tellus, aliquam faucibus, convallis id, congue eu, quam. Mauris ullamcorper felis vitae erat.</p>', 1, '1982567718.jpg'),
(11, 'Arrow', '2019-01-01', 'Aucun résumé', 'Aucun contenu', 1, '3052.jpg'),
(12, 'Dragon Ball', '2019-01-01', 'Aucun résumé', 'Aucun contenu', 1, '7527.jpg'),
(13, 'Supergirl', '2019-01-03', 'Aucun résumé', 'Aucun contenu', 1, '9678.jpg'),
(72, 'Goku', '2019-04-04', 'Pas de résumé', 'Pas de contenu', 1, '1704527130.jpg'),
(79, 'Deadpool', '2019-04-09', 'Aucun résumé', '', 1, NULL),
(80, 'Arrow', '2019-03-03', 'Aucun résumé', '', 1, NULL),
(82, 'Spidey', '2019-04-14', 'Aucun résumé', '', 1, '1234893467.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `article_category`
--

CREATE TABLE `article_category` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article_category`
--

INSERT INTO `article_category` (`id`, `article_id`, `category_id`) VALUES
(132, 9, 52),
(32, 8, 108),
(31, 7, 108),
(30, 6, 9),
(29, 5, 108),
(28, 4, 108),
(27, 3, 10),
(26, 2, 9),
(25, 1, 47),
(23, 12, 113),
(38, 13, 9),
(22, 12, 108),
(14, 11, 9),
(39, 14, 113),
(40, 15, 47),
(41, 16, 47),
(42, 17, 108),
(43, 18, 52),
(44, 19, 47),
(45, 20, 52),
(46, 21, 9),
(47, 22, 47),
(48, 23, 108),
(49, 24, 9),
(50, 29, 113),
(51, 30, 113),
(52, 31, 113),
(53, 32, 113),
(54, 33, 113),
(55, 34, 113),
(56, 35, 113),
(57, 36, 113),
(58, 37, 113),
(59, 38, 113),
(60, 39, 113),
(61, 40, 113),
(62, 41, 113),
(63, 42, 113),
(64, 43, 113),
(65, 44, 113),
(66, 45, 108),
(67, 46, 108),
(68, 47, 108),
(69, 47, 113),
(70, 48, 108),
(71, 48, 113),
(72, 49, 113),
(73, 50, 113),
(74, 51, 113),
(75, 52, 113),
(76, 53, 113),
(77, 54, 113),
(78, 55, 113),
(79, 56, 113),
(80, 57, 113),
(81, 58, 113),
(82, 59, 108),
(83, 60, 108),
(84, 61, 113),
(85, 62, 108),
(86, 63, 108),
(87, 64, 113),
(88, 65, 108),
(89, 66, 113),
(90, 67, 108),
(91, 67, 113),
(92, 68, 108),
(93, 69, 108),
(94, 70, 113),
(95, 71, 108),
(96, 71, 113),
(144, 72, 113),
(143, 72, 108),
(101, 73, 113),
(106, 74, 108),
(131, 75, 108),
(133, 76, 47),
(134, 76, 52),
(135, 76, 108),
(151, 82, 108),
(147, 80, 108),
(146, 80, 9),
(145, 79, 9),
(150, 82, 9);

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `description`, `image`) VALUES
(9, 'Cinéma', 'Trailers, infos, sorties...', NULL),
(47, 'Musique', 'Concerts, sorties d\'albums, festivals...', NULL),
(52, 'Théâtre', 'Dates, représentations, avis...', NULL),
(108, 'Jeux vidéos', 'Videos, tests...', NULL),
(113, 'Mangas', 'Description test', 'eps1_640x360.jpg'),
(116, 'Hentai', '', '1146590386.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `article_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `caption`, `name`, `article_id`) VALUES
(20, 'Aucune', '1930709491.jpg', 75);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `bio` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `lastname`, `firstname`, `email`, `password`, `is_admin`, `bio`) VALUES
(10, 'Admin', 'Admin', 'admin@thebrickbox.net', 'b53759f3ce692de7aff1b5779d3964da', 1, 'Admin du site'),
(11, 'User', 'User', 'user@thebrickbox.net', 'b53759f3ce692de7aff1b5779d3964da', 0, 'Utilisateur du blog test'),
(18, 'Hamrouni', 'Charf', 'a@a.net', '0cc175b9c0f1b6a831c399e269772661', 0, '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `article_category`
--
ALTER TABLE `article_category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT pour la table `article_category`
--
ALTER TABLE `article_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
