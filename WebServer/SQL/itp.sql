-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 21, 2022 at 06:31 PM
-- Server version: 10.5.13-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itp`
--

-- --------------------------------------------------------

--
-- Table structure for table `intervals`
--

CREATE TABLE `intervals` (
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `AWD` int(10) NOT NULL,
  `AMD` int(10) NOT NULL,
  `PL` int(10) NOT NULL,
  `OW` int(10) NOT NULL,
  `admin_override` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `intervals`
--

INSERT INTO `intervals` (`uuid`, `AWD`, `AMD`, `PL`, `OW`, `admin_override`) VALUES
('ac:12:03:57:02:e2', 300, 300, 300, 300, 0),
('b8:27:eb:5a:1d:03', 50, 100, 100, 123, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ping`
--

CREATE TABLE `ping` (
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_connect` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ping`
--

INSERT INTO `ping` (`uuid`, `last_connect`) VALUES
('b8:27:eb:5a:1d:03', '22-07-2022 02:09:56'),
('b8:27:eb:5a:1d:04', '22-07-2022 02:19:29');

-- --------------------------------------------------------

--
-- Table structure for table `proctoring`
--

CREATE TABLE `proctoring` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trigger_count` int(10) NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` varchar(8000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_time` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proctoring`
--

INSERT INTO `proctoring` (`id`, `uuid`, `trigger_count`, `category`, `data`, `date_time`) VALUES
(386, 'ac:12:03:57:02:e2', 2, 'OW', 'telegram, hello', '2022-07-21 12:20:13'),
(387, 'ac:12:03:57:02:e2', 2, 'OW', 'telegram, hello', '2022-07-21 12:20:51'),
(388, 'ac:12:03:57:02:e2', 2, 'OW', 'telegram, hello', '2022-07-21 12:21:02'),
(389, 'ac:12:03:57:02:e2', 2, 'Open Windows', 'telegram, hello', '2022-07-21 12:24:38'),
(390, 'ac:12:03:57:02:e2', 2, 'Open Windows (OW)', 'telegram, hello.exe', '2022-07-21 12:39:44'),
(391, 'ac:12:03:57:02:e2', 2, 'Open Windows (OW)', 'telegram, discord.exe', '2022-07-21 12:39:47'),
(392, 'ac:12:03:57:02:e2', 2, 'Open Windows (OW)', 'telegram, hello', '2022-07-21 12:52:39'),
(393, 'b8:27:eb:5a:1d:03', 0, 'Active Windows Detection (AWD)', 'p\0r\0o\0c\0t\0o\0r\0i\0n\0g\0_\0s\0c\0r\0i\0p\0t\0_\0v\04\0.\0p\0s\01\0 \0-\0 \0V\0i\0s\0u\0a\0l\0 \0S\0t\0u\0d\0i\0o\0 \0C\0o\0d\0e\0', '21-07-2022 22:38:26'),
(394, 'b8:27:eb:5a:1d:03', 0, 'Process List (PL)', '', '21-07-2022 22:38:27'),
(395, 'b8:27:eb:5a:1d:03', 0, 'Active Monitor Detection (AMD)', '2\0', '21-07-2022 22:38:27'),
(396, 'ac:12:03:57:02:e2', 2, 'Open Windows (OW)', 'telegram, hello', '22-07-2022 01:59:29'),
(397, 'b8:27:eb:5a:1d:03', 0, '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `intervals`
--
ALTER TABLE `intervals`
  ADD PRIMARY KEY (`uuid`);

--
-- Indexes for table `proctoring`
--
ALTER TABLE `proctoring`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `proctoring`
--
ALTER TABLE `proctoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=399;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
