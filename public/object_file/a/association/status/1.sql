-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 17, 2020 at 09:10 AM
-- Server version: 5.7.26
-- PHP Version: 7.0.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `emailsubsribers`
--

DROP TABLE IF EXISTS `emailsubsribers`;
CREATE TABLE IF NOT EXISTS `emailsubsribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `emailsubsribers`
--

INSERT INTO `emailsubsribers` (`id`, `email`) VALUES
(1, 'clkj'),
(2, ''),
(5, ''),
(4, ''),
(6, ''),
(7, ''),
(8, ''),
(9, ''),
(10, 'johndoe@yahoo.fr'),
(11, 'johndoe@yahoo.fr'),
(12, 'jPro@yahoo.fr'),
(13, 'tchouya_james@yahoo.fr'),
(14, 'becomepro@becomepro.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
