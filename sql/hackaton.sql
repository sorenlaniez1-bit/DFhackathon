-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 01 fév. 2026 à 12:35
-- Version du serveur : 12.1.2-MariaDB
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `hackaton`
--

-- --------------------------------------------------------

--
-- Structure de la table `achats`
--

DROP TABLE IF EXISTS `achats`;
CREATE TABLE IF NOT EXISTS `achats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `id_article` int(11) NOT NULL,
  `date_achat` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_article` (`id_article`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `achats`
--

INSERT INTO `achats` (`id`, `id_utilisateur`, `id_article`, `date_achat`) VALUES
(1, 3, 2, '2026-02-01 09:29:05');

-- --------------------------------------------------------

--
-- Structure de la table `boutique`
--

DROP TABLE IF EXISTS `boutique`;
CREATE TABLE IF NOT EXISTS `boutique` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix_points` int(11) NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `actif` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `boutique`
--

INSERT INTO `boutique` (`id`, `nom`, `description`, `prix_points`, `stock`, `actif`) VALUES
(1, 'Place de cinéma', 'Une place de cinéma valable dans tous les cinémas partenaires', 100, 20, 1),
(2, 'Bon de réduction 5€', 'Bon de réduction de 5€ valable en magasin', 50, 49, 1),
(3, 'Bon de réduction 10€', 'Bon de réduction de 10€ valable en magasin', 100, 30, 1),
(4, 'Bon de réduction 20€', 'Bon de réduction de 20€ valable en magasin', 200, 20, 1);

-- --------------------------------------------------------

--
-- Structure de la table `demandes`
--

DROP TABLE IF EXISTS `demandes`;
CREATE TABLE IF NOT EXISTS `demandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_demandeur` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `lieu` varchar(255) NOT NULL,
  `duree` varchar(100) NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `date_limite` datetime NOT NULL,
  `statut` enum('ouverte','prise_en_charge','terminee','depassee') DEFAULT 'ouverte',
  `id_benevole` int(11) DEFAULT NULL,
  `commentaire_benevole` text DEFAULT NULL,
  `points_attribues` int(11) DEFAULT 0,
  `date_prise_en_charge` datetime DEFAULT NULL,
  `date_cloture` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_demandeur` (`id_demandeur`),
  KEY `id_benevole` (`id_benevole`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `demandes`
--

INSERT INTO `demandes` (`id`, `id_demandeur`, `titre`, `description`, `ville`, `lieu`, `duree`, `date_creation`, `date_limite`, `statut`, `id_benevole`, `commentaire_benevole`, `points_attribues`, `date_prise_en_charge`, `date_cloture`) VALUES
(3, 3, 'Aide aux courses', 'aidez moi pour les courses', 'Paris', 'Carrefour market', '30 minutes', '2026-02-01 03:22:57', '2026-02-04 15:15:00', 'terminee', 2, NULL, 10, '2026-02-01 03:43:06', '2026-02-01 03:43:12'),
(4, 1, 'Aide pour déménagement', 'J\'ai besoin d\'aide pour déplacer des cartons du 2ème au rez-de-chaussée', 'Paris', 'Paris 15ème', '2', '2026-02-01 04:30:25', '2026-02-15 00:00:00', 'terminee', 3, NULL, 20, '2026-02-01 06:22:27', '2026-02-01 06:22:38'),
(5, 1, 'Courses pour personne âgée', 'Faire les courses hebdomadaires et les livrer', 'Paris', 'Paris 15ème', '1', '2026-02-01 04:30:25', '2026-02-10 00:00:00', 'terminee', 3, NULL, 10, '2026-02-01 08:15:20', '2026-02-01 08:15:33'),
(6, 2, 'Soutien scolaire mathématiques', 'Aide aux devoirs pour un collégien niveau 4ème', 'Paris', 'Lyon 3ème', '1.5', '2026-02-01 04:30:25', '2026-02-20 00:00:00', 'ouverte', NULL, NULL, 15, NULL, NULL),
(7, 2, 'Réparation vélo', 'Besoin d\'aide pour réparer une crevaison et ajuster les freins', 'Paris', 'Lyon 3ème', '0.5', '2026-02-01 04:30:25', '2026-02-08 00:00:00', 'ouverte', NULL, NULL, 10, NULL, NULL),
(8, 3, 'Garde d\'enfants', 'Cherche quelqu\'un pour garder 2 enfants (5 et 8 ans) pendant la soirée', 'Paris', 'Marseille 8ème', '3', '2026-02-01 04:30:25', '2026-02-12 00:00:00', 'ouverte', NULL, NULL, 30, NULL, NULL),
(9, 3, 'Promenade de chien', 'Promener mon labrador pendant que je suis au travail', 'Paris', 'Marseille 8ème', '1', '2026-02-01 04:30:25', '2026-02-09 00:00:00', 'ouverte', NULL, NULL, 10, NULL, NULL),
(10, 4, 'Aide informatique', 'Installation et configuration d\'un nouvel ordinateur', 'Paris', 'Toulouse Centre', '2', '2026-02-01 04:30:25', '2026-02-18 00:00:00', 'ouverte', NULL, NULL, 20, NULL, NULL),
(11, 4, 'Jardinage', 'Taille des haies et ramassage des feuilles dans le jardin', 'Paris', 'Toulouse Centre', '2.5', '2026-02-01 04:30:25', '2026-02-25 00:00:00', 'ouverte', NULL, NULL, 25, NULL, NULL),
(12, 5, 'Préparation repas', 'Aide pour préparer un repas de famille pour 10 personnes', 'Paris', 'Nantes Nord', '3', '2026-02-01 04:30:25', '2026-02-14 00:00:00', 'ouverte', NULL, NULL, 30, NULL, NULL),
(13, 5, 'Montage meuble IKEA', 'Monter une armoire et un bureau', 'Paris', 'Nantes Nord', '1.5', '2026-02-01 04:30:25', '2026-02-11 00:00:00', 'ouverte', NULL, NULL, 15, NULL, NULL),
(14, 18, 'Aide aux courses', 'aidez moi pour les courses', 'Paris', 'Carrefour market', '30 minutes', '2026-02-01 09:40:52', '2026-02-09 11:45:00', 'terminee', 3, NULL, 300, '2026-02-01 10:01:05', '2026-02-01 10:01:12'),
(15, 19, 'Jeu de tarot', 'Jouez avec moi au tarot LOL', 'Paris', '35 rue des pierres', '3', '2026-02-01 10:06:53', '2026-02-03 10:06:00', 'terminee', 3, NULL, 20, '2026-02-01 10:10:27', '2026-02-01 10:11:12'),
(16, 19, 'TAROT 2 !!!', 'j\'ai encore envie :(', 'Paris', '35 rue des pierres', '3', '2026-02-01 10:12:22', '2026-02-01 13:12:00', 'prise_en_charge', 3, NULL, 30, '2026-02-01 10:13:34', NULL),
(17, 19, 'balade ensemle', 'on se balade', 'dunkerque', '15 rue répu', '1.5', '2026-02-01 11:10:46', '2026-02-04 11:10:00', 'prise_en_charge', 3, NULL, 15, '2026-02-01 11:11:46', NULL),
(18, 19, 'Promenade animaux', 'promenez mon chien', 'Dunkerque', 'au parc st pierre', '0.5', '2026-02-01 11:24:41', '2026-02-07 11:24:00', 'ouverte', NULL, NULL, 10, NULL, NULL),
(19, 21, 'tarot 3', 'encore un jeu de tarot', 'Dunkerque', '30 rue buisson', '2', '2026-02-01 11:30:07', '2026-02-04 13:30:00', 'terminee', 19, NULL, 20, '2026-02-01 11:31:14', '2026-02-01 11:31:55'),
(20, 3, 'soirée jeux vidéos', 'venez jouer', 'Dunkerque', '15 rue Albert', '3', '2026-02-01 12:59:33', '2026-02-07 20:00:00', 'terminee', 19, 'très très agréable', 30, '2026-02-01 12:59:55', '2026-02-01 12:59:59');

-- --------------------------------------------------------

--
-- Structure de la table `mises_en_relation`
--

DROP TABLE IF EXISTS `mises_en_relation`;
CREATE TABLE IF NOT EXISTS `mises_en_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_demande` int(11) NOT NULL,
  `id_benevole` int(11) NOT NULL,
  `date_proposition` datetime DEFAULT current_timestamp(),
  `statut` enum('propose','accepte','refuse') DEFAULT 'propose',
  PRIMARY KEY (`id`),
  KEY `id_demande` (`id_demande`),
  KEY `id_benevole` (`id_benevole`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `mises_en_relation`
--

INSERT INTO `mises_en_relation` (`id`, `id_demande`, `id_benevole`, `date_proposition`, `statut`) VALUES
(1, 3, 2, '2026-02-01 03:32:25', 'accepte'),
(2, 7, 3, '2026-02-01 05:57:18', 'propose'),
(3, 4, 3, '2026-02-01 06:21:40', 'accepte'),
(4, 5, 3, '2026-02-01 07:47:06', 'accepte'),
(5, 7, 18, '2026-02-01 09:44:30', 'propose'),
(6, 14, 3, '2026-02-01 10:00:16', 'accepte'),
(7, 15, 3, '2026-02-01 10:09:08', 'accepte'),
(8, 16, 3, '2026-02-01 10:12:42', 'accepte'),
(9, 17, 3, '2026-02-01 11:11:29', 'accepte'),
(10, 18, 3, '2026-02-01 11:24:59', 'propose'),
(11, 19, 19, '2026-02-01 11:30:36', 'accepte'),
(12, 20, 19, '2026-02-01 12:59:42', 'accepte');

-- --------------------------------------------------------

--
-- Structure de la table `points`
--

DROP TABLE IF EXISTS `points`;
CREATE TABLE IF NOT EXISTS `points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `points` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `points`
--

INSERT INTO `points` (`id`, `id_utilisateur`, `points`) VALUES
(1, 2, 10),
(2, 3, 300),
(6, 19, 50);

-- --------------------------------------------------------

--
-- Structure de la table `transactions_points`
--

DROP TABLE IF EXISTS `transactions_points`;
CREATE TABLE IF NOT EXISTS `transactions_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `type` enum('gain','depense') NOT NULL,
  `montant` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `id_demande` int(11) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_demande` (`id_demande`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `transactions_points`
--

INSERT INTO `transactions_points` (`id`, `id_utilisateur`, `type`, `montant`, `description`, `id_demande`, `date`) VALUES
(1, 2, 'gain', 10, 'Mission terminée : Aide aux courses', 3, '2026-02-01 03:43:12'),
(2, 3, 'gain', 20, 'Mission terminée : Aide pour déménagement', 4, '2026-02-01 06:22:38'),
(3, 3, 'gain', 10, 'Mission terminée : Courses pour personne âgée', 5, '2026-02-01 08:15:33'),
(4, 3, 'depense', 50, 'Achat : Bon de réduction 5€', NULL, '2026-02-01 09:29:05'),
(5, 3, 'gain', 300, 'Mission terminée : Aide aux courses', 14, '2026-02-01 10:01:12'),
(6, 3, 'gain', 20, 'Mission terminée : Jeu de tarot', 15, '2026-02-01 10:11:12'),
(7, 19, 'gain', 20, 'Mission terminée : tarot 3', 19, '2026-02-01 11:31:55'),
(8, 19, 'gain', 30, 'Mission terminée : soirée jeux vidéos', 20, '2026-02-01 13:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `roles` set('benevole','demandeur') NOT NULL DEFAULT 'demandeur',
  `date_creation` datetime DEFAULT current_timestamp(),
  `date_modification` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `username`, `password`, `nom`, `prenom`, `age`, `ville`, `bio`, `roles`, `date_creation`, `date_modification`) VALUES
(1, 'marie_d', 'f0fd596f396d8fc32d5e4fe4c73c61fa2ac55c70', 'Dupont', 'Marie', 65, 'Paris', 'Passionnée par la musique et le théâtre. J\'aime aider les personnes âgées et partager des moments conviviaux. Disponible en semaine après 17h.', 'demandeur', '2026-02-01 02:50:10', '2026-02-01 13:07:38'),
(2, 'jean_b', '51f8b1fa9b424745378826727452997ee2a7c3d7', 'Martin', 'Jean', 28, 'Lyon', 'Ancien enseignant à la retraite. Je propose mon aide pour du soutien scolaire et de l\'accompagnement administratif. Grand amateur de jardinage et de lecture.', 'benevole', '2026-02-01 02:50:10', '2026-02-01 13:07:38'),
(3, 'sophie_l', '5f50443bfe76f7279a8e0f2f0a98975cdbff38e9', 'Legrand', 'Sophie', 35, 'Marseille', 'Étudiante en médecine passionnée par l\'entraide. J\'aime faire du sport, cuisiner et découvrir de nouvelles cultures. Toujours partante pour aider !', 'benevole,demandeur', '2026-02-01 02:50:10', '2026-02-01 13:07:38'),
(4, 'pierre_r', 'ff019a5748a52b5641624af88a54a2f0e46a9fb5', 'Roux', 'Pierre', 72, 'Toulouse', 'Retraité actif aimant rendre service. Bricolage, courses, petits travaux... Je suis là pour aider mon quartier. Fan de football et de pétanque.', 'demandeur', '2026-02-01 02:50:10', '2026-02-01 13:07:38'),
(5, 'julie_p', '8d32267b6b4884cf35adeaccde2b6857ae11aace', 'Petit', 'Julie', 24, 'Bordeaux', 'Jeune maman dynamique. J\'adore cuisiner, faire du shopping et organiser des événements. Besoin d\'aide occasionnellement pour la garde d\'enfants.', 'benevole', '2026-02-01 02:50:10', '2026-02-01 13:07:38'),
(6, 'andre_m', 'bc9800b9d52a24cce72a73dd528afed53f10e5fc', 'Moreau', 'André', 68, 'Nice', 'Développeur web passionné par la technologie. Je propose mon aide pour de l\'informatique et du dépannage numérique. Amateur de jeux vidéo et de cinéma.', 'demandeur', '2026-02-01 02:50:10', '2026-02-01 13:07:38'),
(7, 'laura_b', '94745df4bd94de756ea5436584fec066fc7898d5', 'Bernard', 'Laura', 30, 'Nantes', 'Infirmière dévouée adorant aider les autres. Passions: yoga, méditation et nature. Disponible le week-end pour des missions d\'accompagnement.', 'benevole,demandeur', '2026-02-01 02:50:10', '2026-02-01 13:07:38'),
(8, 'robert_d', '12e9293ec6b30c7fa8a0926af42807e929c1684f', 'Durand', 'Robert', 70, 'Strasbourg', 'Artisan à la retraite. Expert en bricolage, électricité et plomberie. J\'aime transmettre mon savoir-faire et rendre service à la communauté.', 'demandeur', '2026-02-01 02:50:10', '2026-02-01 13:07:38'),
(9, 'camille_g', 'b920592808acec58c9833234ce6265ad888f29a6', 'Girard', 'Camille', 26, 'Lille', 'Jeune femme dynamique aimant le contact humain. Passions: danse, photographie et voyages. Toujours prête à donner un coup de main !', 'benevole', '2026-02-01 02:50:10', '2026-02-01 13:07:38'),
(10, 'francoise_t', '4c410502d98af3124c8c11f601b5a964ad36a437', 'Thomas', 'Françoise', 63, 'Rennes', 'Professeur de français à la retraite. J\'aime la lecture, l\'écriture et les sorties culturelles. Je propose du soutien scolaire et de l\'aide administrative.', 'demandeur', '2026-02-01 02:50:10', '2026-02-01 13:07:38'),
(11, 'kofkofkeo', '$2y$10$4EA.aKJgTX.PChj4xZaE6.wWGqfvfHNG7n50NGsVujQRlxD/cIdu6', 'Hajj', 'Moussa', 89, 'root', 'Entrepreneur passionné par l\'innovation sociale. J\'aime créer des liens et faciliter l\'entraide. Amateur de cuisine du monde et de musique.', 'demandeur', '2026-02-01 03:53:51', '2026-02-01 13:07:38'),
(12, 'test', '$2y$10$gGXNgKb7fWm4Su0.Zdw1KejbfGdcFc7GNRuWl8KG6PtM6tsQXiKWK', 'test', 'test', 123, 'lens', 'Étudiant en informatique. Disponible pour du dépannage tech, des courses ou de l\'aide aux devoirs. Passions: gaming, programmation et sciences.', 'demandeur', '2026-02-01 04:20:55', '2026-02-01 13:07:38'),
(13, 'testeu', '$2y$10$LA2sl42ADfgRRUG1JZgIhOVJkAP2jQu6ttj0RZUJP7kDfvMHYbfqq', 'test', 'test', 12, 'test', 'Jeune professionnel curieux. J\'aime découvrir de nouvelles choses et aider mon prochain. Passions: sport, lecture et bénévolat associatif.', 'demandeur', '2026-02-01 04:31:34', '2026-02-01 13:07:38'),
(18, 'jp_d', '7a9a5ab32492d0e3b0fef57765b979d351b7e72b', 'Dumas', 'Jean-Pierre', 78, 'Dunkerque', 'Ouvrier passionné par le travail manuel. Bricolage, déménagement, petits travaux... je suis polyvalent. Amateur de mécanique et de pêche.', 'benevole,demandeur', '2026-02-01 09:39:34', '2026-02-01 13:07:38'),
(19, 'Marie_b', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Blachère', 'Marie', 85, 'Grande-Synthe', 'Passionnée d\'art et de culture. J\'aime organiser des sorties, accompagner les personnes et partager des moments enrichissants. Grande lectrice et amatrice de cinéma.', 'benevole,demandeur', '2026-02-01 10:03:35', '2026-02-01 13:07:38'),
(21, 'charles_p', 'cbdb0cc7f3f5b4be81a75fa7242590e3e9882e1e', 'Pierre', 'Charles', 80, 'Dunkerque', 'Ingénieur retraité à Dunkerque. Passionné de bricolage, jardinage et aide au déménagement. J\'aime être utile et partager mon expérience avec les jeunes.', 'benevole,demandeur', '2026-02-01 11:27:27', '2026-02-01 13:07:38');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achats`
--
ALTER TABLE `achats`
  ADD CONSTRAINT `1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `2` FOREIGN KEY (`id_article`) REFERENCES `boutique` (`id`);

--
-- Contraintes pour la table `demandes`
--
ALTER TABLE `demandes`
  ADD CONSTRAINT `1` FOREIGN KEY (`id_demandeur`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `2` FOREIGN KEY (`id_benevole`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `mises_en_relation`
--
ALTER TABLE `mises_en_relation`
  ADD CONSTRAINT `1` FOREIGN KEY (`id_demande`) REFERENCES `demandes` (`id`),
  ADD CONSTRAINT `2` FOREIGN KEY (`id_benevole`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `points`
--
ALTER TABLE `points`
  ADD CONSTRAINT `1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `transactions_points`
--
ALTER TABLE `transactions_points`
  ADD CONSTRAINT `1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `2` FOREIGN KEY (`id_demande`) REFERENCES `demandes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
