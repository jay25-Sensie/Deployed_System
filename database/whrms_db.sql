-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2024 at 04:24 AM
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
-- Database: `whrms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `diagnosis`
--

CREATE TABLE `diagnosis` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `date` date NOT NULL,
  `subjective` text NOT NULL,
  `objective` text NOT NULL,
  `assessment` text NOT NULL,
  `plan` text NOT NULL,
  `laboratory` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diagnosis`
--

INSERT INTO `diagnosis` (`id`, `pid`, `date`, `subjective`, `objective`, `assessment`, `plan`, `laboratory`) VALUES
(1, 10017, '2024-09-13', 'subject', 'objective', 'assessment', 'plan', 'Lab test'),
(3, 10014, '2024-09-13', 'sub', 'ob', 'as', 'pl', ''),
(4, 10015, '2024-09-14', 'n/a', 'n/a', 'n/a', 'n/a', ''),
(9, 10016, '2024-09-14', 'none', 'none', 'none', 'none', ''),
(13, 10018, '2024-09-14', 'blank', 'blank', 'blank', 'blank', 'Lab test');

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL,
  `pid` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`id`, `pid`, `file_path`, `upload_date`) VALUES
(1000, '10014', 'uploads/132-1329984_attack-on-titan-transparent-images-attack-on-titan.jpg', '2024-09-05 16:35:31'),
(1002, '10015', 'uploads/PHP.png', '2024-09-05 17:07:58'),
(1003, '10016', 'uploads/html.png', '2024-09-10 13:26:50'),
(1004, '10018', 'uploads/FB_IMG_1703419245972.jpg', '2024-09-14 14:21:55');

-- --------------------------------------------------------

--
-- Table structure for table `patient_records`
--

