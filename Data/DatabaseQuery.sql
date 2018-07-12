-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 12, 2018 at 07:02 AM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `syllablewords`
--

-- --------------------------------------------------------

--
-- Table structure for table `syllable`
--

DROP TABLE IF EXISTS `syllable`;
CREATE TABLE IF NOT EXISTS `syllable` (
  `s_id` int(11) NOT NULL AUTO_INCREMENT,
  `syllable` varchar(255) NOT NULL,
  PRIMARY KEY (`s_id`),
  UNIQUE KEY `Unique_syllable` (`syllable`)
) ENGINE=MyISAM AUTO_INCREMENT=4469 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `syllablebyword`
--

DROP TABLE IF EXISTS `syllablebyword`;
CREATE TABLE IF NOT EXISTS `syllablebyword` (
  `w_id` int(11) NOT NULL,
  `s_id` int(11) NOT NULL,
  PRIMARY KEY (`w_id`,`s_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `word`
--

DROP TABLE IF EXISTS `word`;
CREATE TABLE IF NOT EXISTS `word` (
  `w_id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) NOT NULL,
  `syllableWord` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`w_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
