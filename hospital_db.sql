-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2026 at 07:41 AM
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
-- Database: `hospital_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `appointment`
--
DELIMITER $$
CREATE TRIGGER `after_appointment_insert` AFTER INSERT ON `appointment` FOR EACH ROW BEGIN
    INSERT INTO appointment_log(message)
    VALUES (CONCAT('New appointment added for Patient ID: ', NEW.patient_id));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `status`, `notes`, `created_at`) VALUES
(14, 65, 2, '2026-04-12 10:30:00', '00:00:00', 'Scheduled', NULL, '2026-04-07 04:32:50'),
(15, 26, 1, '2026-05-10 11:30:00', '00:00:00', 'Scheduled', NULL, '2026-04-07 05:03:13'),
(16, 18, 3, '2026-04-23 11:00:00', '00:00:00', 'Scheduled', NULL, '2026-04-07 05:04:02');

--
-- Triggers `appointments`
--
DELIMITER $$
CREATE TRIGGER `block_double_booking` BEFORE INSERT ON `appointments` FOR EACH ROW BEGIN
    -- Check if this doctor already has an appointment at the exact same time
    IF EXISTS (
        SELECT 1 FROM appointments 
        WHERE doctor_id = NEW.doctor_id 
        AND appointment_date = NEW.appointment_date
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'This doctor is already booked for this time slot!';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevent_past_appointments` BEFORE INSERT ON `appointments` FOR EACH ROW BEGIN
    IF NEW.appointment_date < NOW() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Cannot book an appointment in the past!';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_log`
--

CREATE TABLE `appointment_log` (
  `log_id` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_log`
--

INSERT INTO `appointment_log` (`log_id`, `message`, `created_at`) VALUES
(1, 'New appointment added for Patient ID: 1', '2026-03-04 06:03:58');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `doctor_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `specialization` varchar(50) NOT NULL,
  `experience` int(11) DEFAULT NULL CHECK (`experience` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`doctor_id`, `name`, `specialization`, `experience`) VALUES
(1, 'Dr. Sharma', 'Cardiology', 10),
(2, 'Dr. Rao', 'Orthopedic', 8),
(3, 'Dr. Mehta', 'Neurology', 12),
(4, 'Dr. Khan', 'Dermatology', 6),
(5, 'Dr. Patel', 'Pediatrics', 9);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `name`, `specialization`, `phone`, `email`) VALUES
(1, 'Smith', 'Cardiology', '8080809095', 'smith123@gmail.com'),
(2, 'Gayatri', 'Dentist', '9596871234', 'gayatri123@gmail.com'),
(3, 'Ravi Kumar', 'Cardiologist', '9876543210', NULL),
(4, 'Sneha Desai', 'Neurologist', '9123456789', NULL),
(5, 'Anil Gupta', 'Orthopedic Surgeon', '9000000001', NULL),
(6, 'Pooja Sharma', 'Pediatrician', '5678654345', NULL),
(7, 'Vikram Singh', 'Dermatologist', '9897543245', NULL),
(8, 'Kavita Patel', 'Psychiatrist', '9456787643', NULL),
(9, 'Sanjay Verma', 'General Surgeon', '8675434450', NULL),
(10, 'Neha Reddy', 'Gynecologist', '8896556578', NULL),
(11, 'Rahul Joshi', 'Oncologist', '9908761122', NULL),
(12, 'Anjali Menon', 'ENT Specialist', '9560081124', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `patient_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patient_id`, `name`, `dob`, `gender`, `phone`, `doctor_id`) VALUES
(11, 'Hyfa K H', '0000-00-00', 'Female', '9988776655', 8),
(15, 'gauri saneesh', '1985-05-14', 'Female', '8080809095', 2),
(16, 'Aarav Sharma', '1995-03-12', 'Male', '9876543210', 1),
(17, 'Vivaan Verma', '1993-09-10', 'Male', '9876543211', 2),
(18, 'Aditya Singh', '1991-08-15', 'Male', '9876543212', 3),
(19, 'Vihaan Gupta', '0000-00-00', 'Male', '9876543213', 4),
(20, 'Arjun Reddy', '0000-00-00', 'Male', '9876543214', 5),
(21, 'Sai Patel', '0000-00-00', 'Male', '9876543215', 6),
(22, 'Ayaan Desai', '0000-00-00', 'Male', '9876543216', 7),
(23, 'Krishna Joshi', '0000-00-00', 'Male', '9876543217', 8),
(24, 'Ishaan Menon', '0000-00-00', 'Male', '9876543218', 9),
(25, 'Shaurya Kumar', '0000-00-00', 'Male', '9876543219', 12),
(26, 'Diya Sharma', '1998-07-22', 'Female', '9876543220', 1),
(27, 'Aarohi Verma', '1997-12-25', 'Female', '9876543221', 2),
(28, 'Ananya Singh', '1999-10-20', 'Female', '9876543222', 3),
(29, 'Saanvi Gupta', '0000-00-00', 'Female', '9876543223', 4),
(30, 'Myra Reddy', '0000-00-00', 'Female', '9876543224', 5),
(31, 'Kavya Patel', '0000-00-00', 'Female', '9876543225', 6),
(32, 'Ira Desai', '0000-00-00', 'Female', '9876543226', 7),
(33, 'Ahana Joshi', '0000-00-00', 'Female', '9876543227', 8),
(34, 'Kyra Menon', '0000-00-00', 'Female', '9876543228', 9),
(35, 'Prisha Kumar', '0000-00-00', 'Female', '9876543229', 10),
(36, 'Rohan Mehta', '1990-01-05', 'Male', '9876543230', 1),
(37, 'Rishabh Jain', '2000-02-28', 'Male', '9876543231', 2),
(38, 'Kabir Das', '1982-01-30', 'Male', '9876543232', 3),
(39, 'Dhruv Bose', '0000-00-00', 'Male', '9876543233', 4),
(40, 'Yash Nair', '0000-00-00', 'Male', '9876543234', 5),
(41, 'Atharv Pillai', '0000-00-00', 'Male', '9876543235', 6),
(42, 'Parth Rao', '0000-00-00', 'Male', '9876543236', 7),
(43, 'Dev Choudhury', '0000-00-00', 'Male', '9876543237', 8),
(44, 'Vedant Iyer', '0000-00-00', 'Male', '9876543238', 9),
(45, 'Arnav Sen', '0000-00-00', 'Male', '9876543239', 8),
(46, 'Meera Mehta', '2001-11-30', 'Female', '9876543240', 1),
(47, 'Sara Jain', '1988-06-18', 'Female', '9876543241', 2),
(48, 'Riya Das', '2003-03-03', 'Female', '9876543242', 3),
(49, 'Tara Bose', '0000-00-00', 'Female', '9876543243', 4),
(50, 'Navya Nair', '0000-00-00', 'Female', '9876543244', 5),
(51, 'Pari Pillai', '0000-00-00', 'Female', '9876543245', 6),
(52, 'Avni Rao', '0000-00-00', 'Female', '9876543246', 7),
(53, 'Isha Choudhury', '0000-00-00', 'Female', '9876543247', 8),
(54, 'Nisha Iyer', '0000-00-00', 'Female', '9876543248', 9),
(55, 'Siya Sen', '0000-00-00', 'Female', '9876543249', 10),
(56, 'Ashwin Kumar', '0000-00-00', 'Male', '9876543250', 11),
(57, 'Sethu Thayyil', '1994-04-03', 'Female', '9876543251', 2),
(58, 'Niranjana Gireesh', '0000-00-00', 'Female', '9876543252', 3),
(59, 'Aamir Khan', '0000-00-00', 'Male', '9876543253', 4),
(60, 'Sanjay Kumar', '0000-00-00', 'Male', '9876543254', 5),
(61, 'Hiba Fathima', '0000-00-00', 'Female', '9876543255', 6),
(62, 'Goutami MK', '0000-00-00', 'Female', '9876543256', 7),
(63, 'Mehthab Shiyas', '0000-00-00', 'Female', '9876543257', 8),
(64, 'Lema Fathima', '0000-00-00', 'Female', '9876543258', 9),
(65, 'Gauri Saneesh', '0000-00-00', 'Female', '9876543259', 6),
(66, 'John Doe', '0000-00-00', 'Male', '5678976589', 8);

