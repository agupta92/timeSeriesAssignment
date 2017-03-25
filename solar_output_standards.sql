-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: staging1.cpw1gtbr1jnd.ap-southeast-1.rds.amazonaws.com
-- Generation Time: Mar 24, 2017 at 05:41 PM
-- Server version: 5.6.27-log
-- PHP Version: 5.5.38
------------------------------------------------

--
-- Table structure for table `solar_output_standards`
--

CREATE TABLE `solar_output_standards` (
  `output_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `city_name` enum('mumbai','delhi','bangalore') DEFAULT NULL,
  `hour_count` int(10) UNSIGNED NOT NULL,
  `datetime_for` datetime NOT NULL,
  `longitude` decimal(10,2) NOT NULL,
  `latitude` decimal(10,2) NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(10) UNSIGNED NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`output_id`),
  KEY `hour_count` (`hour_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




INSERT INTO `user_records` (`user_private_id`, `user_public_id`, `user_name`, `user_city`, `user_longitute`, `user_latitude`, `user_created_by`, `user_created_on`, `user_updated_by`, `user_updated_on`) VALUES (NULL, '1', 'Ankit Gupta', 'mumbai', '73', '19', '1', CURRENT_TIMESTAMP, '1', CURRENT_TIMESTAMP), (NULL, '2', 'Vishal Solanki', 'delhi', '77', '28', '1', CURRENT_TIMESTAMP, '1', CURRENT_TIMESTAMP);
