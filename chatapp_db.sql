-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 22 août 2025 à 15:31
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
-- Base de données : `chatapp_db`
--
CREATE DATABASE IF NOT EXISTS `chatapp_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `chatapp_db`;

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `produit` varchar(255) NOT NULL,
  `quantite` int(11) NOT NULL,
  `unite` varchar(50) NOT NULL DEFAULT 'litres',
  `nom_client` varchar(255) NOT NULL,
  `lieu_livraison` varchar(255) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` varchar(50) DEFAULT 'en attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `user_id`, `produit`, `quantite`, `unite`, `nom_client`, `lieu_livraison`, `telephone`, `email`, `date_commande`, `statut`) VALUES
(4, 10004, 'litres de 15W40', 10, 'unité', 'exit', 'exit', 'exit', 'exit', '2025-08-22 10:37:25', 'Nouveau');

-- --------------------------------------------------------

--
-- Structure de la table `commandes_temp`
--

CREATE TABLE `commandes_temp` (
  `user_id` int(11) NOT NULL,
  `produit` varchar(255) NOT NULL,
  `quantite` int(11) NOT NULL,
  `etape` varchar(50) NOT NULL,
  `valeur` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `user_id`, `message`) VALUES
(2, 10004, 'cool j\'adore'),
(3, 10004, 'cool j\'adore'),
(4, 10004, 'cool j\'adore'),
(5, 10004, 'j\'adore');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `outgoing_msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(11) NOT NULL,
  `msg` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`msg_id`, `outgoing_msg_id`, `incoming_msg_id`, `msg`) VALUES
(137, 10004, 9999, 'hey'),
(138, 9999, 10004, 'Je n\'ai pas compris votre demande. Essayez de me dire ce que vous voulez (par exemple, commander un produit ou définir un rappel), et je vous guiderai avec les bonnes formulations.'),
(139, 10004, 9999, 'qui à crée green engineering?'),
(140, 9999, 10004, 'Je n\'ai pas compris votre demande. Essayez de me dire ce que vous voulez (par exemple, commander un produit ou définir un rappel), et je vous guiderai avec les bonnes formulations.'),
(141, 10004, 9999, 'commande'),
(142, 9999, 10004, 'Il semble que vous voulez passer une commande. Voici comment faire :\n- \'Je veux 10 litres de 15W40\'\n- \'Commande de 5 bidons d\'huile hydraulique\'\n- \'J\'aimerais 2 cartons de SAE40\'\nFaites-moi savoir ce que vous voulez commander, et je vous guiderai pour les détails !'),
(143, 10004, 9999, 'je veux 10 litres de 15W40'),
(144, 9999, 10004, '📝 Pour finaliser la commande de 10 litres de 15W40, merci de fournir les informations suivantes.\n🔹 Nom du client :'),
(145, 10004, 9999, 'exit'),
(146, 9999, 10004, '📍 Merci. Quel est le lieu de livraison ?'),
(147, 10004, 9999, 'exit'),
(148, 9999, 10004, '📞 Très bien. Quel est votre numéro de téléphone ?'),
(149, 10004, 9999, 'exit'),
(150, 9999, 10004, '📧 Parfait. Enfin, indiquez-moi votre adresse email :'),
(151, 10004, 9999, 'exit'),
(152, 9999, 10004, '✅ Merci ! Votre commande est enregistrée. Un conseiller vous contactera bientôt.'),
(153, 10004, 9999, 'ok'),
(154, 9999, 10004, 'Je n\'ai pas compris votre demande. Essayez de me dire ce que vous voulez (par exemple, commander un produit ou définir un rappel), et je vous guiderai avec les bonnes formulations.'),
(155, 10004, 9999, 'bonjour'),
(156, 9999, 10004, 'Bonjour, je suis Green Chat, l\'assistant virtuel de Green Engineering SARL. \n\nGreen Engineering est une entreprise locale camerounaise spécialisée dans les solutions techniques industrielles et environnementales. Nous offrons un service professionnel avec un engagement ferme pour l\'innovation et l\'autonomie technologique. \n\nNotre équipe est à votre disposition pour toute demande.'),
(157, 10004, 9999, 'quels sont vos produits?'),
(158, 9999, 10004, 'Les produits proposés par Green Engineering sont divers. Nous nous spécialisons dans le reconditionnement des huiles moteurs usagées, mais nous avons aussi une gamme de produits finis de haute qualité. Ceux-ci comprennent :\n\n1. Huiles moteurs :\n    - 15w-40\n    - SAE 40\n    - 20w-50\n\n2. Huiles hydrauliques :\n    - DEX III\n\n3. Graisses :\n    - Mécaniques\n    - Pour moteurs\n\n4. Scellés de gaz \n\nCes produits sont conçus pour répondre aux besoins des professionnels et particuliers dans le domaine de l\'automobile et de l\'industrie légère. Les huiles moteurs sont adaptées à la plupart des climats chauds et couvrent la plupart des véhicules essence ou diesel modernes. \n\nLes prix varient selon la quantité commandée et la localisation de la livraison. Pour un devis précis, veuillez nous fournir ces informations ainsi que votre localisation.\n\n💡 Conseil : Il semble que vous voulez passer une commande. Vous pouvez essayer :\n- \'Je veux 10 litres de 15W40\'\n- \'Commande de 5 bidons d\'huile hydraulique\'\n- \'J\'aimerais 2 cartons de SAE40\'\nJe vous guiderai ensuite !'),
(159, 10004, 9999, 'bon comment passer une commande?'),
(160, 9999, 10004, 'Pour passer commande, veuillez nous fournir les informations suivantes :\n\n- La quantité et le type de produit que vous souhaitez commander (par exemple : 10 litres de 15w40, un bidon d\'huile hydraulique, etc.)\n\nUne fois que nous aurons ces informations, nous aurons besoin de vos coordonnées afin d\'enregistrer votre commande :\n\n- Votre nom\n- L\'adresse de livraison\n- Votre numéro de téléphone\n- Votre adresse e-mail\n\nEn fournissant ces détails, votre commande sera enregistrée et traitée. Si vous avez une négociation de prix ou une demande spéciale, un de nos agents commerciaux vous contactera après avoir traité votre commande.\n\nVous pouvez nous fournir ces informations par le biais d\'un formulaire en ligne, par téléphone ou en nous envoyant un e-mail. Notre équipe est à votre disposition pour toute question supplémentaire\n\n💡 Conseil : Il semble que vous voulez passer une commande. Vous pouvez essayer :\n- \'Je veux 10 litres de 15W40\'\n- \'Commande de 5 bidons d\'huile hydraulique\'\n- \'J\'aimerais 2 cartons de SAE40\'\nJe vous guiderai ensuite !'),
(161, 10004, 9999, 'ok merci'),
(162, 9999, 10004, 'Merci à vous ! Au plaisir de vous rendre d\'autres services. L\'équipe de Green Engineering SARL est à votre disposition.'),
(163, 10004, 9999, 'bonjour'),
(164, 9999, 10004, '**Réponse:**\n Bonjour, nous sommes Green Engineering SARL. Nous sommes une entreprise spécialisée dans les solutions techniques locales pour l\'industrie et l\'environnement. \n\nNous recommandons la **15w\n- 40** pour la plupart des moteurs **essence ou diesel modernes** dans des climats **chauds**.\n- \n- \n- **Conseils:**\n- Si vous souhaitez des recommandations pour d\'autres huiles, nous avons une gamme complète de produits dans notre catalogue.\n- N\'hésitez pas à consulter notre guide de choix d\'huile pour trouver le produit le mieux adapté à vos besoins.'),
(165, 10004, 9999, 'bon comment passer une commande?'),
(166, 9999, 10004, 'Réponse: Pour passer une commande, vous devez tout d\'abord nous fournir quelques informations sur le produit que vous souhaitez commander. Dans votre cas, vous avez besoin de :\n- 10 litres d\'huile moteur 15w40\n- Un bidon d\'huile hydraulique\n- 5 sacs de ciment\n\nUne fois que vous avez défini les produits, nous aurons besoin des informations suivantes pour traiter votre commande :\n- Votre nom\n- L\'endroit de livraison\n- Votre numéro de téléphone\n- Votre adresse e\n- mail\n\nAprès avoir fourni ces informations, votre commande sera enregistrée et traitée. Si vous avez des questions spécifiques ou si vous souhaitez négocier les prix, un de nos agents commerciaux sera en contact avec vous après l\'enregistrement de votre commande.\n\nConseils:\n- Assurez\n- vous d\'avoir un descriptif clair des produits à commander, cela facilitera le processus\n\n**Conseils:**\n- Utilisez **\'Je veux 10 litres de 15W40\'**\n- Ou **\'Commande de 5 bidons d\'huile hydraulique\'**\n- Ou **\'J\'aimerais 2 cartons de SAE40\'**\nJe vous guiderai ensuite !'),
(167, 10004, 9999, 'hey'),
(168, 9999, 10004, 'Bonjour, bienvenue sur le chat de Green Engineering. \n\nNotre entreprise a pour mission de proposer des solutions techniques locales innovantes dans les domaines industriels et environnementaux, avec un focus particulier sur l\'autonomie technologique. \n\nNous sommes à votre disposition pour répondre à vos questions. Avez-vous une demande précise concernant nos produits ou services ?'),
(169, 10004, 9999, 'bonjour'),
(170, 9999, 10004, 'Bonjour, je suis Green Chat, l\'assistant virtuel de Green Engineering SARL. \n\nGreen Engineering est une entreprise spécialisée dans la proposition de solutions techniques dans les domaines industriels et environnementaux. Elle a été fondée en 2021 par M. Mbakam Guillaume Chouga.\n\nVoulez-vous en savoir plus sur les activités de Green Engineering SARL?'),
(171, 10004, 9999, 'quels sont vos produit?'),
(172, 9999, 10004, 'Nos produits sont principalement des lubrifiants et des produits d\'entretien pour automobiles. Plus spécifiquement, nous produisons et vendons :\n\n- Huiles moteurs de différentes viscosités : 15w40, SAE40 et 20w50\n- Huiles hydrauliques, notamment la DEX III\n- Graisses mécaniques et pour moteurs\n- Scellés de gaz\n\nNous offrons également des services de reconditionnement d\'huiles moteurs usagées et de fabrication de scellés pour bouteilles de gaz.\n\n**Conseils:**\n- Utilisez **\'Je veux 10 litres de 15W40\'**\n- Ou **\'Commande de 5 bidons d\'huile hydraulique\'**\n- Ou **\'J\'aimerais 2 cartons de SAE40\'**\nJe vous guiderai ensuite !'),
(173, 10004, 9999, 'quels sont vos produit?'),
(174, 9999, 10004, 'Nos produits sont divers et variés dans le domaine des lubrifiants et des produits d\'entretien automobile. Nous produisons et vendons notamment :\n\n- Huiles moteurs de différentes viscosités : 15w40, SAE40 et 20w50.\n- Huiles hydrauliques, notamment la DEX III.\n- Graisses à usage mécanique et pour moteurs.\n- Scellés de gaz.\n\nNous offrons également des services de reconditionnement d\'huiles moteurs usagées et de fabrication de scellés pour bouteilles de gaz.\n\n**Conseils:**\n- Utilisez **\'Je veux 10 litres de 15W40\'**\n- Ou **\'Commande de 5 bidons d\'huile hydraulique\'**\n- Ou **\'J\'aimerais 2 cartons de SAE40\'**\nJe vous guiderai ensuite !'),
(175, 10004, 9999, 'comment commander?'),
(176, 9999, 10004, 'Pour passer commande, veuillez nous préciser le produit souhaité ainsi que la quantité désirée par message dans le chat. Notre équipe s\'occupera de votre demande et vous donnera plus d\'informations sur les prochaines étapes à suivre. Merci de choisir Green Engineering SARL.'),
(177, 10004, 9999, 'comment commander?'),
(178, 9999, 10004, 'Pour passer commande, veuillez nous préciser le produit désiré ainsi que la quantité souhaitée par la saisie de ces informations dans le chat. Notre équipe traitera votre demande et vous fournira davantage d\'informations sur les étapes suivantes.'),
(179, 10004, 9999, 'je voudrais commander'),
(180, 9999, 10004, 'Bienvenue dans le processus de commande de Green Engineering SARL. \n\nVeuillez me fournir les informations suivantes afin que nous puissions traiter votre demande :\n\n- Quel produit souhaitez-vous commander ? (Veuillez préciser le nom du produit et la quantité désirée).\n\nUne fois que j\'aurai ces informations, je vous demanderai votre nom, le lieu de livraison ainsi que vos coordonnées pour finaliser la commande.'),
(181, 10004, 9999, 'quels sont vos produits?'),
(182, 9999, 10004, 'Les produits proposés par Green Engineering sont divers. Nous nous spécialisons dans le reconditionnement des huiles moteurs usagées, mais nous fabriquons également des scellés pour bouteilles de gaz et nous développons des produits d\'entretien automobile.  \n\nNotre gamme de produits comprend:  \n\n- Huiles moteurs de différentes viscosités: 15w-40, SAE 40 et 20w-50  \n- Huiles hydrauliques, notamment le Dex III  \n- Graisses mécaniques et pour moteurs  \n- Scellés de gaz  \n\nNous recommandons à nos clients de choisir l\'huile adaptée à leur moteur selon les recommandations de notre guide, notamment en fonction du climat et de l\'utilisation. Notre personnel est également à la disposition des clients pour les conseiller sur le choix le plus approprié.  \n\nLes prix de ces produits varient selon la quantité commandée et la localisation de la livraison.\n\n💡 Conseil : Pour passer une commande, Vous pouvez essayer :\n- \'Je veux 10 litres de 15W40\'\n- \'Commande de 5 bidons d\'huile hydraulique\'\n- \'J\'aimerais 2 cartons de SAE40\'\nJe vous guiderai ensuite !'),
(183, 10004, 9999, 'bonjour'),
(184, 9999, 10004, 'Bonjour, je suis Green Chat, l\'assistant virtuel de Green Engineering SARL. \n\nGreen Engineering est une entreprise locale camerounaise spécialisée dans les solutions techniques industrielles et environnementales. Nous offrons un service professionnel avec un engagement pour l\'innovation et l\'autonomie technologique. \n\nNotre équipe est à votre disposition pour toute demande.'),
(185, 10004, 9999, 'quels sont vos produits?'),
(186, 9999, 10004, 'Nos produits sont principalement des lubrifiants et des produits d\'entretien pour automobiles. Plus spécifiquement, nous produisons et vendons diverses huiles moteurs, telles que 15w-40, SAE 40 et 20w-50, adaptées aux climats chauds. Nous fabriquons également des huiles hydrauliques, notamment le modèle Dex III, ainsi que des graisses mécaniques et pour moteurs. Enfin, nous offrons des scellés de gaz.\n\n**Conseils:**\n- Utilisez **\'Je veux 10 litres de 15W40\'**\n- Ou **\'Commande de 5 bidons d\'huile hydraulique\'**\n- Ou **\'J\'aimerais 2 cartons de SAE40\'**\nJe vous guiderai ensuite !'),
(187, 10004, 9999, 'quels sont vos produits?'),
(188, 9999, 10004, 'Nos produits sont principalement des lubrifiants et des produits d\'entretien pour automobiles. Plus spécifiquement, nous produisons et vendons diverses huiles moteurs, telles que 15w-40, SAE 40 et 20w-50, adaptées aux climats chauds. Nous fabriquons également des huiles hydrauliques, notamment le modèle DexIII, ainsi que des graisses mécaniques et pour moteurs. Enfin, nous offrons des scellés de gaz.\n\n**Conseils:**\n- Utilisez **\'Je veux 10 litres de 15W40\'**\n- Ou **\'Commande de 5 bidons d\'huile hydraulique\'**\n- Ou **\'J\'aimerais 2 cartons de SAE40\'**\nJe vous guiderai ensuite !'),
(189, 10004, 9999, 'ok merci'),
(190, 9999, 10004, 'Merci à vous ! Au plaisir de vous rendre d\'autres services. L\'équipe de Green Engineering SARL est à votre disposition.'),
(191, 10004, 9999, 'bonjour'),
(192, 9999, 10004, 'Structure la réponse suivante en Markdown pour un chat, en fr. Utilise : \'Mots-clés en gras**. Tirets pour les listes. Retours à la ligne pour lisibilité. Section **Réponse principale** au début.'),
(193, 10004, 9999, 'quels sont vos produits?'),
(194, 9999, 10004, 'Green Engineering SARL offre une variété de produits liés à l\'entretien automobile et aux lubrifiants industriels. Ces produits sont conçus pour répondre à divers besoins en lubrification et entretien des véhicules.'),
(195, 10004, 9999, 'salut'),
(196, 9999, 10004, 'Bonjour, je suis Green Chat, l\'assistant virtuel de Green Engineering SARL. \n\nGreen Engineering est une entreprise spécialisée dans le reconditionnement des huiles moteurs usagées et la fabrication de scellés pour bouteilles de gaz, basée à Yaoundé. amongo. Nous proposons également des lubrifiants industriels et des produits d\'entretien automobile. \n\nNotre équipe est à votre disposition pour toute demande d\'information sur nos activités et nos produits.'),
(197, 10004, 9999, 'quels sont vos produits?'),
(198, 9999, 10004, 'Les produits proposés par Green Engineering SARL sont divers. Nous nous spécialisons dans le reconditionnement des huiles moteurs usagées, mais nous fabriquons également des scellés pour bouteilles de gaz et nous développons une gamme de produits d\'entretien automobile.  \n\nNotre gamme de produits comprend :\n\n- Huiles moteurs de différentes viscosités : 15w-40, SAE 40 et 20w-50.\n- Huiles hydrauliques, notamment le Dex III.\n- Graisses à usage mécanique et pour moteurs.\n- Scellés de gaz.\n\nNous recommandons à nos clients de choisir l\'huile 15w-40 pour la plupart des moteurs essence ou diesel modernes, particulièrement dans les climats chauds. Pour un conseil plus précis, n\'hésitez pas à nous contacter et nous pourrons discuter des besoins spécifiques de votre véhicule ou de votre moteur. \n\nNos prix sont compét\n\n**Conseils:**\n- Utilisez **\'Je veux 10 litres de 15W40\'**\n- Ou **\'Commande de 5 bidons d\'huile hydraulique\'**\n- Ou **\'J\'aimerais 2 cartons de SAE40\'**\nJe vous guiderai ensuite !'),
(199, 10004, 9999, 'comment commander?'),
(200, 9999, 10004, 'Pour passer commande, veuillez nous préciser le produit que vous désirez acquérir ainsi que la quantité souhaitée. Vous pouvez nous en faire part par message en indiquant les références du produit. Nous vous répondrons alors rapidement avec les informations nécessaires concernant la procédure à suivre pour l\'achat. \n\nNous vous remercions de l\'intérêt que vous portez à Green Engineering SARL.'),
(201, 10004, 9999, 'je voudrais commander'),
(202, 9999, 10004, 'Bienvenue dans le service de commande en ligne de Green Engineering SARL. \n\nPour passer votre commande, veuillez me fournir les informations suivantes :\n\n1. Quels produits souhaitez-vous commander ? (Veuillez préciser le nom du produit et la quantité désirée).\n\n2. Votre nom :\n\n3. L\'adresse de livraison :\n\n4. Votre numéro de téléphone :\n\n5. Votre adresse e-mail :\n\nUne fois que j\'aurai ces informations, je traiterai votre commande immédiatement.'),
(203, 10004, 9999, 'hey'),
(204, 9999, 10004, 'Bonjour, bienvenue chez Green Engineering SARL. \n\nNos solutions techniques sont variées et adaptées à votre quotidien. Nous nous spécialisons dans le reconditionnement des huiles moteurs usagées et la fabrication de scellés pour bouteilles de gaz, le tout en veillant à l\'innovation et à l\'autonomie technologique. \n\nVous pouvez découvrir nos produits lubrifiants industriels tels que les huiles moteurs de différentes viscosités : 15w40, SAE40 et 20w50, ainsi que les huiles hydrauliques DEXIII. Nous produisons également des graisses à usage mécanique et pour moteurs. \n\nNotre entreprise, située à Yaoundé, plus précisément à Afanoyoa sur la route de Mbankomo, est à votre disposition pour tout besoin en produits d\'entretien automobile et conseils d\'experts. \n\nNotre équipe est prête à vous renseigner plus amplement sur nos activités et nos'),
(205, 10004, 9999, 'j\'ai un moteur essence vous me conseillez quelle huile pou mon moteur?'),
(206, 9999, 10004, 'Bonjour, \n\nPour les moteurs essence, nous recommandons l\'huile moteur 15w40. Cette huile est adaptée à la plupart des moteurs essence et offre un bon équilibre entre performance et protection. \n\nLe prix pour un bidon de 20 litres est de 40 000 fcfa, et un carton de bouteilles d\'un litre revient à 30 000 fcfa. \n\nVoulez-vous que je vous donne plus de détails sur cette huile?\n\n**Conseils:**\n- Utilisez **\'Définir rappel vidange au YYYY-MM-DD\'** (ex: 2025-09-01)\n- Ou **\'Rappel huile au YYYY-MM-DD\'** (ex: 2025-09-01)\nIndiquez-moi la date !'),
(207, 10004, 9999, 'ok merci'),
(208, 9999, 10004, 'Merci à vous ! Au plaisir de vous rendre service. L\'équipe de Green Engineering SARL est à votre disposition.');

-- --------------------------------------------------------

--
-- Structure de la table `user_form`
--

CREATE TABLE `user_form` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `image` varchar(255) DEFAULT 'default-avatar.png',
  `status` varchar(20) DEFAULT 'Offline Now',
  `langue` varchar(2) DEFAULT 'fr',
  `verification_code` varchar(6) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_form`
