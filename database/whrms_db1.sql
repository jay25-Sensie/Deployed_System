-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2024 at 04:14 AM
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
(16, 10034, '2024-09-19', 'subjective', 'objective', 'assesment', 'plan', 'lab test'),
(17, 10035, '2024-09-20', 'cardiac', 'none', 'none', 'none', 'Lab test'),
(18, 10035, '2024-09-20', 'null', 'null', 'null', 'null', '');

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
(1006, '10035', 'uploads/DFD level 1.png', '2024-09-22 02:00:49');

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
(10034, 'Anya', 'Forger', 'Spy x Family', 7, '2016-10-10', '09499673374', 'Female', 'Active'),
(10035, 'Tanjiro', 'Kamado', 'Demon Slayer', 22, '2002-03-08', '09499673556', 'Male', 'Active'),
(10036, 'Gojo', 'Saturo', 'Shibuya', 25, '1999-03-31', '09499673374', 'Male', 'Active');

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
(1026, 10036, 'paracetamol', '400', '3 times a day', '07:00,12:00,17:00', 0),
(1027, 10035, 'mefinamic', '300', '2 times a day', '11:38,11:39,11:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_sms`
--

CREATE TABLE `scheduled_sms` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `prescription_id` int(11) NOT NULL,
  `time` time NOT NULL,
  `frequency` varchar(50) NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sent` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scheduled_sms`
--

INSERT INTO `scheduled_sms` (`id`, `pid`, `prescription_id`, `time`, `frequency`, `end_date`, `created_at`, `sent`) VALUES
(28, 10036, 1026, '07:00:00', '3 times a day', '2024-10-04', '2024-09-20 13:45:59', 0),
(31, 10035, 1027, '11:38:00', '2 times a day', '2024-10-04', '2024-09-20 15:37:19', 0);

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
(1038, '10034', '$2y$10$C8BWl6PorsUL.nSBl92qq.RqvVR2tG9hVk.20ZywK3MQZ7gJr0dnS', 'patient'),
(1039, '10035', '$2y$10$wlG9zwLfesDUNeQHN/kLhemZNchclb/dnbdBKTGWYGe34zc297VNS', 'patient'),
(1040, '10036', '$2y$10$O.bBKOloNX2MTXgvATQkiOpfIs/R1DoJAhn/zC79rz8nWBz6wA6Rq', 'patient');

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
-- Indexes for table `scheduled_sms`
--
ALTER TABLE `scheduled_sms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescription_id` (`prescription_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1007;

--
-- AUTO_INCREMENT for table `patient_records`
--
ALTER TABLE `patient_records`
  MODIFY `pid` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10037;

--
-- AUTO_INCREMENT for table `prescriptions_data`
--
ALTER TABLE `prescriptions_data`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1041;

--
-- AUTO_INCREMENT for table `scheduled_sms`
--
ALTER TABLE `scheduled_sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1041;

--
-- AUTO_INCREMENT for table `vital_signs`
--
ALTER TABLE `vital_signs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD CONSTRAINT `diagnosis_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `patient_records` (`pid`);

--
-- Constraints for table `scheduled_sms`
--
ALTER TABLE `scheduled_sms`
  ADD CONSTRAINT `scheduled_sms_ibfk_1` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions_data` (`id`);

--
-- Constraints for table `vital_signs`
--
ALTER TABLE `vital_signs`
  ADD CONSTRAINT `vital_signs_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `patient_records` (`pid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