--
-- Triggers `patient`
--
DELIMITER $$
CREATE TRIGGER `after_patient_delete` AFTER DELETE ON `patient` FOR EACH ROW BEGIN
    INSERT INTO patient_log (patient_name, action_performed)
    VALUES (OLD.name, 'DELETED');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `patient_appointment_view`
-- (See below for the actual view)
--
CREATE TABLE `patient_appointment_view` (
`Patient_Name` varchar(50)
,`Doctor_Name` varchar(50)
,`specialization` varchar(50)
,`appointment_date` date
);

-- --------------------------------------------------------

--
-- Table structure for table `patient_audit_log`
--

CREATE TABLE `patient_audit_log` (
  `log_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_log`
--

CREATE TABLE `patient_log` (
  `log_id` int(11) NOT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `action_performed` varchar(50) DEFAULT NULL,
  `action_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_log`
--

INSERT INTO `patient_log` (`log_id`, `patient_name`, `action_performed`, `action_time`) VALUES
(1, 'Hyfa K H', 'DELETED', '2026-04-05 14:35:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_patient_details`
-- (See below for the actual view)
--
CREATE TABLE `view_patient_details` (
`patient_id` int(11)
,`patient_name` varchar(50)
,`dob` date
,`gender` varchar(10)
,`phone` varchar(15)
,`assigned_doctor` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure for view `patient_appointment_view`
--
DROP TABLE IF EXISTS `patient_appointment_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `patient_appointment_view`  AS SELECT `patient`.`name` AS `Patient_Name`, `doctor`.`name` AS `Doctor_Name`, `doctor`.`specialization` AS `specialization`, `appointment`.`appointment_date` AS `appointment_date` FROM ((`appointment` join `patient` on(`appointment`.`patient_id` = `patient`.`patient_id`)) join `doctor` on(`appointment`.`doctor_id` = `doctor`.`doctor_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_patient_details`
--
DROP TABLE IF EXISTS `view_patient_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_patient_details`  AS SELECT `p`.`patient_id` AS `patient_id`, `p`.`name` AS `patient_name`, `p`.`dob` AS `dob`, `p`.`gender` AS `gender`, `p`.`phone` AS `phone`, `d`.`name` AS `assigned_doctor` FROM (`patient` `p` join `doctors` `d` on(`p`.`doctor_id` = `d`.`doctor_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `appointment_log`
--
ALTER TABLE `appointment_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `fk_patient_doctor` (`doctor_id`);

--
-- Indexes for table `patient_audit_log`
--
ALTER TABLE `patient_audit_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `patient_log`
--
ALTER TABLE `patient_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `appointment_log`
--
ALTER TABLE `appointment_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `patient_audit_log`
--
ALTER TABLE `patient_audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_log`
--
ALTER TABLE `patient_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `fk_patient_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
