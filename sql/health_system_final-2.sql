-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 28, 2025 at 06:54 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `health_system_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `professional_id` int(11) NOT NULL,
  `booking_id` int(2) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `professional_id`, `booking_id`, `appointment_date`, `appointment_time`, `created_at`) VALUES
(2, 1, 1, 2, '2025-11-14', '10:30:00', '2025-11-14 08:00:00'),
(4, 1, 1, 4, '2025-11-25', '10:30:00', '2025-11-14 08:00:00'),
(5, 29, 1, 10, '2025-12-06', '12:30:00', '2025-11-28 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `userid`, `booking_date`, `description`) VALUES
(2, 1, '2025-11-06', 'Talk about food intake'),
(3, 1, '2025-11-05', 'Test'),
(4, 1, '2025-11-25', 'checkup'),
(5, 17, '2025-12-19', 'Testing'),
(6, 17, '2025-12-24', 'Checkup'),
(9, 26, '2025-11-27', 'checkup'),
(10, 29, '2025-12-06', 'Checkup'),
(11, 29, '2025-12-22', 'Physical');

-- --------------------------------------------------------

--
-- Table structure for table `logging`
--

CREATE TABLE `logging` (
  `logID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `calories` int(11) DEFAULT 0,
  `sleep` float DEFAULT 0,
  `water` float DEFAULT 0,
  `exercise` varchar(255) DEFAULT NULL,
  `meds` varchar(255) DEFAULT NULL,
  `log_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logging`
--

