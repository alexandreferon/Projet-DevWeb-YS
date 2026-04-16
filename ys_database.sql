-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 16 avr. 2026 à 19:18
-- Version du serveur : 5.7.24
-- Version de PHP : 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ys_database`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

CREATE TABLE `annonces` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `etat` enum('neuf','bon état','correct') NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `date_publication` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `annonces`
--

INSERT INTO `annonces` (`id`, `user_id`, `titre`, `prix`, `etat`, `description`, `image_url`, `date_publication`) VALUES
(3, 15, 'tablette', '249.99', 'correct', 'une belle tablette pas moche', 'uploads/annonces/img_69da343dac051.jpg', '2026-04-11 11:20:31'),
(4, 15, 'poster', '9.99', 'neuf', 'beau & grand', 'uploads/annonces/img_69e0ee44300db.png', '2026-04-16 14:12:20'),
(5, 16, 'truc', '1.00', 'neuf', 'eg', 'uploads/annonces/img_69e114785c828.png', '2026-04-16 16:55:20');

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

CREATE TABLE `favoris` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `annonce_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `favoris`
--

INSERT INTO `favoris` (`id`, `utilisateur_id`, `annonce_id`) VALUES
(36, 15, 5),
(37, 16, 4),
(38, 16, 3);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `id_annonce` int(11) NOT NULL,
  `expediteur_id` int(11) NOT NULL,
  `destinataire_id` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `id_annonce`, `expediteur_id`, `destinataire_id`, `contenu`, `date_envoi`) VALUES
(1, 4, 16, 15, 'salut beau gosse', '2026-04-16 19:15:53'),
(2, 3, 16, 15, 'yo', '2026-04-16 19:16:17');

-- --------------------------------------------------------

--
-- Structure de la table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `nom_role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user_roles`
--

INSERT INTO `user_roles` (`id`, `nom_role`) VALUES
(1, 'membre'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('membre','admin') DEFAULT 'membre',
  `avatar` varchar(255) DEFAULT 'default_avatar.png',
  `role_id` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `password`, `role`, `avatar`, `role_id`) VALUES
(2, 'yass', 'uuuuu@ys.com', '$2y$10$ie0vXhZ0ZxfwdY/l70pBe.km1BQtAIerQVCu4u7EKZ.sr52vXbvKC', 'membre', 'default_avatar.png', 1),
(12, 'yyyy', 'new@ys.com', '$2y$10$gzqhQBNROtOiOlWUmpjfZeqT9hGusFIkzEg6BUbOETgSvfyxad3U2', 'membre', 'default_avatar.png', 1),
(14, 'lolo', 'lolo@ys.com', '$2y$10$P4JSWzdeRUfm/Qgq1P1HPeXnWaTnnyo6SMdCOKoAWdAcACeQF0iDa', 'membre', 'default_avatar.png', 1),
(15, 'Alexandre', 'alexandre.feron@edu.ece.fr', '$2y$10$1Blzx2dBMXHM7Q0PVfD5veNz3cRcw3DCYBa16DcCWWX8aV5BJjcca', 'membre', 'default_avatar.png', 1),
(16, 'homme', 'a@gmail.com', '$2y$10$A.lM.lGFWNjOC0y5DpmRi.DOckfmea.n8NTVbMsIMxpiI5AqFHV1m', 'admin', 'default_avatar.png', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `annonce_id` (`annonce_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expediteur_id` (`expediteur_id`),
  ADD KEY `destinataire_id` (`destinataire_id`),
  ADD KEY `fk_message_annonce` (`id_annonce`);

--
-- Index pour la table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_user_role` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `annonces`
--
ALTER TABLE `annonces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `favoris`
--
ALTER TABLE `favoris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`annonce_id`) REFERENCES `annonces` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_message_annonce` FOREIGN KEY (`id_annonce`) REFERENCES `annonces` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`expediteur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`destinataire_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
