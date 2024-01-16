-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mar. 16 jan. 2024 à 13:44
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ElevageSerpents`
--

-- --------------------------------------------------------

--
-- Structure de la table `Races`
--

CREATE TABLE `Races` (
  `idRace` int(11) NOT NULL,
  `nomRace` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Races`
--

INSERT INTO `Races` (`idRace`, `nomRace`) VALUES
(1, 'Python royal'),
(2, 'Boa constrictor'),
(3, 'Cobra royal'),
(4, 'Serpent des blés'),
(5, 'Serpent ratier'),
(6, 'Serpent de maïs'),
(7, 'Python tapis'),
(8, 'Serpent à sonnette'),
(9, 'Serpent de lait'),
(10, 'Anaconda vert'),
(11, 'Serpent des buissons'),
(12, 'Serpent de mer'),
(13, 'Serpent arc-en-ciel'),
(14, 'Serpent de Garter'),
(15, 'Serpent à nez de cochon'),
(16, 'Serpent du désert'),
(17, 'Serpent venimeux à tête cuivrée'),
(18, 'Serpent taureau'),
(19, 'Serpent de corail'),
(20, 'Serpent des arbres'),
(21, 'Serpent des montagnes rocheuses'),
(22, 'Serpent-lézard'),
(23, 'Serpent volant'),
(25, 'Serpent vert de vigne');

-- --------------------------------------------------------

--
-- Structure de la table `Serpents`
--

CREATE TABLE `Serpents` (
  `idSerpents` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `dureeVie` int(11) NOT NULL,
  `heure_et_date_naissance` datetime NOT NULL,
  `poids` decimal(5,2) NOT NULL,
  `genre` enum('Mâle','Femelle') NOT NULL,
  `idRace` int(11) DEFAULT NULL,
  `idPere` varchar(11) DEFAULT NULL,
  `idMere` varchar(11) DEFAULT NULL,
  `inLoveRoom` tinyint(1) DEFAULT '0',
  `estMort` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Serpents`
--

INSERT INTO `Serpents` (`idSerpents`, `nom`, `dureeVie`, `heure_et_date_naissance`, `poids`, `genre`, `idRace`, `idPere`, `idMere`, `inLoveRoom`, `estMort`) VALUES
(205, 'Cleo', 3, '2024-01-08 03:54:00', '0.50', 'Femelle', 10, NULL, NULL, 0, 1),
(206, 'meu', 3, '2024-01-08 03:54:32', '1.00', 'Mâle', 18, NULL, NULL, 0, 1),
(207, 'Tati', 3, '2024-01-08 03:54:32', '0.30', 'Femelle', 17, NULL, NULL, 0, 1),
(208, 'Elie', 5, '2024-01-08 03:54:32', '1.00', 'Mâle', 17, NULL, NULL, 0, 1),
(209, 'Herly', 4, '2024-01-08 03:54:32', '0.80', 'Femelle', 20, NULL, NULL, 0, 1),
(210, 'Noa', 5, '2024-01-08 04:12:00', '1.00', 'Femelle', 8, NULL, NULL, 0, 1),
(211, 'meu', 5, '2024-01-08 04:12:00', '1.00', 'Mâle', 9, NULL, NULL, 0, 1),
(212, 'Herly', 3, '2024-01-08 04:12:00', '0.80', 'Femelle', 19, NULL, NULL, 0, 1),
(213, 'Slin', 3, '2024-01-12 11:44:00', '6.00', 'Mâle', 1, NULL, NULL, 0, 1),
(214, 'pipip', 2, '2024-01-12 11:44:00', '6.00', 'Femelle', 1, NULL, NULL, 0, 1),
(215, 'Shimmer', 7, '2024-01-12 11:45:36', '5.00', 'Mâle', 1, '213', '214', 0, 1),
(216, 'Flicker', 10, '2024-01-12 11:49:52', '3.00', 'Mâle', 1, '213', '214', 0, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Races`
--
ALTER TABLE `Races`
  ADD PRIMARY KEY (`idRace`);

--
-- Index pour la table `Serpents`
--
ALTER TABLE `Serpents`
  ADD PRIMARY KEY (`idSerpents`),
  ADD KEY `Serpents_fk` (`idRace`) USING BTREE,
  ADD KEY `idRace` (`idRace`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Races`
--
ALTER TABLE `Races`
  MODIFY `idRace` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `Serpents`
--
ALTER TABLE `Serpents`
  MODIFY `idSerpents` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Serpents`
--
ALTER TABLE `Serpents`
  ADD CONSTRAINT `Serpents_fk` FOREIGN KEY (`idRace`) REFERENCES `Races` (`idRace`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
