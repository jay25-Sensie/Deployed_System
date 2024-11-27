-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 09:09 AM
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
(22, 10041, '2024-10-15', 'abdominal pain', 'too much soft drinks', 'UTI', 'coamoxiclav 500mg\r\namoxicillin 500mg\r\nurinalysis', ''),
(24, 10044, '2024-10-30', 'Headache started yesterday.\r\nThrobbing sensation on the right side of the head.', 'Blood pressure 120/80, Heart rate 80 beats per minute, Temperature 98.6 degrees Fahrenheit. \r\nTenderness on palpation of the right temple.', 'Migrane', 'Ibuprofen 500mg\r\nParacetamol 500mg', ''),
(25, 10041, '2024-10-30', 'Urinary frequency, urgency, and dysuria for the past 24 hours.', 'Appears well-nourished and in no apparent distress', 'Urinary Tract Infection (UTI), likely uncomplicated.', 'Nitrofurantoin 100mg\r\nUrinalysis', ''),
(29, 10034, '2024-11-07', 'su', 'ob', 'as', 'pl', '');

-- --------------------------------------------------------

--
-- Table structure for table `dose_timings`
--

CREATE TABLE `dose_timings` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `dose_time` time NOT NULL,
  `meal_time` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1008, '10045', 'uploads/DFD level 1.png', '2024-11-05 12:48:54'),
(1010, '10044', 'uploads/123.docx', '2024-11-07 02:51:02'),
(1011, '10034', 'uploads/background.jpeg', '2024-11-12 08:10:33');

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
  `meal_timing` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_schedule`
--

INSERT INTO `medicine_schedule` (`id`, `pid`, `medicine_name`, `doses_per_day`, `dose_timing_1`, `dose_timing_2`, `dose_timing_3`, `dose_timing_4`, `dose_timing_5`, `meal_timing`, `created_at`, `updated_at`) VALUES
(125, 10034, 'paracetamol', 1, '22:00:00', NULL, NULL, NULL, NULL, 1, '2024-11-15 13:32:51', '2024-11-15 13:32:51'),
(126, 10034, 'paracetamol', 1, '22:00:00', NULL, NULL, NULL, NULL, 1, '2024-11-15 13:44:58', '2024-11-15 13:44:58');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset`
--

CREATE TABLE `password_reset` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expiry` datetime NOT NULL,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_reset`
--

INSERT INTO `password_reset` (`id`, `user_id`, `token`, `expiry`, `phone_number`) VALUES
(12, 1088, 'e9639557fa60021e147cf347d92c5a8e463592ed56e753b3d56a5cb55fa6bd5b18de7aa6a1577291ed0caf4ab490cafe0f2d', '2024-11-18 14:52:22', ''),
(13, 1088, 'afdca0081cda6cdf8dd5bf6f2d1ebfb7996336fb1680f3ead9ce185061c6dd73e50e940eb58593cc19f27961313de6b09012', '2024-11-18 15:02:50', ''),
(15, 1088, '7bcdc957c7c2dee7941a7ea9a9733213becd13b08c2ad44758a197cfae465ca9827f7f4925b8d7c0450665f9ce3051fab9fe', '2024-11-19 14:28:46', ''),
(16, 1088, 'd9ffe75c9bb2a5e608df5c486855f5ccb712908f1fb3c4210dbb2f01d0122969fb54bd3fb722778a2423fef97ed53bb4257c', '2024-11-19 14:29:06', ''),
(17, 1088, '8f5f399cc6ee0e3686e9ee311832720167cb7eadc2098329808eff62e6a1caa3fff73b61cf07936dacbbee64ef11d2236edd', '2024-11-19 14:31:03', ''),
(18, 1088, '9bac96d65fd6f523034b3afad963a1704785b84f0d47e94ffee61ea1714bce93717efa190e2bc7f2f2cde2bc21abedc565ac', '2024-11-21 03:29:07', ''),
(20, 1088, 'c61ac23250ac046f39a9b3973adfee7fb007b648320ae4f6c596ee100fe7ec6588cadb7ac4d74a8f5221bfbc3a5ed356191d', '2024-11-21 03:45:10', ''),
(21, 1088, '83e6471fcdccc06daaec508d1e16b2345c033173dc9094254fa74fc119cb55c8d135213f37ca349dd9405653372a80b165a6', '2024-11-21 03:45:48', '');

-- --------------------------------------------------------

--
-- Table structure for table `patient_records`
--

