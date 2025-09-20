-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 04, 2025 at 03:23 PM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '12345', 'admin', '2025-03-05 11:19:57');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int NOT NULL,
  `patient_id` int NOT NULL,
  `specialist_id` int NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `status` enum('Pending','Confirmed','Completed','Cancelled') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `specialist_id`, `appointment_date`, `appointment_time`, `message`, `status`, `created_at`) VALUES
(19, 26, 39, '2025-05-10', '10:00 AM', 'Looking forward to acne treatment session.', 'Pending', '2025-04-23 07:02:54'),
(20, 27, 39, '2025-06-11', '2:00 PM', 'Need consultation for skin pigmentation.', 'Pending', '2025-04-23 07:04:34'),
(21, 28, 39, '2025-05-12', '6:00 PM', 'Hyperpigmentation getting worse, need expert help.', 'Pending', '2025-04-23 07:05:26'),
(22, 29, 39, '2025-05-14', '8:00 PM', 'Booking for mole removal consultation.', 'Pending', '2025-04-23 07:06:45'),
(23, 30, 39, '2025-05-15', '6:00 PM', 'Laser hair removal session follow-up.', 'Pending', '2025-04-23 07:07:35'),
(24, 31, 39, '2025-05-17', '10:00 AM', 'Need advice for hair loss treatment.', 'Pending', '2025-04-23 07:09:27'),
(25, 32, 39, '2025-05-18', '13:30', 'Thread lifting procedure discussion.', 'Pending', '2025-04-23 07:11:13');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(5, 'Ayesha Khan', 'ayesha.khan@example.com', 'Skin Treatment Inquiry', 'Hello, I would like to know which treatment is best for acne-prone skin. Please guide me.', '2025-04-22 15:19:29'),
(6, 'Usman Ali', 'usman.ali@example.com', 'Free Consultation Details', 'Hi, I heard about your free consultation offer. Can you tell me how to book it?', '2025-04-22 15:19:29'),
(7, 'Sadia Malik', 'sadia.malik@example.com', 'Feedback on Service', 'Thank you for the wonderful treatment! My skin feels much better. The staff was very friendly too.', '2025-04-22 15:19:29'),
(8, 'Tariq Mehmood', 'tariq.mehmood@example.com', 'Certificate Verification', 'Dear admin, I have uploaded my certificate. Please verify it so I can start offering treatments.', '2025-04-22 15:19:29'),
(9, 'Zoya Ahmed', 'zoya.ahmed@example.com', 'Website Issue', 'I was trying to book an appointment but the form wasn’t submitting. Kindly check the issue.', '2025-04-22 15:19:29');

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `id` int NOT NULL,
  `patient_id` int NOT NULL,
  `diagnosis` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `treatment` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `prescription` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `specialist_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_history`
--

