-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 09:07 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wbhrms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(20) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `password`, `role`) VALUES
(1038, '10034', '$2y$10$C8BWl6PorsUL.nSBl92qq.RqvVR2tG9hVk.20ZywK3MQZ7gJr0dnS', 'patient'),
(1041, '10037', '$2y$10$HMKK8tB.hyfA5h1lpVtCrOKrFCQ94JI7S3LUzregxGqjVb4ke6p4y', 'patient'),
(1042, '10038', '$2y$10$ggDrLYkuHIYwqzhwmb.ee.lMZeWFfIMG.81ybXrQpVrmlGFx5LXxm', 'patient'),
(1043, '10039', '$2y$10$NJMdXiZSSrwWfYrhCnO/augFs5V.irNrFJvM0nJv4pB.2i9Mck6c.', 'patient'),
(1044, '10040', '$2y$10$6RYB2sAuGDfAfRcHW2zi/eIYwZWtpm/QOifomWmAgJntoNPILD5A6', 'patient'),
(1045, '10041', '$2y$10$BPmQwkagDhhZcwtgLF5KJOE57ZchCfADg8U4.A5GyTj784aojiOc2', 'patient'),
(1053, '10042', '$2y$10$FFeEZbGR5kMdu6oTcwaGoOcGONBAHiaw6.6bqyDveiYxAXQFTmfli', 'patient'),
(1057, '10043', '$2y$10$WPe0Tkjma.raUwUAz16e7O.zx73LVbaxCRljuMp143q0J/r/jsOeq', 'patient'),
(1061, '10044', '$2y$10$NNnnDxlVhKUUeFaRHTKTiOYp.bWmhB.mdZu9YHfzejwXtGAbRkUpC', 'patient'),
(1062, '10045', '$2y$10$UcsoON5vWfU7Vq2gQGJIROrZIXR2e1h//S4ldrCWaCu84jQBmsDpG', 'patient'),
(1063, '10046', '$2y$10$Y3A6Vq.930PtutKO21zsHuqE14/QnD5uDwqddwcA.WcK.RyMpYpyq', 'patient'),
(1064, '10047', '$2y$10$oHOGYX2D2tahv6IIoxHsSeybO78jVeHbeSFBZyt2VAR5HW5u8Opmq', 'patient'),
(1088, 'jayric25espadero@gmail.com', '$2y$10$K5hRXUXEHuxvMRU79E4Nguju1wHifxfx.3ZhWazRAhKHHUQ27m//S', 'admin'),
(1090, 'jayoredapse@gmail.com', '$2y$10$vuPGEZRghJznJ9I4lsEmZeey.OfjOIvRC0AQK9oVR6ql52vBsq6uK', 'doctor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1091;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
