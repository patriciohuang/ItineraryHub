-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Jan 16, 2026 at 08:19 PM
-- Server version: 12.1.2-MariaDB-ubu2404
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL,
  `trip_item_id` int(11) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `trip_item_id`, `file_path`, `type`) VALUES
(8, 30, '/uploads/696a76990205c_qr-code.png', 'image/png'),
(9, 31, '/uploads/696a76d46e102_where-to-apply-for-a-hotels-com-coupon-code.jpg', 'image/jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Flight'),
(2, 'Hotel'),
(3, 'Train'),
(4, 'Restaurant'),
(5, 'Activity'),
(6, 'Car Rental');

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `title`, `description`, `start_date`, `end_date`, `added_by`) VALUES
(10, 'Summer in Tokyo', 'A two-week exploration of food, tech, and shrines in Japan.', '2026-06-15', '2026-06-29', 5),
(11, 'Italian Road Trip', 'Driving from Rome to Venice via Florence and Tuscany.', '2026-05-01', '2026-05-12', 5),
(12, 'SF Tech Conference', 'Attending the Global Dev Summit. Need to book team dinners.', '2026-11-05', '2026-11-10', 6);

-- --------------------------------------------------------

--
-- Table structure for table `trip_items`
--

CREATE TABLE `trip_items` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` enum('SUGGESTED','APPROVED','REJECTED','PUBLISHED') DEFAULT 'PUBLISHED',
  `created_by` int(11) NOT NULL,
  `is_suggested` tinyint(1) DEFAULT 0,
  `suggested_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `trip_items`
--

INSERT INTO `trip_items` (`id`, `trip_id`, `title`, `start_date`, `end_date`, `url`, `notes`, `category_id`, `status`, `created_by`, `is_suggested`, `suggested_by`) VALUES
(30, 10, 'Flight JL006 to Haneda', '2026-06-15 11:00:00', '2026-06-16 14:30:00', 'https://jal.co.jp', 'Terminal 3. Confirmation #ABC12345.', 1, 'APPROVED', 5, 0, NULL),
(31, 10, 'Shinjuku Granbell Hotel', '2026-06-16 16:00:00', '2026-06-22 10:00:00', 'https://granbell.com', 'Check-in is at 3 PM. I requested a high floor.', 2, 'APPROVED', 5, 0, NULL),
(32, 10, 'Dinner at Ichiran Ramen', '2026-06-16 19:00:00', '2026-06-16 20:30:00', '', 'The famous solo booth ramen. No reservation needed but expect a line.', 4, 'APPROVED', 5, 0, NULL),
(33, 10, 'TeamLab Planets', '2026-06-17 10:00:00', '2026-06-17 12:00:00', 'https://teamlab.art', 'Digital art museum. Wear shorts (water area).', 5, 'APPROVED', 5, 0, NULL),
(34, 11, 'Flight to Rome FCO', '2026-05-01 08:00:00', '2026-05-01 16:00:00', '', 'Alitalia direct.', 1, 'APPROVED', 5, 0, NULL),
(35, 11, 'Pick up Fiat 500 Rental', '2026-05-01 17:00:00', '2026-05-01 17:30:00', '', 'Hertz counter at FCO.', 6, 'APPROVED', 5, 0, NULL),
(36, 12, 'Marriott Marquis SF', '2026-11-05 14:00:00', '2026-11-10 11:00:00', '', 'Conference block rate applied.', 2, 'APPROVED', 6, 0, NULL),
(37, 12, 'Keynote Speech: AI Future', '2026-11-06 09:00:00', '2026-11-06 10:30:00', '', 'Main Hall. Do not miss.', 5, 'APPROVED', 6, 0, NULL),
(38, 10, 'Tsukiji Sushi Making Class', '2026-06-18 10:00:00', '2026-06-18 13:30:00', '', 'Learn to make nigiri and rolls with a local chef. Meet at the main gate.', 5, 'SUGGESTED', 6, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `trip_item_participants`
--

CREATE TABLE `trip_item_participants` (
  `trip_item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `trip_item_participants`
--

INSERT INTO `trip_item_participants` (`trip_item_id`, `user_id`) VALUES
(30, 5),
(31, 5),
(32, 5),
(33, 5),
(34, 5),
(35, 5),
(37, 5),
(30, 6),
(31, 6),
(36, 6),
(37, 6),
(38, 6);

-- --------------------------------------------------------

--
-- Table structure for table `trip_memberships`
--

CREATE TABLE `trip_memberships` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `membership_status` enum('INVITED','PENDING','ACCEPTED','REJECTED') DEFAULT 'INVITED',
  `role` enum('ADMIN','COLLABORATOR','PARTICIPANT') DEFAULT NULL,
  `role_offered` enum('ADMIN','COLLABORATOR','PARTICIPANT') DEFAULT NULL,
  `invited_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `trip_memberships`
--

INSERT INTO `trip_memberships` (`id`, `trip_id`, `user_id`, `membership_status`, `role`, `role_offered`, `invited_by`) VALUES
(11, 10, 5, 'ACCEPTED', 'ADMIN', NULL, NULL),
(12, 10, 6, 'ACCEPTED', 'PARTICIPANT', 'PARTICIPANT', 5),
(13, 11, 5, 'ACCEPTED', 'ADMIN', NULL, NULL),
(14, 11, 6, 'PENDING', NULL, 'COLLABORATOR', 5),
(15, 12, 6, 'ACCEPTED', 'ADMIN', NULL, NULL),
(16, 12, 5, 'ACCEPTED', 'PARTICIPANT', 'PARTICIPANT', 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `username`, `password`, `created_at`) VALUES
(5, 'Patricio', 'Huang', 'patricio@test.com', 'Patricio', '$2y$12$dhKj/BZutwnhfF.ImejhLuQzRspihLd1hIaoRAMKV4TZB7bJGs1ry', '2026-01-16 17:05:21'),
(6, 'Daniel', 'Breczinski', 'teacher@test.com', 'BreczinskiD', '$2y$12$KxLWB0B7cQYEFxUqEzwFbejuRFelQYHhrSFJD5s1ams57wZtGcHei', '2026-01-16 17:37:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_item_id` (`trip_item_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexes for table `trip_items`
--
ALTER TABLE `trip_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_id` (`trip_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `fk_item_suggester` (`suggested_by`);

--
-- Indexes for table `trip_item_participants`
--
ALTER TABLE `trip_item_participants`
  ADD PRIMARY KEY (`trip_item_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `trip_memberships`
--
ALTER TABLE `trip_memberships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trip_id` (`trip_id`,`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `invited_by` (`invited_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `trip_items`
--
ALTER TABLE `trip_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `trip_memberships`
--
ALTER TABLE `trip_memberships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `fk_attachments_cascade` FOREIGN KEY (`trip_item_id`) REFERENCES `trip_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `1` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `trip_items`
--
ALTER TABLE `trip_items`
  ADD CONSTRAINT `2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_item_suggester` FOREIGN KEY (`suggested_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_trip_items_cascade` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trip_item_participants`
--
ALTER TABLE `trip_item_participants`
  ADD CONSTRAINT `2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_item_participants_cascade` FOREIGN KEY (`trip_item_id`) REFERENCES `trip_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trip_memberships`
--
ALTER TABLE `trip_memberships`
  ADD CONSTRAINT `2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `3` FOREIGN KEY (`invited_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_memberships_trip_cascade` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
