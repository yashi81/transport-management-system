-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2026 at 05:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `transport_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `full_name`, `created_at`) VALUES
(1, 'admin', '$2y$12$GS/IHhrUVKdKGXiME9jtQu/ZDIlt.oKgulN5gnmxlCcjeVf8mZUwW', 'Transport Administrator', '2026-09-18 06:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

CREATE TABLE `bus` (
  `bus_id` int(11) NOT NULL,
  `bus_number` varchar(30) NOT NULL,
  `bus_type` varchar(50) DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `status` enum('Available','Maintenance','Inactive') DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`bus_id`, `bus_number`, `bus_type`, `capacity`, `driver_id`, `status`, `created_at`) VALUES
(1, 'IGNOU-BUS-01', 'College Bus', 40, 1, 'Available', '2026-09-18 11:25:44'),
(2, 'IGNOU-BUS-02', 'College Bus', 40, 2, 'Available', '2026-09-18 11:25:44'),
(3, 'IGNOU-BUS-03', 'College Bus', 35, 3, 'Available', '2026-09-18 11:25:44'),
(4, 'IGNOU-BUS-04', 'College Bus', 40, 1, 'Available', '2026-09-21 03:42:15'),
(5, 'IGNOU-BUS-05', 'College Bus', 20, 4, 'Available', '2026-09-23 05:22:18'),
(6, 'VIVA-TEST-01', 'College Bus', 50, 4, 'Available', '2026-09-24 05:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `driver_id` int(11) NOT NULL,
  `driver_name` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `license_number` varchar(50) NOT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`driver_id`, `driver_name`, `mobile`, `license_number`, `address`, `status`, `created_at`) VALUES
(1, 'Rakesh Kumar', '9876500001', 'DL-01-2024-0001', 'Delhi', 'Active', '2026-09-18 11:25:19'),
(2, 'Amit Sharma', '9876500002', 'DL-01-2024-0002', 'Delhi', 'Active', '2026-09-18 11:25:19'),
(3, 'Suresh Singh', '9876500003', 'DL-01-2024-0003', 'Delhi', 'Active', '2026-09-18 11:25:19'),
(4, 'Test Driver', '8178034660', 'DL-TEST-003', 'noida', 'Active', '2026-09-23 05:22:00');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `registration_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` enum('Cash','Online','UPI','Card','Other') DEFAULT 'Online',
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_status` enum('Pending','Paid','Failed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `registration_id`, `amount`, `payment_date`, `payment_method`, `transaction_id`, `payment_status`) VALUES
(1, 1, 1000.00, '2026-09-23', 'UPI', 'TESTUPI001', 'Paid'),
(2, 4, 1000.00, '2026-09-24', 'UPI', 'TESTUPI004', 'Paid'),
(3, 2, 2000.00, '2026-09-24', 'Cash', '', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `route_id` int(11) NOT NULL,
  `route_name` varchar(100) NOT NULL,
  `starting_point` varchar(100) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `stops` text DEFAULT NULL,
  `distance` decimal(8,2) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`route_id`, `route_name`, `starting_point`, `destination`, `stops`, `distance`, `status`, `created_at`) VALUES
(1, 'BADAUN', 'BADAUN', 'IGNOU Campus', 'Main route', NULL, 'Active', '2026-09-18 11:19:45'),
(2, 'C.B.GANJ', 'C.B.GANJ', 'IGNOU Campus', 'Main route', NULL, 'Active', '2026-09-18 11:19:45'),
(3, 'CITY', 'CITY', 'IGNOU Campus', 'Main route', NULL, 'Active', '2026-09-18 11:19:45'),
(4, 'KOHARAPEER', 'KOHARAPEER', 'IGNOU Campus', 'Main route', NULL, 'Active', '2026-09-18 11:19:45'),
(5, 'DHOBI CHAURAHA', 'DHOBI CHAURAHA', 'IGNOU Campus', 'Main route', NULL, 'Active', '2026-09-18 11:19:45'),
(6, 'PILIBHIT', 'PILIBHIT', 'IGNOU Campus', 'Main route', NULL, 'Active', '2026-09-18 11:19:45'),
(7, 'SANJAY NAGAR', 'SANJAY NAGAR', 'IGNOU Campus', 'Main route', NULL, 'Active', '2026-09-18 11:19:45'),
(8, 'KARGAINA', 'KARGAINA', 'IGNOU Campus', 'Main route', NULL, 'Active', '2026-09-18 11:19:45'),
(9, 'JUNCTION', 'JUNCTION', 'IGNOU Campus', 'Main route', NULL, 'Active', '2026-09-18 11:19:45'),
(10, 'TEST ROUTE', 'Delhi', 'IGNOU Campus', 'Test Stop', 10.00, 'Active', '2026-09-24 05:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `course` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `full_name`, `email`, `mobile`, `course`, `password`, `address`, `created_at`) VALUES
(1, 'Test Student', 'teststudent@example.com', '9876543210', 'BCA', '$2y$10$IzGSU5Eu.7/ic6a34CBXJ.55jyMXITw7cgfiepezltrQdz6mn1KBm', 'New Delhi', '2026-09-18 06:15:30'),
(2, 'Rahul Sharma', 'rahultest@example.com', '9876543211', 'BCA', '$2y$10$ktFJLaCaLpZvIj9Nfsldiu7LnkVDuXqjJCQKXLSA.GJrDjS7rEQ/i', 'New Delhi', '2026-09-18 06:21:49'),
(3, 'Test Final Student', 'finaltest@example.com', '9999999999', 'BCA', '$2y$10$XWTG68s9v9FCdkmVCPZwleJXMlFlmNPx7cOIAI3vqnNk9xk6Wd4km', 'New Delhi', '2026-09-23 05:06:45'),
(4, 'Test Phase 2 Student', 'phase2test2026@example.com', '9876543210', 'MBA', '$2y$10$XE19vUg5RNPsJo4Ddy0C1OYlmAff4N6vok9IVtCjp350O9Jb2eWpO', 'Delhi', '2026-09-24 05:27:07');

-- --------------------------------------------------------

--
-- Table structure for table `transport_registration`
--

CREATE TABLE `transport_registration` (
  `registration_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `bus_id` int(11) DEFAULT NULL,
  `registration_date` date NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Active','Expired') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transport_registration`
--

INSERT INTO `transport_registration` (`registration_id`, `student_id`, `route_id`, `bus_id`, `registration_date`, `start_date`, `end_date`, `status`) VALUES
(1, 3, 3, 1, '2026-09-23', '2026-09-23', '2026-10-23', 'Approved'),
(2, 4, 1, 1, '2026-09-24', '2026-10-01', '2026-10-31', 'Approved'),
(4, 4, 2, 3, '2026-09-24', '2026-09-24', '2026-09-24', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `trip`
--

CREATE TABLE `trip` (
  `trip_id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `trip_date` date NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time DEFAULT NULL,
  `trip_type` enum('Morning','Evening','Other') DEFAULT 'Morning',
  `status` enum('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trip`
--

INSERT INTO `trip` (`trip_id`, `bus_id`, `route_id`, `trip_date`, `departure_time`, `arrival_time`, `trip_type`, `status`) VALUES
(1, 1, 1, '2026-09-22', '08:00:00', '09:00:00', 'Morning', 'Scheduled'),
(2, 6, 10, '2026-09-26', '11:00:00', '13:29:00', 'Morning', 'Scheduled');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`bus_id`),
  ADD UNIQUE KEY `bus_number` (`bus_number`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`driver_id`),
  ADD UNIQUE KEY `license_number` (`license_number`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `registration_id` (`registration_id`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `transport_registration`
--
ALTER TABLE `transport_registration`
  ADD PRIMARY KEY (`registration_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `route_id` (`route_id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `trip`
--
ALTER TABLE `trip`
  ADD PRIMARY KEY (`trip_id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `route_id` (`route_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `bus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `driver`
--
ALTER TABLE `driver`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transport_registration`
--
ALTER TABLE `transport_registration`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trip`
--
ALTER TABLE `trip`
  MODIFY `trip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bus`
--
ALTER TABLE `bus`
  ADD CONSTRAINT `bus_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`driver_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`registration_id`) REFERENCES `transport_registration` (`registration_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transport_registration`
--
ALTER TABLE `transport_registration`
  ADD CONSTRAINT `transport_registration_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transport_registration_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `route` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transport_registration_ibfk_3` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`bus_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `trip`
--
ALTER TABLE `trip`
  ADD CONSTRAINT `trip_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`bus_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trip_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `route` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
