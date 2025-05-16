-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2024 at 09:36 AM
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
-- Database: `sms`
--

-- --------------------------------------------------------

--
-- Table structure for table `incident_decision`
--

CREATE TABLE `incident_decision` (
  `id` int(11) NOT NULL,
  `stuid` varchar(255) DEFAULT NULL,
  `incident_decision` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `incident_id` int(50) NOT NULL,
  `academicyear` varchar(50) DEFAULT NULL,
  `date_inserted` varchar(255) DEFAULT NULL,
  `inserted_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_decision`
--

INSERT INTO `incident_decision` (`id`, `stuid`, `incident_decision`, `incident_id`, `academicyear`, `date_inserted`, `inserted_by`) VALUES
(4, 'EEA/2016/0700', 'updted', 86, '2013', 'Nov-29-2024', 'joss'),
(5, 'ENS/2013/00465', 'dew', 89, '2013', 'Nov-29-2024', 'joss'),
(6, 'ENS/2013/00465', 'Red incident form', 82, '2013', 'Nov-29-2024', 'joss'),
(7, 'ENS/2013/00465', 'white', 83, '2013', 'Nov-29-2024', 'joss'),
(8, 'ENS/2013/00465', 'g', 87, '2013', 'Nov-29-2024', 'joss');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `incident_decision`
--
ALTER TABLE `incident_decision`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `incident_decision`
--
ALTER TABLE `incident_decision`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
