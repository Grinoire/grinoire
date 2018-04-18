-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  mer. 04 avr. 2018 à 10:56
-- Version du serveur :  5.6.39-cll-lve
-- Version de PHP :  7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `objectif_grinoire`
--

-- --------------------------------------------------------

--
-- Structure de la table `action`
--

CREATE TABLE `action` (
  `action_id` int(11) NOT NULL,
  `action_name` varchar(25) NOT NULL,
  `action_power_needed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `action`
--

INSERT INTO `action` (`action_id`, `action_name`, `action_power_needed`) VALUES
(1, 'bannir', 1),
(2, 'modifier_profil', 11);

-- --------------------------------------------------------

--
-- Structure de la table `card`
--

CREATE TABLE `card` (
  `card_id` int(11) NOT NULL,
  `card_name` varchar(25) NOT NULL,
  `card_description` varchar(90) NOT NULL,
  `card_background` varchar(25) NOT NULL,
  `card_mana` int(11) NOT NULL,
  `card_life` int(11) NOT NULL,
  `card_attack` int(11) NOT NULL,
  `card_damage_received` int(11) NOT NULL,
  `card_status` varchar(25) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  `deck_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `deck`
--

CREATE TABLE `deck` (
  `deck_id` int(11) NOT NULL,
  `deck_name` varchar(25) NOT NULL,
  `deck_color` varchar(25) NOT NULL,
  `heros_name` varchar(25) DEFAULT NULL,
  `heros_background` varchar(25) DEFAULT NULL,
  `heros_mana` int(11) DEFAULT NULL,
  `heros_life` int(11) DEFAULT NULL,
  `hero_damage_received` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `newsletter`
--

CREATE TABLE `newsletter` (
  `suscriber_id` int(11) NOT NULL,
  `suscriber_mail` varchar(25) NOT NULL,
  `suscriber_inscription` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `can`
--

CREATE TABLE `can` (
  `role_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `can`
--

INSERT INTO `can` (`role_id`, `action_id`) VALUES
(1, 1),
(1, 2),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(25) NOT NULL,
  `role_power` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`role_id`, `role_name`, `role_power`) VALUES
(1, 'Admin', 0),
(2, 'Moderateur', 10),
(3, 'user', 50);

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

CREATE TABLE `type` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(25) NOT NULL,
  `type_background` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_last_name` varchar(25) DEFAULT NULL,
  `user_first_name` varchar(25) DEFAULT NULL,
  `user_mail` varchar(25) NOT NULL,
  `user_login` varchar(25) NOT NULL,
  `user_password` varchar(25) NOT NULL,
  `user_inscription` datetime NOT NULL,
  `user_winned_game` int(11) DEFAULT NULL,
  `user_played_game` int(11) DEFAULT NULL,
  `deck_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `action`
--
ALTER TABLE `action`
  ADD PRIMARY KEY (`action_id`),
  ADD UNIQUE KEY `action_name` (`action_name`);

--
-- Index pour la table `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`card_id`),
  ADD UNIQUE KEY `card_name` (`card_name`),
  ADD KEY `FK_card_type_id` (`type_id`),
  ADD KEY `FK_card_deck_id` (`deck_id`);

--
-- Index pour la table `deck`
--
ALTER TABLE `deck`
  ADD PRIMARY KEY (`deck_id`),
  ADD UNIQUE KEY `deck_name` (`deck_name`,`deck_color`,`heros_name`,`heros_background`);

--
-- Index pour la table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`suscriber_id`);

--
-- Index pour la table `can`
--
ALTER TABLE `can`
  ADD PRIMARY KEY (`role_id`,`action_id`),
  ADD KEY `FK_can_action_id` (`action_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Index pour la table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_mail` (`user_mail`,`user_login`),
  ADD KEY `FK_user_deck_id` (`deck_id`),
  ADD KEY `FK_user_role_id` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `action`
--
ALTER TABLE `action`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `card`
--
ALTER TABLE `card`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `deck`
--
ALTER TABLE `deck`
  MODIFY `deck_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `suscriber_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `type`
--
ALTER TABLE `type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `card`
--
ALTER TABLE `card`
  ADD CONSTRAINT `FK_card_deck_id` FOREIGN KEY (`deck_id`) REFERENCES `deck` (`deck_id`),
  ADD CONSTRAINT `FK_card_type_id` FOREIGN KEY (`type_id`) REFERENCES `type` (`type_id`);

--
-- Contraintes pour la table `can`
--
ALTER TABLE `can`
  ADD CONSTRAINT `FK_can_action_id` FOREIGN KEY (`action_id`) REFERENCES `action` (`action_id`),
  ADD CONSTRAINT `FK_can_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_user_deck_id` FOREIGN KEY (`deck_id`) REFERENCES `deck` (`deck_id`),
  ADD CONSTRAINT `FK_user_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
