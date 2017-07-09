-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2016 at 04:52 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s225395`
--

-- --------------------------------------------------------

DROP TABLE IF EXISTS `reservations`;

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `Id` int(10) NOT NULL,
  `Username` varchar(40) NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `Duration` int(11) NOT NULL,
  `Machine` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`Id`, `Username`, `StartTime`, `EndTime`, `Duration`, `Machine`) VALUES
(1, 'u1@p.it', '00:00:00', '01:00:00', 60, 1),
(2, 'u1@p.it', '01:00:00', '01:20:00', 20, 1),
(3, 'u2@p.it', '12:00:00', '12:30:00', 30, 1),
(4, 'u2@p.it', '04:00:00', '04:10:00', 10, 1),
(5, 'u3@p.it', '19:30:00', '21:30:00', 120, 1),
(6, 'u3@p.it', '23:00:00', '23:15:00', 15, 1),
(7, 'u1@p.it', '15:00:00', '16:30:00', 90, 1),
(8, 'u2@p.it', '16:00:00', '17:00:00', 60, 2);

-- --------------------------------------------------------

DROP TABLE IF EXISTS `users`;

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Name` varchar(40) NOT NULL,
  `Surname` varchar(40) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Password` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Name`, `Surname`, `Email`, `Password`) VALUES
('userOne', 'userOne', 'u1@p.it', 'c40699b6732de008be19964b23689aed'),
('userTwo', 'userTwo', 'u2@p.it', '59e26398c57d69d0a5abba2373a6a3b1'),
('userThree', 'userThree', 'u3@p.it', '2c57282f2df949f08d7c9eddfac1655d');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
