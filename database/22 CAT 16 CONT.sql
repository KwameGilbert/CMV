-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql309.infinityfree.com
-- Generation Time: Jun 02, 2024 at 01:15 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_36570237_voting_db`
--
CREATE DATABASE IF NOT EXISTS `if0_36570237_voting_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `if0_36570237_voting_db`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `cost_per_vote` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `event_id`, `cost_per_vote`) VALUES
(3, 'Face of FADSA', 3, 1),
(4, 'Most Fashionable Student (Male)', 3, 1),
(5, 'Most Fashionable Student (Female)', 3, 1),
(6, 'Best Fashion Designer (Male)', 3, 1),
(7, 'Best Fashion Designer(Female)', 3, 1),
(8, 'Most Decent Student', 3, 1),
(9, 'Most Famous Student', 3, 1),
(10, 'Top Male Model of The Year', 3, 1),
(11, 'Top Female Model Of The Year', 3, 1),
(12, 'Most Sociable Student', 3, 1),
(13, 'Most Zealous Course Rep (Level 100)', 3, 1),
(14, 'Most Zealous Course Rep (Level 200)', 3, 1),
(15, 'Most Zealous Course Rep (Level 300)', 3, 1),
(16, 'Most Zealous Course Rep (Level 400)', 3, 1),
(17, 'Most Influential Student of The Year', 3, 1),
(18, 'Best Fashion Class of the Year', 3, 1),
(19, 'Outstanding Executive of the Year', 3, 1),
(20, 'Outstanding Commitee of the Year', 3, 1),
(21, 'Perfect Gentleman of the Year', 3, 1),
(22, 'Outstanding Student of the Year', 3, 1),
(23, 'Student Entrepreneur of the Year', 3, 1),
(24, 'Best Pals', 3, 1),
(25, 'Textile Student of The Year', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contestants`
--

DROP TABLE IF EXISTS `contestants`;
CREATE TABLE `contestants` (
  `contestant_id` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `contestant_name` varchar(255) NOT NULL,
  `votes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contestants`
--

INSERT INTO `contestants` (`contestant_id`, `category_id`, `contestant_name`, `votes`) VALUES
('FS01', 12, 'Cosmos Twumasi Amaning (Ivy)', 1),
('FS02', 12, 'Afia Bempomaah Boateng (Efya Auxiliary)', 0),
('FS03', 23, 'Cosmos Nyamekye Agbenu (Cosby Walker)', 0),
('FS04', 15, 'Micheal Agbalo (MICKEY)', 0),
('FS05', 4, 'Micheal Agbalo (MICKEY)', 0),
('FS06', 21, 'Micheal Agbalo (MICKEY)', 0),
('FS07', 9, 'Micheal Agbalo (MICKEY)', 0),
('FS08', 22, 'Micheal Agbalo (MICKEY)', 0),
('FS09', 25, 'Micheal Agbalo (MICKEY)', 0),
('FS10', 19, 'Anthoanitte Eshun Annette (Annette)', 0),
('FS11', 7, 'Gifty Adom (Adom)', 0),
('FS12', 23, 'Gifty Adom (Adom)', 0),
('FS13', 16, 'Felecia Dzimabi (Mama)', 0),
('FS14', 18, 'Fashion 400 B (400 B)', 0),
('FS15', 23, 'Naomi Kagbitor (Ama)', 0),
('FS16', 22, 'Yaw Boateng Agyemang (Yaw)', 0);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_host` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cost_per_vote` float(11,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `host_id` int(11) NOT NULL,
  `show_results` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_date`, `event_host`, `description`, `cost_per_vote`, `created_at`, `host_id`, `show_results`) VALUES
(1, 'INFOTESS AWARDS', '2024-05-31', 'INFOTESS', 'INFOTESS', 1.00, '2024-05-17 03:04:54', 1, 1),
(2, 'MASA AWARDS', '2024-05-30', 'MASSA', 'MASSA', 0.50, '2024-05-17 03:04:54', 3, 1),
(3, 'FADSA EXCELLENCE AWARDS', '2024-06-30', 'AAMUSTED - FADSA', 'FADSA ', 1.00, '2024-05-19 19:31:44', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hosts`
--

DROP TABLE IF EXISTS `hosts`;
CREATE TABLE `hosts` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `resuls_show` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hosts`
--

INSERT INTO `hosts` (`id`, `username`, `email`, `password`, `firstname`, `lastname`, `phone_number`, `address`, `company`, `bio`, `resuls_show`) VALUES
(1, 'Kwame Gilbert', 'kwamegilbert1114@gmail.com', 'FearNot', 'Gilbert', 'Kukah', '0541436414', '', 'INFOTESS', '', 0),
(2, 'Fadsa', 'enyonamanison@gmail.com', 'Fadsa@2k25', 'Patience Enyonam', 'Asamoah', '+233263330045', NULL, 'AAMUSTED - FADSA', NULL, 1),
(3, 'iamguest', 'guest@guest.com', 'guest', 'Guest', 'Guest', '0541436414', NULL, 'GUEST', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE `votes` (
  `vote_id` int(11) NOT NULL,
  `contestant_id` varchar(100) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `votes` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `vote_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `fk_categories_events` (`event_id`);

--
-- Indexes for table `contestants`
--
ALTER TABLE `contestants`
  ADD PRIMARY KEY (`contestant_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `host_id` (`host_id`);

--
-- Indexes for table `hosts`
--
ALTER TABLE `hosts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD KEY `contestant_id` (`contestant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hosts`
--
ALTER TABLE `hosts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_events` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);

--
-- Constraints for table `contestants`
--
ALTER TABLE `contestants`
  ADD CONSTRAINT `contestants_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`host_id`) REFERENCES `hosts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
