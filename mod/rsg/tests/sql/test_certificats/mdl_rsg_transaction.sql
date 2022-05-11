-- phpMyAdmin SQL Dump
-- version 4.0.0
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 08 Juillet 2014 à 08:45
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
-- Structure de la table `mdl_rsg_transaction`
--

CREATE TABLE IF NOT EXISTS `mdl_rsg_transaction` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) NOT NULL,
  `response_order_id` varchar(32) NOT NULL DEFAULT '',
  `date_stamp` varchar(32) NOT NULL DEFAULT '',
  `time_stamp` varchar(32) NOT NULL DEFAULT '',
  `bank_transaction_id` varchar(32) NOT NULL DEFAULT '',
  `charge_total` varchar(16) NOT NULL DEFAULT '',
  `bank_approval_code` varchar(16) NOT NULL DEFAULT '',
  `response_code` varchar(4) NOT NULL DEFAULT '',
  `iso_code` varchar(4) NOT NULL DEFAULT '',
  `message` varchar(32) NOT NULL DEFAULT '',
  `cardholder` varchar(64) NOT NULL DEFAULT '',
  `f4l4` varchar(16) NOT NULL DEFAULT '',
  `card` varchar(2) NOT NULL DEFAULT '',
  `expiry_date` varchar(4) NOT NULL DEFAULT '',
  `result` varchar(4) NOT NULL DEFAULT '',
  `event` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='This table saves payment information about an abonnement to ' AUTO_INCREMENT=7 ;

--
-- Contenu de la table `mdl_rsg_transaction`
--

INSERT INTO `mdl_rsg_transaction` (`id`, `userid`, `response_order_id`, `date_stamp`, `time_stamp`, `bank_transaction_id`, `charge_total`, `bank_approval_code`, `response_code`, `iso_code`, `message`, `cardholder`, `f4l4`, `card`, `expiry_date`, `result`, `event`) VALUES
(6, 28, 'mhp14075113524p63', '2012-01-04', '12:30:44', '660053720012490570', '100.00', '794317', '027', '01', 'APPROVED    ', 'Nelson Moller', '4242***4242', 'V', '1412', '1', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
