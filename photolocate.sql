-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 14 Février 2015 à 09:51
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `photolocate`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin_pl`
--

CREATE TABLE IF NOT EXISTS `admin_pl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `admin_pl`
--

INSERT INTO `admin_pl` (`id`, `login`, `password`) VALUES
(1, 'CISIIE', '$2y$10$KcQmyac4XfkoYQfCm2i37OkLLNBTw3ErqrgePH6ALuui/GaERVrH6');

-- --------------------------------------------------------

--
-- Structure de la table `difficulte_pl`
--

CREATE TABLE IF NOT EXISTS `difficulte_pl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(250) NOT NULL,
  `distance` float NOT NULL,
  `temps` int(11) NOT NULL,
  `nb_photos` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `difficulte_pl`
--

INSERT INTO `difficulte_pl` (`id`, `label`, `distance`, `temps`, `nb_photos`) VALUES
(1, 'Facile', 2100, 15, 4),
(2, 'Normale', 1200, 14, 10),
(3, 'Difficile', 300, 13, 12);

-- --------------------------------------------------------

--
-- Structure de la table `game_pl`
--

CREATE TABLE IF NOT EXISTS `game_pl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player` varchar(250) NOT NULL,
  `score` float NOT NULL,
  `date` date NOT NULL,
  `status` varchar(200) NOT NULL,
  `id_difficulte` int(10) unsigned NOT NULL,
  `id_ville` int(10) unsigned NOT NULL,
  `token` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=332 ;

-- --------------------------------------------------------

--
-- Structure de la table `image_pl`
--

CREATE TABLE IF NOT EXISTS `image_pl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(250) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `path` varchar(250) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `adresse` varchar(250) NOT NULL,
  `date` date NOT NULL,
  `id_ville` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Contenu de la table `image_pl`
--

INSERT INTO `image_pl` (`id`, `titre`, `extension`, `path`, `lat`, `lng`, `adresse`, `date`, `id_ville`) VALUES
(1, 'chat_noir', '.jpg', '', 48.6862, 6.1717, '63, rue Jeanne d''Arc,\r\n54000 Nancy , Meurthe-et-moselle', '2015-02-09', 1),
(2, 'envers_club', '.jpg', '', 48.6847, 6.1703, '1, rue Général Hoche,54000 Nancy , Meurthe-et-moselle', '2015-02-09', 1),
(4, 'les_caves', '.jpg', '', 48.6939, 6.18301, '9 place Stanislas\r\n54000 Nancy', '2015-02-09', 1),
(5, '915_kaffe', '.jpg', '', 48.6911, 6.18181, '22 rue St Dizier\r\n54000 Nancy', '2015-02-09', 1),
(6, 'lendroit', '.jpg', '', 48.6924, 6.17852, '3 rue des Michottes\r\n54000 Nancy', '2015-02-09', 1),
(7, 'le_reseau', '.jpg', '', 48.6901, 6.17457, '5 Rue Piroux\r\n54000 Nancy', '2015-02-09', 1),
(8, 'la_place', '.jpg', '', 48.6938, 6.18253, '7 place Stanislas, Nancy', '2015-02-09', 1),
(9, 'ChateauEpinal', '.jpg', '', 48.1753, 6.4556, 'Chateau Epinal', '2015-02-12', 2),
(10, 'BasiliqueEpinal', '.jpg', '', 48.1742, 6.45083, 'Basilique saint maurice epinal', '2015-02-12', 2),
(11, 'ImagerieEpinal', '.jpg', '', 48.184, 6.44636, 'Imagerie Epinal', '2015-02-12', 2),
(12, 'PlaceVosges', '.jpg', '', 48.1744, 6.4512, 'Place des Vosges', '2015-02-12', 2),
(13, 'appart', '.jpg', '', 48.692, 6.18442, '7 Rue Saint-Julien 54000 Nancy', '2015-02-12', 1),
(14, 'les_artistes', '.jpg', '', 48.6931, 6.18088, '34 Rue Stanislas 54000 Nancy, France', '2015-02-12', 1),
(15, 'carthy', '.jpg', '', 48.6925, 6.17746, '6 Rue Guerrier de Dumast, 54000 Nancy', '2015-02-12', 1),
(17, 'irlandais', '.jpg', '', 48.6899, 6.17603, '8 Rue Mazagran, 54000 Nancy', '2015-02-14', 1),
(18, 'mouton', '.jpg', '', 48.6911, 6.18609, 'Rue de la Primatiale, 54000 Nancy', '2015-02-14', 1),
(19, 'ChateauEpinal', '.jpg', '', 48.1753, 6.4556, 'Chateau Epinal', '2015-02-12', 2),
(20, 'ImagerieEpinal', '.jpg', '', 48.184, 6.44636, 'Imagerie Epinal', '2015-02-12', 2),
(21, 'BasiliqueEpinal', '.jpg', '', 48.1742, 6.45083, 'Basilique saint maurice epinal', '2015-02-12', 2),
(22, 'PlaceVosges', '.jpg', '', 48.1744, 6.4512, 'Place des Vosges', '2015-02-12', 2),
(23, 'PlaceVosges', '.jpg', '', 48.1744, 6.4512, 'Place des Vosges', '2015-02-12', 2),
(24, 'ImagerieEpinal', '.jpg', '', 48.184, 6.44636, 'Imagerie Epinal', '2015-02-12', 2),
(25, 'BasiliqueEpinal', '.jpg', '', 48.1742, 6.45083, 'Basilique saint maurice epinal', '2015-02-12', 2),
(26, 'ChateauEpinal', '.jpg', '', 48.1753, 6.4556, 'Chateau Epinal', '2015-02-12', 2);

-- --------------------------------------------------------

--
-- Structure de la table `ville_pl`
--

CREATE TABLE IF NOT EXISTS `ville_pl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `ville_pl`
--

INSERT INTO `ville_pl` (`id`, `nom`, `lat`, `lng`) VALUES
(1, 'Nancy', 48.69, 6.1744),
(2, 'Epinal', 48.178, 6.4414);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
