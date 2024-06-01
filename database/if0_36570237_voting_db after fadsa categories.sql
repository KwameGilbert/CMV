-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql309.infinityfree.com
-- Generation Time: May 31, 2024 at 04:24 PM
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
(1, 'MOST PROMISING STUDENT', 1, 1),
(2, 'BEST SINGER', 2, 1),
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
(24, 'Best Pals', 3, 1);

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
('SO1', 12, 'Cosmos Twumasi Amaning (Ivy)', 0);

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
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`vote_id`, `contestant_id`, `first_name`, `last_name`, `email`, `votes`, `reference`, `vote_date`) VALUES
(35, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 20, 'Gili769742603', '2024-05-21 06:36:49'),
(36, '2', 'Gilbert Elikplim', 'Kukah', 'jadjei689@gmail.com', 258, 'Gili177996835', '2024-05-21 09:43:30'),
(37, '3', 'Jordan', 'Wealth', 'abj7teen@gmail.com', 1, 'Gili885459768', '2024-05-21 11:34:47'),
(38, '1', 'Martin', 'Kwame', 'agbenyenusemartin@gmail.com', 1, 'Gili896420460', '2024-05-21 13:22:28'),
(39, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 55, 'Gili266597551', '2024-05-22 12:06:12'),
(40, '1', 'Joseph', 'Aban', 'kwamegilbert1114@gmail.com', 25, 'Gili201015493', '2024-05-22 14:01:08'),
(41, '1', 'Jackson ', 'Osei', 'osei@fg.com', 25, 'Gili570431102', '2024-05-23 13:13:39'),
(42, '3', 'Ebenezer ', 'Nyarko ', 'ebenezernyarko806@gmail.com', 1000, 'Gili882818694', '2024-05-23 17:38:00'),
(43, '3', 'Ebenezer ', 'Nyarko ', 'ebenezernyarko806@gmail.com', 1000, 'Gili965577176', '2024-05-23 17:51:20'),
(44, '2', 'Ebenezer ', 'Nyarko ', 'ebenezernyarko806@gmail.com', 1000, 'Gili361468717', '2024-05-23 17:53:07'),
(45, '3', 'Ebenezer ', 'Nyarko ', 'ebenezernyarko806@gmail.com', 5000, 'Gili571014842', '2024-05-23 17:54:05'),
(46, '2', 'Ebenezer ', 'Nyarko ', 'ebenezernyarko806@gmail.com', 823645, 'Gili138802611', '2024-05-23 17:56:26'),
(47, '1', '', '', 'JjhjhjhjSD@jhjhjh.com', 3, 'Gili498517121', '2024-05-26 01:40:02'),
(48, '3', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 4, 'Gili900792728', '2024-05-26 02:02:15'),
(49, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 2, 'Gili961100181', '2024-05-26 19:26:43'),
(50, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 30, 'Gili321573450', '2024-05-27 08:23:34'),
(51, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 1, 'Gili417610224', '2024-05-27 09:46:57'),
(52, '1', 'New Test', 'With Real', 'test@real.com', 1, 'Gili502001746', '2024-05-27 09:53:29'),
(53, '3', 'Gili Test', 'Last Test', 'kwamegilbert1114@gmail.com', 2, 'Gili326339577', '2024-05-27 15:58:18'),
(54, '1', '', '', 'hfxfsfzgfxhfch@hydytdhfdhfdfhxfh.com', 1, 'Gili55625501', '2024-05-27 17:56:19'),
(55, '1', 'Ninson', 'Micheal', 'emmanuel@gmail.com', 16, 'Gili957721212', '2024-05-29 17:46:38'),
(56, '1', 'Ninson', 'High', 'gkukah1@gmail.com', 17, 'Gili948031340', '2024-05-29 18:15:31'),
(57, '4', 'Hello', 'Micheal', 'gkukah1@gmail.com', 18, 'Gili223318807', '2024-05-29 18:21:36'),
(58, '3', 'helo', 'jsheh', 'shhs@jej.com', 258, 'Gili143028320', '2024-05-30 05:35:25'),
(59, '2', 'fh', 'yh', 'highrate@gmail.com', 25, 'Gili802546304', '2024-05-30 05:40:12'),
(60, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 58, 'Gili927753177', '2024-05-30 21:45:50'),
(61, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 996, 'Gili167996970', '2024-05-30 21:48:00'),
(62, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 213, 'Gili751982472', '2024-05-30 21:50:48'),
(63, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 277, 'Gili567423203', '2024-05-30 21:54:34'),
(64, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 0, 'Gili568543488', '2024-05-30 21:58:31'),
(65, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 2, 'Gili541996136', '2024-05-30 22:18:02'),
(66, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 20, 'Gili164598277', '2024-05-30 22:25:52'),
(67, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 2, 'Gili287564179', '2024-05-30 22:47:39'),
(68, '1', 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 55, 'Gili987446732', '2024-05-31 07:15:08'),
(69, '3', 'Gilbert', 'Elikplim Kukah', 'kwamegilbert1114@gmail.com', 5, 'Gili551768283', '2024-05-31 07:32:11');

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
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

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
