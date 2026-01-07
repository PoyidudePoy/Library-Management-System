-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2026 at 02:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `students_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `email_acc` varchar(255) NOT NULL,
  `email_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `name`, `contact_number`, `email_acc`, `email_password`) VALUES
(3, 'admin', '94983274', 'admin@gmail.com', '$2y$10$64UdoqZAVGnIc9dZmDKKWeY3ONxlmrXhHSzauLViBlP6EXt31UIge');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `announcement_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `announcement_text`, `created_at`) VALUES
(1, 'Welcome To Libary! Borrow Books Now!', '2025-02-06 23:00:50'),
(2, 'New books have arrived. Check out the latest additions!', '2025-02-06 23:00:50'),
(3, 'Library hours are extended during exams.', '2025-02-06 23:00:50');

-- --------------------------------------------------------

--
-- Table structure for table `book_borrowals`
--

CREATE TABLE `book_borrowals` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `book_name` varchar(100) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_borrowals`
--

INSERT INTO `book_borrowals` (`id`, `student_id`, `firstName`, `lastName`, `book_name`, `borrow_date`, `status`) VALUES
(25, '723641', 'John', 'Doe', 'Rens', '2026-01-07', 'Returned');

-- --------------------------------------------------------

--
-- Table structure for table `computer_availability`
--

CREATE TABLE `computer_availability` (
  `id` int(11) NOT NULL,
  `computer_id` int(11) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `grade_level` varchar(10) DEFAULT NULL,
  `strand` varchar(50) DEFAULT NULL,
  `status` enum('available','occupied') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `computer_availability`
--

INSERT INTO `computer_availability` (`id`, `computer_id`, `student_id`, `firstName`, `lastName`, `grade_level`, `strand`, `status`) VALUES
(29, 5, NULL, 'polyglot', 'Neri', '12', 'TVL-ICT', 'occupied');

-- --------------------------------------------------------

--
-- Table structure for table `logbook`
--

CREATE TABLE `logbook` (
  `log_id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `time_in` datetime DEFAULT current_timestamp(),
  `time_out` datetime DEFAULT NULL,
  `purpose` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `your_students`
--

CREATE TABLE `your_students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `octEmail` varchar(100) NOT NULL,
  `email_password` varchar(255) NOT NULL,
  `grade_level` varchar(10) NOT NULL,
  `strand` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `your_students`
--

INSERT INTO `your_students` (`id`, `student_id`, `firstName`, `lastName`, `octEmail`, `email_password`, `grade_level`, `strand`) VALUES
(72, '723641', 'John', 'Doe', 'user@gmail.com', '$2y$10$iaQEtzsUfagUvEtJkjB3ROC9oiTAyYVasCfTu5MbPaD9Cw7rJJtqG', '12', 'TVL-ICT');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email_acc` (`email_acc`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_borrowals`
--
ALTER TABLE `book_borrowals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `computer_availability`
--
ALTER TABLE `computer_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `logbook`
--
ALTER TABLE `logbook`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `your_students`
--
ALTER TABLE `your_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `octEmail` (`octEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `book_borrowals`
--
ALTER TABLE `book_borrowals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `computer_availability`
--
ALTER TABLE `computer_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `logbook`
--
ALTER TABLE `logbook`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `your_students`
--
ALTER TABLE `your_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book_borrowals`
--
ALTER TABLE `book_borrowals`
  ADD CONSTRAINT `fk_student_borrowals` FOREIGN KEY (`student_id`) REFERENCES `your_students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `computer_availability`
--
ALTER TABLE `computer_availability`
  ADD CONSTRAINT `computer_availability_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `your_students` (`student_id`) ON DELETE SET NULL;

--
-- Constraints for table `logbook`
--
ALTER TABLE `logbook`
  ADD CONSTRAINT `logbook_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `your_students` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
