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
  `action_nom` varchar(25) NOT NULL,
  `action_force_necessaire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `action`
--

INSERT INTO `action` (`action_id`, `action_nom`, `action_force_necessaire`) VALUES
(1, 'bannir', 1),
(2, 'modifier_profil', 11);

-- --------------------------------------------------------

--
-- Structure de la table `carte`
--

CREATE TABLE `carte` (
  `carte_id` int(11) NOT NULL,
  `carte_nom` varchar(25) NOT NULL,
  `carte_description` varchar(90) NOT NULL,
  `carte_bg` varchar(25) NOT NULL,
  `carte_mana` int(11) NOT NULL,
  `carte_vie` int(11) NOT NULL,
  `carte_attaque` int(11) NOT NULL,
  `carte_degat_recu` int(11) NOT NULL,
  `carte_status` varchar(25) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  `deck_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `deck`
--

CREATE TABLE `deck` (
  `deck_id` int(11) NOT NULL,
  `deck_nom` varchar(25) NOT NULL,
  `deck_couleur` varchar(25) NOT NULL,
  `heros_nom` varchar(25) DEFAULT NULL,
  `heros_bg` varchar(25) DEFAULT NULL,
  `heros_mana` int(11) DEFAULT NULL,
  `heros_vie` int(11) DEFAULT NULL,
  `hero_degat_recu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `newsletter`
--

CREATE TABLE `newsletter` (
  `abonne_id` int(11) NOT NULL,
  `abonne_mail` varchar(25) NOT NULL,
  `abonne_date_inscription` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `peut_faire`
--

CREATE TABLE `peut_faire` (
  `role_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `peut_faire`
--

INSERT INTO `peut_faire` (`role_id`, `action_id`) VALUES
(1, 1),
(1, 2),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_nom` varchar(25) NOT NULL,
  `role_force` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`role_id`, `role_nom`, `role_force`) VALUES
(1, 'Admin', 0),
(2, 'Moderateur', 10),
(3, 'Utilisateur', 50);

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

CREATE TABLE `type` (
  `type_id` int(11) NOT NULL,
  `type_nom` varchar(25) NOT NULL,
  `type_bg_contour` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `utilisateur_id` int(11) NOT NULL,
  `utilisateur_nom` varchar(25) DEFAULT NULL,
  `utilisateur_prenom` varchar(25) DEFAULT NULL,
  `utilisateur_mail` varchar(25) NOT NULL,
  `utilisateur_pseudo` varchar(25) NOT NULL,
  `utilisateur_password` varchar(25) NOT NULL,
  `utilisateur_date_inscription` datetime NOT NULL,
  `utilisateur_partie_gagne` int(11) DEFAULT NULL,
  `utilisateur_partie_joue` int(11) DEFAULT NULL,
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
  ADD UNIQUE KEY `action_nom` (`action_nom`);

--
-- Index pour la table `carte`
--
ALTER TABLE `carte`
  ADD PRIMARY KEY (`carte_id`),
  ADD UNIQUE KEY `carte_nom` (`carte_nom`),
  ADD KEY `FK_carte_type_id` (`type_id`),
  ADD KEY `FK_carte_deck_id` (`deck_id`);

--
-- Index pour la table `deck`
--
ALTER TABLE `deck`
  ADD PRIMARY KEY (`deck_id`),
  ADD UNIQUE KEY `deck_nom` (`deck_nom`,`deck_couleur`,`heros_nom`,`heros_bg`);

--
-- Index pour la table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`abonne_id`);

--
-- Index pour la table `peut_faire`
--
ALTER TABLE `peut_faire`
  ADD PRIMARY KEY (`role_id`,`action_id`),
  ADD KEY `FK_peut_faire_action_id` (`action_id`);

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
  ADD UNIQUE KEY `type_nom` (`type_nom`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`utilisateur_id`),
  ADD UNIQUE KEY `utilisateur_mail` (`utilisateur_mail`,`utilisateur_pseudo`),
  ADD KEY `FK_utilisateur_deck_id` (`deck_id`),
  ADD KEY `FK_utilisateur_role_id` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `action`
--
ALTER TABLE `action`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `carte`
--
ALTER TABLE `carte`
  MODIFY `carte_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `deck`
--
ALTER TABLE `deck`
  MODIFY `deck_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `abonne_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `utilisateur_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `carte`
--
ALTER TABLE `carte`
  ADD CONSTRAINT `FK_carte_deck_id` FOREIGN KEY (`deck_id`) REFERENCES `deck` (`deck_id`),
  ADD CONSTRAINT `FK_carte_type_id` FOREIGN KEY (`type_id`) REFERENCES `type` (`type_id`);

--
-- Contraintes pour la table `peut_faire`
--
ALTER TABLE `peut_faire`
  ADD CONSTRAINT `FK_peut_faire_action_id` FOREIGN KEY (`action_id`) REFERENCES `action` (`action_id`),
  ADD CONSTRAINT `FK_peut_faire_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `FK_utilisateur_deck_id` FOREIGN KEY (`deck_id`) REFERENCES `deck` (`deck_id`),
  ADD CONSTRAINT `FK_utilisateur_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
