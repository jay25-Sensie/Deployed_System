-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2024 at 03:22 PM
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
(19, 10037, '2024-09-23', 'nervousness', 'normal', 'apd', 'prescription', ''),
(20, 10039, '2024-09-26', 'subjective', 'objective', 'assessment', 'plan', ''),
(21, 10040, '2024-09-28', 'sub', 'ob', 'asses', 'plan', '');

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
-- Table structure for table `medications`
--

CREATE TABLE `medications` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dosage` varchar(50) DEFAULT NULL,
  `frequency` enum('Once a Day','Twice a Day','Three Times a Day','Four Times a Day','Five Times a Day') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine_schedule`
--

CREATE TABLE `medicine_schedule` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `doses_per_day` int(11) NOT NULL,
  `dose_timing_1` time DEFAULT NULL,
  `dose_timing_2` time DEFAULT NULL,
  `dose_timing_3` time DEFAULT NULL,
  `dose_timing_4` time DEFAULT NULL,
  `dose_timing_5` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_schedule`
--

INSERT INTO `medicine_schedule` (`id`, `pid`, `medicine_name`, `doses_per_day`, `dose_timing_1`, `dose_timing_2`, `dose_timing_3`, `dose_timing_4`, `dose_timing_5`, `created_at`, `updated_at`) VALUES
(44, 10039, 'paracetamol', 4, '07:00:00', '12:00:00', '17:00:00', '19:00:00', NULL, '2024-09-28 14:47:50', '2024-09-28 14:47:50'),
(65, 10040, 'par', 2, '09:14:00', '21:14:00', NULL, NULL, NULL, '2024-09-29 13:14:34', '2024-09-29 13:14:34'),
(66, 10040, 'mef', 1, '09:14:00', NULL, NULL, NULL, NULL, '2024-09-29 13:14:37', '2024-09-29 13:14:37');

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
(10034, 'Anya', 'Forger', 'tokyo', 6, '2017-10-10', '09499673374', 'Female', 'Active'),
(10037, 'Rosalina', 'Bohol', 'Dolos Bulan Sorsogon', 37, '1986-12-08', '09499673374', 'Female', 'Active'),
(10038, 'Juan Francisco', 'García Flores', 'zone 2', 46, '1978-02-06', '09555342840', 'Male', 'Active'),
(10039, 'Jon', 'Doe', '1600 Fake Street', 46, '1978-09-23', '09601952132', 'Male', 'Active'),
(10040, 'Alberto', 'Dimaano', 'Brgy. Lagalag', 40, '1984-07-04', '09499673466', 'Male', 'Active');

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

-- --------------------------------------------------------

--
-- Table structure for table `sms_notifications`
--

CREATE TABLE `sms_notifications` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `medication_id` int(11) NOT NULL,
  `notification_time` datetime NOT NULL,
  `status` tinyint(4) DEFAULT 0 CHECK (`status` in (0,1)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1041, '10037', '$2y$10$HMKK8tB.hyfA5h1lpVtCrOKrFCQ94JI7S3LUzregxGqjVb4ke6p4y', 'patient'),
(1042, '10038', '$2y$10$ggDrLYkuHIYwqzhwmb.ee.lMZeWFfIMG.81ybXrQpVrmlGFx5LXxm', 'patient'),
(1043, '10039', '$2y$10$NJMdXiZSSrwWfYrhCnO/augFs5V.irNrFJvM0nJv4pB.2i9Mck6c.', 'patient'),
(1044, '10040', '$2y$10$6RYB2sAuGDfAfRcHW2zi/eIYwZWtpm/QOifomWmAgJntoNPILD5A6', 'patient');

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
(10, 10037, '2024-09-23', '140/90', 76, 0, '0.00', '0.00', '0.00');

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
-- Indexes for table `medications`
--
ALTER TABLE `medications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine_schedule`
--
ALTER TABLE `medicine_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_records`
--
ALTER TABLE `patient_records`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `sms_notifications`
--
ALTER TABLE `sms_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `medication_id` (`medication_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1007;

--
-- AUTO_INCREMENT for table `medications`
--
ALTER TABLE `medications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine_schedule`
--
ALTER TABLE `medicine_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `patient_records`
--
ALTER TABLE `patient_records`
  MODIFY `pid` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10041;

--
-- AUTO_INCREMENT for table `sms_notifications`
--
ALTER TABLE `sms_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1045;

--
-- AUTO_INCREMENT for table `vital_signs`
--
ALTER TABLE `vital_signs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD CONSTRAINT `diagnosis_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `patient_records` (`pid`);

--
-- Constraints for table `sms_notifications`
--
ALTER TABLE `sms_notifications`
  ADD CONSTRAINT `sms_notifications_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient_records` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `sms_notifications_ibfk_2` FOREIGN KEY (`medication_id`) REFERENCES `medications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vital_signs`
--
ALTER TABLE `vital_signs`
  ADD CONSTRAINT `vital_signs_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `patient_records` (`pid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
