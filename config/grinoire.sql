-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 23 mai 2018 à 13:26
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
  PRIMARY KEY (`game_id`),
  UNIQUE KEY `game_player_1_id` (`game_player_1_id`,`game_player_2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- --------------------------------------------------------

--
-- Structure de la table `tmp_card`
--

DROP TABLE IF EXISTS `tmp_card`;
CREATE TABLE IF NOT EXISTS `tmp_card` (
  `tmp_card_id` int(11) NOT NULL AUTO_INCREMENT,
  `tmp_card_status` varchar(25) DEFAULT NULL,
  `tmp_card_damage_received` int(11) NOT NULL DEFAULT '0',
  `tmp_card_user_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`tmp_card_id`),
  KEY `FK_tmp_card_user_id` (`tmp_card_user_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `tmp_hero`
--

DROP TABLE IF EXISTS `tmp_hero`;
CREATE TABLE IF NOT EXISTS `tmp_hero` (
  `tmp_hero_id` int(11) NOT NULL AUTO_INCREMENT,
  `tmp_hero_mana` int(11) NOT NULL,
  `tmp_hero_damage_received` int(11) NOT NULL,
  `tmp_hero_user_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`tmp_hero_id`),
  KEY `FK_tmp_hero_user_id` (`tmp_hero_user_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  ADD CONSTRAINT `FK_tmp_card_user_id` FOREIGN KEY (`tmp_card_user_id_fk`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `tmp_hero`
--
ALTER TABLE `tmp_hero`
  ADD CONSTRAINT `FK_tmp_hero_user_id` FOREIGN KEY (`tmp_hero_user_id_fk`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_user_deck_id` FOREIGN KEY (`user_deck_id_fk`) REFERENCES `deck` (`deck_id`),
  ADD CONSTRAINT `FK_user_game_id` FOREIGN KEY (`user_game_id_fk`) REFERENCES `game` (`game_id`),
  ADD CONSTRAINT `FK_user_role_id` FOREIGN KEY (`user_role_id_fk`) REFERENCES `role` (`role_id`),
  ADD CONSTRAINT `FK_user_tmp_hero_id` FOREIGN KEY (`user_tmp_hero_id_fk`) REFERENCES `tmp_hero` (`tmp_hero_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
