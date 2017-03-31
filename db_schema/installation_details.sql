-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: staging1.cpw1gtbr1jnd.ap-southeast-1.rds.amazonaws.com
-- Generation Time: Mar 30, 2017 at 06:25 PM
-- Server version: 5.6.27-log
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dev1maindb`
--

-- --------------------------------------------------------

--
-- Table structure for table `installation_details`
--

CREATE TABLE `installation_details` (
  `installation_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `city_name` varchar(255) NOT NULL,
  `longitude` decimal(10,3) NOT NULL,
  `latitude` decimal(10,3) NOT NULL,
  `standard_output` text NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(10) UNSIGNED NOT NULL,
  `updated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`installation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for table `installation_details`
--
ALTER TABLE `installation_details`
  ADD CONSTRAINT `installation_details_ibfk_1` FOREIGN KEY (`installation_id`) REFERENCES `user_records` (`installation_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