CREATE TABLE `patient_records` (
  `pid` int(20) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `lastname` varchar(25) DEFAULT NULL,
  `address` varchar(30) DEFAULT NULL,
  `age` int(25) NOT NULL,
  `birthday` varchar(20) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_records`
--

INSERT INTO `patient_records` (`pid`, `name`, `lastname`, `address`, `age`, `birthday`, `phone_number`, `gender`, `status`) VALUES
(10014, 'Tanjiro', 'Kamado', 'Demon Slayer', 22, '2002-03-25', '09499673466', 'Male', 'Active'),
(10015, 'kugisaki', 'nobara', 'shibuya', 22, '2002-04-08', '09499673324', 'Female', 'Active'),
(10016, 'nezuko', 'Kamado', 'Demon Slayer', 17, '2007-01-15', '09499673374', 'Female', 'Active'),
(10017, 'yugi', 'itadori', 'tokyo', 22, '2002-03-08', '09499673467', 'Male', 'Active'),
(10018, 'Anya ', 'Forger', 'spy x family', 8, '2017-05-08', '09499673556', 'Female', 'Active'),
(10019, 'satoru', 'gojo', 'tokyo', 25, '1998-12-01', '09499673556', 'Male', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions_data`
--

CREATE TABLE `prescriptions_data` (
  `id` int(20) NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `medicine_name` varchar(30) NOT NULL,
  `dosage` varchar(20) NOT NULL,
  `frequency` varchar(25) NOT NULL,
  `time_to_take` varchar(20) NOT NULL,
  `archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions_data`
--

INSERT INTO `prescriptions_data` (`id`, `pid`, `medicine_name`, `dosage`, `frequency`, `time_to_take`, `archived`) VALUES
(1006, 10014, 'Amoxiciline', '500', '2 times a day', '8-1-5', 1),
(1008, 10015, 'paracetamol', '500', '3 times a day', '8-1-5', 0),
(1010, 10016, 'mefinamic', '300', '2 times a day', '7-12-7', 0),
(1011, 10017, 'Amoxiciline', '500', '2 times a day', '8-1-5', 0),
(1012, 10018, 'vitamin', '100', '2 times a day', '7-12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `password`, `role`) VALUES
(1000, 'admin@gmail.com', '$2y$10$nGy10FTed7YmzdSIUbw2SOivBKsVFTL4X462JWmOWe09WFm7BOL0u', 'admin'),
(1004, 'doctor@gmail.com', '$2y$10$9ePWCO0ZVZG4/RjOoPUNEe64TcnX4semp3tB7p22s6Kiw89MxBhR2', 'doctor'),
(1017, '10014', '$2y$10$pxfEi9DEa3dg7wYoyQnG/uE0NKTLKfBvjlp/o7R9I7bsPQbEC1PWC', 'patient'),
(1018, '10015', '$2y$10$7Dw3D9nHylHje02rw806DeGVCKEL.K8g7LGiVDrakGQyrLPuXzgx.', 'patient'),
(1019, '10016', '$2y$10$ZYhtUjAWgBKUoF15EXKvJuqp8nRFexbz2B.BuJOBmAxxSUisuKbl.', 'patient'),
(1020, 'user@gmail.com', '$2y$10$aY5vUPKzQeSypQoslWhqJegufNWtB0K1hdFNcGWhibenrIrLWew0O', 'admin'),
(1021, '10017', '$2y$10$1bNmxdmCjhKWCHyl2uzCT.9g7ysEQzcdOUrkUX43OJDkXQaQvQs5e', 'patient'),
(1022, '10018', '$2y$10$DsEUMAWFRcOgM8VY18GhUuXPx2XYhqrch4PlbsuaShUGuGWSZfIRe', 'patient'),
(1023, '10019', '$2y$10$ZUZ708QIm8zw2Esa7skfSOZMCowUB.azpbyTiJDzHFUtxh0KSHasC', 'patient');

-- --------------------------------------------------------

--
-- Table structure for table `vital_signs`
--

CREATE TABLE `vital_signs` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `date` date NOT NULL,
  `bp` varchar(10) NOT NULL,
  `cr` int(11) NOT NULL,
  `rr` int(11) NOT NULL,
  `t` decimal(4,2) NOT NULL,
  `wt` decimal(5,2) NOT NULL,
  `ht` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vital_signs`
--

INSERT INTO `vital_signs` (`id`, `pid`, `date`, `bp`, `cr`, `rr`, `t`, `wt`, `ht`) VALUES
(1, 10015, '2024-09-13', '120/80', 72, 16, '98.60', '70.00', '170.00'),
(2, 10014, '2024-09-13', '100/80', 76, 18, '95.60', '60.00', '180.00'),
(4, 10016, '2024-09-13', '120/90', 75, 16, '98.60', '61.00', '175.00'),
(5, 10017, '2024-09-13', '120/80', 79, 19, '94.60', '65.00', '185.00'),
(6, 10019, '2024-09-14', '100/80', 75, 18, '98.60', '65.00', '185.00'),
(7, 10018, '2024-09-14', '100/80', 76, 16, '95.60', '60.00', '170.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_records`
--
ALTER TABLE `patient_records`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `prescriptions_data`
--
ALTER TABLE `prescriptions_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `vital_signs`
--
ALTER TABLE `vital_signs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `diagnosis`
--
ALTER TABLE `diagnosis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1005;

--
-- AUTO_INCREMENT for table `patient_records`
--
ALTER TABLE `patient_records`
  MODIFY `pid` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10020;

--
-- AUTO_INCREMENT for table `prescriptions_data`
--
ALTER TABLE `prescriptions_data`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1013;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1024;

--
-- AUTO_INCREMENT for table `vital_signs`
--
ALTER TABLE `vital_signs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD CONSTRAINT `diagnosis_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `patient_records` (`pid`);

--
-- Constraints for table `vital_signs`
--
ALTER TABLE `vital_signs`
  ADD CONSTRAINT `vital_signs_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `patient_records` (`pid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