INSERT INTO `medical_history` (`id`, `patient_id`, `diagnosis`, `treatment`, `date`, `prescription`, `specialist_id`) VALUES
(5, 26, 'Acne Vulgaris', 'Topical retinoids and antibiotics', '2025-04-23', 'Adapalene gel at night, Doxycycline 100mg daily', 39),
(6, 29, 'Eczema', 'Moisturizers and corticosteroid cream', '2025-04-24', 'Hydrocortisone 1% cream twice daily, Cetaphil moisturizer', 39),
(7, 30, 'Psoriasis', 'Topical corticosteroids and phototherapy', '2025-04-25', 'Betamethasone cream, twice daily; Light therapy sessions weekly', 39),
(8, 28, 'Melasma', 'Hydroquinone cream and sun protection', '2025-04-26', 'Hydroquinone 4% at night, SPF 50 sunscreen daily', 39);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `cnic` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('patient') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'patient',
  `certificate_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profile_photo_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `certificate_verified` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `first_name`, `last_name`, `username`, `password`, `email`, `mobile`, `cnic`, `state`, `city`, `role`, `certificate_path`, `profile_photo_path`, `created_at`, `certificate_verified`) VALUES
(26, 'Areeba', 'Khan', 'areebakhan', '12345', 'areeba.khan@example.com', '03001234567', '35202-1234567-0', 'Punjab', 'Lahore', 'patient', 'certificates/1745388439_c1.png', 'profile_photos/1745388439_p2.jpeg', '2025-04-23 06:07:19', 0),
(27, 'Fatima', 'Shaikh', 'fatimashaikh', '12345', 'fatima.shaikh@example.com', '03019876543', '42201-9876543-1', 'Sindh', 'Karachi', 'patient', 'certificates/1745388625_c2.jpeg', 'profile_photos/1745388625_p9.jpeg', '2025-04-23 06:10:25', 0),
(28, 'Zainab', 'Raza', 'zainabraza', '12345', 'zainab.raza@example.com', '03111234567', '34101-1122334-5', 'Punjab', 'Faisalabad', 'patient', 'certificates/1745388844_c3.jpeg', 'profile_photos/1745388844_p3.jpeg', '2025-04-23 06:14:04', 0),
(29, 'Mehwish', 'Ali', 'mehwishali', '12345', 'mehwish.ali@example.com', '03334567891', '61101-2233445-2', 'Khyber Pakhtunkhwa', 'Peshawar', 'patient', 'certificates/1745389090_c4.jpeg', 'profile_photos/1745389090_p1.jpeg', '2025-04-23 06:18:10', 0),
(30, 'Hira', 'Yousuf', 'hirayousuf', '12345', 'hira.yousuf@example.com', '03222334455', '37203-5566778-4', 'Balochistan', 'Quetta', 'patient', 'certificates/1745389242_c5.jpeg', 'profile_photos/1745389242_p4.jpeg', '2025-04-23 06:20:42', 0),
(31, 'Ahmed', 'Nawaz', 'ahmednawaz', '12345', 'ahmed.nawaz@example.com', '03450123456', '37405-9988776-3', 'Punjab', 'Rawalpindi', 'patient', 'certificates/1745389369_c6.jpeg', 'profile_photos/1745389369_p8.jpeg', '2025-04-23 06:22:49', 0),
(32, 'Usman', 'Malik', 'usmanmalik', '12345', 'usman.malik@example.com', '03152347890', '61102-3344556-7', 'Sindh', 'Hyderabad', 'patient', 'certificates/1745389494_c7.jpeg', 'profile_photos/1745389494_p5.jpeg', '2025-04-23 06:24:54', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int NOT NULL,
  `specialist_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `report_date` date DEFAULT NULL,
  `report_details` text COLLATE utf8mb4_general_ci,
  `prescribed_medication` text COLLATE utf8mb4_general_ci,
  `treatment` text COLLATE utf8mb4_general_ci,
  `lab_tests` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `specialist_id`, `patient_id`, `report_date`, `report_details`, `prescribed_medication`, `treatment`, `lab_tests`) VALUES
(3, 39, 26, '2025-04-24', 'Patient presented with moderate acne vulgaris with comedones and pustules on the face.', 'Benzoyl peroxide gel, Doxycycline 100mg daily', 'Topical retinoids and oral antibiotics', 'CBC to monitor infection'),
(4, 39, 29, '2025-04-26', 'Patient reports itchy, inflamed patches on arms and legs. History suggests chronic eczema', 'Hydrocortisone cream, Cetirizine at bedtime', 'Moisturizers and topical steroids', 'Allergy panel test'),
(5, 39, 30, '2025-04-26', 'Well-demarcated scaly plaques observed on elbows and knees. Psoriasis suspected.', 'Betamethasone cream, Vitamin D analogs', 'Topical corticosteroids and phototherapy', 'Skin biopsy to confirm psoriasis'),
(6, 39, 28, '2025-04-27', 'Brown patches on cheeks and forehead. Diagnosed as melasma, worsened by sun exposure.', 'Hydroquinone 4% cream, Broad-spectrum SPF 50 sunscreen', 'Skin lightening agents and strict sun protection', 'Wood’s lamp examination');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `patient_id` int NOT NULL,
  `specialist_id` int NOT NULL,
  `rating` int NOT NULL,
  `feedback` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `patient_id`, `specialist_id`, `rating`, `feedback`, `created_at`, `image_path`) VALUES
