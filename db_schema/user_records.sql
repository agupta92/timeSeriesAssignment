-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: staging1.cpw1gtbr1jnd.ap-southeast-1.rds.amazonaws.com
-- Generation Time: Mar 24, 2017 at 06:02 PM
--------------------------------------------------

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