--

INSERT INTO `user_form` (`user_id`, `fname`, `lname`, `email`, `password`, `image`, `status`, `langue`, `verification_code`, `verified`) VALUES
(9999, 'Green', 'Chat', 'bot@greenengineering.com', 'c601812eb502869f486e420b48e7cc83', 'Logo Green Engineering OK.png', 'En ligne', 'fr', NULL, 0),
(10004, 'Romuald', 'MBE', 'romualdmbe07@gmail.com', 'ab4f63f9ac65152575886860dde480a1', 'default-avatar.png', 'Offline Now', 'fr', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `vehicle_reminders`
--

CREATE TABLE `vehicle_reminders` (
  `reminder_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `next_oil_change_date` date DEFAULT NULL,
  `oil_reminder_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `commandes_temp`
--
ALTER TABLE `commandes_temp`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`),
  ADD KEY `outgoing_msg_id` (`outgoing_msg_id`),
  ADD KEY `incoming_msg_id` (`incoming_msg_id`);

--
-- Index pour la table `user_form`
--
ALTER TABLE `user_form`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `vehicle_reminders`
--
ALTER TABLE `vehicle_reminders`
  ADD PRIMARY KEY (`reminder_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT pour la table `user_form`
--
ALTER TABLE `user_form`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10005;

--
-- AUTO_INCREMENT pour la table `vehicle_reminders`
--
ALTER TABLE `vehicle_reminders`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_form` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_form` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`outgoing_msg_id`) REFERENCES `user_form` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`incoming_msg_id`) REFERENCES `user_form` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `vehicle_reminders`
--
ALTER TABLE `vehicle_reminders`
  ADD CONSTRAINT `vehicle_reminders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_form` (`user_id`);