INSERT INTO `logging` (`logID`, `userID`, `calories`, `sleep`, `water`, `exercise`, `meds`, `log_date`) VALUES
(2, 1, 2000, 8, 2000, '3', '2001', '2025-11-06 04:10:22'),
(3, 1, 2000, 1, 2200, '3', '4', '2025-11-06 08:00:00'),
(5, 17, 2000, 8, 2000, '2', '0', '2025-11-27 08:00:00'),
(6, 17, 2000, 8, 2000, '2', '0', '2025-11-27 08:00:00'),
(9, 17, 11, 1, 1, '1', '0', '2025-11-27 08:00:00'),
(10, 17, 9, 9, 9, '9', '0', '2025-11-27 08:00:00'),
(15, 17, 44, 44, 44, '44', '0', '2025-11-27 08:00:00'),
(43, 24, 1, 1, 1, '1', '0', '2025-11-26 08:00:00'),
(44, 25, 2000, 8, 2000, '3', '0', '2025-11-27 08:00:00'),
(47, 26, 3, 3, 3, '3', '0', '2025-11-26 08:00:00'),
(48, 26, 2, 2, 2, '2', '0', '2025-11-26 08:00:00'),
(49, 26, 2000, 8, 2000, '2', '0', '2025-11-26 08:00:00'),
(50, 26, 2000, 7, 1200, '1', '0', '2025-11-27 08:00:00'),
(51, 29, 2000, 8, 1800, '2', '0', '2025-11-28 08:00:00'),
(52, 29, 1800, 7, 1650, '1', '0', '2025-11-28 08:00:00'),
(53, 29, 1900, 8, 2100, '1', '0', '2025-11-28 08:00:00'),
(54, 29, 1700, 6, 1750, '1', '0', '2025-11-28 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `monitor`
--

CREATE TABLE `monitor` (
  `current` int(2) DEFAULT 0,
  `amount` int(5) DEFAULT 0,
  `id` int(2) NOT NULL,
  `score` int(5) DEFAULT 0,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monitor`
--

INSERT INTO `monitor` (`current`, `amount`, `id`, `score`, `date`) VALUES
(1, 2000, 1, 2000, '2025-11-24'),
(0, 0, 23, 0, '2025-11-24'),
(NULL, NULL, 24, NULL, NULL),
(1, 2000, 25, 2000, '2025-11-27'),
(1, 1000, 26, 1001, '2025-11-26'),
(0, 0, 27, 0, NULL),
(0, 0, 28, 0, NULL),
(1, 1900, 29, 1850, '2025-11-28'),
(0, 0, 30, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `professionals`
--

CREATE TABLE `professionals` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `zip` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professionals`
--

INSERT INTO `professionals` (`id`, `name`, `specialty`, `zip`) VALUES
(1, 'Dr. Sarah Johnson', 'Family Medicine', '94103'),
(2, 'Dr. Alex Kim', 'Pediatrics', '94103'),
(3, 'Dr. Emily Carter', 'Cardiology', '94016'),
(4, 'Dr. James Patel', 'Dermatology', '94016'),
(5, 'Dr. Lisa Nguyen', 'Dentistry', '94022'),
(6, 'Dr. Michael Smith', 'Orthopedics', '94022'),
(7, 'Dr. Olivia Brown', 'Psychiatry', '94022'),
(8, 'Dr. David Lee', 'Internal Medicine', '94016'),
(9, 'Dr. Rachel Adams', 'Gynecology', '94103'),
(10, 'Dr. Robert Young', 'Neurology', '94016');

-- --------------------------------------------------------

--
-- Table structure for table `stress_levels`
--

CREATE TABLE `stress_levels` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `stress_level` int(11) NOT NULL CHECK (`stress_level` between 1 and 10),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stress_levels`
--

INSERT INTO `stress_levels` (`id`, `user_id`, `log_date`, `stress_level`, `notes`, `created_at`) VALUES
(13, 29, '2025-11-28', 6, 'Lots of work lately', '2025-11-28 05:52:12');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `height` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `privilege` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `age`, `height`, `weight`, `gender`, `profile_img`, `created_at`, `privilege`) VALUES
(1, 'user', '123', 90, 160, 155, 'male', 'profile.jpg', '2025-11-04 06:20:18', 0),
(11, 'user2', '1234', 90, 180, 180, 'male', '', '2025-11-13 08:00:00', 1),
(13, 'hashTest', '$2y$12$3GVoCgvx5ZSJMpi9Ft2ADeDqIkGM4hbePmUNXocAQSxk6IzSrIb6W', 1000, 500, 500, 'don\'t know', '', '2025-11-26 08:00:00', 0),
(17, 'hashy', '$2y$12$DkfHSeKRPdmuC6fwpHQnQOlQtGWwKqFqKn7zOlCnxEBsbTD/wWKwe', 90, 90, 90, 'sss', '', '2025-11-26 08:00:00', 0),
(23, 'olllo', '$2y$12$vcs2I9OdNBZQ0wrizZmQEe2keIzgW63sF8YEBYXwjbTTiXrBUVMVe', 11, 11, 11, '11', 'profile.jpg', '2025-11-27 08:00:00', 0),
(24, 'x', '$2y$12$ONGWv.MAVaplXnn9D9OjBemzQgpbcZG8lXFzyBBYx.jTDQZ94x.pu', 12, 12, 12, '12', 'profile.jpg', '2025-11-27 08:00:00', 0),
(25, 'brandnew', '$2y$12$dCU62zzPCvLz9ev1in/iwOOigoPYsvaDKFiwx2qMMCMHf378EmzI.', 1, 1, 1, '1', 'profile.jpg', '2025-11-27 08:00:00', 0),
(26, 'xyy', '$2y$12$mgg8iVG9.0X4GBQs5DgIr.cAytX8NIxdBVslu4sCMN.tPp8Y3F6WS', 21, 180, 180, 'male', 'icon0.png', '2025-11-27 08:00:00', 0),
(27, 'zvv', '$2y$12$TA..AS68ydwHrr1jnezyAuT9tjkY/drUzQlNuUfJILbvd.6QQ5fde', 25, 180, 170, 'male', 'profile.jpg', '2025-11-28 08:00:00', 0),
(28, 'zzz', '$2y$12$bIKcuKjTuFn8ijulV/TtY.jT6HrgeI6sSkvOBWlB.Dlr0uP8SfdwS', 23, 180, 180, 'male', 'profile.jpg', '2025-11-28 08:00:00', 0),
(29, 'newuser', '$2y$12$91Yg3wdn3.vR.M8BCfpdj.FYO63IIigCFEVruHiTyFMm0rSQ5rgNm', 25, 180, 180, 'male', 'icon19.png', '2025-11-28 08:00:00', 0),
(30, 'admin', '$2y$12$9ayZL9PUX8d2BMllDnowvOtZgnVpG9LMF9QcnqSSHm2HrxeRU3RjG', 30, 188, 170, 'male', 'profile.jpg', '2025-11-28 08:00:00', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `professional_id` (`professional_id`),
  ADD KEY `fk_booking_id` (`booking_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_date` (`userid`,`booking_date`);

--
-- Indexes for table `logging`
--
ALTER TABLE `logging`
  ADD PRIMARY KEY (`logID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `monitor`
--
ALTER TABLE `monitor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `professionals`
--
ALTER TABLE `professionals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stress_levels`
--
ALTER TABLE `stress_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_date` (`user_id`,`log_date`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `logging`
--
ALTER TABLE `logging`
  MODIFY `logID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `professionals`
--
ALTER TABLE `professionals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stress_levels`
--
ALTER TABLE `stress_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_booking_id` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `fk_booking_user` FOREIGN KEY (`userid`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `logging`
--
ALTER TABLE `logging`
  ADD CONSTRAINT `logging_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `monitor`
--
ALTER TABLE `monitor`
  ADD CONSTRAINT `fkid` FOREIGN KEY (`id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `stress_levels`
--
ALTER TABLE `stress_levels`
  ADD CONSTRAINT `stress_levels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