CREATE TABLE `patient_records` (
  `pid` int(20) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `lastname` varchar(25) DEFAULT NULL,
  `brgy` varchar(100) NOT NULL,
  `municipality` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
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

INSERT INTO `patient_records` (`pid`, `name`, `lastname`, `brgy`, `municipality`, `province`, `address`, `age`, `birthday`, `phone_number`, `gender`, `status`) VALUES
(10034, 'Anya', 'Forger', 'Zone 1', 'Bulan', 'Sorsogon', 'Zone 1', 7, '2017-10-10', '+639455543566', 'Female', 'Active'),
(10037, 'Rosalina', 'Bohol', 'Dolos', 'Bulan', 'Sorsogon', 'Dolos Bulan Sorsogon', 37, '1986-12-08', '+639499673374', 'Female', 'Active'),
(10038, 'Christian', 'Paras', 'Zone 6', 'Bulan', 'Sorsogon', 'Zone 6', 22, '2002-08-16', '+639519324908', 'Male', 'Active'),
(10039, 'Jon', 'Doe', 'Obrero', 'Bulan', 'Sorsogon', 'Obrero', 46, '1978-09-23', '+639601952132', 'Male', 'Active'),
(10040, 'Alberto', 'Dimaano', 'A. Bonifacio', 'Bulan', 'Sorsogon', 'bonifacio', 40, '1984-07-04', '+639925497331', 'Male', 'Active'),
(10041, 'Lyka', 'Ocampo', 'Biton', 'Magallanes', 'Sorsogon', 'Biton, Magallanes Sorsogon', 21, '2003-08-12', '+639609029244', 'Female', 'Active'),
(10042, 'Justine', 'Smith', 'Antipolo', 'Bulan', 'Sorsogon', 'Antipolo', 34, '1990-01-01', '+639123456789', 'Male', 'Active'),
(10043, 'Johnny', 'Domingo', 'Otavi', 'Bulan', 'Sorsogon', '123 Main St', 37, '1987-01-01', '+639123456712', 'Male', 'Active'),
(10044, 'Akiesha', 'Roque', 'Danao', 'Bulan', 'Sorsogon', 'Danao, Bulan, Sorsogon', 21, '2003-10-14', '+639345629451', 'Female', 'Active'),
(10045, 'Shajara', 'Ronao', 'Namo', 'Bulan', 'Sorsogon', 'Namo ', 21, '2003-10-06', '+639609029248', 'Female', 'Active'),
(10046, 'Jayric', 'Espadero', 'Santa Remedos', 'Bulan', 'Sorsogon', 'Santa Remedios', 21, '2003-03-25', '+639455543568', 'Male', 'Active');

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
-- Table structure for table `sms_notification`
--

CREATE TABLE `sms_notification` (
  `id` int(11) NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `message` text NOT NULL,
  `status` enum('Sent','Failed') NOT NULL,
  `sent_time` datetime NOT NULL
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
  `ht` decimal(5,2) NOT NULL,
  `time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vital_signs`
--

INSERT INTO `vital_signs` (`id`, `pid`, `date`, `bp`, `cr`, `rr`, `t`, `wt`, `ht`, `time`) VALUES
(27, 10034, '2024-11-04', '120/80', 71, 17, '34.00', '44.00', '147.00', '16:28:14'),
(28, 10037, '2024-11-04', '130/90', 74, 19, '33.00', '54.00', '152.00', '16:31:03'),
(29, 10038, '2024-11-05', '100/80', 72, 18, '35.00', '57.00', '153.00', '13:36:18'),
(31, 10038, '2024-11-05', '120/80', 76, 18, '35.00', '56.00', '154.00', '13:38:36'),
(32, 10037, '2024-11-05', '100/80', 76, 16, '34.00', '55.00', '153.00', '13:39:29'),
(33, 10039, '2024-11-05', '120/90', 72, 19, '34.00', '52.00', '153.00', '13:41:31'),
(34, 10040, '2024-11-05', '120/80', 76, 20, '34.00', '58.00', '157.00', '13:42:22'),
(35, 10041, '2024-11-05', '100/80', 74, 19, '35.00', '51.00', '154.00', '13:43:04'),
(36, 10042, '2024-11-05', '130/90', 76, 17, '33.00', '54.00', '161.00', '13:43:59'),
(37, 10043, '2024-11-05', '130/90', 76, 20, '35.10', '55.00', '149.00', '13:44:44'),
(38, 10044, '2024-11-05', '120/80', 80, 21, '98.60', '50.00', '158.00', '13:45:40'),
(39, 10045, '2024-11-05', '130/90', 77, 21, '36.00', '52.00', '155.00', '13:46:40'),
(40, 10040, '2024-11-12', '100/80', 71, 18, '34.00', '58.00', '157.00', '09:08:12'),
(41, 10040, '2024-11-12', '100/80', 71, 18, '34.00', '58.00', '157.00', '09:08:14');

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
-- Indexes for table `dose_timings`
--
ALTER TABLE `dose_timings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_id` (`schedule_id`);

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
-- Indexes for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patient_records`
--
ALTER TABLE `patient_records`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `sms_notification`
--
ALTER TABLE `sms_notification`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `dose_timings`
--
ALTER TABLE `dose_timings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1012;

--
-- AUTO_INCREMENT for table `medications`
--
ALTER TABLE `medications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine_schedule`
--
ALTER TABLE `medicine_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `password_reset`
--
ALTER TABLE `password_reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `patient_records`
--
ALTER TABLE `patient_records`
  MODIFY `pid` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10049;

--
-- AUTO_INCREMENT for table `sms_notification`
--
ALTER TABLE `sms_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_notifications`
--
ALTER TABLE `sms_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1091;

--
-- AUTO_INCREMENT for table `vital_signs`
--
ALTER TABLE `vital_signs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD CONSTRAINT `diagnosis_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `patient_records` (`pid`);

--
-- Constraints for table `dose_timings`
--
ALTER TABLE `dose_timings`
  ADD CONSTRAINT `dose_timings_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `medicine_schedule` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD CONSTRAINT `password_reset_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userID`);

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