--
-- Base de données : `commerce`
--
CREATE DATABASE IF NOT EXISTS `commerce` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `commerce`;

-- --------------------------------------------------------

--
-- Structure de la table `achat`
--
-- Erreur de lecture de structure pour la table commerce.achat : #1932 - Table &#039;commerce.achat&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table commerce.achat : #1064 - Erreur de syntaxe près de &#039;FROM `commerce`.`achat`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `admi`
--
-- Erreur de lecture de structure pour la table commerce.admi : #1932 - Table &#039;commerce.admi&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table commerce.admi : #1064 - Erreur de syntaxe près de &#039;FROM `commerce`.`admi`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--
-- Erreur de lecture de structure pour la table commerce.clients : #1932 - Table &#039;commerce.clients&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table commerce.clients : #1064 - Erreur de syntaxe près de &#039;FROM `commerce`.`clients`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--
-- Erreur de lecture de structure pour la table commerce.commentaires : #1932 - Table &#039;commerce.commentaires&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table commerce.commentaires : #1064 - Erreur de syntaxe près de &#039;FROM `commerce`.`commentaires`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--
-- Erreur de lecture de structure pour la table commerce.messages : #1932 - Table &#039;commerce.messages&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table commerce.messages : #1064 - Erreur de syntaxe près de &#039;FROM `commerce`.`messages`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `products`
--
-- Erreur de lecture de structure pour la table commerce.products : #1932 - Table &#039;commerce.products&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table commerce.products : #1064 - Erreur de syntaxe près de &#039;FROM `commerce`.`products`&#039; à la ligne 1
--
-- Base de données : `etudiant`
--
CREATE DATABASE IF NOT EXISTS `etudiant` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `etudiant`;

