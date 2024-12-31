-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2024 at 06:50 PM
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
-- Database: `gatherly`
--

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `reminder_time` varchar(20) NOT NULL DEFAULT '0',
  `reminder_checkbox` varchar(6) DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `start_date`, `end_date`, `event_type`, `description`, `event_name`, `reminder_time`, `reminder_checkbox`) VALUES
(27, '2024-12-13', '2024-12-13', 'Academic', 'test 1', 'crypto test', '0', 'No'),
(28, '2024-12-06', '2024-12-06', 'Personal', 'birthday ayah', 'Birthday Ayah', '0', 'No'),
(29, '2024-12-04', '2024-12-04', 'Academic', 'test 1', 'INTERNETWORK TEST', '0', 'No'),
(30, '2024-12-18', '2024-12-18', 'Entrepreneurship', 'business', 'UTM Business School Auditorium', '1 day', 'Yes'),
(31, '2024-12-20', '2024-12-21', 'Sport', 'sport event', 'frisbee suskom', '0', 'No'),
(32, '2024-12-20', '2024-12-20', 'Academic', 'pcs', 'submission PCS', '0', 'No'),
(33, '2024-12-28', '2024-12-30', 'Volunteering', 'volunteering', 'Bantuan Banjir', '0', 'No'),
(34, '2024-12-08', '2024-12-08', 'Academic', 'test 1', 'AI test', '1 hour', 'Yes'),
(35, '2024-12-30', '2024-12-31', 'Sport', 'cuba aja', 'try', '30 minutes', 'Yes'),
(41, '2025-01-02', '2025-01-02', 'Personal', 'iteration 3 presentation', 'AD Presentation', '3 days', 'Yes'),
(42, '2025-01-07', '2025-01-07', 'Academic', 'Mpk3 8pm', 'SBT2 Internetwork', '4 days', 'Yes'),
(43, '2025-01-02', '2025-01-03', 'Volunteering', '8am until 12 pm', 'blood donation', '3 days', 'Yes'),
(44, '2025-01-04', '2025-01-05', 'Entrepreneurship', 'festival keusahawanan', 'cesco\'24', '4 days', 'Yes');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
