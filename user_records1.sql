-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: staging1.cpw1gtbr1jnd.ap-southeast-1.rds.amazonaws.com
-- Generation Time: Mar 24, 2017 at 06:02 PM
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
  `user_city` varchar(255) NOT NULL,
  `user_longitute` decimal(10,2) NOT NULL,
  `user_latitude` decimal(10,2) NOT NULL,
  `user_created_by` int(10) UNSIGNED NOT NULL,
  `user_created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_updated_by` int(10) UNSIGNED NOT NULL,
  `user_updated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_private_id`),
  KEY `user_city` (`user_city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_records`
--

INSERT INTO `user_records` (`user_private_id`, `user_public_id`, `user_name`, `user_city`, `user_longitute`, `user_latitude`, `user_created_by`, `user_created_on`, `user_updated_by`, `user_updated_on`) VALUES
(1, '1', 'Ankit Gupta', 'mumbai', '73.00', '19.00', 1, '2017-03-24 17:59:30', 1, '2017-03-24 17:59:30'),
(2, '2', 'Vishal Solanki', 'delhi', '77.00', '28.00', 1, '2017-03-24 17:59:30', 1, '2017-03-24 17:59:30'),
(3, '3', 'Arpan', 'bangalore', '78.00', '28.00', 1, '2017-03-24 18:01:06', 1, '2017-03-24 18:01:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_records`
--
ALTER TABLE `user_records`
  ADD PRIMARY KEY (`user_private_id`),
  ADD KEY `user_city` (`user_city`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_records`
--
ALTER TABLE `user_records`
  MODIFY `user_private_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