-- --------------------------------------------------------

--
-- Structure de la table `badge`
--
-- Erreur de lecture de structure pour la table etudiant.badge : #1932 - Table &#039;etudiant.badge&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table etudiant.badge : #1064 - Erreur de syntaxe près de &#039;FROM `etudiant`.`badge`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `certificat_scolarite`
--
-- Erreur de lecture de structure pour la table etudiant.certificat_scolarite : #1932 - Table &#039;etudiant.certificat_scolarite&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table etudiant.certificat_scolarite : #1064 - Erreur de syntaxe près de &#039;FROM `etudiant`.`certificat_scolarite`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--
-- Erreur de lecture de structure pour la table etudiant.enseignant : #1932 - Table &#039;etudiant.enseignant&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table etudiant.enseignant : #1064 - Erreur de syntaxe près de &#039;FROM `etudiant`.`enseignant`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `enseigner`
--
-- Erreur de lecture de structure pour la table etudiant.enseigner : #1932 - Table &#039;etudiant.enseigner&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table etudiant.enseigner : #1064 - Erreur de syntaxe près de &#039;FROM `etudiant`.`enseigner`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--
-- Erreur de lecture de structure pour la table etudiant.etudiant : #1932 - Table &#039;etudiant.etudiant&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table etudiant.etudiant : #1064 - Erreur de syntaxe près de &#039;FROM `etudiant`.`etudiant`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--
-- Erreur de lecture de structure pour la table etudiant.salle : #1932 - Table &#039;etudiant.salle&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table etudiant.salle : #1064 - Erreur de syntaxe près de &#039;FROM `etudiant`.`salle`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `tenir`
--
-- Erreur de lecture de structure pour la table etudiant.tenir : #1932 - Table &#039;etudiant.tenir&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table etudiant.tenir : #1064 - Erreur de syntaxe près de &#039;FROM `etudiant`.`tenir`&#039; à la ligne 1
--
-- Base de données : `gestion_etudiants`
--
CREATE DATABASE IF NOT EXISTS `gestion_etudiants` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gestion_etudiants`;

-- --------------------------------------------------------

--
-- Structure de la table `badge`
--
-- Erreur de lecture de structure pour la table gestion_etudiants.badge : #1932 - Table &#039;gestion_etudiants.badge&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table gestion_etudiants.badge : #1064 - Erreur de syntaxe près de &#039;FROM `gestion_etudiants`.`badge`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `certificat_scolarite`
--
-- Erreur de lecture de structure pour la table gestion_etudiants.certificat_scolarite : #1932 - Table &#039;gestion_etudiants.certificat_scolarite&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table gestion_etudiants.certificat_scolarite : #1064 - Erreur de syntaxe près de &#039;FROM `gestion_etudiants`.`certificat_scolarite`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--
-- Erreur de lecture de structure pour la table gestion_etudiants.cours : #1932 - Table &#039;gestion_etudiants.cours&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table gestion_etudiants.cours : #1064 - Erreur de syntaxe près de &#039;FROM `gestion_etudiants`.`cours`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--
-- Erreur de lecture de structure pour la table gestion_etudiants.enseignant : #1932 - Table &#039;gestion_etudiants.enseignant&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table gestion_etudiants.enseignant : #1064 - Erreur de syntaxe près de &#039;FROM `gestion_etudiants`.`enseignant`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--
-- Erreur de lecture de structure pour la table gestion_etudiants.etudiant : #1932 - Table &#039;gestion_etudiants.etudiant&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table gestion_etudiants.etudiant : #1064 - Erreur de syntaxe près de &#039;FROM `gestion_etudiants`.`etudiant`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--
-- Erreur de lecture de structure pour la table gestion_etudiants.paiement : #1932 - Table &#039;gestion_etudiants.paiement&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table gestion_etudiants.paiement : #1064 - Erreur de syntaxe près de &#039;FROM `gestion_etudiants`.`paiement`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--
-- Erreur de lecture de structure pour la table gestion_etudiants.salle : #1932 - Table &#039;gestion_etudiants.salle&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table gestion_etudiants.salle : #1064 - Erreur de syntaxe près de &#039;FROM `gestion_etudiants`.`salle`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `suivre`
--
-- Erreur de lecture de structure pour la table gestion_etudiants.suivre : #1932 - Table &#039;gestion_etudiants.suivre&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table gestion_etudiants.suivre : #1064 - Erreur de syntaxe près de &#039;FROM `gestion_etudiants`.`suivre`&#039; à la ligne 1
--
-- Base de données : `l2e`
--
CREATE DATABASE IF NOT EXISTS `l2e` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `l2e`;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--
-- Erreur de lecture de structure pour la table l2e.etudiant : #1932 - Table &#039;l2e.etudiant&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table l2e.etudiant : #1064 - Erreur de syntaxe près de &#039;FROM `l2e`.`etudiant`&#039; à la ligne 1
--
-- Base de données : `natstock`
--
CREATE DATABASE IF NOT EXISTS `natstock` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `natstock`;
--
-- Base de données : `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Structure de la table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Structure de la table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Structure de la table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Structure de la table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Structure de la table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Structure de la table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Structure de la table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Structure de la table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Structure de la table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Structure de la table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Déchargement des données de la table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"chatapp_db\",\"table\":\"feedbacks\"},{\"db\":\"chatapp_db\",\"table\":\"user_form\"},{\"db\":\"chatapp_db\",\"table\":\"vehicle_reminders\"},{\"db\":\"chatapp_db\",\"table\":\"commandes\"},{\"db\":\"chatapp_db\",\"table\":\"commandes_temp\"},{\"db\":\"chatapp_db\",\"table\":\"messages\"}]');

