-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 07, 2024 at 12:11 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Orbit`
--

-- --------------------------------------------------------

--
-- Table structure for table `BlackList`
--

CREATE TABLE `BlackList` (
  `id` int(11) NOT NULL,
  `uid` text NOT NULL,
  `ip` text NOT NULL,
  `reason` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiration` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Grain`
--

CREATE TABLE `Grain` (
  `id` int(11) NOT NULL,
  `uid` text NOT NULL,
  `redirectTo` text NOT NULL,
  `secret` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Grain`
--

INSERT INTO `Grain` (`id`, `uid`, `redirectTo`, `secret`) VALUES
(1, '0VKYNzTEDO', 'login.php', 'bVHXumwTiW'),
(2, 'soLqjBZ9R9', 'error.php', 'yuNKXQ3mLY'),
(3, 'GvhetheSO6', 'profile.php', 'KqI0VMY5op'),
(4, 'BqnTWdz5ZW', 'reset_password.php', '8HWmb0Mi5v'),
(5, 'HVDnQXzCHk', 'secure.php', 'fHc1TvkO4C'),
(7, 'PnKP2sD9L6', 'invite.php', '5knPDKM43V'),
(8, 'EbSWiiPrOf', 'invite_generator.php', 'eYd8V7ebNq'),
(9, 'YxzrHsOZOn', 'admin.php', 'uJokCCuTgw'),
(10, 'Djdfdsk4', 'do_not_click.php', 'srfdj4fffdgod'),
(11, 'fjlhsdfsd', 'test.php', 'sdffdfss');

-- --------------------------------------------------------

--
-- Table structure for table `LoggingEntry`
--

CREATE TABLE `LoggingEntry` (
  `id` int(11) NOT NULL,
  `method` text NOT NULL,
  `request` longtext NOT NULL,
  `request2` longtext NOT NULL,
  `input` longtext NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip` text NOT NULL,
  `userAgent` longtext NOT NULL,
  `currentScript` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `LoggingSystemEntry`
--

CREATE TABLE `LoggingSystemEntry` (
  `id` int(11) NOT NULL,
  `reason` longtext NOT NULL,
  `data` longtext NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `username`, `password`) VALUES
(1, 'test', 'test'),
(2, 'admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `BlackList`
--
ALTER TABLE `BlackList`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Grain`
--
ALTER TABLE `Grain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `LoggingEntry`
--
ALTER TABLE `LoggingEntry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `LoggingSystemEntry`
--
ALTER TABLE `LoggingSystemEntry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `BlackList`
--
ALTER TABLE `BlackList`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `Grain`
--
ALTER TABLE `Grain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `LoggingEntry`
--
ALTER TABLE `LoggingEntry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1146;

--
-- AUTO_INCREMENT for table `LoggingSystemEntry`
--
ALTER TABLE `LoggingSystemEntry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=556;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
