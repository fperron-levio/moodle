-- phpMyAdmin SQL Dump
-- version 4.0.0
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 08 Juillet 2014 à 08:47
-- Version du serveur: 5.6.11
-- Version de PHP: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `201401_moodle26`
--

-- --------------------------------------------------------

--
-- Structure de la table `mdl_rsg`
--

CREATE TABLE IF NOT EXISTS `mdl_rsg` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL DEFAULT '0',
  `category` bigint(10) NOT NULL DEFAULT '0',
  `uec` decimal(4,2) DEFAULT '0.00',
  `duree` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `outil` varchar(511) NOT NULL DEFAULT '',
  `timecreated` bigint(10) NOT NULL DEFAULT '0',
  `timemodified` bigint(10) NOT NULL DEFAULT '0',
  `cm_scorm_id` bigint(10) NOT NULL,
  `cm_resou_id` bigint(10) NOT NULL,
  `cm_quizz_id` bigint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='each record is one rsg resource' AUTO_INCREMENT=9 ;

--
-- Contenu de la table `mdl_rsg`
--

INSERT INTO `mdl_rsg` (`id`, `course`, `category`, `uec`, `duree`, `name`, `outil`, `timecreated`, `timemodified`, `cm_scorm_id`, `cm_resou_id`, `cm_quizz_id`) VALUES
(1, 9, 5, '1.30', '45 minutes', 'Capsule 2', 'outil_01', 1391126400, 0, 194, 195, 198),
(2, 9, 5, '1.20', '30 minutes', 'L’écoute, essentielle à la communication', 'outil_01', 1404228199, 0, 194, 195, 198),
(3, 9, 5, '1.20', '30 minutes', 'Les clés d’une communication harmonieuse', 'outil_01', 1404228199, 0, 194, 195, 198),
(4, 9, 5, '1.20', '30 minutes', 'Le parent au comportement particulier', 'outil_01', 1404228199, 0, 194, 195, 198),
(5, 9, 5, '1.20', '30 minutes', 'Résoudre des conflits avec les parents', 'outil_01', 1404228199, 0, 194, 195, 198),
(6, 9, 5, '1.20', '30 minutes', 'Communiquer le vécu quotidien de l’enfant', 'outil_01', 1404228199, 0, 194, 195, 198),
(7, 9, 5, '1.20', '30 minutes', 'Quand le non verbal parle', 'outil_01', 1404228199, 0, 194, 195, 198),
(8, 9, 5, '1.20', '30 minutes', 'Quand le non verbal parle II', 'outil_01', 1404228199, 0, 194, 195, 198);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
