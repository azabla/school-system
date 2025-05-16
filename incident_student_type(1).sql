-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2024 at 08:54 AM
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
-- Table structure for table `incident_student_type`
--

CREATE TABLE `incident_student_type` (
  `id` int(11) NOT NULL,
  `stuid` varchar(255) DEFAULT NULL,
  `incident_type` varchar(255) NOT NULL,
  `incident_id` int(50) NOT NULL,
  `academicyear` varchar(50) DEFAULT NULL,
  `date_inserted` varchar(255) DEFAULT NULL,
  `inserted_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `incident_student_type`
--

INSERT INTO `incident_student_type` (`id`, `stuid`, `incident_type`, `incident_id`, `academicyear`, `date_inserted`, `inserted_by`) VALUES
(41, 'ENS/2013/00465', 'Alcohol', 82, '2013', '20/11/2024', 'joss'),
(42, 'ENS/2013/00465', 'Alcohol', 83, '2013', '20/11/2024', 'joss'),
(43, 'ENS/2013/00465', 'Drug', 83, '2013', '20/11/2024', 'joss'),
(44, 'ENS/2013/00465', 'red', 83, '2013', '20/11/2024', 'joss'),
(47, 'EEA/2016/0700', 'Alcohol', 86, '2013', '20/11/2024', 'joss'),
(48, 'ENS/2013/00465', 'Alcohol', 87, '2013', '28/11/2024', 'joss'),
(49, 'ENS/2013/00465', 'Alcohol g', 88, '2013', '28/11/2024', 'joss'),
(50, 'ENS/2013/00465', 'Alcohol drink', 89, '2013', '28/11/2024', 'Alem');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `incident_student_type`
--
ALTER TABLE `incident_student_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `incident_student_type`
--
ALTER TABLE `incident_student_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
