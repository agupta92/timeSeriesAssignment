-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: staging1.cpw1gtbr1jnd.ap-southeast-1.rds.amazonaws.com
-- Generation Time: Mar 30, 2017 at 06:29 PM
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
-- Table structure for table `user_records`
--

CREATE TABLE `user_records` (
  `user_private_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_public_id` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `installation_id` int(11) UNSIGNED NOT NULL,
  `user_created_by` int(10) UNSIGNED NOT NULL,
  `user_created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_updated_by` int(10) UNSIGNED NOT NULL,
  `user_updated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_private_id`),
  KEY `installation_id` (`installation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_records`
--

INSERT INTO `user_records` (`user_private_id`, `user_public_id`, `user_name`, `email_id`, `installation_id`, `user_created_by`, `user_created_on`, `user_updated_by`, `user_updated_on`) VALUES
(1, '1', 'Ankit Gupta', 'agupta.92@gmail.com', 1, 1, '2017-03-24 17:59:30', 1, '2017-03-24 17:59:30'),
(2, '2', 'Vishal Solanki', 'agupta.92@gmail.com', 2, 1, '2017-03-24 17:59:30', 1, '2017-03-24 17:59:30'),
(3, '3', 'Arpan', 'agupta.92@gmail.com', 3, 1, '2017-03-24 18:01:06', 1, '2017-03-24 18:01:06');
