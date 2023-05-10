-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2023 at 08:51 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_account`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabungin_user`
--

CREATE TABLE `tabungin_user` (
  `ID_User` int(11) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Tanggal` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabungin_user`
--

INSERT INTO `tabungin_user` (`ID_User`, `Username`, `Password`, `Tanggal`) VALUES
(1, 'Gojo', '$2y$10$hDeE0teT/lK/UJfCge2GyuYTKC1xFLr3yGVYLHKjArZ2PfLTcW.A.', '2023-04-28 12:50:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabungin_user`
--
ALTER TABLE `tabungin_user`
  ADD PRIMARY KEY (`ID_User`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabungin_user`
--
ALTER TABLE `tabungin_user`
  MODIFY `ID_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
