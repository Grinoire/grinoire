-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 11 juil. 2018 à 13:18
-- Version du serveur :  5.7.19
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `grinoire`
--

-- --------------------------------------------------------

--
-- Structure de la table `action`
--

DROP TABLE IF EXISTS `action`;
CREATE TABLE IF NOT EXISTS `action` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_name` varchar(25) NOT NULL,
  PRIMARY KEY (`action_id`),
  UNIQUE KEY `action_name` (`action_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `action`
--

INSERT INTO `action` (`action_id`, `action_name`) VALUES
(1, 'bannir'),
(2, 'modifier_profil');

-- --------------------------------------------------------

--
-- Structure de la table `can`
--

DROP TABLE IF EXISTS `can`;
CREATE TABLE IF NOT EXISTS `can` (
  `can_role_id_fk` int(11) NOT NULL,
  `can_action_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`can_role_id_fk`,`can_action_id_fk`),
  KEY `FK_can_action_id` (`can_action_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `can`
--

INSERT INTO `can` (`can_role_id_fk`, `can_action_id_fk`) VALUES
(1, 1),
(1, 2),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `card`
--

DROP TABLE IF EXISTS `card`;
CREATE TABLE IF NOT EXISTS `card` (
  `card_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_name` varchar(25) NOT NULL,
  `card_description` varchar(90) NOT NULL,
  `card_bg` varchar(25) NOT NULL,
  `card_mana` int(11) NOT NULL,
  `card_life` int(11) DEFAULT NULL,
  `card_attack` int(11) NOT NULL,
  `card_damage_received` int(11) DEFAULT '0',
  `card_status` varchar(25) DEFAULT NULL,
  `card_type_id_fk` int(11) DEFAULT NULL,
  `card_deck_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`card_id`),
  KEY `FK_card_type_id` (`card_type_id_fk`),
  KEY `FK_card_deck_id` (`card_deck_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `card`
--

INSERT INTO `card` (`card_id`, `card_name`, `card_description`, `card_bg`, `card_mana`, `card_life`, `card_attack`, `card_damage_received`, `card_status`, `card_type_id_fk`, `card_deck_id_fk`) VALUES
(1, 'Cavalier sans tête', 'Un tête à tête ?', 'cavalier-sans-tete-bg.png', 9, 9, 9, NULL, NULL, 1, 1),
(2, 'Mr Pingouin', 'Je jette un froid', 'mr-pingouin-bg.png', 1, 3, 1, NULL, NULL, 2, 1),
(3, 'Mr Pingouin', 'Je jette un froid', 'mr-pingouin-bg.png', 1, 3, 1, NULL, NULL, 2, 1),
(4, 'Beetlejuice', 'Beetlejuice, Beetlejuice, Beetlejuice !', 'beetlejuice-bg.png', 3, 6, 3, NULL, NULL, 2, 1),
(5, 'Beetlejuice', 'Beetlejuice, Beetlejuice, Beetlejuice !', 'beetlejuice-bg.png', 3, 6, 3, NULL, NULL, 2, 1),
(6, 'Golden ticket ', 'A definir', 'golden-ticket-bg.png', 5, NULL, 6, NULL, NULL, 3, 1),
(7, 'Le chat', 'ce monde n’a aucun sens….', 'le-chat-bg.png', 1, NULL, 1, NULL, NULL, 3, 1),
(8, 'Barnabas', 'je reviendrais', 'barnabas-bg.png', 3, NULL, 4, NULL, NULL, 3, 1),
(9, 'Sépulcreux', 'tant de chemins pour vous atteindre', 'sepulcreux-bg.png', 1, 1, 2, NULL, NULL, 4, 1),
(10, 'Sépulcreux', 'tant de chemins pour vous atteindre', 'sepulcreux-bg.png', 1, 1, 2, NULL, NULL, 4, 1),
(11, 'Emily', 'un coeur mort peut-il se briser ?', 'emily-bg.png', 2, 3, 2, NULL, NULL, 4, 1),
(12, 'Emily', 'un coeur mort peut-il se briser ?', 'emily-bg.png', 2, 3, 2, NULL, NULL, 4, 1),
(13, 'Le martien', 'nous venons en paix', 'martien-bg.png', 3, 3, 5, NULL, NULL, 4, 1),
(14, 'Le martien', 'nous venons en paix', 'martien-bg.png', 3, 3, 5, NULL, NULL, 4, 1),
(15, 'Edward', 'couic couic, ca vous la coupe', 'edouard-bg.png', 4, 4, 2, NULL, NULL, 4, 1),
(16, 'Edward', 'couic couic, ca vous la coupe', 'edouard-bg.png', 4, 4, 2, NULL, NULL, 4, 1),
(17, 'Mr Jack', 'le Roi des citrouilles', 'mr-jack-bg.png', 5, 5, 7, NULL, NULL, 4, 1),
(18, 'Mr Jack', 'le Roi des citrouilles', 'mr-jack-bg.png', 5, 5, 7, NULL, NULL, 4, 1),
(19, 'Catwoman', 'Je suis née le jour de ma mort', 'catwoman-bg.png', 7, 6, 8, NULL, NULL, 4, 1),
(20, 'Catwoman', 'Je suis née le jour de ma mort', 'catwoman-bg.png', 7, 6, 8, NULL, NULL, 4, 1),
(21, 'Drogo', 'Le feu et le sang', 'drogo-bg.png', 9, 9, 9, 0, NULL, 1, 2),
(22, 'Paladin', 'Le rempart de la justice', 'paladin-bg.png', 1, 3, 1, 0, NULL, 2, 2),
(23, 'Paladin', 'Le rempart de la justice', 'paladin-bg.png', 1, 3, 1, 0, NULL, 2, 2),
(24, 'La moria', 'L\'infranchissable forteresse', 'moria-bg.png', 3, 6, 3, 0, NULL, 2, 2),
(25, 'La moria', 'L\'infranchissable forteresse', 'moria-bg.png', 3, 6, 3, 0, NULL, 2, 2),
(26, 'Le saint graal', 'Restaure X point de vie', 'graal-bg.png', 1, NULL, 2, 0, NULL, 3, 2),
(27, 'Armageddon', 'Detruit toutes les créatures du plateau', 'armageddon-bg.png', 3, NULL, 5, 0, NULL, 3, 2),
(28, 'Résurrection', 'Rappelle une créature morte', 'resurrection-bg.png', 5, NULL, 7, 0, NULL, 3, 2),
(29, 'Voldemort', 'Avada Kedavra', 'voldemort-bg.png', 2, 3, 2, 0, NULL, 4, 2),
(30, 'Voldemort', 'Avada Kedavra', 'voldemort-bg.png', 2, 3, 2, 0, NULL, 4, 2),
(31, 'Maléfique', 'Dors mon ange', 'malefique-bg.png', 1, 1, 1, 0, NULL, 4, 2),
(32, 'Maléfique', 'Dors mon ange', 'malefique-bg.png', 1, 1, 1, 0, NULL, 4, 2),
(33, 'Zoltan', 'C\'est pas la taille qui compte', 'zoltan-bg.png', 3, 3, 4, 0, NULL, 4, 2),
(34, 'Zoltan', 'C\'est pas la taille qui compte', 'zoltan-bg.png', 3, 3, 4, 0, NULL, 4, 2),
(35, 'Garrosh', 'La victoire ou la mort', 'garrosh-bg.png', 4, 4, 2, 0, NULL, 4, 2),
(36, 'Garrosh', 'La victoire ou la mort', 'garrosh-bg.png', 4, 4, 2, 0, NULL, 4, 2),
(37, 'Eilistraee', 'La nuit est mon domaine', 'eilistraee-bg.png', 5, 5, 6, 0, NULL, 4, 2),
(38, 'Eilistraee', 'La nuit est mon domaine', 'eilistraee-bg.png', 5, 5, 6, 0, NULL, 4, 2),
(39, 'Ragnaros', 'Meurs insecte', 'ragnaros-bg.png', 7, 6, 8, 0, NULL, 4, 2),
(40, 'Ragnaros', 'Meurs insecte', 'ragnaros-bg.png', 7, 6, 8, 0, NULL, 4, 2);

-- --------------------------------------------------------

--
-- Structure de la table `deck`
--

DROP TABLE IF EXISTS `deck`;
CREATE TABLE IF NOT EXISTS `deck` (
  `deck_id` int(11) NOT NULL AUTO_INCREMENT,
  `deck_name` varchar(25) NOT NULL,
  `deck_color` varchar(25) NOT NULL,
  `hero_name` varchar(25) DEFAULT NULL,
  `hero_bg` varchar(25) DEFAULT NULL,
  `hero_mana` int(11) DEFAULT NULL,
  `hero_life` int(11) DEFAULT NULL,
  `hero_damage_received` int(11) DEFAULT NULL,
  PRIMARY KEY (`deck_id`),
  UNIQUE KEY `deck_name` (`deck_name`,`deck_color`,`hero_name`,`hero_bg`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `deck`
--

INSERT INTO `deck` (`deck_id`, `deck_name`, `deck_color`, `hero_name`, `hero_bg`, `hero_mana`, `hero_life`, `hero_damage_received`) VALUES
(1, 'Tim Burton', 'gray', 'Le Chapelier', 'chapelier-bg.png', 0, 20, 0),
(2, 'Heroic Fantasy', 'green', 'Gandalf', 'gandalf-bg.png', 0, 20, 0);

-- --------------------------------------------------------

--
-- Structure de la table `game`
--

DROP TABLE IF EXISTS `game`;
CREATE TABLE IF NOT EXISTS `game` (
  `game_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_player_1_id` int(11) NOT NULL,
  `game_player_2_id` int(11) NOT NULL,
  `game_turn` int(11) NOT NULL DEFAULT '1',
  `game_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = game ended, 1 = game on',
  `game_create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `newsletter`
--

DROP TABLE IF EXISTS `newsletter`;
CREATE TABLE IF NOT EXISTS `newsletter` (
  `suscriber_id` int(11) NOT NULL AUTO_INCREMENT,
  `suscriber_mail` varchar(25) NOT NULL,
  `suscriber_inscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `suscriber_status` int(11) NOT NULL DEFAULT '1' COMMENT 'boolean',
  PRIMARY KEY (`suscriber_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(25) NOT NULL,
  `role_power` int(11) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`role_id`, `role_name`, `role_power`) VALUES
(1, 'Admin', 0),
(2, 'Moderateur', 10),
(3, 'user', 50);

-- --------------------------------------------------------

--
-- Structure de la table `tmp_card`
--

DROP TABLE IF EXISTS `tmp_card`;
CREATE TABLE IF NOT EXISTS `tmp_card` (
  `tmpcard_id` int(11) NOT NULL AUTO_INCREMENT,
  `tmpcard_name` varchar(50) NOT NULL,
  `tmpcard_description` varchar(250) NOT NULL,
  `tmpcard_bg` varchar(250) NOT NULL,
  `tmpcard_life` int(3) DEFAULT NULL,
  `tmpcard_attack` int(2) NOT NULL,
  `tmpcard_mana` int(2) NOT NULL,
  `tmpcard_status` varchar(25) DEFAULT NULL,
  `tmpcard_damage_received` int(11) DEFAULT '0',
  `tmpcard_type_id_fk` int(11) NOT NULL,
  `tmpcard_user_id_fk` int(11) NOT NULL,
  `tmpcard_deck_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`tmpcard_id`),
  KEY `FK_tmp_card_user_id` (`tmpcard_user_id_fk`),
  KEY `FK_tmp_card_type_id` (`tmpcard_type_id_fk`),
  KEY `FK_tmp_card_deck_id` (`tmpcard_deck_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=942 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tmp_card`
--

INSERT INTO `tmp_card` (`tmpcard_id`, `tmpcard_name`, `tmpcard_description`, `tmpcard_bg`, `tmpcard_life`, `tmpcard_attack`, `tmpcard_mana`, `tmpcard_status`, `tmpcard_damage_received`, `tmpcard_type_id_fk`, `tmpcard_user_id_fk`, `tmpcard_deck_id_fk`) VALUES
(902, 'Eilistraee', 'La nuit est mon domaine', 'eilistraee-bg.png', 5, 6, 5, '4', 0, 4, 17, 2),
(903, 'Paladin', 'Le rempart de la justice', 'paladin-bg.png', 3, 1, 1, '1', 0, 2, 17, 2),
(904, 'Drogo', 'Le feu et le sang', 'drogo-bg.png', 9, 9, 9, '4', 0, 1, 17, 2),
(905, 'La moria', 'L\'infranchissable forteresse', 'moria-bg.png', 6, 3, 3, '2', 8, 2, 17, 2),
(906, 'Le saint graal', 'Restaure X point de vie', 'graal-bg.png', NULL, 2, 1, '4', 0, 3, 17, 2),
(907, 'Maléfique', 'Dors mon ange', 'malefique-bg.png', 1, 1, 1, '0', 0, 4, 17, 2),
(908, 'Voldemort', 'Avada Kedavra', 'voldemort-bg.png', 3, 2, 2, '0', 0, 4, 17, 2),
(909, 'Garrosh', 'La victoire ou la mort', 'garrosh-bg.png', 4, 2, 4, '0', 0, 4, 17, 2),
(910, 'Armageddon', 'Detruit toutes les créatures du plateau', 'armageddon-bg.png', NULL, 5, 3, '0', 0, 3, 17, 2),
(911, 'Ragnaros', 'Meurs insecte', 'ragnaros-bg.png', 6, 8, 7, '0', 0, 4, 17, 2),
(912, 'Eilistraee', 'La nuit est mon domaine', 'eilistraee-bg.png', 5, 6, 5, '0', 0, 4, 17, 2),
(913, 'La moria', 'L\'infranchissable forteresse', 'moria-bg.png', 6, 3, 3, '0', 0, 2, 17, 2),
(914, 'Ragnaros', 'Meurs insecte', 'ragnaros-bg.png', 6, 8, 7, '0', 0, 4, 17, 2),
(915, 'Résurrection', 'Rappelle une créature morte', 'resurrection-bg.png', NULL, 7, 5, '0', 0, 3, 17, 2),
(916, 'Zoltan', 'C\'est pas la taille qui compte', 'zoltan-bg.png', 3, 4, 3, '0', 0, 4, 17, 2),
(917, 'Paladin', 'Le rempart de la justice', 'paladin-bg.png', 3, 1, 1, '0', 0, 2, 17, 2),
(918, 'Zoltan', 'C\'est pas la taille qui compte', 'zoltan-bg.png', 3, 4, 3, '0', 0, 4, 17, 2),
(919, 'Maléfique', 'Dors mon ange', 'malefique-bg.png', 1, 1, 1, '2', 8, 4, 17, 2),
(920, 'Garrosh', 'La victoire ou la mort', 'garrosh-bg.png', 4, 2, 4, '2', 8, 4, 17, 2),
(921, 'Voldemort', 'Avada Kedavra', 'voldemort-bg.png', 3, 2, 2, '2', 9, 4, 17, 2),
(922, 'Le martien', 'nous venons en paix', 'martien-bg.png', 3, 5, 3, '4', NULL, 4, 26, 1),
(923, 'Emily', 'un coeur mort peut-il se briser ?', 'emily-bg.png', 3, 2, 2, '0', NULL, 4, 26, 1),
(924, 'Sépulcreux', 'tant de chemins pour vous atteindre', 'sepulcreux-bg.png', 1, 2, 1, '0', NULL, 4, 26, 1),
(925, 'Beetlejuice', 'Beetlejuice, Beetlejuice, Beetlejuice !', 'beetlejuice-bg.png', 6, 3, 3, '0', NULL, 2, 26, 1),
(926, 'Mr Pingouin', 'Je jette un froid', 'mr-pingouin-bg.png', 3, 1, 1, '0', NULL, 2, 26, 1),
(927, 'Mr Jack', 'le Roi des citrouilles', 'mr-jack-bg.png', 5, 7, 5, '0', NULL, 4, 26, 1),
(928, 'Mr Jack', 'le Roi des citrouilles', 'mr-jack-bg.png', 5, 7, 5, '0', NULL, 4, 26, 1),
(929, 'Golden ticket ', 'A definir', 'golden-ticket-bg.png', NULL, 6, 5, '0', NULL, 3, 26, 1),
(930, 'Mr Pingouin', 'Je jette un froid', 'mr-pingouin-bg.png', 3, 1, 1, '0', NULL, 2, 26, 1),
(931, 'Edward', 'couic couic, ca vous la coupe', 'edouard-bg.png', 4, 2, 4, '0', NULL, 4, 26, 1),
(932, 'Edward', 'couic couic, ca vous la coupe', 'edouard-bg.png', 4, 2, 4, '0', NULL, 4, 26, 1),
(933, 'Emily', 'un coeur mort peut-il se briser ?', 'emily-bg.png', 3, 2, 2, '0', NULL, 4, 26, 1),
(934, 'Beetlejuice', 'Beetlejuice, Beetlejuice, Beetlejuice !', 'beetlejuice-bg.png', 6, 3, 3, '0', NULL, 2, 26, 1),
(935, 'Sépulcreux', 'tant de chemins pour vous atteindre', 'sepulcreux-bg.png', 1, 2, 1, '0', NULL, 4, 26, 1),
(936, 'Le martien', 'nous venons en paix', 'martien-bg.png', 3, 5, 3, '0', NULL, 4, 26, 1),
(937, 'Barnabas', 'je reviendrais', 'barnabas-bg.png', NULL, 4, 3, '0', NULL, 3, 26, 1),
(938, 'Cavalier sans tête', 'Un tête à tête ?', 'cavalier-sans-tete-bg.png', 9, 9, 9, '0', NULL, 1, 26, 1),
(939, 'Catwoman', 'Je suis née le jour de ma mort', 'catwoman-bg.png', 6, 8, 7, '4', NULL, 4, 26, 1),
(940, 'Catwoman', 'Je suis née le jour de ma mort', 'catwoman-bg.png', 6, 8, 7, '4', 3, 4, 26, 1),
(941, 'Le chat', 'ce monde n’a aucun sens….', 'le-chat-bg.png', NULL, 1, 1, '2', 2, 3, 26, 1);

-- --------------------------------------------------------

--
-- Structure de la table `tmp_hero`
--

DROP TABLE IF EXISTS `tmp_hero`;
CREATE TABLE IF NOT EXISTS `tmp_hero` (
  `tmphero_id` int(11) NOT NULL AUTO_INCREMENT,
  `tmphero_mana` int(11) NOT NULL,
  `tmphero_name` varchar(50) NOT NULL,
  `tmphero_life` int(3) NOT NULL,
  `tmphero_bg` varchar(200) NOT NULL,
  `tmphero_damage_received` int(11) NOT NULL,
  `tmphero_user_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`tmphero_id`),
  KEY `tmp_hero_user_FK` (`tmphero_user_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tmp_hero`
--

INSERT INTO `tmp_hero` (`tmphero_id`, `tmphero_mana`, `tmphero_name`, `tmphero_life`, `tmphero_bg`, `tmphero_damage_received`, `tmphero_user_id_fk`) VALUES
(27, 0, 'Gandalf', 20, 'gandalf-bg.png', 227, 26),
(28, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(29, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(30, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(31, 0, 'Le Chapelier', 20, 'chapelier-bg.png', 0, 17),
(32, 0, 'Le Chapelier', 20, 'chapelier-bg.png', 0, 17),
(33, 0, 'Le Chapelier', 20, 'chapelier-bg.png', 0, 17),
(34, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(35, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(36, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(37, 0, 'Le Chapelier', 20, 'chapelier-bg.png', 0, 17),
(38, 0, 'Le Chapelier', 20, 'chapelier-bg.png', 0, 17),
(39, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(40, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(41, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(42, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(43, 0, 'Le Chapelier', 20, 'chapelier-bg.png', 0, 17),
(44, 0, 'Le Chapelier', 20, 'chapelier-bg.png', 0, 17),
(45, 0, 'Le Chapelier', 20, 'chapelier-bg.png', 0, 17),
(46, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(47, 0, 'Le Chapelier', 20, 'chapelier-bg.png', 0, 17),
(48, 0, 'Le Chapelier', 20, 'chapelier-bg.png', 0, 17),
(49, 0, 'Le Chapelier', 20, 'chapelier-bg.png', 0, 17),
(50, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(51, 0, 'Gandalf', 20, 'gandalf-bg.png', 0, 17),
(52, 0, 'Le Chapelier', 20, 'chapelier-bg.png', 0, 26);

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(25) NOT NULL,
  `type_bg` varchar(25) NOT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `type`
--

INSERT INTO `type` (`type_id`, `type_name`, `type_bg`) VALUES
(1, 'Légendaire', 'legendaire-bg.png'),
(2, 'Bouclier', 'bouclier-bg.png'),
(3, 'Sort', 'sort-bg.png'),
(4, 'Créature', 'creature-bg.png');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_last_name` varchar(25) DEFAULT NULL,
  `user_first_name` varchar(25) DEFAULT NULL,
  `user_mail` varchar(100) NOT NULL,
  `user_login` varchar(25) NOT NULL,
  `user_password` varchar(25) NOT NULL,
  `user_avatar` varchar(100) DEFAULT NULL,
  `user_inscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_winned_game` int(11) DEFAULT NULL,
  `user_played_game` int(11) DEFAULT NULL,
  `user_ready` int(11) NOT NULL DEFAULT '0' COMMENT 'boolean',
  `user_deck_id_fk` int(11) DEFAULT NULL,
  `user_role_id_fk` int(11) NOT NULL DEFAULT '3',
  `user_game_id_fk` int(11) DEFAULT NULL,
  `user_tmp_hero_id_fk` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_mail` (`user_mail`,`user_login`),
  KEY `FK_user_deck_id` (`user_deck_id_fk`),
  KEY `FK_user_role_id` (`user_role_id_fk`),
  KEY `FK_user_game_id` (`user_game_id_fk`),
  KEY `FK_user_tmp_hero_id` (`user_tmp_hero_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`user_id`, `user_last_name`, `user_first_name`, `user_mail`, `user_login`, `user_password`, `user_avatar`, `user_inscription`, `user_winned_game`, `user_played_game`, `user_ready`, `user_deck_id_fk`, `user_role_id_fk`, `user_game_id_fk`, `user_tmp_hero_id_fk`) VALUES
(17, 'bi', 'bi', 'bibi@gmail.com', 'bibi', 'bibi', '112131.jpg', '2018-05-23 11:40:17', NULL, NULL, 1, 2, 3, 190, NULL),
(26, NULL, NULL, 'tou@gmail.com', 'tou', 'tou', NULL, '2018-05-23 11:48:44', NULL, NULL, 1, 1, 3, 190, NULL),
(27, NULL, NULL, 'titi@gmail.com', 'titi', 'titi', NULL, '2018-05-23 11:49:32', NULL, NULL, 0, NULL, 3, NULL, NULL),
(28, NULL, NULL, 'tobi@gmail.com', 'tobi', 'tobi', NULL, '2018-05-23 11:51:28', NULL, NULL, 0, NULL, 3, NULL, NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `can`
--
ALTER TABLE `can`
  ADD CONSTRAINT `FK_can_action_id` FOREIGN KEY (`can_action_id_fk`) REFERENCES `action` (`action_id`),
  ADD CONSTRAINT `FK_can_role_id` FOREIGN KEY (`can_role_id_fk`) REFERENCES `role` (`role_id`);

--
-- Contraintes pour la table `card`
--
ALTER TABLE `card`
  ADD CONSTRAINT `FK_card_deck_id` FOREIGN KEY (`card_deck_id_fk`) REFERENCES `deck` (`deck_id`),
  ADD CONSTRAINT `FK_card_type_id` FOREIGN KEY (`card_type_id_fk`) REFERENCES `type` (`type_id`);

--
-- Contraintes pour la table `tmp_card`
--
ALTER TABLE `tmp_card`
  ADD CONSTRAINT `FK_tmp_card_deck_id` FOREIGN KEY (`tmpcard_deck_id_fk`) REFERENCES `deck` (`deck_id`),
  ADD CONSTRAINT `FK_tmp_card_type_id` FOREIGN KEY (`tmpcard_type_id_fk`) REFERENCES `type` (`type_id`),
  ADD CONSTRAINT `FK_tmp_card_user_id` FOREIGN KEY (`tmpcard_user_id_fk`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `tmp_hero`
--
ALTER TABLE `tmp_hero`
  ADD CONSTRAINT `tmp_hero_user_FK` FOREIGN KEY (`tmphero_user_id_fk`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_user_deck_id` FOREIGN KEY (`user_deck_id_fk`) REFERENCES `deck` (`deck_id`),
  ADD CONSTRAINT `FK_user_game_id` FOREIGN KEY (`user_game_id_fk`) REFERENCES `game` (`game_id`),
  ADD CONSTRAINT `FK_user_role_id` FOREIGN KEY (`user_role_id_fk`) REFERENCES `role` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
