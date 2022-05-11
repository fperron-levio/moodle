-- phpMyAdmin SQL Dump
-- version 4.0.0
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 08 Juillet 2014 à 08:44
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
-- Structure de la table `mdl_rsg_track`
--

CREATE TABLE IF NOT EXISTS `mdl_rsg_track` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL DEFAULT '0',
  `rsgid` bigint(10) NOT NULL DEFAULT '0',
  `timestarted` bigint(10) NOT NULL DEFAULT '0',
  `lastvisit` bigint(10) NOT NULL DEFAULT '0',
  `visits` bigint(10) NOT NULL DEFAULT '0',
  `timeadduec` bigint(10) NOT NULL DEFAULT '0',
  `value` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_rsgtrac_usersg_uix` (`userid`,`rsgid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='to track the student on each activity' AUTO_INCREMENT=13 ;

--
-- Contenu de la table `mdl_rsg_track`
--

INSERT INTO `mdl_rsg_track` (`id`, `userid`, `rsgid`, `timestarted`, `lastvisit`, `visits`, `timeadduec`, `value`) VALUES
(1, 2, 1, 1404219004, 1404329261, 5, 1403286042, NULL),
(2, 28, 1, 1404221063, 1404324255, 31, 1325653200, NULL),
(3, 28, 2, 1404306919, 1404758049, 5, 1340337600, NULL),
(4, 2, 2, 1404329249, 0, 1, 0, NULL),
(5, 28, 3, 1404221063, 1404757482, 34, 1341892800, NULL),
(7, 28, 4, 1404221063, 1404324255, 31, 1357102800, NULL),
(8, 28, 5, 1404221063, 1404324255, 31, 1357275600, NULL),
(9, 28, 6, 1404221063, 1404324255, 31, 1371873600, NULL),
(10, 28, 7, 1404221063, 1404324255, 31, 1373428800, NULL),
(12, 28, 8, 1404221063, 1404324255, 31, 1388638800, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
