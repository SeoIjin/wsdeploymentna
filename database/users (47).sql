-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2025 at 06:59 PM
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
-- Database: `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `usertype` varchar(55) NOT NULL DEFAULT '',
  `account_status` varchar(20) DEFAULT 'pending',
  `is_disabled` tinyint(1) DEFAULT 0,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `id_type` varchar(100) DEFAULT NULL,
  `resident_type` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `usertype`, `account_status`, `is_disabled`, `first_name`, `middle_name`, `last_name`, `contact_number`, `email`, `password`, `barangay`, `id_type`, `resident_type`, `file_path`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'pending', 0, 'Admin', 'Test', 'User', '0', 'admin@gov.ph', 'admin123', 'Barangay 170', 'government-id', 'resident', 'uploads/default.jpg', NULL, '2025-01-01 00:00:00', '2025-11-24 06:05:54'),
(2, '', 'approved', 0, 'John', 'Dela', 'Cruz', '09167039130', 'john@gmail.com', 'user123', 'Barangay 170', 'government-id', 'resident', 'uploads/john_id.jpg', 'uploads/profile_pictures/profile_2_1764289051.jpeg', '2025-01-15 00:00:00', '2025-11-30 17:14:48'),
(3, '', 'approved', 0, 'Shou', 'Nicol', 'Ballesteros', '0', 'shou123@gmail.com', 'pogi', 'Sangandaan', 'passport', 'resident', 'uploads/6918b4726a53c_1763226738.png', NULL, '2025-11-15 17:12:18', '2025-11-25 10:18:16'),
(6, '', 'approved', 0, 'Shou', 'Nicol', 'Ballesteros', '09167239008', 'shouballesteros4@gmail.com', 'dasdasd', 'Sangandaan', 'sss-id', 'non-resident', 'uploads/69234a44e6477_1763920452.png', 'uploads/profile_pictures/profile_6_1764522951.png', '2025-11-23 17:54:12', '2025-11-30 17:15:51'),
(7, '', 'approved', 0, 'Christien', 'Michael', 'Jimenez', '0', 'tien@gmail.com', 'tientien', 'Sangandaan', 'philhealth-id', 'non-resident', 'uploads/6923e772d81ec_1763960690.png', NULL, '2025-11-24 05:04:50', '2025-11-24 11:10:17'),
(9, '', 'approved', 0, 'shou', 'nicol', 'ballesteros', '0', 'shouballesteros5@gmail.com', 'pogiako', 'Sangandaan', 'sss-id', 'resident', 'uploads/6923fe5103005_1763966545.JPG', NULL, '2025-11-24 06:42:25', '2025-11-24 10:19:49'),
(10, '', 'approved', 1, 'airam', 'jesse', 'licerio', '0', 'airamjesse@gmail.com', 'airammae', 'Sangandaan', 'umid', 'non-resident', 'uploads/69240b744f635_1763969908.jpg', NULL, '2025-11-24 07:38:28', '2025-12-04 17:57:15'),
(12, '', 'approved', 0, 'jesus', 'jes', 'cuzon', '2147483647', 'ballesteros.shou.nicol@gmail.com', 'jespogi', 'Sangandaan Premium', 'sss-id', 'resident', 'uploads/6924724629668_1763996230.png', NULL, '2025-11-24 14:57:10', '2025-12-04 17:34:48'),
(20, '', 'approved', 0, 'mae', 'czarina', 'anne', '2147483647', 'ballesteros.136512150091@depedqc.ph', 'shou123', 'Sangandaan', 'philhealth-id', 'non-resident', 'uploads/6925e4c39d1d2_1764091075.png', 'uploads/profile_pictures/profile_20_1764091133.png', '2025-11-25 17:17:55', '2025-11-25 17:18:53'),
(22, '', 'approved', 0, 'test', 'test', 'test', '09178923516', 'test123@gmail.com', 'nkeroro09', 'Sangandaan', 'pwd-id', 'non-resident', 'uploads/692c7c349126f_1764523060.png', NULL, '2025-11-30 17:17:40', '2025-12-01 18:05:03'),
(23, '', 'approved', 0, 'test', 'test', 'test', '09167039139', 'taewonkim301@gmail.com', 'test123', 'Sangandaan Premium', 'umid', 'non-resident', 'uploads/6931cad19b513_1764870865.jpg', NULL, '2025-12-04 17:54:25', '2025-12-04 17:54:39');

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `action_type` enum('LOGIN','LOGOUT','REQUEST_UPDATE','REQUEST_DELETE','REQUEST_REJECT','STATUS_CHANGE','NOTIFICATION_ADD','NOTIFICATION_DELETE','USER_VIEW','PRIORITY_CHANGE','ACCOUNT_APPROVAL','ACCOUNT_REJECTION','OFFICIAL_ADD','OFFICIAL_UPDATE','OFFICIAL_DELETE','ACCOUNT_DISABLE','ACCOUNT_ENABLE') NOT NULL,
  `action_description` text NOT NULL,
  `target_type` varchar(50) DEFAULT NULL COMMENT 'Type of target (request, user, notification, etc.)',
  `target_id` varchar(50) DEFAULT NULL COMMENT 'ID of the affected item',
  `old_value` text DEFAULT NULL COMMENT 'Previous value before change',
  `new_value` text DEFAULT NULL COMMENT 'New value after change',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`id`, `admin_id`, `admin_email`, `action_type`, `action_description`, `target_type`, `target_id`, `old_value`, `new_value`, `ip_address`, `user_agent`, `created_at`) VALUES
(208, 1, 'admin@gov.ph', 'LOGOUT', 'Admin logged out from the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:06:36'),
(209, 1, 'admin@gov.ph', 'LOGIN', 'Admin logged into the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:06:39'),
(210, 1, 'admin@gov.ph', 'STATUS_CHANGE', 'Updated request BHR-2025-557604 status from \'PENDING\' to \'COMPLETED\' with message: jwu', 'request', 'BHR-2025-557604', 'PENDING', 'COMPLETED', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:06:56'),
(211, 1, 'admin@gov.ph', 'PRIORITY_CHANGE', 'Changed priority for request BHR-2025-557604 from \'HIGH\' to \'LOW\'', 'request', 'BHR-2025-557604', 'HIGH', 'LOW', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:06:56'),
(212, 1, 'admin@gov.ph', 'REQUEST_DELETE', 'Deleted request BHR-2025-557604 (Type: Barangay Certificate for Water/Electric Connection)', 'request', 'BHR-2025-557604', 'Request: Barangay Certificate for Water/Electric Connection', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:07:06'),
(213, 1, 'admin@gov.ph', 'NOTIFICATION_DELETE', 'Deleted notification: \'dota\'', 'notification', '6', 'dota', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:07:17'),
(214, 1, 'admin@gov.ph', 'NOTIFICATION_ADD', 'Added new NEWS notification: \'to na\'', 'notification', NULL, NULL, 'to na', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:07:29'),
(215, 1, 'admin@gov.ph', 'OFFICIAL_DELETE', 'Deleted barangay official: \'Shou\' (Test)', 'official', '10', 'Shou - Test', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:07:37'),
(216, 1, 'admin@gov.ph', 'LOGOUT', 'Admin logged out from the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:08:21'),
(217, 1, 'admin@gov.ph', 'LOGIN', 'Admin logged into the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:09:28'),
(218, 1, 'admin@gov.ph', 'STATUS_CHANGE', 'Updated request BHR-2025-000014 status from \'PENDING\' to \'READY\'', 'request', 'BHR-2025-000014', 'PENDING', 'READY', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:09:55'),
(219, 1, 'admin@gov.ph', 'PRIORITY_CHANGE', 'Changed priority for request BHR-2025-000014 from \'HIGH\' to \'LOW\'', 'request', 'BHR-2025-000014', 'HIGH', 'LOW', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:09:55'),
(220, 1, 'admin@gov.ph', 'STATUS_CHANGE', 'Updated request BHR-2025-603098 status from \'PENDING\' to \'COMPLETED\'', 'request', 'BHR-2025-603098', 'PENDING', 'COMPLETED', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:23:01'),
(221, 1, 'admin@gov.ph', 'PRIORITY_CHANGE', 'Changed priority for request BHR-2025-603098 from \'MEDIUM\' to \'HIGH\'', 'request', 'BHR-2025-603098', 'MEDIUM', 'HIGH', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:23:01'),
(222, 1, 'admin@gov.ph', 'STATUS_CHANGE', 'Updated request BHR-2025-603098 status from \'COMPLETED\' to \'PENDING\'', 'request', 'BHR-2025-603098', 'COMPLETED', 'PENDING', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:23:15'),
(223, 1, 'admin@gov.ph', 'PRIORITY_CHANGE', 'Changed priority for request BHR-2025-603098 from \'HIGH\' to \'LOW\'', 'request', 'BHR-2025-603098', 'HIGH', 'LOW', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:23:15'),
(224, 1, 'admin@gov.ph', 'REQUEST_REJECT', 'Rejected request BHR-2025-603098 (Type: Barangay Business Clearance). Reason: what', 'request', 'BHR-2025-603098', 'Barangay Business Clearance', 'REJECTED', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:30:59'),
(225, 1, 'admin@gov.ph', 'REQUEST_REJECT', 'Rejected request BHR-2025-603098 (Type: Barangay Business Clearance). Reason: hehe', 'request', 'BHR-2025-603098', 'Barangay Business Clearance', 'REJECTED', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:31:09'),
(226, 1, 'admin@gov.ph', 'LOGOUT', 'Admin logged out from the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:31:23'),
(227, 1, 'admin@gov.ph', 'LOGIN', 'Admin logged into the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:31:42'),
(228, 1, 'admin@gov.ph', 'REQUEST_REJECT', 'Rejected request BHR-2025-791784 (Type: Barangay Certificate of No Derogatory Record). Reason: dad', 'request', 'BHR-2025-791784', 'Barangay Certificate of No Derogatory Record', 'REJECTED', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:46:48'),
(229, 1, 'admin@gov.ph', 'ACCOUNT_DISABLE', 'Disabled account for user: airam licerio (airamjesse@gmail.com)', 'account', '10', 'enabled', 'disabled', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:48:05'),
(230, 1, 'admin@gov.ph', 'LOGOUT', 'Admin logged out from the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:48:09'),
(231, 1, 'admin@gov.ph', 'LOGIN', 'Admin logged into the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:48:43'),
(232, 1, 'admin@gov.ph', 'ACCOUNT_ENABLE', 'Enabled account for user: airam licerio (airamjesse@gmail.com)', 'account', '10', 'disabled', 'enabled', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:48:54'),
(233, 1, 'admin@gov.ph', 'LOGOUT', 'Admin logged out from the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:54:16'),
(234, 1, 'admin@gov.ph', 'LOGIN', 'Admin logged into the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:54:20'),
(235, 1, 'admin@gov.ph', 'REQUEST_DELETE', 'Deleted request BHR-2025-603098 (Type: Barangay Business Clearance)', 'request', 'BHR-2025-603098', 'Request: Barangay Business Clearance', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:54:23'),
(236, 1, 'admin@gov.ph', 'REQUEST_DELETE', 'Deleted request BHR-2025-791784 (Type: Barangay Certificate of No Derogatory Record)', 'request', 'BHR-2025-791784', 'Request: Barangay Certificate of No Derogatory Record', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:54:25'),
(237, 1, 'admin@gov.ph', 'REQUEST_REJECT', 'Rejected request BHR-2025-924084 (Type: Barangay Certificate of Guardianship). Reason: ah', 'request', 'BHR-2025-924084', 'Barangay Certificate of Guardianship', 'REJECTED', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:54:31'),
(238, 1, 'admin@gov.ph', 'REQUEST_REJECT', 'Rejected request BHR-2025-136754 (Type: Barangay Certification for PWD). Reason: a', 'request', 'BHR-2025-136754', 'Barangay Certification for PWD', 'REJECTED', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', '2025-12-04 16:58:02'),
(239, 1, 'admin@gov.ph', 'REQUEST_REJECT', 'Rejected request BHR-2025-197360 (Type: Barangay Clearance). Reason: haha', 'request', 'BHR-2025-197360', 'Barangay Clearance', 'REJECTED', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', '2025-12-04 16:58:49'),
(240, 1, 'admin@gov.ph', 'REQUEST_REJECT', 'Rejected request BHR-2025-326146 (Type: Barangay Certificate for Water/Electric Connection). Reason: a', 'request', 'BHR-2025-326146', 'Barangay Certificate for Water/Electric Connection', 'REJECTED', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:10:40'),
(241, 1, 'admin@gov.ph', 'LOGOUT', 'Admin logged out from the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:15:04'),
(242, 1, 'admin@gov.ph', 'LOGIN', 'Admin logged into the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:15:08'),
(243, 1, 'admin@gov.ph', 'REQUEST_DELETE', 'Deleted request BHR-2025-326146 (Type: Barangay Certificate for Water/Electric Connection)', 'request', 'BHR-2025-326146', 'Request: Barangay Certificate for Water/Electric Connection', NULL, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', '2025-12-04 17:20:16'),
(244, 1, 'admin@gov.ph', 'REQUEST_REJECT', 'Rejected request BHR-2025-000016 (Type: Barangay Certification for PWD). Reason: whaat', 'request', 'BHR-2025-000016', 'Barangay Certification for PWD', 'REJECTED', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', '2025-12-04 17:20:24'),
(245, 1, 'admin@gov.ph', 'LOGOUT', 'Admin logged out from the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:28:09'),
(246, 1, 'admin@gov.ph', 'LOGIN', 'Admin logged into the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:28:14'),
(247, 1, 'admin@gov.ph', 'REQUEST_DELETE', 'Deleted request BHR-2025-000016 (Type: Barangay Certification for PWD)', 'request', 'BHR-2025-000016', 'Request: Barangay Certification for PWD', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:28:25'),
(248, 1, 'admin@gov.ph', 'NOTIFICATION_DELETE', 'Deleted notification: \'test\'', 'notification', '8', 'test', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:31:51'),
(249, 1, 'admin@gov.ph', 'LOGOUT', 'Admin logged out from the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:32:02'),
(250, 1, 'admin@gov.ph', 'LOGIN', 'Admin logged into the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:32:20'),
(251, 1, 'admin@gov.ph', 'OFFICIAL_ADD', 'Added new barangay official: \'Leah Banguilan\' as Boss', 'official', NULL, NULL, 'Leah Banguilan - Boss', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:33:50'),
(252, 1, 'admin@gov.ph', 'NOTIFICATION_ADD', 'Added new NEWS notification: \'sa wakas\'', 'notification', NULL, NULL, 'sa wakas', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:34:06'),
(253, 1, 'admin@gov.ph', 'ACCOUNT_DISABLE', 'Disabled account for user: jesus cuzon (ballesteros.shou.nicol@gmail.com)', 'account', '12', 'enabled', 'disabled', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:34:28'),
(254, 1, 'admin@gov.ph', 'LOGOUT', 'Admin logged out from the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:34:32'),
(255, 1, 'admin@gov.ph', 'LOGIN', 'Admin logged into the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:34:41'),
(256, 1, 'admin@gov.ph', 'ACCOUNT_ENABLE', 'Enabled account for user: jesus cuzon (ballesteros.shou.nicol@gmail.com)', 'account', '12', 'disabled', 'enabled', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:34:48'),
(257, 1, 'admin@gov.ph', 'OFFICIAL_ADD', 'Added new barangay official: \'Steven\' (Roblox)', 'official', NULL, NULL, 'Steven - Roblox', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:44:25'),
(258, 1, 'admin@gov.ph', 'LOGOUT', 'Admin logged out from the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:44:29'),
(259, 1, 'admin@gov.ph', 'LOGIN', 'Admin logged into the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:46:20'),
(260, 1, 'admin@gov.ph', 'ACCOUNT_REJECTION', 'Rejected account registration for: taewonkim301@gmail.com (jhamir go) (Email sent)', 'account', '13', 'pending', 'rejected', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:53:25'),
(261, 1, 'admin@gov.ph', 'LOGOUT', 'Admin logged out from the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:53:57'),
(262, 1, 'admin@gov.ph', 'LOGIN', 'Admin logged into the system', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:54:31'),
(263, 1, 'admin@gov.ph', 'ACCOUNT_APPROVAL', 'Approved account for user: taewonkim301@gmail.com (Email sent)', 'account', '23', 'pending', 'approved', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:54:42'),
(264, 1, 'admin@gov.ph', 'ACCOUNT_DISABLE', 'Disabled account for user: airam licerio (airamjesse@gmail.com)', 'account', '10', 'enabled', 'disabled', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:57:15'),
(265, 1, 'admin@gov.ph', 'NOTIFICATION_DELETE', 'Deleted notification: \'test\'', 'notification', '13', 'test', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:58:43'),
(266, 1, 'admin@gov.ph', 'NOTIFICATION_DELETE', 'Deleted notification: \'sa wakas\'', 'notification', '15', 'sa wakas', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:58:49'),
(267, 1, 'admin@gov.ph', 'NOTIFICATION_DELETE', 'Deleted notification: \'to na\'', 'notification', '14', 'to na', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `barangay_officials`
--

CREATE TABLE `barangay_officials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_officials`
--

INSERT INTO `barangay_officials` (`id`, `name`, `position`, `profile_picture`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'Hon. Maria Santos', 'Barangay Captain', NULL, 1, '2025-11-25 11:17:52', '2025-11-25 11:17:52'),
(2, 'Ms. Ana Cruz', 'Secretary', NULL, 2, '2025-11-25 11:17:52', '2025-11-25 11:17:52'),
(3, 'Mr. Pedro Garcia', 'Treasurer', NULL, 3, '2025-11-25 11:41:19', '2025-11-25 11:41:19'),
(4, 'Shou Ballesteros', 'Lead Dev', NULL, 4, '2025-11-25 11:50:34', '2025-11-25 11:50:34'),
(5, 'Airam Licerio', 'Figma Gods', NULL, 5, '2025-11-25 11:55:01', '2025-11-25 11:55:01'),
(6, 'Tyron Chavez', 'Drug Lord', NULL, 6, '2025-11-25 11:56:16', '2025-11-25 11:56:16'),
(9, 'Andalite', 'Valo', NULL, 7, '2025-11-30 17:00:54', '2025-11-30 17:00:54'),
(12, 'Leah Banguilan', 'Boss', NULL, 8, '2025-12-04 17:33:50', '2025-12-04 17:33:50'),
(13, 'Steven', 'Roblox', 'uploads/officials/official_1764870265_6931c879d2c46.png', 9, '2025-12-04 17:44:25', '2025-12-04 17:44:25');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `type` enum('NEWS','EVENT') NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `title`, `date`, `description`, `created_at`, `updated_at`) VALUES
(10, 'NEWS', 'testing', 'November 21, 2025', 'wawa', '2025-11-24 07:33:34', '2025-11-24 07:33:34');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT (current_timestamp() + interval 15 minute),
  `used` tinyint(1) DEFAULT 0,
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `otp`, `created_at`, `expires_at`, `used`, `reset_token`) VALUES
(12, 'admin@gov.qc.ph', '639117', '2025-11-23 20:45:24', '2025-11-23 21:45:24', 0, NULL),
(16, 'shouballesteros5@gmail.com', '284435', '2025-11-24 07:35:06', '2025-11-24 08:35:06', 0, NULL),
(25, 'ballesteros.shou.nicol@gmail.com', '184332', '2025-12-01 18:31:40', '2025-12-01 19:31:40', 0, NULL),
(26, 'shouballesteros4@gmail.com', '130178', '2025-12-04 17:45:50', '2025-12-04 18:45:50', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `ticket_id` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `priority` varchar(55) NOT NULL DEFAULT '',
  `contact` varchar(15) NOT NULL,
  `requesttype` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `status` enum('PENDING','UNDER REVIEW','IN PROGRESS','READY','COMPLETED','REJECTED') DEFAULT 'PENDING',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `ticket_id`, `user_id`, `fullname`, `priority`, `contact`, `requesttype`, `description`, `status`, `submitted_at`, `updated_at`) VALUES
(9, 'BHR-2025-000004', 2, 'john@gmail.com', '', 'N/A', 'Barangay Certificate of Household Membership', 'dwasd', 'PENDING', '2025-11-16 16:40:41', '2025-11-24 05:52:38'),
(13, 'BHR-2025-000005', 2, 'john@gmail.com', 'HIGH', '09167039130', 'Barangay Clearance', 'haha', 'IN PROGRESS', '2025-11-17 20:33:56', '2025-11-24 05:52:21'),
(14, 'BHR-2025-000006', 2, 'john@gmail.com', 'LOW', '09167039130', 'Barangay Construction / Renovation Permit', 'test', 'PENDING', '2025-11-17 20:34:10', '2025-11-23 21:25:22'),
(16, 'BHR-2025-000008', 9, 'shouballesteros5@gmail.com', 'HIGH', '09167039130', 'Barangay Event Permit (Sound Permit, Activity Perm', 'urgent', 'READY', '2025-11-24 11:12:57', '2025-11-24 11:13:32'),
(17, 'BHR-2025-000009', 6, 'shouballesteros4@gmail.com', 'HIGH', '09167039130', 'Clearance of No Objection', 'pa shuk', 'COMPLETED', '2025-11-24 11:16:37', '2025-11-24 11:18:00'),
(20, 'BHR-2025-000012', 2, 'john@gmail.com', 'HIGH', '0', 'Barangay Certification for Solo Parent (Referral f', 'for school', 'COMPLETED', '2025-11-25 09:26:26', '2025-11-25 10:47:30'),
(21, 'BHR-2025-000013', 2, 'john@gmail.com', 'MEDIUM', '0', 'Barangay Business Clearance', 'dada', 'PENDING', '2025-11-25 10:54:33', '2025-11-25 10:54:33'),
(22, 'BHR-2025-000014', 10, 'airamjesse@gmail.com', 'LOW', '0', 'Barangay Blotter / Incident Report Copy', 'gago', 'READY', '2025-11-25 10:54:53', '2025-12-04 16:09:55'),
(23, 'BHR-2025-000015', 10, 'airamjesse@gmail.com', 'LOW', '0', 'Barangay Clearance for Street Vending', 'gaga', 'COMPLETED', '2025-11-25 10:54:58', '2025-11-28 00:51:56'),
(31, 'BHR-2025-197360', 2, 'john@gmail.com', 'HIGH', '09167039130', 'Barangay Clearance', 'gsdf', 'REJECTED', '2025-12-01 19:00:29', '2025-12-04 16:58:49'),
(32, 'BHR-2025-136754', 2, 'john@gmail.com', 'MEDIUM', '09167039130', 'Barangay Certification for PWD', 'ada', 'REJECTED', '2025-12-01 19:50:39', '2025-12-04 16:58:02'),
(33, 'BHR-2025-924084', 2, 'john@gmail.com', 'HIGH', '09167039130', 'Barangay Certificate of Guardianship', 'daws', 'REJECTED', '2025-12-01 19:53:43', '2025-12-04 16:54:31'),
(37, 'BHR-2025-341428', 2, 'john@gmail.com', 'HIGH', '09167039130', 'Barangay Certificate for Water/Electric Connection', 'dada', 'PENDING', '2025-12-04 17:32:15', '2025-12-04 17:32:15');

-- --------------------------------------------------------

--
-- Table structure for table `request_updates`
--

CREATE TABLE `request_updates` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `message` text DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT 'Admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_updates`
--