-- --------------------------------------------------------

--
-- Structure de la table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Structure de la table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Structure de la table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Structure de la table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Structure de la table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

--
-- Déchargement des données de la table `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'chatapp_db', 'user_form', '{\"CREATE_TIME\":\"2025-08-22 09:47:32\",\"col_order\":[0,1,2,3,4,5,6,7,8,9],\"col_visib\":[1,1,1,1,0,0,1,1,1,1]}', '2025-08-22 09:32:57');

-- --------------------------------------------------------

--
-- Structure de la table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Structure de la table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Déchargement des données de la table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2025-08-22 13:30:58', '{\"Console\\/Mode\":\"collapse\",\"lang\":\"fr\",\"NavigationWidth\":249}');

-- --------------------------------------------------------

--
-- Structure de la table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Structure de la table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Index pour la table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Index pour la table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Index pour la table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Index pour la table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Index pour la table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Index pour la table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Index pour la table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Index pour la table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Index pour la table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Index pour la table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Index pour la table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Index pour la table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Index pour la table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Index pour la table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Index pour la table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Index pour la table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Index pour la table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Base de données : `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;

-- --------------------------------------------------------

--
-- Structure de la table `test5`
--
-- Erreur de lecture de structure pour la table test.test5 : #1932 - Table &#039;test.test5&#039; doesn&#039;t exist in engine
-- Erreur de lecture des données pour la table test.test5 : #1064 - Erreur de syntaxe près de &#039;FROM `test`.`test5`&#039; à la ligne 1
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
