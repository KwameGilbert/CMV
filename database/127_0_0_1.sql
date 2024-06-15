-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2024 at 10:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_36570237_voting_db`
--
CREATE DATABASE IF NOT EXISTS `if0_36570237_voting_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `if0_36570237_voting_db`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `event_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `event_id`) VALUES
(1, 'MOST PROMISING STUDENT', 1),
(2, 'BEST SINGER', 2);

-- --------------------------------------------------------

--
-- Table structure for table `contestants`
--

CREATE TABLE `contestants` (
  `contestant_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `contestant_name` varchar(255) NOT NULL,
  `votes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contestants`
--

INSERT INTO `contestants` (`contestant_id`, `category_id`, `contestant_name`, `votes`) VALUES
(1, 1, 'Micheal Adum', 661),
(2, 1, 'Godfred Essuman', 3),
(3, 2, 'Gilbert Kukah', 200),
(4, 2, 'Derrick Agyeman', 1511);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `vote_id` int(11) NOT NULL,
  `contestant_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `votes` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`vote_id`, `contestant_id`, `first_name`, `last_name`, `email`, `votes`, `timestamp`) VALUES
(18, 2, 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 1, '2024-05-16 21:30:20'),
(19, 1, 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 8, '2024-05-16 21:45:17'),
(20, 1, 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 99, '2024-05-16 21:47:46'),
(21, 1, 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 2, '2024-05-16 21:53:09'),
(22, 4, 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 700, '2024-05-16 21:53:38'),
(23, 4, 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 22, '2024-05-16 21:59:50'),
(24, 2, 'Gilbert', 'Kukah', 'kwamegilbert1114@gmail.com', 2, '2024-05-16 22:27:54'),
(25, 3, 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 200, '2024-05-16 22:50:37'),
(26, 4, 'Desmond Owusu', 'Ogyedom', 'kwamegilbert1114@gmail.com', 789, '2024-05-16 22:52:56'),
(27, 1, 'Gilbert Elikplim', 'Kukah', 'kwamegilbert1114@gmail.com', 552, '2024-05-18 02:26:32');

--
-- Triggers `votes`
--
DELIMITER $$
CREATE TRIGGER `update_contestant_votes` AFTER INSERT ON `votes` FOR EACH ROW BEGIN
    UPDATE contestants
    SET votes = (
        SELECT SUM(votes)
        FROM votes
        WHERE contestant_id = NEW.contestant_id
    )
    WHERE contestant_id = NEW.contestant_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `voting_events`
--

CREATE TABLE `voting_events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_host` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voting_events`
--

INSERT INTO `voting_events` (`event_id`, `event_name`, `event_date`, `event_host`, `description`, `created_at`) VALUES
(1, 'INFOTESS AWARDS', '2024-05-31', 'INFOTESS', 'INFOTESS', '2024-05-16 23:04:54'),
(2, 'MASA AWARDS', '2024-05-30', 'MASA', NULL, '2024-05-16 23:04:54');

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
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD KEY `contestant_id` (`contestant_id`);

--
-- Indexes for table `voting_events`
--
ALTER TABLE `voting_events`
  ADD PRIMARY KEY (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contestants`
--
ALTER TABLE `contestants`
  MODIFY `contestant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `voting_events`
--
ALTER TABLE `voting_events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_events` FOREIGN KEY (`event_id`) REFERENCES `voting_events` (`event_id`);

--
-- Constraints for table `contestants`
--
ALTER TABLE `contestants`
  ADD CONSTRAINT `contestants_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`contestant_id`) REFERENCES `contestants` (`contestant_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