(21, 26, 39, 5, 'Dr. Nida’s acne treatment truly changed my skin. Highly satisfied with the results.', '2025-04-23 06:49:50', 'feedback_images/1745390990_p2.jpeg'),
(22, 27, 40, 4, 'Very clean setup and effective facial. Dr. Mehak is very professional.', '2025-04-23 06:51:09', 'feedback_images/1745391069_p9.jpeg'),
(23, 28, 41, 5, 'Hyperpigmentation reduced significantly. Dr. Fareeha’s care is excellent.', '2025-04-23 06:51:56', 'feedback_images/1745391116_p3.jpeg'),
(24, 29, 39, 5, 'The mole removal was painless and smooth. Highly recommended.', '2025-04-23 06:52:47', 'feedback_images/1745391167_p1.jpeg'),
(25, 31, 43, 4, 'Dr. Rayan handled my hair loss treatment with care and confidence. Great results.', '2025-04-23 06:54:00', 'feedback_images/1745391240_p8.jpeg'),
(26, 32, 42, 5, 'Had severe acne for years. Dr. Rayan’s treatment really cleared up my skin. Confident again.', '2025-04-23 06:56:57', 'feedback_images/1745391417_p5.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `specialists`
--

CREATE TABLE `specialists` (
  `id` int NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `cnic` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `qualification` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `area_of_expertise` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profile_photo_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('specialist') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'specialist',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specialists`
--

INSERT INTO `specialists` (`id`, `first_name`, `last_name`, `username`, `password`, `email`, `mobile`, `cnic`, `state`, `city`, `qualification`, `area_of_expertise`, `profile_photo_path`, `role`, `created_at`) VALUES
(39, 'Dr. Nida', 'Rehman', 'nidarehman', '12345', 'nida.rehman@example.com', '03121234567', '35201-4567890-1', 'Punjab', 'Lahore', 'MBBS, FCPS (Dermatology)', 'Expert in acne, pigmentation, fillers, facials, mole & fat removal, hair & wrinkle care', 'profile_photos/photo_68088a46cff4c2.59041869.jpeg', 'specialist', '2025-04-23 06:35:50'),
(40, 'Dr. Mehak', 'Iqbal', 'mehak.iqbal', '12345', 'mehak.iqbal@example.com', '03216789123', '42101-7654321-2', 'Sindh', 'Karachi', 'MBBS, Diploma (Aesthetic Medicine)', 'Acne, facials, lasers, mole removal, wrinkle & fat reduction, hair solutions', 'profile_photos/photo_68088ae72fb067.60783057.jpeg', 'specialist', '2025-04-23 06:38:31'),
(41, 'Dr. Fareeha', 'Sultan', 'fareehasultan', '12345', 'fareeha.sultan@example.com', '03331239876', '61101-9988776-3', 'KPK', 'Peshawar', 'MBBS, MCPS (Skin & Aesthetics)', 'Specialist in skin care, acne, pigmentation, lasers, fillers, hair & face lifts', 'profile_photos/photo_68088b6f498632.29767362.jpeg', 'specialist', '2025-04-23 06:40:47'),
(42, 'Dr. Rayan', 'Javed', 'rayanjaved', '12345', 'rayan.javed@example.com', '03451112345', '37203-1122334-4', 'Punjab', 'Rawalpindi', 'MBBS, FCPS (Dermatology)', 'Aesthetic & skin expert: acne, lasers, fat loss, wrinkle care, hair, moles', 'profile_photos/photo_68088bcf536269.05069556.jpeg', 'specialist', '2025-04-23 06:42:23'),
(43, 'Dr. Bilal', 'Naseem', 'bilalnaseem', '12345', 'bilal.naseem@example.com', '03085556677', '42104-3344556-5', 'Sindh', 'Hyderabad', 'MBBS, Diploma (Cosmetology)', 'Advanced skin, facial aesthetics, acne, pigmentation, hair & wrinkle care', 'profile_photos/photo_68088c314ed426.49015301.jpeg', 'specialist', '2025-04-23 06:44:01');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int NOT NULL,
  `setting_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `setting_value` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_name`, `setting_value`) VALUES
(1, 'clinic_name', 'Derma Elixir Studio'),
(2, 'support_email', 'info@dermaelixirstudio.com'),
(4, 'phone_number', '+92-333-1234567');

-- --------------------------------------------------------

--
-- Table structure for table `treatments`
--

CREATE TABLE `treatments` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category` enum('Skin','Hair','Aesthetic') COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treatments`
--

INSERT INTO `treatments` (`id`, `name`, `category`, `description`, `price`, `created_at`) VALUES
(33, 'Hyperpigmentation Treatment', 'Skin', 'Advanced treatment to reduce dark spots and even out skin tone using medical-grade ingredients and laser technology.', 25000.00, '2025-04-12 08:31:39'),
(34, 'Acne Treatment', 'Skin', 'Comprehensive acne solution including deep cleansing, medical extractions, and specialized serums to treat and prevent breakouts.', 15000.00, '2025-04-12 08:31:39'),
(35, 'Dermal Fillers', 'Aesthetic', 'Injectable treatments to restore volume, smooth wrinkles, and enhance facial contours for a youthful appearance.', 40000.00, '2025-04-12 08:31:39'),
(36, 'Professional Facials', 'Skin', 'Customized facial treatments tailored to your skin type and concerns, using premium skincare products and techniques.', 8000.00, '2025-04-12 08:31:39'),
(37, 'Fat Reduction', 'Aesthetic', 'Non-invasive fat reduction treatments using advanced technologies to contour and shape the body.', 35000.00, '2025-04-12 08:31:39'),
(38, 'Mole Removal', 'Skin', 'Safe and effective removal of benign moles using either laser or surgical methods performed by dermatologists.', 12000.00, '2025-04-12 08:31:39'),
(39, 'Laser Hair Removal', 'Aesthetic', 'Permanent hair reduction using FDA-approved laser technology for smooth, hair-free skin.', 25000.00, '2025-04-12 08:31:39'),
(40, 'Anti-Wrinkle Injections', 'Aesthetic', 'Injectable treatments to temporarily relax facial muscles and reduce the appearance of wrinkles and fine lines.', 35000.00, '2025-04-12 08:31:39'),
(41, 'Hair Loss Treatments', 'Hair', 'Medical-grade solutions for hair regrowth including PRP therapy, laser treatments, and topical medications.', 30000.00, '2025-04-12 08:31:39'),
(42, 'Thread Lifting', 'Aesthetic', 'Minimally invasive facial lifting procedure using dissolvable threads to lift and tighten sagging skin.', 60000.00, '2025-04-12 08:31:39'),
(43, 'Hair Transplant', 'Hair', 'Surgical procedure to transplant healthy hair follicles to thinning or balding areas for natural-looking results.', 150000.00, '2025-04-12 08:31:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `specialist_id` (`specialist_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cnic` (`cnic`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specialist_id` (`specialist_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `specialist_id` (`specialist_id`);

--
-- Indexes for table `specialists`
--
ALTER TABLE `specialists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cnic` (`cnic`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treatments`
--
ALTER TABLE `treatments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `specialists`
--
ALTER TABLE `specialists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `treatments`
--
ALTER TABLE `treatments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`specialist_id`) REFERENCES `specialists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `medical_history_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`specialist_id`) REFERENCES `specialists` (`id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`specialist_id`) REFERENCES `specialists` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