INSERT INTO `request_updates` (`id`, `request_id`, `status`, `message`, `updated_by`, `created_at`) VALUES
(4, 13, 'IN PROGRESS', '', 'Admin', '2025-11-24 05:52:21'),
(5, 13, 'IN PROGRESS', '', 'Admin', '2025-11-24 05:52:23'),
(6, 13, 'IN PROGRESS', '', 'Admin', '2025-11-24 05:52:24'),
(7, 13, 'IN PROGRESS', '', 'Admin', '2025-11-24 05:52:25'),
(8, 13, 'IN PROGRESS', '', 'Admin', '2025-11-24 05:52:26'),
(9, 13, 'IN PROGRESS', '', 'Admin', '2025-11-24 05:52:27'),
(10, 13, 'IN PROGRESS', '', 'Admin', '2025-11-24 05:52:27'),
(11, 13, 'IN PROGRESS', '', 'Admin', '2025-11-24 05:52:28'),
(12, 9, 'PENDING', '', 'Admin', '2025-11-24 05:52:38'),
(17, 16, 'READY', 'game sah', 'Admin', '2025-11-24 11:13:32'),
(18, 17, 'COMPLETED', '', 'Admin', '2025-11-24 11:18:00'),
(22, 20, 'COMPLETED', '', 'Admin', '2025-11-25 10:47:30'),
(23, 23, 'COMPLETED', '', 'Admin', '2025-11-28 00:51:56'),
(25, 31, 'COMPLETED', 'nah', 'Admin', '2025-12-01 19:18:49'),
(35, 33, 'PENDING', '', 'Admin', '2025-12-04 15:59:57'),
(36, 32, 'PENDING', 'd', 'Admin', '2025-12-04 16:01:50'),
(37, 32, 'COMPLETED', '', 'Admin', '2025-12-04 16:04:03'),
(38, 32, 'IN PROGRESS', '', 'Admin', '2025-12-04 16:04:21'),
(39, 33, 'COMPLETED', '', 'Admin', '2025-12-04 16:05:11'),
(40, 33, 'READY', 'aha', 'Admin', '2025-12-04 16:05:23'),
(42, 22, 'READY', '', 'Admin', '2025-12-04 16:09:55'),
(48, 33, 'REJECTED', 'Request rejected. Reason: ah', 'Admin', '2025-12-04 16:54:31'),
(49, 32, 'REJECTED', 'Request rejected. Reason: a', 'Admin', '2025-12-04 16:58:02'),
(50, 31, 'REJECTED', 'Request rejected. Reason: haha', 'Admin', '2025-12-04 16:58:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_usertype` (`usertype`);

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin_id` (`admin_id`),
  ADD KEY `idx_action_type` (`action_type`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_target` (`target_type`,`target_id`),
  ADD KEY `idx_admin_action_date` (`admin_id`,`action_type`,`created_at`),
  ADD KEY `idx_account_actions` (`action_type`,`target_type`,`target_id`);

--
-- Indexes for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_expires_at` (`expires_at`),
  ADD KEY `idx_reset_token` (`email`),
  ADD KEY `idx_email_used` (`email`,`used`,`expires_at`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_id` (`ticket_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_submitted_at` (`submitted_at`);

--
-- Indexes for table `request_updates`
--
ALTER TABLE `request_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_request_id` (`request_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `request_updates`
--
ALTER TABLE `request_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD CONSTRAINT `audit_trail_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `account` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `account` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `request_updates`
--
ALTER TABLE `request_updates`
  ADD CONSTRAINT `request_updates_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
