-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2020 at 05:34 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tvboxes`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `type` enum('1','2') DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@owl.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2', '2020-10-13 08:51:18', '2020-10-13 08:51:18');

-- --------------------------------------------------------

--
-- Table structure for table `redeem_coupon`
--

CREATE TABLE `redeem_coupon` (
  `id` int(30) NOT NULL,
  `redeem_code` varchar(255) NOT NULL,
  `user_id` int(14) NOT NULL,
  `validity` varchar(255) NOT NULL,
  `expiry` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `creation_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `upload_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `fcm_token` varchar(255) DEFAULT NULL,
  `device_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `phone`, `email`, `name`, `status`, `deleted`, `fcm_token`, `device_id`, `created_at`, `updated_at`) VALUES
(1, '+923004801734', 'talhabhatti0257@gmail.com', 'Talha Tahir', 1, '0', 'd2yMIGc0InAB1Egw-cogFo:APA91bENOUpiA5v1V0fhjqiki-xp8vwy1pOMo3uuHcQ2VLzVmTfOkIKikfzmtVeMEyqYc9Kkvs-xMeV78ZfO1wzelAea2fOeGUImyPLlPWk-lMdYIgI-42RMlWlHnHgDrtU0qYpp0stB', '13797f4962d6baf1', '2020-10-08 12:26:46', '2020-10-27 14:39:44'),
(3, '+923360491332', 'hassanraza632@gmail.com', 'Hassan Raza', 1, '0', NULL, '13797f4962d6baf112', '2020-10-09 09:52:41', '2020-10-27 14:39:56'),
(6, '+19179000878', 'fatimamaqsood@gmail.com', 'Fatima', 1, '0', NULL, '13797f4962d6baf1123', '2020-10-10 17:17:30', '2020-10-27 14:40:01'),
(15, '+923484468124', 'rohailnazir047@gmail.com', 'Tasir Aseem', 1, '0', NULL, '13797f4962d6baf113', '2020-10-14 09:48:32', '2020-10-27 14:40:17'),
(16, '03320671357', 'nabeel53055@gmail.com', 'Test User', 1, '0', NULL, '123456789', '2020-10-27 14:50:21', '2020-10-27 14:50:21'),
(18, '03484468124', 'nabeeljutt7374@gmail.com', 'Nabeel Jutt', 0, '0', NULL, 'dhhdh123456', '2020-10-27 17:18:18', '2020-10-29 16:24:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `redeem_coupon`
--
ALTER TABLE `redeem_coupon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `redeem_coupon`
--
ALTER TABLE `redeem_coupon`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `redeem_coupon`
--
ALTER TABLE `redeem_coupon`
  ADD CONSTRAINT `redeem_coupon_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
