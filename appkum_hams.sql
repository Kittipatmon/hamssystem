-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2026 at 09:26 AM
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
-- Database: `hamssystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `activitys`
--

CREATE TABLE `activitys` (
  `activitys_id` bigint(20) UNSIGNED NOT NULL COMMENT 'รหัสข้อเสนอ',
  `proposer_name` varchar(255) DEFAULT NULL COMMENT 'ชื่อผู้เสนอ (ไม่บังคับ)',
  `organization` varchar(255) DEFAULT NULL COMMENT 'หน่วยงาน / แผนก (ไม่บังคับ)',
  `activity_title` varchar(255) NOT NULL COMMENT 'หัวข้อกิจกรรม',
  `activity_detail` text NOT NULL COMMENT 'รายละเอียดกิจกรรม',
  `reason` text DEFAULT NULL COMMENT 'หมายเหตุ / เหตุผลในการเสนอ',
  `proposed_date` date DEFAULT NULL COMMENT 'วันที่ต้องการจัด (ไม่บังคับ)',
  `attachment_path` varchar(255) DEFAULT NULL COMMENT 'ไฟล์แนบ (ถ้ามี)',
  `status` enum('pending','approved','rejected') DEFAULT 'pending' COMMENT 'สถานะข้อเสนอ',
  `review_comment` text DEFAULT NULL COMMENT 'คำตอบ / ข้อเสนอแนะจากผู้พิจารณา',
  `reviewed_at` datetime DEFAULT NULL COMMENT 'วันเวลาที่พิจารณาแล้ว',
  `reviewed_by` varchar(255) DEFAULT NULL COMMENT 'ชื่อผู้พิจารณา (กรณีไม่มีระบบ login)',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP ของผู้เสนอ (ใช้ตรวจสอบ)',
  `user_agent` text DEFAULT NULL COMMENT 'ข้อมูลเบราว์เซอร์ (optional)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='ตารางสำหรับการนำเสนอกิจกรรม (ไม่ต้อง login)';

--
-- Dumping data for table `activitys`
--

INSERT INTO `activitys` (`activitys_id`, `proposer_name`, `organization`, `activity_title`, `activity_detail`, `reason`, `proposed_date`, `attachment_path`, `status`, `review_comment`, `reviewed_at`, `reviewed_by`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'test', 'testtt', 'testtttttt', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, '2025-06-06 09:41:23', '2025-06-06 09:41:23'),
(2, NULL, NULL, 'test1', 'testtt', 'testtttttt', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, '2025-06-06 09:41:23', '2025-06-09 02:53:35'),
(3, NULL, NULL, 'test2', 'testtt', 'testtttttt', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, '2025-06-06 09:41:23', '2025-06-09 02:53:37'),
(4, NULL, NULL, 'test3', 'testtt', 'testtttttt', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, '2025-06-06 09:41:23', '2025-06-09 02:53:39'),
(5, NULL, NULL, 'test4', 'testtt', 'testtttttt', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, '2025-06-06 09:41:23', '2025-06-09 02:53:41'),
(6, NULL, NULL, 'test5', 'testtt', 'testtttttt', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, '2025-06-06 09:41:23', '2025-06-09 02:53:42'),
(7, NULL, 'ict', 'tyttt', 'tyttt', 'tyttt', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, '2025-07-04 04:26:32', '2025-07-04 04:26:32'),
(8, NULL, 'ict', 'tyttt', 'tyttt', 'tyttt', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, '2025-07-04 04:26:59', '2025-07-04 04:26:59'),
(9, NULL, NULL, 'icttest', 'icttest', 'icttest', '2025-07-22', NULL, 'pending', NULL, NULL, NULL, NULL, NULL, '2025-07-22 05:57:55', '2025-07-22 05:57:55'),
(10, NULL, NULL, 'เที่ยวบริษัท', 'ท่องเที่ยวบริษัท', 'อยากเที่ยว', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, '2025-07-23 06:45:48', '2025-07-23 06:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `announcement_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'หัวข้อประกาศ',
  `content` text NOT NULL COMMENT 'รายละเอียดประกาศ',
  `published_date` date NOT NULL COMMENT 'วันที่ประกาศ',
  `image_path` text DEFAULT NULL COMMENT 'รูปภาพประกอบประกาศ (เช่น ป้ายเตือน)',
  `is_urgent` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'เร่งด่วนหรือไม่',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`announcement_id`, `title`, `content`, `published_date`, `image_path`, `is_urgent`, `created_at`, `updated_at`) VALUES
(2, 'ออกแบบ UI', 'ใช้เวลาการออกแบบประมาณ 2 เดือน ถ้าหากเกินกำหนดสามารถสอบถามได้ว่าเพราะอะไร', '2025-05-29', 'images/announcements/1749009373_683fc3dd03886.jpg', 1, '2025-05-28 00:45:28', '2025-06-06 01:19:20'),
(6, 'testing', 'testtttttttttttt', '2025-06-13', 'images/announcements/1749809862_684bfac6a6c06.jpg', 1, '2025-06-13 03:17:42', '2025-06-13 03:17:42'),
(7, 'testing', 'testtttttttttttttttttttttttttttttttttttttttt', '2025-06-16', 'images/announcements/1749809922_684bfb0260857.jpg', 1, '2025-06-13 03:18:42', '2025-06-13 03:18:42'),
(16, 'เจ้าหน้าที่ Coway เข้าดำเนินการ', 'เจ้าหน้าที่ Coway เข้าดำเนินการเปลี่ยนไส้กรองเครื่องกดน้ำร้อน-น้ำเย็น\r\nบริเวณชั้น 2,3,5,6 จึงทำให้ไม่สามารถใช้งานได้ในขณะนี้\r\nขออภัยในความไม่สะดวก', '2025-07-18', 'images/announcements/1752826786_687a03a2dd701.jpg', 1, '2025-07-18 01:19:46', '2025-07-18 01:19:46'),
(17, 'testออกแบบreport2', 'testtt', '2025-07-29', 'images/announcements/1775098988_69cddc6c02ff3.jpg', 0, '2025-07-28 21:42:12', '2026-04-06 00:24:13');

-- --------------------------------------------------------

--
-- Table structure for table `approval_settings`
--

CREATE TABLE `approval_settings` (
  `approval_settings_id` int(10) UNSIGNED NOT NULL COMMENT 'รหัส',
  `department_name` varchar(100) NOT NULL COMMENT 'ชื่อแผนก',
  `approver_user_id` int(10) UNSIGNED NOT NULL COMMENT 'user_id ของหัวหน้าแผนกที่มีสิทธิ์อนุมัติ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='กำหนดผู้อนุมัติในแต่ละแผนก (หัวหน้า)';

--
-- Dumping data for table `approval_settings`
--

INSERT INTO `approval_settings` (`approval_settings_id`, `department_name`, `approver_user_id`, `created_at`, `updated_at`) VALUES
(1, 'ICT', 2, '2025-06-20 10:13:34', '2025-06-20 10:13:34');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `bookings_id` int(11) NOT NULL COMMENT 'รหัสรายการจอง',
  `booking_code` varchar(25) NOT NULL COMMENT 'เลขที่ใบจอง',
  `user_id` int(11) NOT NULL COMMENT 'รหัสผู้จอง (เชื่อมกับ users)',
  `vehicle_id` int(11) NOT NULL COMMENT 'รหัสรถที่ถูกจอง (เชื่อมกับ vehicles)',
  `purpose` text DEFAULT NULL COMMENT 'วัตถุประสงค์ในการใช้รถ',
  `project_owner` varchar(100) DEFAULT NULL COMMENT 'เจ้าของงาน/ผู้รับผิดชอบภารกิจ',
  `destination` varchar(200) DEFAULT NULL COMMENT 'สถานที่ปลายทางที่จะเดินทางไป',
  `travel_date` date DEFAULT NULL COMMENT 'วันที่เดินทางจริง',
  `passenger_count` int(11) DEFAULT NULL COMMENT 'จำนวนผู้โดยสาร',
  `start_datetime` datetime DEFAULT NULL COMMENT 'วันและเวลาเริ่มใช้งานรถ',
  `end_datetime` datetime DEFAULT NULL COMMENT 'วันและเวลาสิ้นสุดการใช้งานรถ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้างรายการจอง',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางเก็บข้อมูลการจองรถโดยไม่ต้องอนุมัติ' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_id` int(11) NOT NULL,
  `cart_item_id` int(11) DEFAULT NULL,
  `cart_code` varchar(50) DEFAULT NULL,
  `cart_name` varchar(255) DEFAULT NULL,
  `cart_quantity` int(11) DEFAULT NULL,
  `cart_quantity_pack` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_id`, `cart_item_id`, `cart_code`, `cart_name`, `cart_quantity`, `cart_quantity_pack`, `user_id`, `created_at`, `updated_at`) VALUES
(337, 74, '3150120', 'กระดาษกาวย่น สีขาวครีม', 2, NULL, 652, '2026-06-25 18:28:46', '2026-06-30 23:38:04');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` bigint(20) UNSIGNED NOT NULL COMMENT 'รหัสความคิดเห็น',
  `name` varchar(100) DEFAULT NULL COMMENT 'ชื่อผู้แสดงความเห็น (ไม่บังคับ)',
  `message` text NOT NULL COMMENT 'ข้อความแสดงความคิดเห็น',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันเวลาแสดงความคิดเห็น',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'วันเวลาอัปเดตล่าสุด'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='ตารางเก็บความคิดเห็นจากผู้ใช้งาน';

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `name`, `message`, `created_at`, `updated_at`) VALUES
(1, NULL, 'test', '2025-06-06 10:32:54', '2025-06-06 10:32:54'),
(2, NULL, 'ttt', '2025-07-04 04:25:36', '2025-07-04 04:25:36'),
(3, NULL, 'icttest', '2025-07-22 05:57:37', '2025-07-22 05:57:37'),
(4, NULL, 'หิวข้าว', '2025-07-23 06:45:11', '2025-07-23 06:45:11');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaints_id` bigint(20) NOT NULL COMMENT 'รหัสร้องเรียน',
  `complaints_code` varchar(255) NOT NULL COMMENT 'รหัสการร้องเรียน',
  `title` varchar(255) NOT NULL COMMENT 'หัวข้อเรื่องที่ร้องเรียน',
  `description` text NOT NULL COMMENT 'รายละเอียดเนื้อหาการร้องเรียน',
  `category` varchar(255) DEFAULT NULL COMMENT 'ลักษณะหรือประเภทของเรื่องร้องเรียน เช่น ทุจริต, พฤติกรรมไม่เหมาะสม ฯลฯ',
  `location` varchar(255) DEFAULT NULL COMMENT 'สถานที่เกิดเหตุ',
  `incident_date` date DEFAULT NULL COMMENT 'วันที่เกิดเหตุการณ์ที่ร้องเรียน',
  `incident_time` time DEFAULT NULL COMMENT 'เวลาที่เกิดเหตุการณ์ที่ร้องเรียน',
  `accused_unit_or_person` text DEFAULT NULL COMMENT 'ชื่อบุคคลหรือหน่วยงานที่ถูกร้องเรียน (หากระบุ)',
  `evidence_file` text DEFAULT NULL COMMENT 'ชื่อไฟล์/พาธหลักฐานที่แนบ เช่น รูปภาพหรือเอกสาร',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'วันเวลาที่บันทึกข้อมูล',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'วันเวลาที่มีการแก้ไขล่าสุด'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaints_id`, `complaints_code`, `title`, `description`, `category`, `location`, `incident_date`, `incident_time`, `accused_unit_or_person`, `evidence_file`, `created_at`, `updated_at`) VALUES
(1, '', 'testing', 'testingการดำเนินงาน', 'บริการ', 'Kumwell ชั้น 3', '2025-06-09', '16:00:00', 'ไม่มี', 'evidence_files/42AfgN7HfefXYtas4jNMTGLS3vs7gF1aH7Na0ygj.pdf', '2025-06-06 00:10:38', '2025-06-06 00:10:38'),
(2, 'CP25060002', 'test', 'testttt', 'บริการ', 'test', '2025-06-09', '12:00:00', NULL, NULL, '2025-06-08 21:29:04', '2025-06-08 21:29:04'),
(3, 'CP25070001', 'ttt', 'tttt', 'บริการ', 'ttt', '2025-07-04', '12:00:00', 'tttt', NULL, '2025-07-04 04:25:29', '2025-07-04 04:25:29'),
(4, 'CP25070002', 'KumwellNewSoftware', 'icttest', 'อื่นๆ', 'ชั้น 3 แผนก ICT', '2025-07-22', '10:00:00', NULL, NULL, '2025-07-22 05:58:16', '2025-07-22 05:58:16'),
(5, 'CP25070003', 'test', 'test', 'สภาพแวดล้อม', 'test', '2025-07-30', '16:00:00', 'test', 'evidence_files/bN1CcoWiEMg5QU47Xax5OV24AMPNA1HerU1DD2XS.pdf', '2025-07-23 06:46:39', '2025-07-23 06:46:39');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) DEFAULT 0,
  `sect_id` int(11) DEFAULT NULL COMMENT 'รหัสฝ่าย'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `name`, `code`, `phone`, `email`, `location`, `created_at`, `updated_at`, `status`, `sect_id`) VALUES
(1, 'Testing', 'TT', NULL, NULL, '3', '2025-05-14 21:26:46', '2025-06-02 06:27:13', 0, NULL),
(2, 'Research and innovation (RI)', 'RI', NULL, NULL, NULL, '2025-05-14 23:40:44', '2025-05-15 00:04:28', 0, NULL),
(3, 'Accounting', 'AT', NULL, NULL, NULL, '2025-05-14 23:42:32', '2025-05-14 23:42:32', 0, NULL),
(4, 'Domestic Sale', 'DS', NULL, NULL, NULL, '2025-05-14 23:43:00', '2025-05-14 23:43:00', 0, NULL),
(5, 'Engineering Solution', 'ES', NULL, NULL, NULL, '2025-05-14 23:43:21', '2025-05-14 23:43:21', 0, NULL),
(6, 'Export', 'EX', NULL, NULL, NULL, '2025-05-14 23:43:49', '2025-05-14 23:43:49', 0, NULL),
(7, 'Lab test', 'LT', NULL, NULL, NULL, '2025-05-14 23:44:03', '2025-05-14 23:44:03', 0, NULL),
(8, 'Research and innovation (RI-1)', 'RI-1', NULL, NULL, NULL, '2025-05-14 23:44:28', '2025-05-15 00:03:28', 0, NULL),
(9, 'Research and innovation (RI-2)', 'RI-2', NULL, NULL, NULL, '2025-05-14 23:44:37', '2025-05-15 00:03:48', 0, NULL),
(10, 'Research and innovation (RI-3)', 'RI-3', NULL, NULL, NULL, '2025-05-14 23:44:48', '2025-05-15 00:03:58', 0, NULL),
(11, 'Research and innovation (RI-4)', 'RI-4', NULL, NULL, NULL, '2025-05-14 23:44:56', '2025-05-15 00:04:10', 0, NULL),
(12, 'HAMS', 'HAMS', NULL, NULL, NULL, '2025-05-14 23:46:19', '2025-05-14 23:46:19', 0, NULL),
(13, 'HAM', 'HAM', NULL, NULL, NULL, '2025-05-29 04:50:37', '2025-05-29 04:50:37', 0, NULL),
(14, 'ICT', 'ICT', NULL, NULL, NULL, '2025-06-02 06:27:26', '2025-06-02 06:27:26', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL COMMENT 'รหัสพนักงาน (ลำดับอัตโนมัติ)',
  `full_name` varchar(100) NOT NULL COMMENT 'ชื่อ-นามสกุล',
  `nickname` varchar(50) DEFAULT NULL COMMENT 'ชื่อเล่น',
  `email` varchar(100) DEFAULT NULL COMMENT 'อีเมลพนักงาน',
  `department` varchar(100) DEFAULT NULL COMMENT 'แผนกที่สังกัด',
  `phone` varchar(20) DEFAULT NULL COMMENT 'เบอร์โทรศัพท์',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้างรายการจอง',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางเก็บข้อมูลพนักงาน' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `full_name`, `nickname`, `email`, `department`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'คุณวรานันทน์ เถาธรรมพิทักษ์', 'ปูณร์', 'waranan.to@kumwell.com', 'HAMS', '3321', '2025-06-02 10:57:43', '2025-06-02 10:57:43'),
(3, 'ป้อมรปภ', 'น้าสุทธิ', NULL, 'HAMS', '3272', '2025-06-04 02:03:04', '2025-06-04 02:11:17'),
(4, 'คุณรุ่งทิพย์ พ่วงพันธ์', 'พี่ทิพย์', 'Rungthip.pu@kumwell.com', 'HAMS', '3001', '2025-06-04 02:03:48', '2025-06-04 02:03:58'),
(5, 'คุณวานิสา อารมณ์ชื่น', 'น้องวิว อาคาร', 'Wanisa.Ar@kumwell.com', 'HAMS', '3330', '2025-06-04 02:05:12', '2025-06-04 02:05:12'),
(6, 'คุณผกามาศ หาญณรงค์', 'น้องแบม', 'Phakamat.Ha@kumwell.com', 'HAMS', '3002', '2025-06-04 02:05:58', '2025-06-04 02:05:58'),
(7, 'คุณประภาส อัมรินทร์', NULL, 'prapas.am@kumwell.com', 'Infra', '3339', '2025-06-04 02:06:54', '2025-06-04 02:11:28'),
(8, 'คุณชลธิชา เปาชม', 'นุช', 'cholticha.po@kumwell.com', 'Infra', '3338', '2025-06-04 02:07:24', '2025-06-04 02:07:24'),
(12, 'คุณไพศร บู', 'ศร', NULL, 'Infra', '3341', '2025-06-04 02:09:54', '2025-06-04 02:11:15');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `img` text NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('new','in_progress','resolved') DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_numbers`
--

CREATE TABLE `general_numbers` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `value` int(11) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `global_approvers`
--

CREATE TABLE `global_approvers` (
  `global_approvers_id` int(10) UNSIGNED NOT NULL,
  `role` enum('manager_hams','committee') NOT NULL COMMENT 'ตำแหน่ง: ผู้จัดการ HAMS หรือ กรรมการบ้านพัก',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'user_id ที่รับผิดชอบตำแหน่งนี้',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='ผู้อนุมัติระดับองค์กร เช่น ผู้จัดการ HAMS และกรรมการบ้านพัก';

-- --------------------------------------------------------

--
-- Table structure for table `hams_permissions`
--

CREATE TABLE `hams_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_hams_editor` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hams_permissions`
--

INSERT INTO `hams_permissions` (`id`, `user_id`, `is_hams_editor`, `created_at`, `updated_at`) VALUES
(1, 6, 0, '2026-04-09 20:34:27', '2026-04-09 20:41:54'),
(2, 348, 0, '2026-04-09 21:01:47', '2026-04-09 21:04:02'),
(3, 325, 0, '2026-04-09 21:10:09', '2026-04-09 21:10:17'),
(4, 669, 0, '2026-04-16 19:09:53', '2026-04-16 19:10:55');

-- --------------------------------------------------------

--
-- Table structure for table `hams_permission_logs`
--

CREATE TABLE `hams_permission_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `target_user_id` int(11) NOT NULL,
  `granted_by_user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hams_permission_logs`
--

INSERT INTO `hams_permission_logs` (`id`, `target_user_id`, `granted_by_user_id`, `action`, `created_at`, `updated_at`) VALUES
(1, 6, 664, 'granted', '2026-04-09 20:34:27', '2026-04-09 20:34:27'),
(2, 6, 664, 'revoked', '2026-04-09 20:41:54', '2026-04-09 20:41:54'),
(3, 348, 664, 'granted', '2026-04-09 21:01:47', '2026-04-09 21:01:47'),
(4, 348, 664, 'revoked', '2026-04-09 21:04:02', '2026-04-09 21:04:02'),
(5, 325, 664, 'granted', '2026-04-09 21:10:09', '2026-04-09 21:10:09'),
(6, 325, 664, 'revoked', '2026-04-09 21:10:17', '2026-04-09 21:10:17'),
(7, 669, 664, 'granted', '2026-04-16 19:09:54', '2026-04-16 19:09:54'),
(8, 669, 664, 'revoked', '2026-04-16 19:10:55', '2026-04-16 19:10:55');

-- --------------------------------------------------------

--
-- Table structure for table `hams_special_rights`
--

CREATE TABLE `hams_special_rights` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID จากตาราง appkum_user.employees',
  `granted_by` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID ของผู้ที่ให้สิทธิ์',
  `right_type` varchar(255) NOT NULL DEFAULT 'report_access',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `housing_committees`
--

CREATE TABLE `housing_committees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `housing_committees`
--

INSERT INTO `housing_committees` (`id`, `user_id`, `role`, `order`, `created_at`, `updated_at`) VALUES
(7, 519, 'หัวหน้าบ้านพัก', 1, '2026-03-31 02:13:50', '2026-04-05 23:10:44'),
(8, 669, 'ผู้ช่วยหัวหน้าบ้านพัก', 1, '2026-03-31 02:14:06', '2026-04-16 20:19:47'),
(9, 642, 'ผู้ช่วยหัวหน้าบ้านพัก', 2, '2026-03-31 02:14:42', '2026-04-16 20:21:49'),
(11, 664, 'ผู้ช่วยหัวหน้าบ้านพัก', 3, '2026-03-31 02:28:56', '2026-04-16 20:30:24'),
(13, 664, 'เท่', 1, '2026-04-16 20:22:45', '2026-04-16 20:22:45');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_code` varchar(50) NOT NULL COMMENT 'รหัสสินค้า',
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `quantity` int(11) DEFAULT 0 COMMENT 'จำนวนขิ้น',
  `items_per_pack` int(11) DEFAULT 0 COMMENT 'จำนวนแพ็ค',
  `type_id` int(11) DEFAULT NULL COMMENT 'ประเภทอุปกรณ์',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `item_pic` varchar(255) NOT NULL COMMENT 'รูป',
  `per_unit` int(11) DEFAULT 0 COMMENT 'ราคาต่อชิ้น',
  `per_pack` int(11) DEFAULT 0 COMMENT 'ราคาแพ็ค',
  `send_status` int(11) DEFAULT 0 COMMENT '0= ใช้งาน 1=ไม่ใช้งาน'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_code`, `name`, `description`, `quantity`, `items_per_pack`, `type_id`, `created_at`, `updated_at`, `status`, `item_pic`, `per_unit`, `per_pack`, `send_status`) VALUES
(1, '1091566', 'ปากกาลูกลื่น0.5มม. น้ำเงิน', 'ปากกาลูกลื่น0.5มม.น้ำเงิน \r\nควอนตั้ม Gelo Plus X5', 66, 13, 2, '2026-04-16 01:26:34', '2026-04-16 01:26:34', 0, '1762933195.png', 5, 250, 0),
(2, '1003023', 'ปากกาลูกลื่น0.5มม. สีแดง', 'QUANTUM ปากกาลูกลื่น 0.5 มม. รุ่น Skate 555 สีแดง', 53, 6, 2, '2026-06-17 01:30:00', '2026-06-17 01:30:00', 0, '1762935562.png', 5, 245, 0),
(3, '1006347', 'ดินสอดำ 2B', 'ดินสอดำ 2B สเต็ดเล่อร์ Exam', 66, 4, 2, '2026-06-17 01:30:00', '2026-06-17 01:30:00', 0, '1762933205.png', 3, 152, 0),
(4, '1000790', 'ปากกามาร์คเกอร์ 2 หัว สีน้ำเงิน', 'ปากกามาร์คเกอร์ 2 หัว น้ำเงิน ตราม้า\r\nหัวแหลม ขนาดหัวปากกา 2 มม.\r\nหัวตัด ขนาดเส้น 5 มม.', 10, 0, 2, '2026-04-16 02:10:12', '2026-04-16 02:10:12', 0, '', 15, 142, 0),
(5, '1000796', 'ปากกามาร์คเกอร์ 2 หัว สีแดง', 'ปากกามาร์คเกอร์ 2 หัว ตราม้า หมึกสีแดง\r\nหัวแหลม ขนาดหัวปากกา 2 มม.\r\nหัวตัด ขนาดเส้น 5 มม.', 17, 0, 2, '2025-11-12 07:38:48', '2025-10-17 16:50:49', 0, '', 15, 142, 0),
(6, '1001081', 'ปากกาเน้นข้อความ สีเหลือง', 'QUANTUM ปากกาเน้นข้อความ รุ่น QH710YL/P5 สีเหลือง', 13, 0, 2, '2025-11-12 07:40:28', '2025-11-12 07:40:28', 0, '1762933228.png', 25, 109, 0),
(7, '1000906', 'ปากกาไวท์บอร์ด สีน้ำเงิน', 'ปากกาไวท์บอร์ดหัวกลม \r\nสีน้ำเงิน ยี่ห้อ Pilot รุ่น WBMKM\r\nขนาดหัวปากกา: 2 มม.', 12, 5, 2, '2025-11-12 07:40:47', '2025-11-12 07:40:47', 0, '1762933247.png', 25, 260, 0),
(71, '2430041', 'หมึกเติมแท่นประทับกันน้ำ สีดำ', 'หมึกเติมแท่นประทับกันน้ำ ขนาด 30 ซีซี สีดำ', 2, 0, 2, '2025-11-14 04:28:25', '2025-11-14 04:28:25', 0, '', 18, 0, 0),
(8, '1000907', 'ปากกาไวท์บอร์ด สีแดง', 'ปากกาไวท์บอร์ดหัวกลม \r\nสีแดง ยี่ห้อ Pilot รุ่น WBMKM\r\nขนาดหัวปากกา: 2 มม.', 27, 10, 2, '2026-04-01 02:50:31', '2026-04-01 02:50:31', 0, '1762933298.png', 25, 260, 0),
(9, '1009411', 'ไม้บรรทัดพลาสติก 12 นิ้ว', 'ONE ไม้บรรทัดพลาสติก 12 นิ้ว ONER001', 4, 15, 2, '2025-11-12 07:41:51', '2025-11-12 07:41:51', 0, '1762933311.jpg', 8, NULL, 0),
(10, '1008727', 'เทปลบคำผิด 5มม.x8ม.', 'เทปลบคำผิด ONE CR-K25 5มม.x8ม. คละสี', 9, 0, 2, '2025-11-13 07:16:27', '2025-11-13 07:16:27', 0, '1762933330.jpg', 25, NULL, 0),
(11, '1002916', 'ยางลบดินสอ', 'ยางลบดินสอ สเต็ดเล่อร์ 526 35F', 45, 0, 2, '2025-11-12 07:42:20', '2025-11-12 07:42:20', 0, '1762933340.png', 4, 239, 0),
(12, '7000888', 'แปรงลบกระดาน', 'HORSE แปรงลบกระดาน รุ่น H-01 (คละสี)\r\nสามารถลบได้ทั้งกระดานไวท์บอร์ดและกระดานดำ', 0, 0, 2, '2025-11-12 07:42:36', '2025-11-12 07:42:36', 0, '1762933356.jpg', 27, NULL, 0),
(13, '2002981', 'มีดคัตเตอร์ 18 มม.', 'มีดคัตเตอร์ 51 สีเงิน อโรม่า', 1, 0, 2, '2026-03-04 03:47:17', '2026-03-04 03:47:17', 0, '1762933420.jpg', 45, NULL, 0),
(14, '2004640', 'มีดคัตเตอร์ 9 มม.', 'ONE มีดคัตเตอร์ รุ่น SX48-12 สีเงิน ขนาด 9 มม.', 10, 0, 2, '2025-11-12 07:44:00', '2025-11-12 07:44:00', 0, '1762933440.png', 21, 245, 0),
(15, '2003414', 'ใบมีดคัตเตอร์ 18 มม. (หลอด)', 'ใบมีดคัตเตอร์ ใบโพธิ์ L-150  \r\n10 หลอด/กล่อง (6 ใบ/หลอด), 1 กล่อง', 5, 0, 2, '2025-11-12 07:38:48', '2025-10-03 10:21:22', 0, '', 12, 120, 0),
(16, '2003413', 'ใบมีดคัตเตอร์ 9 มม. (หลอด)', 'ใบมีดดัตเตอร์ใบโพธิ์ ใบโพธิ์ A-100\r\n10 หลอด/กล่อง (6 ใบ/หลอด), 1 กล่อง', 38, 0, 2, '2025-11-12 07:38:48', '2025-09-10 16:18:21', 0, '', 10, 96, 0),
(17, '2071810', 'กรรไกร 7 นิ้ว', 'กรรไกร 7 นิ้ว สก๊อตช์ Multi Purpose 1427', 2, 0, 2, '2025-11-12 07:38:48', '2025-10-08 16:26:16', 0, '', 69, NULL, 0),
(18, '3001727', 'กาวแท่ง 8.2 กรัม', 'UHU กาวแท่ง สีขาว 8.2 กรัม (แพ็ค6แท่ง)', 4, 0, 2, '2025-11-12 07:38:48', '2025-10-08 13:54:41', 0, '', 31, 449, 0),
(19, '3121150', 'แท่นตัดเทป แกน 1 นิ้ว', 'แท่นตัดเทป แกน 1 นิ้ว ตราม้า H-15.\r\nรองรับขนาดแกนเทป 1 นิ้ว.\r\nรองรับขนาดหน้าเทปกว้าง 1/2, 3/4 นิ้ว.', 0, 0, 2, '2025-11-12 07:38:48', '2025-10-17 16:50:49', 0, '', 79, NULL, 0),
(20, '3090367', 'เทปใสแกน 1 นิ้ว 18มม.x36หลา', 'เทปใส ONE แกน1นิ้ว 18มม.x36หลา แพ็ค 10 ฟรี 2', 11, 0, 2, '2025-11-12 07:38:48', '2025-10-17 16:50:49', 0, '', 16, 185, 0),
(21, '3090367', 'เทปใสแกน1นิ้ว 48 มม. x 45หลา', 'ONE เทปโอพีพี 48 มม. x 45หลา สีใส (แพ็ค 9 ฟรี 3) \"', 5, 0, 2, '2025-11-12 07:38:48', '2025-09-12 13:57:29', 0, '', 18, 208, 0),
(22, '3001341', 'เทปเยื่อกาว 2 หน้า 12 มม.x20 หลา', 'เทปเยื่อกาว 2 หน้า 12 มม.x20 หลา (แพ็ค6ม้วน) ONE', 2, 0, 2, '2025-11-12 07:38:48', '2025-09-05 16:08:03', 0, '', 14, 83, 0),
(23, '2000131', 'ลวดเย็บ เบอร์ 10 (27/4.8)', 'วดเย็บ (แพ็ค 24 กล่อง) MAX เบอร์ 10-1M.\r\nเบอร์ 10 (ขนาด 27/4.8 มม.).\r\nเย็บได้หนา 15 แผ่น (กระดาษ 80 แกรม).\r\nบรรจุ 24 กล่อง/แพ็ค.', 30, 0, 2, '2025-11-12 07:38:48', '2025-10-17 16:50:49', 0, '', 13, 205, 0),
(24, '2000136', 'ลวดเย็บ เบอร์ 35 (26/6)', 'ลวดเย็บ (แพ็ค 24 กล่อง) MAX เบอร์ 35-1M.\r\nเบอร์ 35 (ขนาด 26/6 มม.).\r\nเย็บได้หนา 22 แผ่น (กระดาษ 80 แกรม).\r\nบรรจุ 24 กล่อง/แพ็ค.', 76, 0, 2, '2025-11-12 07:38:48', '2025-09-05 16:11:42', 0, '', 20, 469, 0),
(25, '2000140', 'ลวดเย็บ เบอร์: M8 หลังโค้ง', 'ลวดเย็บหลังโค้ง (แพ็ค 12 กล่อง) MAX รุ่น M8-1M.\r\nเบอร์: M8 (หลังโค้ง).\r\nเย็บกระดาษได้หนา 22 แผ่น (กระดาษ 80 แกรม)', 16, 0, 2, '2025-11-12 07:38:48', '2025-09-05 16:13:02', 0, '', 20, 238, 0),
(26, '2000063', 'เครื่องเย็บกระดาษ เบอร์ 10 (27/4.8)', 'ELEPHANT เครื่องเย็บกระดาษ+ลวดเย็บ HS-1 สีคละสี.\r\nใช้กับลวดเย็บเบอร์ 10 (27/4.8).', 9, 0, 2, '2025-11-12 07:38:48', '2025-10-08 10:09:29', 0, '', 70, NULL, 0),
(27, '2002092', 'เครื่องเย็บกระดาษ HD-50', 'เครื่องเย็บกระดาษ น้ำเงิน แม็กซ์ HD-50.\r\nใช้กับลวดเย็บเบอร์ 3 (24/6) และ 35 (26/6).', 1, 0, 2, '2025-11-12 07:38:48', '2025-09-05 16:14:53', 0, '', 525, NULL, 0),
(28, '6003120', 'สันรูด 5 มม.', 'สันรูด 5 มม. (แพ็ค 12 อัน).\r\nยี่ห้อ: แพนด้า.\r\nสำหรับเข้าเล่มเอกสารขนาด A4.\r\nรองรับเอกสารได้ประมาณ 30–35 แผ่น (กระดาษ 70 แกรม).', 12, 0, 2, '2025-11-12 07:38:48', '2025-10-08 13:55:01', 0, '', 5, 59, 0),
(29, '6003124', 'สันรูด 7 มม.', 'สันรูด 7 มม. (แพ็ค 12 อัน).\r\nยี่ห้อ: แพนด้า.\r\nสำหรับเข้าเล่มเอกสารขนาด A4.\r\nรองรับเอกสารได้ประมาณ 35–45 แผ่น (กระดาษ 70 แกรม).', 13, 0, 2, '2025-11-18 07:25:25', '2025-11-18 07:25:25', 0, '', 24, 70, 0),
(66, '6003145', 'สันรูด 11 มม.', 'สันรูด 11 มม. น้ำเงิน (แพ็ค10อัน) เบนน่อน C-11', 27, 0, 2, '2025-11-12 07:38:48', '2025-09-05 16:22:05', 0, '', 6, 0, 0),
(30, '2000181', 'ลวดเสียบกระดาษ (กล่อง)', 'HORSE ลวดเสียบกระดาษชนิดกลม No.1 บรรจุ 50 ตัว / กล่อง.', 9, 0, 2, '2025-11-12 07:38:48', '2025-10-17 16:50:48', 0, '', 11, 112, 0),
(81, '3000395', 'เทปโฟมกาว 2 หน้า แกนใหญ่ 24 มม.x5 ม.', 'เทปโฟมกาวสองหน้าแกนใหญ่ ชนิดบาง เนื้อโฟมเคลือบกาวเหนียวทั้งสองด้าน ติดแน่น ทนนาน\r\nใช้ยึดติดสิ่งของ ตะขอ ป้ายบอกทางในออฟฟิศ กระจก โปสเตอร์ เทปโฟมสี  : ขาว\r\nเนื้อโฟมหนา : 1.6 มม. ขนาดหน้าเทปกว้าง : 24 มิลลิเมตร ความยาว : 5 เมตร', 2, 0, 2, '2025-11-12 07:38:48', '2025-09-18 10:42:10', 0, '', 54, 0, 0),
(31, '2150830', 'คลิปดำ 19 มม. รุ่น E112 (กล่อง)', 'ELEPHANT คลิปดำ 19 มม. รุ่น E112 (กล่อง 12 ตัว).\r\nสำหรับหนีบเอกสารหนา : 8 มม.', 10, 0, 2, '2025-11-12 07:38:48', '2025-10-17 16:50:48', 0, '', 23, 23, 0),
(32, '2150820', 'คลิปดำ 25 มม. รุ่น E111 (กล่อง)', 'ELEPHANT คลิปดำ 25 มม. รุ่น E111 (กล่อง 12 ตัว). \r\nสำหรับหนีบเอกสารหนา : 10 มม.', 22, 0, 2, '2025-11-12 07:38:48', '2025-10-17 16:50:48', 0, '', 32, 32, 0),
(33, '2150810', 'คลิปดำ 32 มม. รุ่น E110 (กล่อง)', 'ELEPHANT คลิปดำ 32 มม. รุ่น E110 (กล่อง 12 ตัว). \r\nสำหรับหนีบเอกสารหนา : 12 มม.', 1, 0, 2, '2025-11-12 07:38:48', '2025-09-05 16:47:49', 0, '', 42, 42, 0),
(34, '2150790', 'คลิปดำ 50 มม. รุ่น E108 (ชิ้น)', 'ELEPHANT คลิปดำ 50 มม. รุ่น E110 (กล่อง 12 ตัว). \r\nสำหรับหนีบเอกสารหนา : 25 มม.', 96, 0, 2, '2025-11-12 07:38:48', '2025-09-05 16:49:16', 0, '', 7, 79, 0),
(35, '5096481', 'กระดาษโน้ต ขนาด 3x3 นิ้ว', 'POST-IT กระดาษโน้ต รุ่น 654-24RP-AP คละสี', 19, 0, 2, '2025-11-12 07:38:48', '2025-10-09 14:28:09', 0, '', 30, 861, 0),
(36, '5005583', 'โพสต์-อิท อีโคแฟลกช์ (แถว)', 'โพสต์-อิท อีโคแฟลกซ์ บรรจุ: 20 แผ่น/สี', 15, 0, 2, '2025-11-12 07:38:48', '2025-10-08 13:55:48', 0, '', 24, NULL, 0),
(37, '5097667', 'สมุดปกอ่อน', 'ME.STYLE สมุดปกอ่อนเย็บลวด ตัดเก้า 60 แกรม 40 แผ่น', 10, 0, 2, '2025-11-12 07:38:48', '2025-09-12 15:34:31', 0, '', 14, 86, 0),
(38, '5004995', 'กระดาษสีถ่ายเอกสาร 8 A4 80 แกรม ชมพู', 'กระดาษสีถ่ายเอกสาร 8 A4 80 แกรม ชมพู (500แผ่น) ONE', 0, 0, 2, '2025-11-12 07:38:48', '2025-09-05 17:00:34', 0, '', 285, 285, 0),
(39, 'Kumwell01', 'แฟ้มเอกสาร 4 ห่วง สัน 2 นิ้ว', 'Kumwell', 19, 0, 2, '2025-11-12 07:38:48', '2025-09-23 10:28:19', 0, '', 0, NULL, 0),
(40, '6003897', 'แผ่นพลาสติกทำปก A4 ใส (แผ่น)', 'แผ่นพลาสติกทำปก A4 ใส', 91, 0, 2, '2025-11-12 07:38:48', '2025-09-10 15:39:23', 0, '', 3, NULL, 0),
(41, '6004054', 'แฟ้มซองพลาสติก A4 ขาว (แผ่น)', 'แฟ้มซองพลาสติก A4 ขาว ตราช้าง 405', 24, 0, 2, '2025-11-12 07:38:48', '2025-10-01 15:24:06', 0, '', 5, 65, 0),
(42, '6003912', 'แฟ้มซองพลาสติก F4 ขาว (แผ่น)', 'ONE ซองเอกสาร สีขาว ขนาด F4', 6, 0, 2, '2025-11-12 07:38:48', '2025-09-12 15:34:31', 0, '', 7, 79, 0),
(43, '6003919', 'ซองถนอมเอกสาร 11 รู ขนาด A4 (แผ่น)', 'ONE ซองถนอมเอกสาร 11 รู ขนาด A4', 100, 0, 2, '2025-11-12 07:38:48', '2025-09-29 14:17:29', 0, '', 1, 110, 0),
(44, '611018T', 'แฟ้มเจาะพลาสติก A4', 'แฟ้มเจาะพลาสติก A4 เทา เบนน่อน LW320', 2, 0, 2, '2025-11-12 07:38:48', '2025-09-05 17:08:13', 0, '', 41, NULL, 0),
(45, '6200680', 'ลิ้นแฟ้มเหล็ก', 'ELEPHANT ลิ้นแฟ้มเหล็ก รุ่น 905 สีเงิน ขนาด 8 ซม.', 85, 0, 2, '2025-11-12 07:38:48', '2025-09-05 17:09:01', 0, '', 3, NULL, 0),
(46, '6270101', 'ถาดเอกสารพลาสติก 3 ชั้น', 'ORCA ถาดเอกสารพลาสติก 3 ชั้น รุ่น L3 สีดำ', 1, 0, 2, '2025-11-12 07:38:48', '2025-09-11 09:55:55', 0, '', 305, NULL, 0),
(47, '6004341', 'กล่องใส่เอกสาร', 'กล่องใส่แม็กกาซีน ดำ ONE BF-93.\r\nเหมาะสำหรับใส่หนังสือหรือเอกสารขนาด A4 และ F4.', 0, 0, 2, '2025-11-12 07:38:48', '2025-09-11 13:57:39', 0, '', 73, NULL, 0),
(48, '8003414', 'ถ่านอัลคาไลน์ AA', 'ถ่านอัลคาไลน์ AA Panasonic LR6T/20SL', 20, 0, 2, '2025-11-12 07:38:48', '2025-10-08 16:26:47', 0, '', 23, 460, 0),
(49, '8003835', 'ถ่านอัลคาไลน์ AAA', 'PANASONIC ถ่านอัลคาไลน์ AAA รุ่น LR03T/20SL', 36, 0, 2, '2025-11-12 07:38:48', '2025-09-05 17:11:44', 0, '', 23, 460, 0),
(50, '2420073', 'แท่นประทับสีน้ำเงิน', 'HORSE แท่นประทับสีน้ำเงิน ขนาด 5.4 x 8.5 ซม.', 1, 0, 2, '2025-11-12 07:38:48', '2025-09-05 17:14:15', 0, '', 38, NULL, 0),
(51, '2420072', 'แท่นประทับ สีแดง', 'HORSE แท่นประทับ สีแดง ขนาด 5.4 x 8.5 ซม.', 4, 0, 2, '2025-11-12 07:38:48', '2025-09-05 17:14:34', 0, '', 38, NULL, 0),
(52, '2430043', 'หมึกเติมแท่นประทับ สีน้ำเงิน', 'HORSE หมึกเติมแท่นประทับ 30CC สีน้ำเงิน (1 ขวด)', 1, 0, 2, '2025-11-12 07:38:48', '2025-05-16 13:27:02', 0, '', 18, 216, 0),
(53, '2430042', 'หมึกเติมแท่นประทับกันน้ำ สีแดง', 'HORSE หมึกเติมแท่นประทับกันน้ำ สีแดง ขนาด 30 ซีซี (1 ขวด)', 0, 0, 2, '2025-11-12 07:38:48', '2025-09-05 17:15:02', 0, '', 18, 216, 0),
(54, '8091089', 'เครื่องคิดเลข', 'NEO เครื่องคิดเลข 12 หลัก รุ่น 2223-12', 1, 0, 2, '2025-11-12 07:38:48', '2025-09-05 17:17:44', 0, '', 225, 449, 0),
(63, '1100091', 'ปากกาไวท์บอร์ด สีดำ', 'ปากกาไวท์บอร์ด หัวแหลม รุ่น WBMK-M สีดำ', 3, 0, 2, '2026-06-26 02:14:15', '2026-06-26 02:14:15', 0, '1782440055.jpg', 25, 0, 0),
(64, '1100094', 'ปากกาไวท์บอร์ด สีเขียว', 'ปากกาไวท์บอร์ด หัวแหลม WBMK-M หมึกสีเขียว ขนาดเส้น 1.5-2 มม.', 1, 0, 2, '2025-11-12 07:38:48', '2025-09-05 15:46:08', 0, '', 25, 0, 0),
(65, '1011520', 'ปากกาไวท์บอร์ด สีม่วง', 'ปากกาไวท์บอร์ดหัวกลม สีม่วง', 1, 0, 2, '2025-11-12 07:38:48', '2025-09-05 15:48:44', 0, '', 25, 0, 0),
(70, '8031860', 'ถ่านกระดุม CR-2032', 'สำหรับรีโมทรถยนต์\r\nถ่านกระดุมลิเธี่ยม Panasonic CR-2032PT/1B', 4, 0, 2, '2025-11-12 07:38:48', '2025-09-05 17:13:39', 0, '', 55, 0, 0),
(67, 'Mix-Slide Fastener', 'สันรูด คละสี คละขนาด', 'สันรูด คละสี คละขนาด', 14, 0, 2, '2025-11-12 07:38:48', '2025-09-05 16:25:17', 0, '', 0, 0, 0),
(68, 'Mix-Comb Binding', 'สันห่วงกระดูกงู คละสี คละขนาด', 'สันห่วงกระดูกงู คละสี คละขนาด', 10, 0, 2, '2025-11-12 07:38:48', '2025-09-05 16:27:55', 0, '', 0, 0, 0),
(69, '2150800', 'คลิปดำ 40 มม. รุ่น E109 (กล่อง)', 'ELEPHANT คลิปดำ 40 มม. รุ่น E109 (กล่อง 12 ตัว)\r\nหนีบเอกสารหนา : 19 มม.\r\nหน้ากว้าง : 40 มม./ตัว', 8, 0, 2, '2025-11-12 07:38:48', '2025-09-12 15:34:31', 0, '', 73, 0, 0),
(72, '2000134', 'ลวดเย็บ เบอร์ 3 (24/6)', 'ลวดเย็บ (แพ็ค 24 กล่อง) เบอร์ 3 (24/6) รุ่น 3-1M', 24, 0, 2, '2025-11-12 07:38:48', '2025-09-05 17:20:52', 0, '', 27, 0, 0),
(73, '3090358', 'เทปผ้า สีบรอนซ์', 'เทปผ้า ONE สีบรอนซ์ (48มม. x 9หลา)', 1, 0, 2, '2025-11-12 07:38:48', '2025-09-05 17:23:20', 0, '', 45, 0, 0),
(74, '3150120', 'กระดาษกาวย่น สีขาวครีม', 'กระดาษกาวย่น แกน 3\" 2\"x25 y. สีขาวครีม', 2, 0, 2, '2025-11-12 07:38:48', '2025-09-05 17:25:27', 0, '', 72, 0, 0),
(75, '3140580', 'เทปเยื่อกาว 2 หน้า 24 มม.x10 หลา', 'เทปเยื่อกาว 2 หน้า 24 มม.x10 หลา สก๊อตช์* แกน 3 นิ้ว', 0, 0, 2, '2025-11-12 07:38:48', '2025-09-12 15:34:31', 0, '', 62, 0, 0),
(76, '6003693', 'ขี้ผึ้งนับธนบัตร', 'ขี้ผึ้งนับแบงก์ 40 กรัม ตราม้า * น้ำหนัก 40 กรัม * 1 ตลับ', 12, 0, 2, '2025-11-12 07:38:48', '2025-09-10 10:35:26', 0, '', 37, 0, 0),
(77, '6003068', 'ซองพลาสติกแข็ง A5', '* ซองพลาสติก Digit PVC ชนิดแข็ง ใส หนา ทนทาน\r\n* ผ่านกระบวนการผลิตที่ทันสมัยไม่เป็นรอยหรือแตกหักง่าย\r\n* ซองเปิดทางด้านยาว สอดเอกสารเข้า-ออก ได้สะดวก\r\n* ช่วยป้องกันเอกสารไม่ให้ฉีกขาด กันน้ำ และฝุ่นละออง\r\n* ขนาด 153 x 210 มม. (A5)', 6, 0, 2, '2025-11-12 07:38:48', '2025-09-10 10:41:54', 0, '', 21, 0, 0),
(78, '208140700612652', 'กระดาษถ่ายเอกสาร A4-70G', 'กระดาษถ่ายเอกสาร\r\nA4-70G-500 DOUBLE A', 77, 0, 2, '2025-11-12 07:38:48', '2025-10-17 16:47:39', 0, '', 81, 0, 0),
(79, '208140800622412', 'กระดาษถ่ายเอกสาร A3-80G-500 DOUBLE A', 'กระดาษถ่ายเอกสาร\r\nA3-80G-500 DOUBLE A', 10, 0, 2, '2025-11-12 07:38:48', '2025-09-17 14:30:27', 0, '', 208, 0, 0),
(80, '5041540', 'กระดาษต่อเนื่องเคมี ไม่มีเส้น ขนาด 9 x 11 นิ้ว 2 ชั้น', 'T.K.S. กระดาษต่อเนื่องเคมี ไม่มีเส้น ขนาด 9 x 11 นิ้ว 2 ชั้น', 0, 0, 2, '2025-11-12 07:38:48', '2025-09-10 16:25:17', 0, '', 1095, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `items_type`
--

CREATE TABLE `items_type` (
  `item_type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `items_type`
--

INSERT INTO `items_type` (`item_type_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(2, 'อุปกรณ์สำนักงาน', '-', 1, '2025-05-16 01:55:12', '2025-05-16 08:55:12'),
(3, 'เครื่องมือ', '-', 0, '2025-11-13 07:34:04', '2025-11-13 07:34:04'),
(4, 'อุปกรณ์สื่อสาร', '-', 0, '2025-05-15 08:26:26', '2025-05-15 08:26:26');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"6f9f4e60-0390-4c3d-8c5c-bbc50df0550b\",\"displayName\":\"App\\\\Jobs\\\\SendOutlookNewsNotificationJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendOutlookNewsNotificationJob\",\"command\":\"O:39:\\\"App\\\\Jobs\\\\SendOutlookNewsNotificationJob\\\":3:{s:7:\\\"\\u0000*\\u0000news\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\News\\\";s:2:\\\"id\\\";i:57;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:18:\\\"\\u0000*\\u0000recipientEmails\\\";a:1:{i:0;s:24:\\\"Kittiphan.Bu@kumwell.com\\\";}s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2025-07-16 08:00:00.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:12:\\\"Asia\\/Bangkok\\\";}}\"},\"createdAt\":1752554638,\"delay\":72962}', 0, NULL, 1752627600, 1752554638),
(2, 'default', '{\"uuid\":\"d556bf25-6248-41b7-827f-f961aaa2b1bf\",\"displayName\":\"App\\\\Jobs\\\\SendOutlookNewsNotificationJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendOutlookNewsNotificationJob\",\"command\":\"O:39:\\\"App\\\\Jobs\\\\SendOutlookNewsNotificationJob\\\":3:{s:7:\\\"\\u0000*\\u0000news\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\News\\\";s:2:\\\"id\\\";i:58;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:18:\\\"\\u0000*\\u0000recipientEmails\\\";a:1:{i:0;s:24:\\\"Kittiphan.Bu@kumwell.com\\\";}s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2025-07-15 13:07:16.045836\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:12:\\\"Asia\\/Bangkok\\\";}}\"},\"createdAt\":1752559636,\"delay\":0}', 0, NULL, 1752559636, 1752559636),
(3, 'default', '{\"uuid\":\"6cce2db4-28c9-4696-a52b-669805dcc433\",\"displayName\":\"App\\\\Jobs\\\\SendOutlookNewsNotificationJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendOutlookNewsNotificationJob\",\"command\":\"O:39:\\\"App\\\\Jobs\\\\SendOutlookNewsNotificationJob\\\":2:{s:7:\\\"\\u0000*\\u0000news\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\News\\\";s:2:\\\"id\\\";i:59;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:18:\\\"\\u0000*\\u0000recipientEmails\\\";a:1:{i:0;s:24:\\\"Kittiphan.Bu@kumwell.com\\\";}}\"},\"createdAt\":1752559817,\"delay\":null}', 0, NULL, 1752559817, 1752559817),
(4, 'default', '{\"uuid\":\"cfa972c6-c50b-41ef-a855-30da18679dea\",\"displayName\":\"App\\\\Jobs\\\\SendOutlookNewsNotificationJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendOutlookNewsNotificationJob\",\"command\":\"O:39:\\\"App\\\\Jobs\\\\SendOutlookNewsNotificationJob\\\":2:{s:7:\\\"\\u0000*\\u0000news\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\News\\\";s:2:\\\"id\\\";i:60;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:18:\\\"\\u0000*\\u0000recipientEmails\\\";a:1:{i:0;s:24:\\\"Kittiphan.Bu@kumwell.com\\\";}}\"},\"createdAt\":1752559847,\"delay\":null}', 0, NULL, 1752559847, 1752559847);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(5, '0001_01_01_000000_create_users_table', 1),
(6, '0001_01_01_000001_create_cache_table', 1),
(7, '0001_01_01_000002_create_jobs_table', 1),
(8, '2026_03_04_092529_add_color_to_reservations_table', 2),
(9, '2026_03_24_094719_add_maintenance_fields_to_vehicles_table', 2),
(10, '2026_03_31_084353_add_technician_columns_to_repair_table', 3),
(13, '2026_04_01_084146_create_announcements_table', 4),
(14, '2026_04_02_074555_add_residence_room_id_to_residence_leaves', 5),
(15, '2026_04_08_022428_create_hams_special_rights_table', 6),
(16, '2026_04_08_031614_add_approval_to_reservations_table', 7),
(17, '2026_04_08_164204_create_news_view_logs_table', 8),
(18, '2026_04_08_165021_add_views_count_to_news_table', 9),
(19, '2026_04_10_031820_create_hams_permissions_and_logs_tables', 10),
(20, '2026_04_16_041949_add_phone_to_resident_guest_members_table', 11),
(21, '2026_04_17_062922_add_approvers_to_requisitions_table', 12),
(22, '2026_06_26_083157_add_blueprint_image_to_residence_table', 13),
(23, '2026_06_29_013628_add_cover_image_to_residence_table', 14),
(24, '2026_07_01_042412_add_indexes_for_performance', 15);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `news_id` bigint(20) UNSIGNED NOT NULL COMMENT 'รหัสข่าวสาร',
  `newto` varchar(255) DEFAULT NULL COMMENT 'เรียน....',
  `title` varchar(255) NOT NULL COMMENT 'หัวข้อข่าว/ประกาศ',
  `content` text NOT NULL COMMENT 'เนื้อหาข่าว/ประกาศแบบเต็ม',
  `published_date` date NOT NULL COMMENT 'วันที่เผยแพร่ข่าว (ใช้แสดงในหน้าเว็บ)',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'สถานะการแสดงข่าว: 1 = แสดง, 0 = ซ่อน',
  `views_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่บันทึกข่าว',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วันที่แก้ไขข่าวล่าสุด',
  `image_path` text DEFAULT NULL COMMENT 'รูปภาพประกอบประกาศ (เช่น ป้ายเตือน)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางเก็บข้อมูลข่าวสาร/ประชาสัมพันธ์' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`news_id`, `newto`, `title`, `content`, `published_date`, `is_active`, `views_count`, `created_at`, `updated_at`, `image_path`) VALUES
(79, 'ผู้บริหาร หัวหน้างาน และพนักงานทุกท่าน', 'ตรวจสอบการปิดไฟและถอดปลั๊กไฟทุกครั้งก่อนออกจากที่ทำงาน', 'เพื่อความปลอดภัยในการใช้ไฟฟ้า ลดพลังงาน และใช้ทรัพยากรอย่างคุ้มค่า\r\nขอความร่วมมือทุกท่าน กรุณาตรวจสอบระบบไฟฟ้าให้เรียบร้อย สำรวจเครื่องใช้ไฟฟ้า\r\nถอดปลั๊กเครื่องใช้ไฟฟ้าและสวิตช์ไฟ ทุกครั้งก่อนออกจากที่ทำงาน\r\nและเปิดฝาช่องเสียบปลั๊กไว้ เพื่อความสะดวกในการตรวจสอบ\r\n\r\n-ขอบคุณค่ะ-', '2025-10-17', 1, 0, '2025-10-15 10:28:38', '2025-11-11 02:41:45', '[\"\\/images\\/news\\/20251111094145_a0HrRKNc.jpg\",\"\\/images\\/news\\/20251111094145_N3lU9W5s.jpg\",\"\\/images\\/news\\/20251111094145_JxAkZTDR.png\"]'),
(83, 'test', 'test', 'test', '2026-04-01', 1, 0, '2026-04-01 03:01:37', '2026-04-01 03:01:37', '[\"images\\/news\\/20260401100137_yW7JeNFZ.png\"]'),
(84, 'test1', 'test1', 'test1', '2026-04-01', 1, 0, '2026-04-01 03:01:51', '2026-04-01 03:01:51', '[\"images\\/news\\/20260401100151_zA5y2MWP.png\"]'),
(85, 'test2', 'test2', 'test2', '2026-04-01', 1, 0, '2026-04-01 03:02:14', '2026-04-01 03:02:14', '[\"images\\/news\\/20260401100214_wIsJ6fmN.png\"]'),
(86, 'test3', 'test3', 'test3', '2026-04-01', 1, 1, '2026-04-01 03:02:33', '2026-04-08 09:54:02', '[\"images\\/news\\/20260401100233_5c3wCPSj.png\"]'),
(87, 'test4', 'test4', 'test4', '2026-04-01', 1, 2, '2026-04-01 03:02:56', '2026-04-16 18:08:55', '[\"images\\/news\\/20260401100256_OjcKDNdD.gif\"]'),
(88, 'พนักงาน', 'ข่าวใหญ่', 'ข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มากข่าวใหญ่มาก', '2026-04-08', 1, 0, '2026-04-08 08:36:42', '2026-04-08 08:36:42', '[\"images\\/news\\/20260408153642_7bmEJZKU.png\",\"images\\/news\\/20260408153642_ugUcYbvu.png\"]');

-- --------------------------------------------------------

--
-- Table structure for table `news_attachments`
--

CREATE TABLE `news_attachments` (
  `news_attachments_id` bigint(20) UNSIGNED NOT NULL COMMENT 'รหัสไฟล์แนบ',
  `news_id` bigint(20) UNSIGNED NOT NULL COMMENT 'รหัสข่าวที่แนบไฟล์',
  `file_name` varchar(255) NOT NULL COMMENT 'ชื่อไฟล์ที่แสดงในหน้าจอ',
  `file_path` varchar(255) NOT NULL COMMENT 'เส้นทางเก็บไฟล์บนเซิร์ฟเวอร์หรือระบบไฟล์',
  `file_type` varchar(50) DEFAULT NULL COMMENT 'ประเภทของไฟล์ (เช่น pdf, docx, jpg)',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่อัปโหลดไฟล์'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางแนบไฟล์ประกอบข่าวสาร/ประกาศ' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `news_logs`
--

CREATE TABLE `news_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `news_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_logs`
--

INSERT INTO `news_logs` (`id`, `news_id`, `user_id`, `ip_address`, `user_agent`, `viewed_at`, `created_at`, `updated_at`) VALUES
(1, 87, 669, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-17 01:08:55', '2026-04-16 18:08:55', '2026-04-16 18:08:55');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `policy`
--

CREATE TABLE `policy` (
  `policy_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'หัวข้อ เช่น นโยบาย หรือ การดำเนินงาน',
  `content` text NOT NULL COMMENT 'เนื้อหาโดยละเอียดของนโยบายหรือการดำเนินงาน',
  `type` enum('policy','operation') NOT NULL COMMENT 'ประเภทของข้อมูล: policy = นโยบาย, operation = การดำเนินงาน',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่บันทึกข้อมูล',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วันที่แก้ไขล่าสุด',
  `order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางเก็บข้อมูลนโยบายและการดำเนินงาน' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `policy`
--

INSERT INTO `policy` (`policy_id`, `title`, `content`, `type`, `created_at`, `updated_at`, `order`) VALUES
(1, 'นโยบาย', 'มุ่งมั่นพัฒนางานบริการและสวัสดิการแก่พนักงาน ให้เกิดความพึงพอใจ สร้างความรักและผูกพันต่อองค์กรอย่างยั่งยืน', 'policy', '2025-05-28 06:58:34', '2026-03-26 19:14:15', 2),
(2, 'testingการดำเนินงาน', 'กำหนดเป้าหมายและวางแผนการดำเนินงานประจำปี', 'operation', '2025-05-28 07:01:02', '2025-05-30 00:54:55', 0),
(3, 'testingการดำเนินงาน', 'ติดตามและประเมินผลการดำเนินงานอย่างต่อเนื่อง', 'operation', '2025-05-28 07:01:02', '2025-05-30 00:54:52', 0),
(4, 'testingการดำเนินงาน', 'ปรับปรุงกระบวนการทำงานให้มีประสิทธิภาพมากขึ้น', 'operation', '2025-05-28 07:01:02', '2025-05-30 00:54:17', 0),
(5, 'testingการดำเนินงาน', 'ส่งเสริมการมีส่วนร่วมของพนักงานในทุกระดับ', 'operation', '2025-05-28 07:01:02', '2025-05-30 00:54:17', 0),
(6, 'KumwellNewSoftware', 'test1', 'policy', '2025-07-18 08:16:51', '2026-03-26 19:13:51', 3),
(7, 'KumwellNewSoftware', 'test', 'policy', '2025-07-22 02:21:11', '2026-03-26 19:14:01', 1),
(8, 'cc12', 'cc1', 'policy', '2026-03-26 19:19:22', '2026-04-06 00:23:50', 5),
(9, 'kk12', 'kk1', 'operation', '2026-03-26 19:19:43', '2026-04-06 00:23:58', 6);

-- --------------------------------------------------------

--
-- Table structure for table `repairrequests_items`
--

CREATE TABLE `repairrequests_items` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'รหัสไอดีของรายการอุปกรณ์',
  `item_code` varchar(50) DEFAULT NULL COMMENT 'รหัสประจำอุปกรณ์ เช่น IT-001',
  `item_name` varchar(255) DEFAULT NULL COMMENT 'ชื่ออุปกรณ์ เช่น เครื่องปริ้นเตอร์ HP',
  `category` varchar(100) DEFAULT NULL COMMENT 'ประเภทของอุปกรณ์ เช่น คอมพิวเตอร์, ปรินเตอร์',
  `location` varchar(255) DEFAULT NULL COMMENT 'สถานที่ตั้งของอุปกรณ์ เช่น ห้องประชุม 2',
  `images` text DEFAULT NULL COMMENT 'รูป',
  `description` text DEFAULT NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `purchase_date` date DEFAULT NULL COMMENT 'วันที่ซื้อหรือได้รับอุปกรณ์',
  `warranty_expiration` date DEFAULT NULL COMMENT 'วันที่หมดอายุประกันอุปกรณ์',
  `maintenance_schedule` text DEFAULT NULL COMMENT 'รอบการดูแลรักษา เช่น ทุก 6 เดือน',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่บันทึกรายการ',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วันที่แก้ไขล่าสุด',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='ตารางเก็บข้อมูลอุปกรณ์ส่วนกลางที่ต้องตรวจสอบหรือซ่อมบำรุง';

--
-- Dumping data for table `repairrequests_items`
--

INSERT INTO `repairrequests_items` (`id`, `item_code`, `item_name`, `category`, `location`, `images`, `description`, `purchase_date`, `warranty_expiration`, `maintenance_schedule`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'KML-HQ-PC81', 'จอคอมพิวเตอร์', 'คอมพิวเตอร์', 'ชั้น 3 แผนก ICT', '[\"1752054719_686e3bbf96aa7.jpg\"]', 'testtttttt', '2023-01-01', '2025-08-20', 'ทุก 6 เดือน', '2025-06-17 08:59:44', '2025-07-24 02:17:37', NULL, 1),
(2, 'KML-HQ-PC82', 'notebook', 'คอมพิวเตอร์', 'ชั้น 3 แผนก ICT', '[\"1753321245_68818f1d1731d.jpg\"]', 'test', '2025-07-01', '2027-01-01', 'ทุก 6 เดือน', '2025-07-24 01:40:45', '2025-07-24 01:40:45', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `repairrequests_log`
--

CREATE TABLE `repairrequests_log` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'รหัสไอดีของบันทึกการดำเนินการ',
  `item_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'อ้างอิงถึงอุปกรณ์ในตาราง repairrequests_items',
  `action_type` enum('inspection','repair') DEFAULT NULL COMMENT 'ประเภทการดำเนินการ: inspection = ตรวจสอบ, repair = ซ่อม',
  `action_date` date DEFAULT NULL COMMENT 'วันที่มีการดำเนินการตรวจสอบหรือซ่อมบำรุง',
  `technician_name` varchar(100) DEFAULT NULL COMMENT 'ชื่อช่างหรือผู้ที่ดำเนินการ',
  `detail` text DEFAULT NULL COMMENT 'รายละเอียดเกี่ยวกับสิ่งที่ได้ตรวจสอบหรือซ่อมแซม',
  `images` text DEFAULT NULL COMMENT 'รูป',
  `log_file` text DEFAULT NULL COMMENT 'ไฟล์แนบ(ถ้ามี)',
  `cost` decimal(10,2) DEFAULT NULL COMMENT 'ค่าใช้จ่ายในการซ่อมหรือดูแลรักษา (บาท)',
  `next_schedule` date DEFAULT NULL COMMENT 'วันนัดหมายหรือตรวจสอบครั้งถัดไป',
  `status` enum('completed','pending','cancelled') DEFAULT NULL COMMENT 'สถานะของรายการ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้างบันทึกนี้',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วันที่อัปเดตล่าสุดของบันทึกนี้',
  `created_id` int(11) DEFAULT NULL,
  `updated_id` int(11) DEFAULT NULL,
  `deleted_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='ตารางบันทึกการตรวจสอบและซ่อมบำรุงของอุปกรณ์แต่ละชิ้น';

--
-- Dumping data for table `repairrequests_log`
--

INSERT INTO `repairrequests_log` (`id`, `item_id`, `action_type`, `action_date`, `technician_name`, `detail`, `images`, `log_file`, `cost`, `next_schedule`, `status`, `created_at`, `updated_at`, `created_id`, `updated_id`, `deleted_id`) VALUES
(12, 1, 'inspection', '2025-07-09', 'nampu11', 'test', '[\"1752055305_4cd571c0-97b4-4dff-be4d-1fd5469e7e9d (2).jpg\"]', '1752055305_testpdf.pdf', 2000.00, '2025-07-10', 'completed', '2025-07-09 10:01:45', '2025-07-11 04:51:52', 1, 1, NULL),
(13, 1, 'repair', '2025-07-23', 'nampu', 'test', '[\"1753262046_istockphoto-537331500-612x612.jpg\"]', '1753262046_test.pdf', 1500.00, '2025-09-15', 'completed', '2025-07-23 09:14:06', '2025-08-20 09:03:01', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `repair_logs`
--

CREATE TABLE `repair_logs` (
  `repair_log_id` bigint(20) NOT NULL COMMENT 'รหัสบันทึกการดำเนินการ',
  `repair_request_id` bigint(20) NOT NULL COMMENT 'อ้างอิงรายการแจ้งซ่อม',
  `action_by` bigint(20) NOT NULL COMMENT 'ผู้ดำเนินการเปลี่ยนสถานะ (users.id)',
  `action_date` datetime DEFAULT current_timestamp() COMMENT 'วันที่และเวลาที่ดำเนินการ',
  `status` enum('pending','in_progress','completed','unrepairable') NOT NULL COMMENT 'สถานะที่ถูกเปลี่ยนเป็น',
  `note` text DEFAULT NULL COMMENT 'บันทึกหรือรายละเอียดเพิ่มเติมในการดำเนินการ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางเก็บบันทึกสถานะและการดำเนินการเกี่ยวกับการซ่อม' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `repair_requests`
--

CREATE TABLE `repair_requests` (
  `repair_requests_id` bigint(20) NOT NULL COMMENT 'รหัสการแจ้งซ่อม',
  `user_id` bigint(20) NOT NULL COMMENT 'รหัสผู้แจ้งซ่อม (เชื่อมกับ users.id)',
  `asset_name` varchar(255) NOT NULL COMMENT 'ชื่อทรัพย์สินหรือของที่ชำรุด',
  `asset_type` enum('office','shared') NOT NULL COMMENT 'ประเภทของที่ชำรุด: office=ของสำนักงาน, shared=ของส่วนกลาง',
  `location` varchar(255) NOT NULL COMMENT 'จุดที่พบของชำรุด เช่น อาคาร/ห้อง',
  `description` text NOT NULL COMMENT 'รายละเอียดปัญหาที่พบ',
  `report_date` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่และเวลาที่แจ้งซ่อม',
  `status` enum('pending','in_progress','completed','unrepairable') NOT NULL DEFAULT 'pending' COMMENT 'สถานะการซ่อม: pending=รอรับเรื่อง, in_progress=กำลังซ่อม, completed=ซ่อมเสร็จ, unrepairable=ซ่อมไม่ได้',
  `update_status_id` int(11) NOT NULL DEFAULT 0,
  `completed_date` datetime DEFAULT NULL COMMENT 'วันที่ซ่อมเสร็จ (ถ้ามี)',
  `completed_comment` text DEFAULT NULL COMMENT 'หมายเหตุ',
  `repair_cost` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'งบประมาณที่ใช้ในการซ่อม (บาท)',
  `note` text DEFAULT NULL COMMENT 'หมายเหตุเพิ่มเติม',
  `photo_path` text DEFAULT NULL COMMENT 'รูปภาพ',
  `photo_after` text DEFAULT NULL COMMENT 'รูปหลังดำเนินการเสร็จสิ้น\r\n',
  `repair_requests_code` varchar(255) DEFAULT NULL COMMENT 'เลขที่ใบแจ้งซ่อม',
  `priority` int(11) DEFAULT NULL COMMENT '0 ปกติ 1 เร่งด่วน',
  `repair_date` datetime DEFAULT NULL COMMENT 'วันที่ซ่อม',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางหลักเก็บข้อมูลการแจ้งซ่อม' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `repair_requests`
--

INSERT INTO `repair_requests` (`repair_requests_id`, `user_id`, `asset_name`, `asset_type`, `location`, `description`, `report_date`, `status`, `update_status_id`, `completed_date`, `completed_comment`, `repair_cost`, `note`, `photo_path`, `photo_after`, `repair_requests_code`, `priority`, `repair_date`, `created_at`) VALUES
(32, 14, 'ก๊อกน้ำ', 'shared', '6', 'น้ำไม่ไหล 1', '2025-09-05 18:20:40', 'unrepairable', 13, NULL, NULL, 150.00, NULL, 'images/repairrequest/1757071240_v9462t.png', NULL, '25090001', 1, NULL, NULL),
(33, 7, 'ฟลัชวาล์วโถสุขภัณฑ์', 'shared', '6', 'น้ำไหลไม่หยุด', '2025-09-08 13:54:08', 'pending', 1, '2025-09-10 10:09:58', NULL, 6000.00, NULL, 'images/repairrequest/1757314448_S__166060042_0.jpg', NULL, '25090002', 1, NULL, NULL),
(50, 15, 'หลอดไฟ', 'shared', '3', 'ไฟดับไม่ติด', '2025-09-15 16:45:54', 'pending', 13, NULL, NULL, 0.00, NULL, 'images/repairrequest/1757929554_S__12140554.jpg', NULL, '25090004', 1, NULL, NULL),
(51, 13, 'SMOKE  ST2 F.2 Alarm', 'shared', '2', 'สัญญาณแจ้งเตือน\r\nวันที่ 16/9/68 \r\nเวลา 22:08 น.', '2025-09-17 08:42:35', 'completed', 13, '2025-09-17 10:32:34', NULL, 0.00, NULL, 'images/repairrequest/1758073355_284171_0.jpg', 'images/repairrequest/1758079954_after_284175_0.jpg', '25090005', 1, NULL, NULL),
(52, 13, 'น้ำซึม บริเวณลานจอด F.1', 'shared', '1', 'ท่อระบายน้ำจาก F.2 ลานพระวิษณุ ไหลลงมาด้านล่าง\r\nทำการแก้จุดน้ำทิ้งให้ลงบ่อ', '2025-09-17 10:32:12', 'completed', 13, '2025-09-17 10:32:51', NULL, 0.00, NULL, 'images/repairrequest/1758079932_S__87654419_0.jpg', 'images/repairrequest/1758079971_after_S__87654427_0.jpg', '25090006', 1, NULL, NULL),
(54, 15, 'ปลั๊กไฟใช้ไม่ได้', 'shared', '2', 'แผนก SS  แจ้งปลั๊กไฟโต๊ะทำงานดับ', '2025-09-17 15:10:57', 'unrepairable', 15, '2025-09-17 15:12:43', NULL, 0.00, NULL, 'images/repairrequest/1758096657_S__87678980_0.jpg', 'images/repairrequest/1758096763_after_S__87679000.jpg', '25090007', 1, NULL, NULL),
(55, 15, 'ปลั๊กไฟใช้ไม่ได้', 'shared', '2', 'แผนก SS แจ้งปลั๊กไฟโต๊ะทำงานดับ', '2025-09-17 15:33:54', 'completed', 15, '2025-09-17 15:36:07', 'แผนก SS ได้นำสายไฟ(ลำโพง)ไปเสียบกับเต้าไฟฟ้า ทำให้เบกเกอร์โซนแผนก SS  ตัดจึงทำการดึงเบรกเกอร์ขึ้นเพื่อใช้งาน', 0.00, NULL, 'images/repairrequest/1758098034_S__87678980_0.jpg', 'images/repairrequest/1758098167_after_S__87679000.jpg', '25090008', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `requisitionitem_list`
--

CREATE TABLE `requisitionitem_list` (
  `requistionitemlist_id` int(11) NOT NULL,
  `requisition_id` int(11) NOT NULL COMMENT 'รหัสการเบิกของ',
  `item_id` int(11) NOT NULL COMMENT 'รหัสของที่เบิก',
  `quantity` int(11) NOT NULL COMMENT 'จำนวน(ชิ้น)ที่เบิก',
  `quantity_pack` int(11) DEFAULT 0 COMMENT 'จำนวน(แพ็ค)ที่เบิก',
  `unit` varchar(50) DEFAULT NULL COMMENT 'หน่วยที่เบิก',
  `total_price` decimal(10,2) DEFAULT NULL COMMENT 'ราคารวมทั้งหมด',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `check_item` int(11) NOT NULL DEFAULT 0 COMMENT '0 = ยังไม่ได้จัด, 1 = จัดเรียบร้อย'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `requisitionitem_list`
--

INSERT INTO `requisitionitem_list` (`requistionitemlist_id`, `requisition_id`, `item_id`, `quantity`, `quantity_pack`, `unit`, `total_price`, `created_at`, `updated_at`, `check_item`) VALUES
(75, 8, 1, 1, 0, NULL, NULL, '2025-09-10 11:06:56', '2025-09-10 11:06:56', 0),
(76, 17, 78, 1, 0, NULL, NULL, '2025-09-11 11:16:11', '2025-09-11 11:16:11', 0);

-- --------------------------------------------------------

--
-- Table structure for table `requisitions`
--

CREATE TABLE `requisitions` (
  `requisitions_id` int(11) NOT NULL,
  `requester_id` int(11) DEFAULT NULL COMMENT 'รหัสผู้เบิก',
  `request_date` date NOT NULL COMMENT 'วันที่เบิก',
  `status` enum('pending','approved','rejected','returned','cancelled','endprogress') DEFAULT 'pending',
  `remarks` text DEFAULT NULL COMMENT 'หมายเหตุ',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `request_number` varchar(50) DEFAULT NULL COMMENT 'หมายเลขการเบิก',
  `approve_id` int(11) DEFAULT NULL,
  `approve_status` int(11) NOT NULL DEFAULT 0,
  `approve_comment` text DEFAULT NULL,
  `approve_date` date DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `packing_staff_id` int(11) DEFAULT NULL,
  `packing_staff_status` int(11) DEFAULT 0,
  `packing_staff_comment` text DEFAULT NULL,
  `packing_staff_date` datetime DEFAULT NULL,
  `requisitions_code` varchar(50) DEFAULT NULL,
  `requester_comment` text DEFAULT NULL,
  `commander_id` bigint(20) UNSIGNED DEFAULT NULL,
  `commander_status` int(11) NOT NULL DEFAULT 0,
  `commander_comment` text DEFAULT NULL,
  `commander_date` timestamp NULL DEFAULT NULL,
  `managerhams_id` bigint(20) UNSIGNED DEFAULT NULL,
  `managerhams_status` int(11) NOT NULL DEFAULT 0,
  `managerhams_comment` text DEFAULT NULL,
  `managerhams_date` timestamp NULL DEFAULT NULL,
  `Committee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `Committee_status` int(11) NOT NULL DEFAULT 0,
  `Committee_comment` text DEFAULT NULL,
  `Committee_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `requisitions`
--

INSERT INTO `requisitions` (`requisitions_id`, `requester_id`, `request_date`, `status`, `remarks`, `created_at`, `updated_at`, `request_number`, `approve_id`, `approve_status`, `approve_comment`, `approve_date`, `total_price`, `packing_staff_id`, `packing_staff_status`, `packing_staff_comment`, `packing_staff_date`, `requisitions_code`, `requester_comment`, `commander_id`, `commander_status`, `commander_comment`, `commander_date`, `managerhams_id`, `managerhams_status`, `managerhams_comment`, `managerhams_date`, `Committee_id`, `Committee_status`, `Committee_comment`, `Committee_date`) VALUES
(6, 14, '2025-09-10', 'endprogress', 'คุณภัทราภรณ์ บุญเกิด DS เบิกส่วนกลางชั้น 2', '2025-09-10 10:52:46', '2025-09-10 11:11:10', NULL, 14, 1, 'คุณภัทราภรณ์ บุญเกิด DS เบิกส่วนกลางชั้น 2', '2025-09-10', 405.00, 14, 1, 'ใช่', '2025-09-10 11:11:10', '25090003', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(7, 14, '2025-09-10', 'endprogress', 'คุณภากร หุตะสิงกาศ (ตั๋น) ES', '2025-09-10 10:56:42', '2025-09-10 11:11:27', NULL, 14, 1, 'คุณภากร หุตะสิงกาศ (ตั๋น) ES', '2025-09-10', 147.00, 14, 1, 'ใช่', '2025-09-10 11:11:27', '25090004', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(9, 14, '2025-09-10', 'endprogress', 'คุณชลธชา สกุลจันทร์ SS (9/9/68)', '2025-09-10 10:58:37', '2025-09-10 11:11:39', NULL, 14, 1, 'คุณชลธชา สกุลจันทร์ SS (9/9/68)', '2025-09-10', 121.00, 14, 1, 'ใช่', '2025-09-10 11:11:39', '25090006', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(10, 14, '2025-09-10', 'endprogress', 'คุณประภัสสร สมรูป ACC (9/9/68)', '2025-09-10 11:00:38', '2025-09-10 11:11:48', NULL, 14, 1, 'คุณประภัสสร สมรูป ACC (9/9/68)', '2025-09-10', 24.00, 14, 1, 'ใช่', '2025-09-10 11:11:48', '25090007', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(11, 14, '2025-09-10', 'endprogress', 'คุณวรรณเฉลิมพร ทองไทย ACC (9/9/68)', '2025-09-10 11:01:30', '2025-09-10 11:11:58', NULL, 14, 1, 'คุณวรรณเฉลิมพร ทองไทย ACC (9/9/68)', '2025-09-10', 24.00, 14, 1, 'ใช่', '2025-09-10 11:11:58', '25090008', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(12, 14, '2025-09-10', 'endprogress', 'คุณปรียาพร แก้วตะโก ACC ส่วนกลางแผนกบัญชี (9/9/68)', '2025-09-10 11:03:34', '2025-09-10 11:12:08', NULL, 14, 1, 'คุณปรียาพร แก้วตะโก ACC ส่วนกลางแผนกบัญชี (9/9/68)', '2025-09-10', 405.00, 14, 1, 'ใช่', '2025-09-10 11:12:08', '25090009', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(14, 5, '2025-09-10', 'endprogress', 'คุณกัลยา พูนศิริ', '2025-09-10 16:08:35', '2025-09-10 16:20:19', NULL, 14, 1, 'คุณกัลยา พูนศิริ', '2025-09-10', 25.00, 14, 1, 'คุณกัลยา พูนศิริ รับของเรียบร้อย', '2025-09-10 16:20:19', '25090007', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(15, 5, '2025-09-10', 'endprogress', 'แผนก LG/10530', '2025-09-10 16:18:20', '2025-09-10 16:21:35', NULL, 14, 1, 'แผนก LG/ 10530 คุณกฤติยา อรัณยะนาค', '2025-09-10', 653.00, 14, 1, 'คุณกฤติยา อรัณยะนาค รับของเรียบร้อย', '2025-09-10 16:21:35', '25090008', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(16, 5, '2025-09-10', 'endprogress', 'คุณเหรียญทอง สุพร ใช้ในแผนกบัญชี', '2025-09-10 16:25:17', '2025-09-11 11:19:57', NULL, 14, 1, 'คุณเหรียญทอง สุพร ใช้ในแผนกบัญชี', '2025-09-11', 6570.00, 14, 1, 'คุณเหรียญทองรับของไปแล้ว', '2025-09-11 11:19:57', '25090009', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(18, 5, '2025-09-11', 'endprogress', 'พรนิภา สุขเกษม ส่วนงานกฎหมาย', '2025-09-11 09:55:55', '2025-09-11 11:21:52', NULL, 14, 1, 'พรนิภา สุขเกษม ส่วนงานกฎหมาย', '2025-09-11', 305.00, 14, 1, 'พรนิภา สุขเกษม ส่วนงานกฎหมาย รับของแล้ว', '2025-09-11 11:21:52', '25090011', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(19, 5, '2025-09-11', 'endprogress', 'แผนก LG เบิกใช้ส่วนกลางชั้น 2', '2025-09-11 11:18:08', '2025-09-11 11:22:20', NULL, 14, 1, 'แผนก LG เบิกใช้ส่วนกลางชั้น 2', '2025-09-11', 405.00, 14, 1, 'แผนก LG เบิกใช้ส่วนกลางชั้น 2 รับของแล้ว', '2025-09-11 11:22:20', '25090012', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(20, 5, '2025-09-11', 'endprogress', 'ผกามาศ หาญณรงค์ HAMS', '2025-09-11 11:18:53', '2025-09-11 11:22:35', NULL, 14, 1, 'ผกามาศ หาญณรงค์ HAMS', '2025-09-11', 18.00, 14, 1, 'ผกามาศ หาญณรงค์ HAMS รับของแล้ว', '2025-09-11 11:22:35', '25090013', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(21, 5, '2025-09-11', 'endprogress', 'ผกามาศ HAMS', '2025-09-11 11:19:54', '2025-09-11 11:23:01', NULL, 14, 1, 'ผกามาศ HAMS', '2025-09-11', 15.00, 14, 1, 'ผกามาศ HAMS รับของแล้ว', '2025-09-11 11:23:01', '25090014', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(22, 5, '2025-09-11', 'endprogress', 'กชณิชา วรรณรัตน์', '2025-09-11 13:57:39', '2025-09-11 14:04:59', NULL, 14, 1, 'กชณิชา วรรณรัตน์ HAMS', '2025-09-11', 73.00, 14, 1, 'กชณิชา วรรณรัตน์ HAMS รับของแล้ว', '2025-09-11 14:04:59', '25090015', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(23, 5, '2025-09-11', 'endprogress', 'วิภาดา', '2025-09-11 16:19:24', '2025-09-11 16:55:21', NULL, 14, 1, 'วิภาดา', '2025-09-11', 24.00, 14, 1, 'วิภาดา รับของแล้ว', '2025-09-11 16:55:21', '25090016', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(24, 5, '2025-09-11', 'endprogress', 'ของชั้น 5', '2025-09-11 16:26:04', '2025-09-11 16:55:37', NULL, 14, 1, 'ของชั้น 5', '2025-09-11', 405.00, 14, 1, 'ส่วนกลางของชั้น 5', '2025-09-11 16:55:37', '25090017', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(25, 5, '2025-09-12', 'endprogress', 'คุณรัชนีกร ภักดีวงศ์', '2025-09-12 13:57:29', '2025-09-12 15:26:48', NULL, 14, 1, 'คุณรัชนีกร ภักดีวงศ์', '2025-09-12', 40.00, 14, 1, 'คุณรัชนีกร ภักดีวงศ์ รับแล้ว', '2025-09-12 15:26:48', '25090018', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(26, 14, '2025-09-12', 'endprogress', 'โรงงานบางใหญ่ ไทรใหญ่', '2025-09-12 15:34:30', '2025-09-15 14:48:36', NULL, 14, 1, 'โรงงานบางใหญ่ ไทรใหญ่', '2025-09-15', 2513.00, 14, 1, 'ส่งของไป โรงงานบางใหญ่ ไทรใหญ่', '2025-09-15 14:48:36', '25090019', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(27, 5, '2025-09-15', 'endprogress', 'ปรียาพร แก้วตะโก ส่วนกลางชั้น 3', '2025-09-15 13:50:44', '2025-09-15 14:48:50', NULL, 14, 1, 'ปรียาพร แก้วตะโก ส่วนกลางชั้น 3', '2025-09-15', 405.00, 14, 1, 'ปรียาพร แก้วตะโก ส่วนกลางชั้น 3 รับแล้ว', '2025-09-15 14:48:50', '25090020', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(28, 5, '2025-09-17', 'endprogress', 'กฤติยา อรัณยะนาค แผนกLG 10530', '2025-09-17 09:32:30', '2025-09-17 09:40:05', NULL, 14, 1, 'กฤติยา อรัณยะนาค แผนกLG 10530', '2025-09-17', 724.00, 14, 1, 'กฤติยา อรัณยะนาค แผนกLG รับแล้ว', '2025-09-17 09:40:05', '25090021', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(29, 5, '2025-09-17', 'endprogress', 'ศศิภรณ์ ACC', '2025-09-17 09:54:24', '2025-09-18 09:27:00', NULL, 14, 1, 'ศศิภรณ์ ACC', '2025-09-18', 10.00, 14, 1, 'ศศิภรณ์ ACC รับแล้ว', '2025-09-18 09:27:00', '25090022', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(30, 5, '2025-09-17', 'endprogress', 'ธนิษฐ์ ES', '2025-09-17 14:30:27', '2025-09-18 09:27:14', NULL, 14, 1, 'ธนิษฐ์ ES', '2025-09-18', 208.00, 14, 1, 'ธนิษฐ์ ES รับแล้ว', '2025-09-18 09:27:14', '25090023', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(31, 5, '2025-09-17', 'endprogress', 'ธนิษฐ์ วิสารทกุล - Engineering Solution', '2025-09-17 15:10:57', '2025-09-18 09:27:27', NULL, 14, 1, 'ธนิษฐ์ วิสารทกุล - Engineering Solution', '2025-09-18', 405.00, 14, 1, 'ธนิษฐ์ ES รับแล้ว', '2025-09-18 09:27:27', '25090024', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(32, 5, '2025-09-18', 'endprogress', 'คุณกฤติยา LG', '2025-09-18 10:42:10', '2025-09-18 10:43:36', NULL, 14, 1, 'คุณกฤติยา LG', '2025-09-18', 162.00, 14, 1, 'คุณกฤติยา LG รับของแล้ว', '2025-09-18 10:43:36', '25090025', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(33, 5, '2025-09-19', 'endprogress', 'กชณิชา วรรณรัตน์', '2025-09-19 13:17:47', '2025-09-23 09:55:38', NULL, 14, 1, 'กชณิชา วรรณรัตน์', '2025-09-23', 11.00, 14, 1, 'กชณิชา รับแล้ว', '2025-09-23 09:55:38', '25090026', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(34, 5, '2025-09-19', 'endprogress', 'พีรสรณ์ หรั่งชั้น', '2025-09-19 17:09:23', '2025-09-23 09:55:51', NULL, 14, 1, 'พีรสรณ์ หรั่งชั้น', '2025-09-23', 81.00, 14, 1, 'พีรสรณ์ รับแล้ว', '2025-09-23 09:55:51', '25090027', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(35, 1, '2025-09-22', 'endprogress', 'กิตติพรรณ บุญช่วย ICT', '2025-09-22 08:52:53', '2025-09-23 09:56:08', NULL, 14, 1, 'กิตติพรรณ บุญช่วย ICT', '2025-09-23', 81.00, 14, 1, 'กิตติพรรณ บุญช่วย ICT  รับแล้ว', '2025-09-23 09:56:08', '25090028', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(36, 15, '2025-09-22', 'endprogress', 'คุณวัณณ์นลัท    HA', '2025-09-22 15:27:39', '2025-09-23 09:56:21', NULL, 14, 1, 'คุณวัณณ์นลัท HA', '2025-09-23', 10.00, 14, 1, 'คุณวัณณ์นลัท HA รับแล้ว', '2025-09-23 09:56:21', '25090029', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(37, 15, '2025-09-22', 'endprogress', 'คุณผกามาศ  HAMS', '2025-09-22 15:28:22', '2025-09-23 09:56:35', NULL, 14, 1, 'คุณผกามาศ HAMS', '2025-09-23', 25.00, 14, 1, 'คุณผกามาศ HAMS รับแล้ว', '2025-09-23 09:56:35', '25090030', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(38, 15, '2025-09-22', 'endprogress', 'คุณชลธชา  SS', '2025-09-22 15:28:59', '2025-09-23 09:56:48', NULL, 14, 1, 'คุณชลธชา SS', '2025-09-23', 50.00, 14, 1, 'คุณชลธชา SS  รับแล้ว', '2025-09-23 09:56:48', '25090031', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(39, 15, '2025-09-22', 'endprogress', 'คุณวัณณ์นลัท HAM', '2025-09-22 16:51:45', '2025-09-23 09:57:05', NULL, 14, 1, 'คุณวัณณ์นลัท HAM', '2025-09-23', 23.00, 14, 1, 'คุณวัณณ์นลัท HAM  รับแล้ว', '2025-09-23 09:57:05', '25090032', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(40, 5, '2025-09-23', 'endprogress', 'กฤษณา จินาวัลย์ เบิกใช้ในโครงการ EMC ดาวเทียม', '2025-09-23 10:28:19', '2025-09-25 09:48:42', NULL, 1, 1, 'รับทราบ', '2025-09-24', 80.00, 14, 1, 'กฤษณา จินาวัลย์ เบิกใช้ในโครงการ EMC ดาวเทียม \r\nรับแล้ว', '2025-09-25 09:48:42', '25090033', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(41, 5, '2025-09-23', 'endprogress', 'เฉลิมพรรษ เคล้าศรี', '2025-09-23 10:29:08', '2025-09-25 09:48:54', NULL, 14, 1, 'หมายเหตุ: เฉลิมพรรษ เคล้าศรี', '2025-09-25', 31.00, 14, 1, 'เฉลิมพรรษ เคล้าศรี รับแล้ว', '2025-09-25 09:48:54', '25090034', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(42, 5, '2025-09-23', 'endprogress', 'ชลธชา สกุลจันทร์', '2025-09-23 11:49:09', '2025-09-25 09:49:07', NULL, 14, 1, 'ชลธชา สกุลจันทร์', '2025-09-25', 31.00, 14, 1, 'ชลธชา สกุลจันทร์ รับแล้ว', '2025-09-25 09:49:07', '25090035', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(43, 5, '2025-09-23', 'endprogress', 'ศุภวิชญ์ ภูริวัฒนกุล', '2025-09-23 16:44:44', '2025-09-25 09:49:24', NULL, 14, 1, 'ศุภวิชญ์ ภูริวัฒนกุล', '2025-09-25', 525.00, 14, 1, 'ศุภวิชญ์ ภูริวัฒนกุล รับแล้ว', '2025-09-25 09:49:24', '25090036', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(44, 15, '2025-09-24', 'endprogress', 'คุณกฤติยา อรัณยะนาค   LG', '2025-09-24 09:24:58', '2025-09-25 09:49:36', NULL, 14, 1, 'คุณกฤติยา อรัณยะนาค LG', '2025-09-25', 48.00, 14, 1, 'คุณกฤติยา อรัณยะนาค LG รับแล้ว', '2025-09-25 09:49:36', '25090037', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(45, 5, '2025-09-24', 'endprogress', 'ผกามาศ', '2025-09-24 11:09:14', '2025-09-25 09:49:51', NULL, 14, 1, 'ผกามาศ', '2025-09-25', 68.00, 14, 1, 'ผกามาศ รับแล้ว', '2025-09-25 09:49:51', '25090038', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(46, 15, '2025-09-24', 'endprogress', 'คุณศิริธร เกียรติจรูญเลิศ  CS', '2025-09-24 15:10:32', '2025-09-25 09:50:03', NULL, 14, 1, 'คุณศิริธร เกียรติจรูญเลิศ CS', '2025-09-25', 13.00, 14, 1, 'คุณศิริธร เกียรติจรูญเลิศ CS รับแล้ว', '2025-09-25 09:50:03', '25090039', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(47, 5, '2025-09-25', 'endprogress', 'แคชทริยา เบิกส่วนกลางชั้น 5', '2025-09-25 09:46:09', '2025-09-25 09:50:17', NULL, 14, 1, 'แคชทริยา เบิกส่วนกลางชั้น 5', '2025-09-25', 162.00, 14, 1, 'แคชทริยา เบิกส่วนกลางชั้น 5 รับแล้ว', '2025-09-25 09:50:17', '25090040', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(48, 5, '2025-09-25', 'endprogress', 'ฑัณฑิกา เบิกส่วนกลางชั้น 3', '2025-09-25 10:17:50', '2025-09-25 10:18:48', NULL, 14, 1, 'ฑัณฑิกา เบิกส่วนกลางชั้น 3', '2025-09-25', 405.00, 14, 1, 'ฑัณฑิกา เบิกส่วนกลางชั้น 3 รับแล้ว', '2025-09-25 10:18:48', '25090041', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(50, 209, '2025-09-26', 'endprogress', 'ใช้งานแผนก', '2025-09-26 16:22:52', '2025-10-03 17:26:18', NULL, 266, 1, 'k', '2025-10-03', 15.00, 266, 1, 'รับแล้ว', '2025-10-03 17:26:18', '25090043', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(56, 228, '2025-09-29', 'endprogress', 'ยืนยัน', '2025-09-29 10:28:43', '2025-10-03 17:26:27', NULL, 266, 1, 'ok', '2025-10-03', 90.00, 266, 1, 'รับแล้ว', '2025-10-03 17:26:27', '25090044', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(57, 242, '2025-09-29', 'endprogress', 'ยืนยัน', '2025-09-29 10:30:43', '2025-10-03 17:26:36', NULL, 266, 1, 'ok', '2025-10-03', 23.00, 266, 1, 'รับแล้ว', '2025-10-03 17:26:36', '25090045', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(58, 280, '2025-09-29', 'endprogress', 'ยืนยัน', '2025-09-29 14:17:29', '2025-10-03 17:26:45', NULL, 266, 1, 'ok', '2025-10-03', 10.00, 266, 1, 'รับแล้ว', '2025-10-03 17:26:45', '25090046', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(60, 295, '2025-09-30', 'endprogress', 'ยืนยัน', '2025-09-30 09:41:44', '2025-10-03 17:26:53', NULL, 266, 1, 'อนุมัติ', '2025-09-30', 147.00, 266, 1, 'รับแล้ว', '2025-10-03 17:26:53', '25090048', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(61, 213, '2025-09-30', 'endprogress', 'ok', '2025-09-30 10:38:06', '2025-10-03 17:27:01', NULL, 266, 1, 'ok', '2025-10-03', 12.00, 266, 1, 'รับแล้ว', '2025-10-03 17:27:01', '25090049', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(62, 300, '2025-09-30', 'endprogress', 'ok', '2025-09-30 11:05:50', '2025-10-03 17:27:09', NULL, 266, 1, 'ok', '2025-10-03', 21.00, 266, 1, 'รับแล้ว', '2025-10-03 17:27:09', '25090050', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(63, 86, '2025-09-30', 'endprogress', 'ok', '2025-09-30 11:13:26', '2025-10-03 17:27:19', NULL, 266, 1, 'ok', '2025-10-03', 810.00, 266, 1, 'รับแล้ว', '2025-10-03 17:27:19', '25090051', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(64, 91, '2025-09-30', 'endprogress', 'ok', '2025-09-30 14:42:52', '2025-10-03 17:27:26', NULL, 266, 1, 'ok', '2025-10-03', 405.00, 266, 1, 'รับแล้ว', '2025-10-03 17:27:26', '25090052', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(65, 209, '2025-10-01', 'endprogress', 'ส่วนกลางชั้น 5', '2025-10-01 15:01:55', '2025-10-03 17:27:34', NULL, 266, 1, 'ok', '2025-10-03', 405.00, 266, 1, 'รับแล้ว', '2025-10-03 17:27:34', '25100001', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(66, 242, '2025-10-01', 'endprogress', 'ยืนยัน', '2025-10-01 15:24:06', '2025-10-03 17:27:42', NULL, 266, 1, 'ok', '2025-10-03', 85.00, 266, 1, 'รับแล้ว', '2025-10-03 17:27:42', '25100002', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(67, 244, '2025-10-02', 'endprogress', 'ok', '2025-10-02 10:44:45', '2025-10-03 17:27:50', NULL, 266, 1, 'ok', '2025-10-03', 162.00, 266, 1, 'รับแล้ว', '2025-10-03 17:27:50', '25100003', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(68, 261, '2025-10-03', 'endprogress', 'ok', '2025-10-03 10:21:22', '2025-10-03 17:27:59', NULL, 266, 1, 'ok', '2025-10-03', 429.00, 266, 1, 'รับแล้ว', '2025-10-03 17:27:59', '25100004', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(69, 272, '2025-10-06', 'endprogress', 'ok', '2025-10-06 13:38:28', '2025-10-06 13:39:35', NULL, 266, 1, 'ok', '2025-10-06', 5.00, 266, 1, 'รับแล้ว', '2025-10-06 13:39:35', '25100005', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(70, 281, '2025-10-08', 'endprogress', 'ยืนยัน', '2025-10-08 10:06:12', '2025-10-08 10:11:42', NULL, 266, 1, 'ok', '2025-10-08', 21.00, 266, 1, 'รับแล้ว', '2025-10-08 10:11:42', '25100006', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(71, 250, '2025-10-08', 'endprogress', 'ยืนยัน', '2025-10-08 10:09:29', '2025-10-08 10:11:48', NULL, 266, 1, 'ok', '2025-10-08', 70.00, 266, 1, 'รับแล้ว', '2025-10-08 10:11:48', '25100007', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(72, 99, '2025-10-08', 'endprogress', 'สำหรับโรงงาน', '2025-10-08 10:10:47', '2025-10-08 10:11:55', NULL, 266, 1, 'ok', '2025-10-08', 5670.00, 266, 1, 'รับแล้ว', '2025-10-08 10:11:55', '25100008', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(73, 273, '2025-10-08', 'endprogress', 'ยืนยัน', '2025-10-08 10:16:08', '2025-10-08 11:29:54', NULL, 266, 1, 'ok', '2025-10-08', 162.00, 266, 1, 'รับแล้ว', '2025-10-08 11:29:54', '25100009', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(74, 233, '2025-10-08', 'endprogress', 'ok', '2025-10-08 11:32:51', '2025-10-08 11:33:32', NULL, 266, 1, 'ok', '2025-10-08', 405.00, 266, 1, 'รับแล้ว', '2025-10-08 11:33:32', '25100010', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(75, 91, '2025-10-08', 'endprogress', 'ok', '2025-10-08 11:37:14', '2025-10-08 11:41:43', NULL, 266, 1, 'ok', '2025-10-08', 405.00, 266, 1, 'รับแล้ว', '2025-10-08 11:41:43', '25100011', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(76, 208, '2025-10-08', 'endprogress', 'แผนก DB ขอเบิกกระดาษ A4 ใช้ส่วนกลางชั้น 2 จำนวน  5 รีม ค่ะ 23/9/68', '2025-10-08 11:40:49', '2025-10-08 11:41:50', NULL, 266, 1, 'ok', '2025-10-08', 405.00, 266, 1, 'รับแล้ว', '2025-10-08 11:41:50', '25100012', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(77, 308, '2025-10-08', 'endprogress', 'ok', '2025-10-08 16:26:16', '2025-10-16 11:18:55', NULL, 266, 1, 'ok', '2025-10-16', 99.00, 266, 1, 'ok', '2025-10-16 11:18:55', '25100013', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(78, 104, '2025-10-08', 'endprogress', 'ok', '2025-10-08 16:26:47', '2025-10-16 11:19:03', NULL, 266, 1, 'ok', '2025-10-16', 23.00, 266, 1, 'ok', '2025-10-16 11:19:03', '25100014', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(79, 305, '2025-10-09', 'endprogress', 'ok', '2025-10-09 14:27:37', '2025-10-16 11:19:11', NULL, 266, 1, 'ok', '2025-10-16', 405.00, 266, 1, 'ok', '2025-10-16 11:19:11', '25100015', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(80, 312, '2025-10-09', 'endprogress', 'ok', '2025-10-09 14:28:09', '2025-10-16 11:19:19', NULL, 266, 1, 'ok', '2025-10-16', 30.00, 266, 1, 'ok', '2025-10-16 11:19:19', '25100016', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(81, 237, '2025-10-15', 'endprogress', 'ยืนยัน', '2025-10-15 10:55:56', '2025-10-16 11:19:30', NULL, 266, 1, 'ok', '2025-10-16', 59.00, 266, 1, 'ok', '2025-10-16 11:19:30', '25100017', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(82, 91, '2025-10-15', 'endprogress', 'ส่วนกลางชั้น 2', '2025-10-15 10:57:24', '2025-10-16 11:19:37', NULL, 266, 1, 'ok', '2025-10-16', 405.00, 266, 1, 'ok', '2025-10-16 11:19:37', '25100018', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(83, 31, '2025-10-15', 'endprogress', 'ยืนยัน', '2025-10-15 10:58:07', '2025-10-16 11:19:44', NULL, 266, 1, 'ok', '2025-10-16', 10.00, 266, 1, 'ok', '2025-10-16 11:19:44', '25100019', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(84, 305, '2025-10-16', 'endprogress', 'ส่วนกลางชั้น 3', '2025-10-16 10:15:34', '2025-10-16 11:19:52', NULL, 266, 1, 'ok', '2025-10-16', 405.00, 266, 1, 'ok', '2025-10-16 11:19:52', '25100020', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(85, 266, '2025-10-16', 'endprogress', 'ใช้ในห้องประชุม', '2025-10-16 11:18:27', '2025-10-16 11:19:59', NULL, 266, 1, 'ok', '2025-10-16', 27.00, 266, 1, 'ok', '2025-10-16 11:19:59', '25100021', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(86, 105, '2025-10-17', 'endprogress', 'ok', '2025-10-17 16:47:39', '2025-10-17 16:51:54', NULL, 266, 1, 'ok', '2025-10-17', 81.00, 266, 1, 'รับแล้ว', '2025-10-17 16:51:54', '25100022', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(87, 58, '2025-10-17', 'endprogress', 'ok', '2025-10-17 16:50:48', '2025-10-17 16:52:11', NULL, 266, 1, 'ok', '2025-10-17', 264.00, 266, 1, 'รับแล้ว', '2025-10-17 16:52:11', '25100023', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(94, 306, '2025-11-18', 'cancelled', 'ทดสอบระบบ', '2025-11-18 07:17:33', '2025-11-18 07:25:25', NULL, NULL, 0, NULL, NULL, 24.00, NULL, 0, NULL, NULL, '25110001', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(95, 306, '2025-11-18', 'cancelled', 'ทดสอบระบบ', '2025-11-18 07:24:50', '2025-11-18 07:25:22', NULL, NULL, 0, NULL, NULL, 5.00, NULL, 0, NULL, NULL, '25110002', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(96, 306, '2025-11-18', 'endprogress', 'ทดสอบระบบ\r\nครั้งที่ 100000000', '2025-11-18 07:32:19', '2026-03-23 08:29:11', NULL, NULL, 0, NULL, NULL, 5.00, 322, 1, NULL, '2026-03-23 08:29:11', '25110003', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(97, 306, '2026-03-04', 'endprogress', 'ใช้ตัดกระดาษ', '2026-03-04 03:47:17', '2026-03-05 01:24:43', NULL, NULL, 0, NULL, NULL, 45.00, 306, 1, 'เ', '2026-03-05 01:24:43', '26030001', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(98, 322, '2026-03-05', 'endprogress', 'test', '2026-03-05 02:20:26', '2026-03-23 08:28:59', NULL, NULL, 0, NULL, NULL, 3.00, 322, 1, NULL, '2026-03-23 08:28:59', '26030002', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(99, 322, '2026-03-30', 'endprogress', 'อันเก่าหมด', '2026-03-30 02:13:50', '2026-03-30 03:16:28', NULL, NULL, 0, NULL, NULL, 3.00, 306, 1, NULL, '2026-03-30 03:16:28', '26030003', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(100, 306, '2026-03-30', 'endprogress', 'test', '2026-03-30 02:29:18', '2026-03-30 02:59:10', NULL, NULL, 0, NULL, NULL, 3.00, 306, 1, NULL, '2026-03-30 02:59:10', '26030004', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(101, 306, '2026-03-30', 'cancelled', 'test', '2026-03-30 03:37:50', '2026-03-30 03:38:31', NULL, NULL, 0, NULL, NULL, 8.00, NULL, 0, NULL, NULL, '26030005', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(102, 322, '2026-03-30', 'endprogress', 'test', '2026-03-30 04:06:06', '2026-04-06 07:41:03', NULL, NULL, 0, NULL, NULL, 3.00, 322, 1, 'ok', '2026-04-06 07:41:03', '26030006', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(103, 322, '2026-03-30', 'endprogress', 'test', '2026-03-30 04:12:01', '2026-03-30 04:38:10', NULL, NULL, 0, NULL, NULL, 5.00, 306, 1, NULL, '2026-03-30 04:38:10', '26030007', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(104, 306, '2026-03-30', 'endprogress', 'test2', '2026-03-30 04:20:13', '2026-03-30 04:20:48', NULL, NULL, 0, NULL, NULL, 3.00, 306, 1, NULL, '2026-03-30 04:20:48', '26030008', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(105, 306, '2026-04-01', 'endprogress', 'งาน', '2026-04-01 02:50:31', '2026-04-01 02:58:21', NULL, NULL, 0, NULL, NULL, 50.00, 306, 1, 'ok', '2026-04-01 02:58:21', '26040001', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(106, 322, '2026-04-06', 'endprogress', 'ok', '2026-04-06 01:26:18', '2026-04-06 01:39:33', NULL, NULL, 0, NULL, NULL, 5.00, 306, 1, NULL, '2026-04-06 01:39:33', '26040002', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(107, 664, '2026-04-06', 'cancelled', 'ok', '2026-04-06 03:42:10', '2026-04-06 07:35:32', NULL, NULL, 0, NULL, NULL, 5.00, 322, 2, 's', '2026-04-06 07:35:32', '26040003', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(108, 322, '2026-04-06', 'cancelled', 'test', '2026-04-06 07:34:43', '2026-04-06 07:40:35', NULL, NULL, 0, NULL, NULL, 5.00, 322, 2, 'สินค้าหมด', '2026-04-06 07:40:35', '26040004', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(109, 322, '2026-04-06', 'cancelled', 'ko', '2026-04-06 07:43:56', '2026-04-06 07:48:52', NULL, NULL, 0, NULL, NULL, 5.00, NULL, 0, NULL, NULL, '26040005', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(110, 664, '2026-04-10', 'endprogress', 'ก', '2026-04-10 08:23:05', '2026-04-10 08:25:04', NULL, NULL, 0, NULL, NULL, 5.00, 664, 1, 'ส', '2026-04-10 08:25:04', '26040006', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(111, 664, '2026-04-16', 'endprogress', 'ร', '2026-04-16 01:26:34', '2026-04-16 01:47:20', NULL, NULL, 0, NULL, NULL, 5.00, 664, 1, 'า', '2026-04-16 01:47:20', '26040007', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(112, 664, '2026-04-17', 'endprogress', 'test', '2026-04-17 04:29:23', '2026-04-17 04:30:09', NULL, NULL, 0, NULL, NULL, 3.00, 664, 1, NULL, '2026-04-17 04:30:09', '26040008', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(113, 664, '2026-04-17', 'endprogress', 'ใช้งาน', '2026-04-17 04:56:24', '2026-04-17 05:32:57', NULL, NULL, 0, NULL, NULL, 5.00, 664, 1, NULL, '2026-04-17 05:32:57', '26040009', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(114, 664, '2026-04-17', 'endprogress', 'd', '2026-04-17 05:54:23', '2026-04-20 08:50:33', NULL, 664, 1, NULL, '2026-04-20', 5.00, 664, 1, NULL, '2026-04-17 06:00:39', '26040010', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(115, 664, '2026-04-17', 'endprogress', 'ok', '2026-04-17 06:52:44', '2026-04-17 07:46:00', NULL, 669, 1, NULL, '2026-04-17', 5.00, 664, 1, NULL, '2026-04-17 07:46:00', '26040011', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(116, 664, '2026-04-17', 'endprogress', 'ใช่งาน', '2026-04-17 07:47:35', '2026-04-17 08:07:39', NULL, 664, 1, NULL, '2026-04-17', 21.00, 664, 1, NULL, '2026-04-17 08:07:39', '26040012', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(117, 664, '2026-04-23', 'endprogress', 'test', '2026-04-23 03:21:10', '2026-04-23 03:43:21', NULL, 664, 1, NULL, '2026-04-23', 3.00, 664, 1, NULL, '2026-04-23 03:21:56', '26040013', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(118, 664, '2026-04-23', 'endprogress', 'จำเป็น', '2026-04-23 03:27:36', '2026-04-23 03:46:06', NULL, 664, 1, NULL, '2026-04-23', 3.00, 664, 1, NULL, '2026-04-23 03:46:06', '26040014', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(119, 652, '2026-06-17', 'endprogress', 'ok', '2026-06-17 01:30:00', '2026-06-17 01:35:26', NULL, 652, 1, NULL, '2026-06-17', 8.00, 652, 1, NULL, '2026-06-17 01:35:26', '26060001', NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `requisition_items`
--

CREATE TABLE `requisition_items` (
  `requistionitem_id` int(11) NOT NULL,
  `requisition_id` int(11) NOT NULL COMMENT 'รหัสการเบิกของ',
  `item_id` int(11) NOT NULL COMMENT 'รหัสของที่เบิก',
  `quantity` int(11) NOT NULL COMMENT 'จำนวน(ชิ้น)ที่เบิก',
  `unit` varchar(50) DEFAULT NULL COMMENT 'หน่วยที่เบิก',
  `total_price` decimal(10,2) DEFAULT NULL COMMENT 'ราคารวมทั้งหมด',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `check_item` int(11) NOT NULL DEFAULT 0 COMMENT '0 = ยังไม่ได้จัด, 1 = จัดเรียบร้อย'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `requisition_items`
--

INSERT INTO `requisition_items` (`requistionitem_id`, `requisition_id`, `item_id`, `quantity`, `unit`, `total_price`, `created_at`, `updated_at`, `check_item`) VALUES
(31, 6, 78, 5, NULL, NULL, '2025-09-10 10:52:46', '2025-09-10 11:10:50', 1),
(32, 7, 4, 1, NULL, NULL, '2025-09-10 10:56:42', '2025-09-10 11:11:16', 1),
(33, 7, 6, 1, NULL, NULL, '2025-09-10 10:56:42', '2025-09-10 11:11:17', 1),
(34, 7, 11, 1, NULL, NULL, '2025-09-10 10:56:42', '2025-09-10 11:11:17', 1),
(35, 7, 9, 1, NULL, NULL, '2025-09-10 10:56:42', '2025-09-10 11:11:17', 1),
(36, 7, 35, 1, NULL, NULL, '2025-09-10 10:56:42', '2025-09-10 11:11:19', 1),
(37, 7, 2, 1, NULL, NULL, '2025-09-10 10:56:42', '2025-09-10 11:11:19', 1),
(38, 7, 10, 2, NULL, NULL, '2025-09-10 10:56:42', '2025-09-10 11:11:21', 1),
(39, 7, 1, 2, NULL, NULL, '2025-09-10 10:56:42', '2025-09-10 11:11:21', 1),
(41, 9, 19, 1, NULL, NULL, '2025-09-10 10:58:37', '2025-09-10 11:11:31', 1),
(42, 9, 20, 2, NULL, NULL, '2025-09-10 10:58:37', '2025-09-10 11:11:31', 1),
(43, 9, 1, 2, NULL, NULL, '2025-09-10 10:58:37', '2025-09-10 11:11:32', 1),
(44, 10, 1, 2, NULL, NULL, '2025-09-10 11:00:38', '2025-09-10 11:11:43', 1),
(45, 10, 37, 1, NULL, NULL, '2025-09-10 11:00:38', '2025-09-10 11:11:44', 1),
(46, 11, 37, 1, NULL, NULL, '2025-09-10 11:01:30', '2025-09-10 11:11:53', 1),
(47, 11, 1, 2, NULL, NULL, '2025-09-10 11:01:30', '2025-09-10 11:11:54', 1),
(48, 12, 78, 5, NULL, NULL, '2025-09-10 11:03:34', '2025-09-10 11:12:02', 1),
(50, 14, 10, 1, NULL, NULL, '2025-09-10 16:08:35', '2025-09-10 16:20:01', 1),
(51, 15, 11, 53, NULL, NULL, '2025-09-10 16:18:20', '2025-09-10 16:21:18', 1),
(52, 15, 23, 5, NULL, NULL, '2025-09-10 16:18:20', '2025-09-10 16:21:19', 1),
(53, 15, 30, 5, NULL, NULL, '2025-09-10 16:18:20', '2025-09-10 16:21:20', 1),
(54, 15, 20, 3, NULL, NULL, '2025-09-10 16:18:20', '2025-09-10 16:21:20', 1),
(55, 15, 10, 3, NULL, NULL, '2025-09-10 16:18:20', '2025-09-10 16:21:21', 1),
(56, 15, 14, 2, NULL, NULL, '2025-09-10 16:18:20', '2025-09-10 16:21:21', 1),
(57, 15, 13, 2, NULL, NULL, '2025-09-10 16:18:20', '2025-09-10 16:21:22', 1),
(58, 15, 15, 3, NULL, NULL, '2025-09-10 16:18:21', '2025-09-10 16:21:22', 1),
(59, 15, 16, 3, NULL, NULL, '2025-09-10 16:18:21', '2025-09-10 16:21:23', 1),
(60, 16, 80, 6, NULL, NULL, '2025-09-10 16:25:17', '2025-09-11 11:19:36', 1),
(62, 18, 46, 1, NULL, NULL, '2025-09-11 09:55:55', '2025-09-11 11:21:39', 1),
(63, 19, 78, 5, NULL, NULL, '2025-09-11 11:18:08', '2025-09-11 11:21:58', 1),
(64, 20, 21, 1, NULL, NULL, '2025-09-11 11:18:53', '2025-09-11 11:22:27', 1),
(65, 21, 4, 1, NULL, NULL, '2025-09-11 11:19:54', '2025-09-11 11:22:43', 1),
(66, 22, 47, 1, NULL, NULL, '2025-09-11 13:57:39', '2025-09-11 14:04:48', 1),
(67, 23, 11, 1, NULL, NULL, '2025-09-11 16:19:24', '2025-09-11 16:55:12', 1),
(68, 23, 1, 1, NULL, NULL, '2025-09-11 16:19:24', '2025-09-11 16:55:12', 1),
(69, 23, 4, 1, NULL, NULL, '2025-09-11 16:19:24', '2025-09-11 16:55:13', 1),
(70, 24, 78, 5, NULL, NULL, '2025-09-11 16:26:04', '2025-09-11 16:55:28', 1),
(71, 25, 21, 1, NULL, NULL, '2025-09-12 13:57:29', '2025-09-12 15:26:27', 1),
(72, 25, 30, 2, NULL, NULL, '2025-09-12 13:57:29', '2025-09-12 15:26:28', 1),
(73, 26, 7, 3, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:12:31', 1),
(74, 26, 43, 100, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:12:31', 1),
(75, 26, 42, 12, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:12:33', 1),
(76, 26, 69, 2, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:12:34', 1),
(77, 26, 37, 10, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:48:14', 1),
(78, 26, 32, 2, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:48:16', 1),
(79, 26, 26, 3, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:48:17', 1),
(80, 26, 23, 48, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:48:17', 1),
(81, 26, 31, 2, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:48:18', 1),
(82, 26, 75, 2, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:48:18', 1),
(83, 26, 30, 15, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:48:19', 1),
(84, 26, 10, 5, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:48:19', 1),
(85, 26, 1, 50, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:48:20', 1),
(86, 26, 4, 24, NULL, NULL, '2025-09-12 15:34:31', '2025-09-15 14:48:20', 1),
(87, 27, 78, 5, NULL, NULL, '2025-09-15 13:50:44', '2025-09-15 14:48:40', 1),
(88, 28, 35, 3, NULL, NULL, '2025-09-17 09:32:30', '2025-09-17 09:39:47', 1),
(89, 28, 36, 2, NULL, NULL, '2025-09-17 09:32:30', '2025-09-17 09:39:48', 1),
(90, 28, 6, 2, NULL, NULL, '2025-09-17 09:32:30', '2025-09-17 09:39:50', 1),
(91, 28, 15, 3, NULL, NULL, '2025-09-17 09:32:30', '2025-09-17 09:39:50', 1),
(92, 28, 1, 5, NULL, NULL, '2025-09-17 09:32:30', '2025-09-17 09:39:51', 1),
(93, 28, 78, 5, NULL, NULL, '2025-09-17 09:32:30', '2025-09-17 09:39:51', 1),
(94, 28, 11, 5, NULL, NULL, '2025-09-17 09:32:30', '2025-09-17 09:39:51', 1),
(95, 28, 10, 2, NULL, NULL, '2025-09-17 09:32:30', '2025-09-17 09:39:52', 1),
(96, 29, 43, 10, NULL, NULL, '2025-09-17 09:54:24', '2025-09-18 09:26:47', 1),
(97, 30, 79, 1, NULL, NULL, '2025-09-17 14:30:27', '2025-09-18 09:27:04', 1),
(98, 31, 78, 5, NULL, NULL, '2025-09-17 15:10:57', '2025-09-18 09:27:18', 1),
(99, 32, 81, 3, NULL, NULL, '2025-09-18 10:42:10', '2025-09-18 10:43:23', 1),
(100, 33, 30, 1, NULL, NULL, '2025-09-19 13:17:47', '2025-09-23 09:55:30', 1),
(101, 34, 78, 1, NULL, NULL, '2025-09-19 17:09:23', '2025-09-23 09:55:44', 1),
(102, 35, 78, 1, NULL, NULL, '2025-09-22 08:52:53', '2025-09-23 09:56:00', 1),
(103, 36, 1, 2, NULL, NULL, '2025-09-22 15:27:39', '2025-09-23 09:56:14', 1),
(104, 37, 10, 1, NULL, NULL, '2025-09-22 15:28:22', '2025-09-23 09:56:28', 1),
(105, 38, 10, 2, NULL, NULL, '2025-09-22 15:28:59', '2025-09-23 09:56:42', 1),
(106, 39, 48, 1, NULL, NULL, '2025-09-22 16:51:45', '2025-09-23 09:56:59', 1),
(107, 40, 43, 20, NULL, NULL, '2025-09-23 10:28:19', '2025-09-25 09:48:25', 1),
(108, 40, 39, 1, NULL, NULL, '2025-09-23 10:28:19', '2025-09-25 09:48:27', 1),
(109, 40, 41, 12, NULL, NULL, '2025-09-23 10:28:19', '2025-09-25 09:48:27', 1),
(110, 41, 18, 1, NULL, NULL, '2025-09-23 10:29:08', '2025-09-25 09:48:48', 1),
(111, 42, 4, 1, NULL, NULL, '2025-09-23 11:49:09', '2025-09-25 09:49:00', 1),
(112, 42, 20, 1, NULL, NULL, '2025-09-23 11:49:09', '2025-09-25 09:49:01', 1),
(113, 43, 35, 1, NULL, NULL, '2025-09-23 16:44:44', '2025-09-25 09:49:14', 1),
(114, 43, 10, 2, NULL, NULL, '2025-09-23 16:44:44', '2025-09-25 09:49:15', 1),
(115, 43, 4, 1, NULL, NULL, '2025-09-23 16:44:44', '2025-09-25 09:49:17', 1),
(116, 43, 2, 1, NULL, NULL, '2025-09-23 16:44:44', '2025-09-25 09:49:17', 1),
(117, 43, 1, 4, NULL, NULL, '2025-09-23 16:44:44', '2025-09-25 09:49:18', 1),
(118, 43, 78, 5, NULL, NULL, '2025-09-23 16:44:44', '2025-09-25 09:49:18', 1),
(119, 44, 36, 2, NULL, NULL, '2025-09-24 09:24:58', '2025-09-25 09:49:31', 1),
(120, 45, 3, 1, NULL, NULL, '2025-09-24 11:09:14', '2025-09-25 09:49:45', 1),
(121, 45, 1, 1, NULL, NULL, '2025-09-24 11:09:14', '2025-09-25 09:49:46', 1),
(122, 45, 35, 2, NULL, NULL, '2025-09-24 11:09:14', '2025-09-25 09:49:46', 1),
(123, 46, 23, 1, NULL, NULL, '2025-09-24 15:10:32', '2025-09-25 09:49:58', 1),
(124, 47, 78, 2, NULL, NULL, '2025-09-25 09:46:09', '2025-09-25 09:50:12', 1),
(125, 48, 78, 5, NULL, NULL, '2025-09-25 10:17:50', '2025-09-25 10:18:36', 1),
(126, 49, 1, 1, NULL, NULL, '2025-09-26 11:07:55', '2025-09-26 11:07:55', 0),
(127, 50, 41, 3, NULL, NULL, '2025-09-26 16:22:52', '2025-10-03 17:26:07', 1),
(128, 51, 2, 1, NULL, NULL, '2025-09-26 16:31:23', '2025-09-26 16:31:23', 0),
(129, 52, 29, 1, NULL, NULL, '2025-09-26 16:31:47', '2025-09-26 16:31:47', 0),
(130, 53, 41, 3, NULL, NULL, '2025-09-26 16:32:31', '2025-09-26 16:32:31', 0),
(131, 54, 2, 1, NULL, NULL, '2025-09-26 16:33:35', '2025-09-26 16:33:35', 0),
(132, 55, 1, 1, NULL, NULL, '2025-09-26 16:35:19', '2025-09-26 16:35:19', 0),
(133, 56, 17, 1, NULL, NULL, '2025-09-29 10:28:43', '2025-10-03 17:26:22', 1),
(134, 56, 14, 1, NULL, NULL, '2025-09-29 10:28:43', '2025-10-03 17:26:23', 1),
(135, 57, 48, 1, NULL, NULL, '2025-09-29 10:30:43', '2025-10-03 17:26:31', 1),
(136, 58, 43, 10, NULL, NULL, '2025-09-29 14:17:29', '2025-10-03 17:26:40', 1),
(137, 59, 1, 1, NULL, NULL, '2025-09-29 14:26:55', '2025-09-29 14:27:57', 1),
(138, 60, 35, 1, NULL, NULL, '2025-09-30 09:41:44', '2025-09-30 09:46:45', 1),
(139, 60, 36, 2, NULL, NULL, '2025-09-30 09:41:44', '2025-09-30 09:46:46', 1),
(140, 60, 30, 4, NULL, NULL, '2025-09-30 09:41:44', '2025-09-30 09:46:46', 1),
(141, 60, 10, 1, NULL, NULL, '2025-09-30 09:41:44', '2025-09-30 09:46:47', 1),
(142, 61, 15, 1, NULL, NULL, '2025-09-30 10:38:06', '2025-10-03 17:26:57', 1),
(143, 62, 14, 1, NULL, NULL, '2025-09-30 11:05:50', '2025-10-03 17:27:05', 1),
(144, 63, 78, 10, NULL, NULL, '2025-09-30 11:13:26', '2025-10-03 17:27:14', 1),
(145, 64, 78, 5, NULL, NULL, '2025-09-30 14:42:52', '2025-10-03 17:27:22', 1),
(146, 65, 78, 5, NULL, NULL, '2025-10-01 15:01:55', '2025-10-03 17:27:30', 1),
(147, 66, 41, 5, NULL, NULL, '2025-10-01 15:24:06', '2025-10-03 17:27:38', 1),
(148, 66, 35, 2, NULL, NULL, '2025-10-01 15:24:06', '2025-10-03 17:27:38', 1),
(149, 67, 78, 2, NULL, NULL, '2025-10-02 10:44:45', '2025-10-03 17:27:46', 1),
(150, 68, 15, 2, NULL, NULL, '2025-10-03 10:21:22', '2025-10-03 17:27:54', 1),
(151, 68, 78, 5, NULL, NULL, '2025-10-03 10:21:22', '2025-10-03 17:27:55', 1),
(152, 69, 1, 1, NULL, NULL, '2025-10-06 13:38:28', '2025-10-06 13:39:19', 1),
(153, 70, 14, 1, NULL, NULL, '2025-10-08 10:06:12', '2025-10-08 10:11:35', 1),
(154, 71, 26, 1, NULL, NULL, '2025-10-08 10:09:29', '2025-10-08 10:11:45', 1),
(155, 72, 78, 70, NULL, NULL, '2025-10-08 10:10:48', '2025-10-08 10:11:52', 1),
(156, 73, 78, 2, NULL, NULL, '2025-10-08 10:16:08', '2025-10-08 11:29:49', 1),
(157, 74, 78, 5, NULL, NULL, '2025-10-08 11:32:51', '2025-10-08 11:33:26', 1),
(158, 75, 78, 5, NULL, NULL, '2025-10-08 11:37:14', '2025-10-08 11:41:32', 1),
(159, 76, 78, 5, NULL, NULL, '2025-10-08 11:40:49', '2025-10-08 11:41:47', 1),
(160, 77, 17, 1, NULL, NULL, '2025-10-08 16:26:16', '2025-10-16 11:18:51', 1),
(161, 77, 35, 1, NULL, NULL, '2025-10-08 16:26:16', '2025-10-16 11:18:51', 1),
(162, 78, 48, 1, NULL, NULL, '2025-10-08 16:26:47', '2025-10-16 11:19:00', 1),
(163, 79, 78, 5, NULL, NULL, '2025-10-09 14:27:37', '2025-10-16 11:19:07', 1),
(164, 80, 35, 1, NULL, NULL, '2025-10-09 14:28:09', '2025-10-16 11:19:15', 1),
(165, 81, 3, 1, NULL, NULL, '2025-10-15 10:55:56', '2025-10-16 11:19:24', 1),
(166, 81, 10, 1, NULL, NULL, '2025-10-15 10:55:56', '2025-10-16 11:19:24', 1),
(167, 81, 2, 1, NULL, NULL, '2025-10-15 10:55:56', '2025-10-16 11:19:25', 1),
(168, 81, 4, 1, NULL, NULL, '2025-10-15 10:55:56', '2025-10-16 11:19:26', 1),
(169, 81, 30, 1, NULL, NULL, '2025-10-15 10:55:56', '2025-10-16 11:19:27', 1),
(170, 82, 78, 5, NULL, NULL, '2025-10-15 10:57:24', '2025-10-16 11:19:34', 1),
(171, 83, 1, 2, NULL, NULL, '2025-10-15 10:58:07', '2025-10-16 11:19:41', 1),
(172, 84, 78, 5, NULL, NULL, '2025-10-16 10:15:35', '2025-10-16 11:19:49', 1),
(173, 85, 12, 1, NULL, NULL, '2025-10-16 11:18:27', '2025-10-16 11:19:56', 1),
(174, 86, 78, 1, NULL, NULL, '2025-10-17 16:47:39', '2025-10-17 16:51:46', 1),
(175, 87, 31, 1, NULL, NULL, '2025-10-17 16:50:48', '2025-10-17 16:51:59', 1),
(176, 87, 32, 1, NULL, NULL, '2025-10-17 16:50:48', '2025-10-17 16:52:00', 1),
(177, 87, 30, 3, NULL, NULL, '2025-10-17 16:50:49', '2025-10-17 16:52:01', 1),
(178, 87, 23, 2, NULL, NULL, '2025-10-17 16:50:49', '2025-10-17 16:52:02', 1),
(179, 87, 19, 1, NULL, NULL, '2025-10-17 16:50:49', '2025-10-17 16:52:02', 1),
(180, 87, 20, 1, NULL, NULL, '2025-10-17 16:50:49', '2025-10-17 16:52:03', 1),
(181, 87, 10, 1, NULL, NULL, '2025-10-17 16:50:49', '2025-10-17 16:52:03', 1),
(182, 87, 4, 1, NULL, NULL, '2025-10-17 16:50:49', '2025-10-17 16:52:04', 1),
(183, 87, 5, 1, NULL, NULL, '2025-10-17 16:50:49', '2025-10-17 16:52:06', 1),
(184, 88, 2, 5, NULL, NULL, '2025-11-13 03:51:24', '2025-11-13 03:51:24', 0),
(185, 89, 3, 3, NULL, NULL, '2025-11-13 04:57:46', '2025-11-13 04:57:46', 0),
(186, 90, 1, 2, NULL, NULL, '2025-11-13 06:35:18', '2025-11-13 06:48:58', 1),
(187, 90, 10, 1, NULL, NULL, '2025-11-13 06:35:18', '2025-11-13 06:48:58', 1),
(188, 90, 8, 1, NULL, NULL, '2025-11-13 06:35:18', '2025-11-13 06:44:23', 1),
(189, 90, 13, 1, NULL, NULL, '2025-11-13 06:35:18', '2025-11-13 06:44:24', 1),
(190, 91, 71, 1, NULL, NULL, '2025-11-14 04:22:19', '2025-11-14 04:22:25', 1),
(191, 91, 3, 3, NULL, NULL, '2025-11-14 04:22:19', '2025-11-14 04:22:26', 1),
(192, 92, 2, 2, NULL, NULL, '2025-11-14 04:29:54', '2025-11-14 04:29:54', 0),
(193, 93, 2, 3, NULL, NULL, '2025-11-14 04:41:55', '2025-11-17 01:40:58', 0),
(194, 94, 29, 1, NULL, NULL, '2025-11-18 07:17:33', '2025-11-18 07:17:33', 0),
(195, 95, 2, 1, NULL, NULL, '2025-11-18 07:24:50', '2025-11-18 07:24:50', 0),
(196, 96, 2, 1, NULL, NULL, '2025-11-18 07:32:19', '2026-03-23 08:29:10', 1),
(197, 97, 13, 1, NULL, NULL, '2026-03-04 03:47:17', '2026-03-05 01:24:37', 1),
(198, 98, 3, 1, NULL, NULL, '2026-03-05 02:20:26', '2026-03-23 08:28:32', 1),
(199, 99, 3, 1, NULL, NULL, '2026-03-30 02:13:50', '2026-03-30 03:16:25', 1),
(200, 100, 3, 1, NULL, NULL, '2026-03-30 02:29:18', '2026-03-30 02:59:07', 1),
(201, 101, 3, 1, NULL, NULL, '2026-03-30 03:37:50', '2026-03-30 03:38:19', 0),
(202, 101, 1, 1, NULL, NULL, '2026-03-30 03:37:50', '2026-03-30 03:37:50', 0),
(203, 102, 3, 1, NULL, NULL, '2026-03-30 04:06:06', '2026-04-06 07:40:54', 1),
(204, 103, 1, 1, NULL, NULL, '2026-03-30 04:12:01', '2026-03-30 04:38:08', 1),
(205, 104, 3, 1, NULL, NULL, '2026-03-30 04:20:13', '2026-03-30 04:20:34', 1),
(206, 105, 8, 2, NULL, NULL, '2026-04-01 02:50:31', '2026-04-01 02:57:55', 1),
(207, 106, 2, 1, NULL, NULL, '2026-04-06 01:26:18', '2026-04-06 01:36:27', 1),
(208, 107, 1, 1, NULL, NULL, '2026-04-06 03:42:10', '2026-04-06 03:42:10', 0),
(209, 108, 2, 1, NULL, NULL, '2026-04-06 07:34:43', '2026-04-06 07:40:13', 1),
(210, 109, 1, 1, NULL, NULL, '2026-04-06 07:43:56', '2026-04-06 07:43:56', 0),
(211, 110, 1, 1, NULL, NULL, '2026-04-10 08:23:05', '2026-04-10 08:25:00', 1),
(212, 111, 1, 1, NULL, NULL, '2026-04-16 01:26:34', '2026-04-16 01:47:15', 1),
(213, 112, 3, 1, NULL, NULL, '2026-04-17 04:29:23', '2026-04-17 04:30:07', 1),
(214, 113, 2, 1, NULL, NULL, '2026-04-17 04:56:24', '2026-04-17 05:32:53', 1),
(215, 114, 2, 1, NULL, NULL, '2026-04-17 05:54:23', '2026-04-17 06:00:33', 1),
(216, 115, 2, 1, NULL, NULL, '2026-04-17 06:52:44', '2026-04-17 07:45:57', 1),
(217, 116, 3, 7, NULL, NULL, '2026-04-17 07:47:35', '2026-04-17 08:07:36', 1),
(218, 117, 3, 1, NULL, NULL, '2026-04-23 03:21:10', '2026-04-23 03:21:53', 1),
(219, 118, 3, 1, NULL, NULL, '2026-04-23 03:27:36', '2026-04-23 03:46:03', 1),
(220, 119, 3, 1, NULL, NULL, '2026-06-17 01:30:00', '2026-06-17 01:31:25', 1),
(221, 119, 2, 1, NULL, NULL, '2026-06-17 01:30:00', '2026-06-17 01:35:15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL COMMENT 'รหัสการจอง',
  `reservation_code` varchar(20) DEFAULT NULL COMMENT 'รหัสการจองห้อง',
  `user_id` int(11) NOT NULL COMMENT 'ผู้จอง',
  `room_id` int(11) NOT NULL COMMENT 'ห้องที่จอง',
  `reservation_date` date NOT NULL COMMENT 'วันที่จอง',
  `reservation_dateend` date DEFAULT NULL,
  `start_time` time NOT NULL COMMENT 'เวลาเริ่ม',
  `end_time` time NOT NULL COMMENT 'เวลาสิ้นสุด',
  `topic` varchar(255) NOT NULL COMMENT 'หัวข้อการประชุม',
  `objective` text DEFAULT NULL COMMENT 'วัตถุประสงค์',
  `details` text DEFAULT NULL COMMENT 'รายละเอียดเพิ่มเติม',
  `participant_count` int(11) DEFAULT 1 COMMENT 'จำนวนผู้เข้าร่วม',
  `requester_name` varchar(200) DEFAULT NULL COMMENT 'ชื่อผู้ร้องขอ (เรียน)',
  `attached_file` text DEFAULT NULL COMMENT 'ชื่อไฟล์แนบ (ถ้ามี)',
  `status` enum('pending','acknowledge','rejected','cancelled') DEFAULT 'pending' COMMENT 'สถานะ',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#dc2626',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'วันที่จอง',
  `updated_at` datetime DEFAULT current_timestamp(),
  `break_morning` tinyint(4) DEFAULT 0 COMMENT 'เบรคเช้า',
  `lunch` tinyint(4) DEFAULT 0 COMMENT 'อาหารกลางวัน',
  `dinner` tinyint(4) DEFAULT 0,
  `break_afternoon` tinyint(4) DEFAULT 0 COMMENT 'เบรคบ่าย',
  `break_morning_detail` text DEFAULT NULL COMMENT 'รายละเอียดเบรคเช้า',
  `lunch_detail` text DEFAULT NULL COMMENT 'รายละเอียดอาหารกลางวัน',
  `dinner_detail` text DEFAULT NULL,
  `break_afternoon_detail` text DEFAULT NULL COMMENT 'รายละเอียดเบรคบ่าย',
  `budget_file` text DEFAULT NULL COMMENT 'แนบไฟล์งบประมาณ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `reservation_code`, `user_id`, `room_id`, `reservation_date`, `reservation_dateend`, `start_time`, `end_time`, `topic`, `objective`, `details`, `participant_count`, `requester_name`, `attached_file`, `status`, `approved_by`, `approved_at`, `color`, `created_at`, `updated_at`, `break_morning`, `lunch`, `dinner`, `break_afternoon`, `break_morning_detail`, `lunch_detail`, `dinner_detail`, `break_afternoon_detail`, `budget_file`) VALUES
(56, 'RES-250903', 14, 15, '2025-09-12', '2025-09-12', '09:00:00', '18:00:00', 'ทีมฝ่ายขายในประเทศมีจัดประชุมภายในฝ่าย', 'ฝ่ายขายในประเทศมีจัดประชุมภายในฝ่าย', NULL, 40, NULL, NULL, 'acknowledge', NULL, NULL, '#dc2626', '2025-09-11 11:25:28', '2025-09-11 11:28:39', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),
(57, 'RES-250904', 14, 15, '2025-09-25', '2025-09-25', '08:00:00', '18:00:00', 'สัมมนาประจำเดือนกันยายน หัวข้อ ความรู้พื้นฐาน การเลือกใช้ การทดสอบ และการประยุกต์ใช้ SPD ในระบบต่าง ๆ  ตามมาตรฐาน IEC 61643', 'สัมมนาประจำเดือนกันยายน', NULL, 100, NULL, '[\"\\/HAMS\\/reservations\\/attachments\\/68c7c9e03d141_20250912155055.pdf\"]', 'acknowledge', NULL, NULL, '#dc2626', '2025-09-15 15:10:08', '2025-09-15 15:11:04', 0, 0, 0, 0, NULL, NULL, NULL, NULL, '[\"\\/HAMS\\/reservations\\/budget_files\\/68c7c9e03d4eb_20250912155055.pdf\"]'),
(58, 'RES-250905', 5, 5, '2025-09-26', '2025-09-26', '13:30:00', '14:30:00', 'ประชุมเรื่องแคล้มยึดสำหรับSolar', 'เพื่อสรุป', '-', 8, NULL, NULL, 'rejected', 664, '2026-04-16 21:06:59', '#dc2626', '2025-09-19 17:32:37', '2026-04-17 04:06:59', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),
(61, 'RES-251001', 151, 1, '2025-10-06', '2025-10-06', '08:00:00', '18:00:00', 'Audit', 'Audit', NULL, 5, NULL, NULL, 'rejected', 664, '2026-04-16 21:06:54', '#dc2626', '2025-10-02 16:31:32', '2026-04-17 04:06:54', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),
(62, 'RES-251002', 151, 1, '2025-10-07', '2025-10-07', '08:00:00', '18:00:00', 'Audit', 'Audit', NULL, 5, NULL, NULL, 'rejected', 664, '2026-04-16 21:06:47', '#dc2626', '2025-10-02 16:32:02', '2026-04-17 04:06:47', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),
(63, 'RES-251003', 151, 1, '2025-10-08', '2025-10-08', '08:00:00', '18:00:00', 'Audit', 'Audit', NULL, 5, NULL, NULL, 'rejected', 664, '2026-04-16 21:06:41', '#dc2626', '2025-10-02 16:32:25', '2026-04-17 04:06:41', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),
(64, 'RES-251004', 151, 1, '2025-10-09', '2025-10-09', '08:00:00', '18:00:00', 'Audit', 'Audit', NULL, 5, NULL, NULL, 'rejected', 664, '2026-04-16 21:06:25', '#dc2626', '2025-10-02 16:32:47', '2026-04-17 04:06:25', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),
(65, 'RES-251005', 151, 1, '2025-10-10', '2025-10-10', '08:00:00', '18:00:00', 'Audit', 'Audit', NULL, 5, NULL, NULL, 'rejected', 664, '2026-04-16 21:06:35', '#dc2626', '2025-10-02 16:33:18', '2026-04-17 04:06:35', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),
(68, 'RES-69A7EBDA3297F', 306, 1, '2026-03-04', '2026-03-04', '08:30:00', '12:00:00', 'test ระบบ2', 'ะะกก', 'กั', 7, 'ผู้จัดการ', 'AF_1772612570_69a7ebda31c9e.docx', 'cancelled', NULL, NULL, '#dc2626', '2026-03-04 08:22:50', '2026-03-05 01:30:40', 1, 1, 0, 1, NULL, NULL, NULL, NULL, 'BF_1772612570_69a7ebda32562.docx'),
(70, 'RES-69A7FA2A5F08E', 322, 15, '2026-03-04', '2026-03-04', '09:00:00', '22:00:00', 'ห', 'ด', 'ด', 1, 'ห', NULL, 'cancelled', NULL, NULL, '#dc2626', '2026-03-04 09:23:54', '2026-03-05 01:32:08', 1, 1, 0, 1, NULL, NULL, NULL, NULL, 'BF_1772616234_69a7fa2a5e9a2.docx'),
(74, 'RES-69A8DE43DF4C1', 322, 1, '2026-03-05', '2026-03-05', '08:30:00', '10:00:00', 'test', 'test', 'test', 1, 'test', NULL, 'cancelled', NULL, NULL, '#dc2626', '2026-03-05 01:37:07', '2026-03-05 01:40:51', 1, 1, 0, 1, NULL, NULL, NULL, NULL, 'BF_1772674627_69a8de43decd3.docx'),
(75, 'RES-69A8DF512E96B', 322, 2, '2026-03-05', '2026-03-05', '13:30:00', '17:00:00', 'test', 'test', 'test', 1, 'test', NULL, 'cancelled', NULL, NULL, '#16a34a', '2026-03-05 01:41:37', '2026-03-23 03:56:25', 0, 1, 0, 1, NULL, NULL, NULL, NULL, 'BF_1772674897_69a8df512e440.docx'),
(76, 'RES-69A8DFB85453F', 322, 8, '2026-03-05', '2026-03-05', '13:00:00', '17:00:00', 'test', 'test', 'test', 1, 'test', NULL, 'acknowledge', 664, '2026-04-09 21:28:35', '#2563eb', '2026-03-05 01:43:20', '2026-04-10 04:28:35', 1, 1, 0, 1, NULL, NULL, NULL, NULL, 'BF_1772675000_69a8dfb854197.docx'),
(77, 'RES-69A8DFD8975B0', 322, 1, '2026-03-05', '2026-03-05', '13:00:00', '17:00:00', 'test', 'test', 'test', 1, 'test', NULL, 'rejected', 664, '2026-04-16 21:06:30', '#7c3aed', '2026-03-05 01:43:52', '2026-04-17 04:06:30', 1, 1, 0, 1, NULL, NULL, NULL, NULL, 'BF_1772675032_69a8dfd897196.docx'),
(78, 'RES-69A8E0234B51C', 322, 1, '2026-03-05', '2026-03-05', '08:30:00', '12:00:00', 'test', 'test', 'test', 1, 'test', NULL, 'cancelled', NULL, NULL, '#db2777', '2026-03-05 01:45:07', '2026-03-05 01:45:18', 1, 1, 0, 1, NULL, NULL, NULL, NULL, 'BF_1772675107_69a8e0234b1b0.docx'),
(79, 'RES-69BCDAF7774BB', 322, 1, '2026-03-20', '2026-03-23', '10:30:00', '15:00:00', 'test ระบบ2', 'test ระบบ2', 'test ระบบ2', 7, 'test ระบบ2', 'AF_1773984503_69bcdaf776862.pdf', 'acknowledge', 664, '2026-04-08 03:24:41', '#475569', '2026-03-20 05:28:23', '2026-04-08 10:24:41', 1, 1, 0, 1, NULL, NULL, NULL, NULL, 'BF_1773984503_69bcdaf776ed8.pdf'),
(80, 'RES-69C0B83FEDC78', 322, 10, '2026-03-24', '2026-03-25', '08:30:00', '17:00:00', 'test ระบบ3', 'test ระบบ3', 'test ระบบ3', 14, 'test ระบบ3', 'AF_1774237759_69c0b83fed301.pdf', 'acknowledge', NULL, NULL, '#db2777', '2026-03-23 03:49:19', '2026-03-23 08:21:39', 1, 1, 0, 1, NULL, NULL, NULL, NULL, 'BF_1774237759_69c0b83fed934.pdf'),
(81, 'RES-69CCA22F5D154', 306, 1, '2026-04-01', '2026-04-01', '08:30:00', '12:00:00', 'test', 'ไ', 'ะ', 1, 'test', 'AF_1775018543_69cca22f5821c.pdf', 'acknowledge', 664, '2026-04-07 20:18:51', '#dc2626', '2026-04-01 04:42:23', '2026-04-08 03:18:51', 1, 0, 0, 0, NULL, NULL, NULL, NULL, 'BF_1775018543_69cca22f586e1.pdf'),
(82, 'RES-69CDE1B9ABB99', 306, 1, '2026-04-02', '2026-04-02', '08:30:00', '17:00:00', 'test', 'test', 'test', 1, 'test', 'AF_1775100345_69cde1b9ab36d.pdf', 'acknowledge', NULL, NULL, '#ea580c', '2026-04-02 03:25:45', '2026-04-02 03:27:23', 1, 0, 1, 0, NULL, NULL, NULL, NULL, 'BF_1775100345_69cde1b9ab82b.pdf'),
(83, 'RES-69D367AAA1519', 322, 1, '2026-04-07', '2026-04-07', '08:30:00', '12:00:00', 'test', 'test', 'test', 7, 'test', 'AF_1775462314_69d367aa9d76d.png', 'acknowledge', NULL, NULL, '#dc2626', '2026-04-06 07:58:34', '2026-04-06 08:01:08', 1, 0, 1, 0, NULL, NULL, NULL, NULL, 'BF_1775462314_69d367aa9dc77.pdf'),
(84, 'RES-69D8B58976A8C', 664, 1, '2026-04-10', '2026-04-10', '08:30:00', '17:00:00', 'testtest', 'testtest', 'testtest', 6, 'testtest', 'AF_1775809929_69d8b58976180.pdf', 'acknowledge', 664, '2026-04-15 19:25:37', '#dc2626', '2026-04-10 08:32:09', '2026-04-16 02:25:37', 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'BF_1775809929_69d8b58976701.pdf'),
(85, 'RES-69E1AF1924E31', 664, 1, '2026-04-17', '2026-04-17', '08:30:00', '17:00:00', 'test', 'test', 'test', 1, 'test', 'AF_1776398105_69e1af1923f3b.pdf', 'pending', NULL, NULL, '#dc2626', '2026-04-17 03:55:05', '2026-04-17 03:55:05', 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'BF_1776398105_69e1af1924789.pdf'),
(86, 'RES-69E6D52CDA258', 669, 1, '2026-04-21', '2026-04-22', '08:30:00', '17:00:00', 'test', 'test', 'test', 1, 'test', 'AF_1776735532_69e6d52cd7c41.pdf', 'pending', NULL, NULL, '#dc2626', '2026-04-21 01:38:52', '2026-04-21 01:38:52', 1, 0, 0, 0, NULL, NULL, NULL, NULL, 'BF_1776735532_69e6d52cd81bf.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `reservation_meal_items`
--

CREATE TABLE `reservation_meal_items` (
  `reservation_meal_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL COMMENT 'อ้างอิงการจอง',
  `meal_type` enum('break_morning','lunch','break_afternoon','dinner') NOT NULL COMMENT 'ประเภทมื้ออาหาร',
  `item_name` varchar(255) NOT NULL COMMENT 'ชื่ออาหาร/เครื่องดื่ม',
  `quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'จำนวนที่ต้องการ',
  `note` text DEFAULT NULL COMMENT 'หมายเหตุเพิ่มเติม (ถ้ามี)',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation_meal_items`
--

INSERT INTO `reservation_meal_items` (`reservation_meal_id`, `reservation_id`, `meal_type`, `item_name`, `quantity`, `note`, `created_at`, `updated_at`) VALUES
(20, 57, 'break_morning', 'ขนมเบรก', 100, NULL, '2025-09-15 15:10:08', '2025-09-15 15:10:08'),
(21, 57, 'break_afternoon', 'ขนมเบรก', 100, NULL, '2025-09-15 15:10:08', '2025-09-15 15:10:08'),
(22, 57, 'lunch', 'ข้าวกล่อง', 100, NULL, '2025-09-15 15:10:08', '2025-09-15 15:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `residence`
--

CREATE TABLE `residence` (
  `residence_id` int(11) NOT NULL COMMENT 'รหัสบ้านพัก',
  `name` varchar(100) NOT NULL COMMENT 'ชื่อบ้านพัก เช่น บางใหญ่ หรือ ไทรใหญ่',
  `address` text DEFAULT NULL COMMENT 'ที่อยู่โดยละเอียดของบ้านพัก',
  `blueprint_image` varchar(255) DEFAULT NULL COMMENT 'รูปแผนผังรายละเอียดของอาคาร',
  `cover_image` varchar(255) DEFAULT NULL COMMENT 'รูปปกอาคาร',
  `total_floors` int(11) NOT NULL COMMENT 'จำนวนชั้นทั้งหมดของบ้านพัก',
  `total_rooms` int(11) NOT NULL COMMENT 'จำนวนห้องทั้งหมดในบ้านพัก',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'วันที่บันทึกข้อมูล',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_createdid` int(11) DEFAULT NULL COMMENT 'คนเพิ่มข้อมูล',
  `user_updateid` int(11) DEFAULT NULL COMMENT 'คนแก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางเก็บข้อมูลบ้านพักภายในบริษัท' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `residence`
--

INSERT INTO `residence` (`residence_id`, `name`, `address`, `blueprint_image`, `cover_image`, `total_floors`, `total_rooms`, `created_at`, `updated_at`, `user_createdid`, `user_updateid`) VALUES
(1, 'บางเลน', NULL, 'uploads/housing_residences/1782468387_blueprint_6a3e4f238da9b.jpg', 'uploads/housing_residences/1782697542_cover_6a41ce46bb68d.png', 2, 12, '2025-06-04 14:47:54', '2026-06-29 01:45:42', 0, 652),
(2, 'ไทรใหญ่', NULL, 'uploads/housing_residences/1782468522_blueprint_6a3e4faab581a.jpg', 'uploads/housing_residences/1782701011_cover_6a41dbd3e5861.png', 4, 24, '2025-06-04 14:49:32', '2026-06-29 02:43:31', 0, 652);

-- --------------------------------------------------------

--
-- Table structure for table `residence_agreements`
--

CREATE TABLE `residence_agreements` (
  `agreement_id` int(11) NOT NULL,
  `agreement_code` varchar(100) DEFAULT NULL COMMENT 'เลขใบข้อตกลงการเข้าพักอาศัยบ้านพักพนักงาน',
  `user_id` int(11) DEFAULT NULL COMMENT 'รหัสผู้ใช้งาน',
  `agreement_date` date DEFAULT NULL COMMENT 'วันที่ทำข้อตกลง',
  `title` varchar(10) DEFAULT NULL COMMENT 'คำนำหน้าชื่อ เช่น นาย นาง นางสาว',
  `full_name` varchar(200) DEFAULT NULL COMMENT 'ชื่อ-นามสกุลพนักงาน',
  `position` varchar(100) DEFAULT NULL COMMENT 'ตำแหน่งงานของพนักงาน',
  `department` varchar(100) DEFAULT NULL COMMENT 'แผนก',
  `section` varchar(100) DEFAULT NULL COMMENT 'ฝ่าย',
  `residence_address` varchar(255) DEFAULT NULL COMMENT 'บ้านพักที่ได้รับอนุญาตให้พักอาศัย (เลขที่บ้าน)',
  `residence_floor` varchar(50) DEFAULT NULL COMMENT 'ชั้นของบ้านพักที่พักอยู่',
  `number_of_residents` int(11) DEFAULT NULL COMMENT 'จำนวนผู้อาศัยรวมทั้งหมดในบ้านพัก',
  `send_status` int(11) NOT NULL DEFAULT 0 COMMENT 'สถานะ\r\n0 = รอผู้บังคับบัญชาอนุมัติ\r\n1 = รอผู้จัดการแผนกจัดการและบํารุงอาคารอนุมัติ\r\n2 = รอกรรมการบ้านพักตรวจสอบ\r\n3 = ดำเนินการเสร็จสิ้น\r\n4 = ส่งกลับแก้ไข\r\n5 = ยกเลิก',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้างข้อมูล',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วันที่แก้ไขล่าสุด',
  `commander_id` int(11) DEFAULT NULL COMMENT 'ผู้บังคับบัญชา',
  `commander_status` int(11) NOT NULL DEFAULT 0,
  `commander_comment` text DEFAULT NULL,
  `commander_date` date DEFAULT NULL,
  `managerhams_id` int(11) DEFAULT NULL COMMENT 'ผู้จัดการแผนกจัดการและบํารุงอาคาร',
  `managerhams_status` int(11) NOT NULL DEFAULT 0,
  `managerhams_comment` text DEFAULT NULL,
  `managerhams_date` date DEFAULT NULL,
  `Committee_id` int(11) DEFAULT NULL COMMENT 'กรรมการบ้านพก',
  `Committee_status` int(11) NOT NULL DEFAULT 0,
  `Committee_comment` text DEFAULT NULL,
  `Committee_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='ตารางเก็บข้อมูลแบบฟอร์มข้อตกลงการเข้าพักบ้านพักพนักงาน';

--
-- Dumping data for table `residence_agreements`
--

INSERT INTO `residence_agreements` (`agreement_id`, `agreement_code`, `user_id`, `agreement_date`, `title`, `full_name`, `position`, `department`, `section`, `residence_address`, `residence_floor`, `number_of_residents`, `send_status`, `created_at`, `updated_at`, `commander_id`, `commander_status`, `commander_comment`, `commander_date`, `managerhams_id`, `managerhams_status`, `managerhams_comment`, `managerhams_date`, `Committee_id`, `Committee_status`, `Committee_comment`, `Committee_date`) VALUES
(2, 'RA-250601', 1, '2025-06-20', 'นาย', 'คุณวานิสา อารมณ์ชื่น', 'Software Engineer', 'ICT', 'ICT', 'A101', '1', 2, 1, '2025-06-20 03:18:54', '2026-04-17 01:08:58', 669, 1, NULL, '2026-03-27', 669, 0, NULL, NULL, 669, 0, NULL, NULL),
(4, 'RA-260403', 306, '2026-04-02', 'นาย', 'กิตติพรรณ บุญช่วย', 'วิศวกรซอฟต์แวร์', 'ICT', 'ICT', 'บางใหญ่ ห้อง 103', '1', 1, 3, '2026-04-02 01:42:35', '2026-04-02 01:56:20', 306, 1, NULL, '2026-04-02', 306, 1, NULL, '2026-04-02', 306, 1, NULL, '2026-04-02'),
(5, 'RA-260405', 306, '2026-04-02', 'นาย', 'กิตติพรรณ บุญช่วย', 'วิศวกรซอฟต์แวร์', 'ICT', 'ICT', 'ไทรใหญ่ ห้อง 203', '2', 1, 3, '2026-04-02 02:47:57', '2026-04-02 02:48:26', 306, 1, NULL, '2026-04-02', 306, 1, NULL, '2026-04-02', 306, 1, NULL, '2026-04-02'),
(7, 'RA-260406', 664, '2026-04-16', 'นาย', 'กิตติพัฒน์ มานุช', 'Software Specialist', 'Information Communication Technology', 'ICT', 'บางใหญ่ ห้อง 206', '2', 1, 3, '2026-04-15 20:40:51', '2026-04-15 21:08:20', 664, 1, NULL, '2026-04-16', 664, 1, NULL, '2026-04-16', 664, 1, NULL, '2026-04-16'),
(8, 'RA-260408', 664, '2026-04-17', 'นาย', 'กิตติพัฒน์ มานุช', 'Software Specialist', 'Information Communication Technology', 'ICT', 'บางใหญ่ ห้อง 203', '2', 1, 3, '2026-04-16 19:40:32', '2026-04-16 20:16:20', 669, 1, NULL, '2026-04-17', 664, 1, NULL, '2026-04-17', 664, 1, NULL, '2026-04-17');

-- --------------------------------------------------------

--
-- Table structure for table `residence_bill`
--

CREATE TABLE `residence_bill` (
  `bill_id` int(11) NOT NULL COMMENT 'รหัสบิล',
  `stay_id` int(11) NOT NULL COMMENT 'อ้างอิงรหัสการพัก',
  `billing_year` int(11) NOT NULL COMMENT 'ปีที่คิดค่าใช้จ่าย',
  `billing_month` tinyint(4) NOT NULL COMMENT 'เดือนที่คิดค่าใช้จ่าย',
  `room_rate` decimal(10,2) NOT NULL COMMENT 'ค่าห้องรายเดือน',
  `electricity_unit_start` int(11) DEFAULT 0 COMMENT 'หน่วยไฟฟ้าต้นเดือน',
  `electricity_unit_end` int(11) DEFAULT 0 COMMENT 'หน่วยไฟฟ้าปลายเดือน',
  `electricity_rate_per_unit` decimal(10,2) DEFAULT 5.00 COMMENT 'ค่าไฟต่อหน่วย',
  `electricity_total` decimal(10,2) DEFAULT 0.00 COMMENT 'รวมค่าไฟฟ้า (คำนวณภายนอก)',
  `other_fee` decimal(10,2) DEFAULT 0.00 COMMENT 'ค่าใช้จ่ายอื่น ๆ',
  `total_amount` decimal(10,2) DEFAULT 0.00 COMMENT 'รวมยอดทั้งหมด (คำนวณภายนอก)',
  `is_paid` tinyint(1) DEFAULT 0 COMMENT 'ชำระแล้วหรือไม่ (0=ยัง, 1=แล้ว)',
  `paid_at` datetime DEFAULT NULL COMMENT 'วันที่ชำระเงิน',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'วันที่สร้างบิล',
  `bill_file` varchar(255) DEFAULT NULL COMMENT 'ไฟล์บิล (เช่น PDF, รูป)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='บิลค่าใช้จ่ายห้องพักรายเดือน' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `residence_dependents`
--

CREATE TABLE `residence_dependents` (
  `dependents_id` bigint(20) NOT NULL COMMENT 'รหัสผู้พักร่วม',
  `request_id` bigint(20) DEFAULT NULL COMMENT 'เชื่อมโยงกับคำร้อง (residence_requests.id)',
  `full_name` varchar(100) DEFAULT NULL COMMENT 'ชื่อ-นามสกุลผู้พักร่วม',
  `relation` varchar(50) DEFAULT NULL COMMENT 'ความสัมพันธ์ เช่น บุตร ภรรยา',
  `age` int(11) DEFAULT NULL COMMENT 'อายุผู้พักร่วม',
  `related_detail` text DEFAULT NULL COMMENT 'ข้อมูลเกี่ยวข้องเพิ่มเติม',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='ผู้พักร่วมในบ้านพักพนักงาน';

--
-- Dumping data for table `residence_dependents`
--

INSERT INTO `residence_dependents` (`dependents_id`, `request_id`, `full_name`, `relation`, `age`, `related_detail`, `created_at`, `updated_at`) VALUES
(26, 22, 'public user', 'เพื่อน', 21, NULL, '2025-06-24 04:01:05', '2025-06-24 04:01:05'),
(27, 22, 'กิตติพรรณ บุญช่วย', 'เพื่อน', 22, NULL, '2025-06-24 04:01:05', '2025-06-24 04:01:05');

-- --------------------------------------------------------

--
-- Table structure for table `residence_leaves`
--

CREATE TABLE `residence_leaves` (
  `residence_leaves_id` int(11) NOT NULL,
  `residence_leaves_code` varchar(10) DEFAULT NULL COMMENT 'เลขที่ขอ',
  `user_id` int(11) DEFAULT NULL COMMENT 'รหัสคนขอ',
  `residence_room_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'รหัสห้องจากตาราง residence_room',
  `request_date` date NOT NULL COMMENT 'วันที่กรอกคำร้อง',
  `prefix` varchar(10) NOT NULL COMMENT 'คำนำหน้า',
  `first_name` varchar(100) NOT NULL COMMENT 'ชื่อ',
  `last_name` varchar(100) NOT NULL COMMENT 'นามสกุล',
  `position` varchar(100) NOT NULL COMMENT 'ตำแหน่ง',
  `department` varchar(100) NOT NULL COMMENT 'แผนก',
  `section` varchar(100) NOT NULL COMMENT 'ฝ่าย',
  `residence_type` varchar(255) DEFAULT NULL,
  `room_number` varchar(50) DEFAULT NULL,
  `floor` varchar(50) DEFAULT NULL,
  `move_out_date` date NOT NULL COMMENT 'วันที่ย้ายออก',
  `reason` text NOT NULL COMMENT 'เหตุผลที่ขอย้ายออก',
  `send_status` int(11) DEFAULT 0 COMMENT 'สถานะ\r\n0 = รอผู้บังคับบัญชาอนุมัติ\r\n1 = รอผู้จัดการแผนกจัดการและบํารุงอาคารอนุมัติ\r\n2 = รอกรรมการบ้านพักตรวจสอบ\r\n3 = ดำเนินการเสร็จสิ้น\r\n4 = ส่งกลับแก้ไข\r\n5 = ยกเลิก',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `managerhams_id` int(11) DEFAULT NULL COMMENT 'ผู้จัดการแผนกจัดการและบํารุงอาคาร',
  `managerhams_status` int(11) NOT NULL DEFAULT 0,
  `managerhams_comment` text DEFAULT NULL,
  `managerhams_date` date DEFAULT NULL,
  `Committee_id` int(11) DEFAULT NULL COMMENT 'กรรมการบ้านพก',
  `Committee_status` int(11) NOT NULL DEFAULT 0,
  `Committee_comment` text DEFAULT NULL,
  `Committee_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บข้อมูลคำร้องขอย้ายออกจากบ้านพักพนักงาน';

--
-- Dumping data for table `residence_leaves`
--

INSERT INTO `residence_leaves` (`residence_leaves_id`, `residence_leaves_code`, `user_id`, `residence_room_id`, `request_date`, `prefix`, `first_name`, `last_name`, `position`, `department`, `section`, `residence_type`, `room_number`, `floor`, `move_out_date`, `reason`, `send_status`, `created_at`, `updated_at`, `managerhams_id`, `managerhams_status`, `managerhams_comment`, `managerhams_date`, `Committee_id`, `Committee_status`, `Committee_comment`, `Committee_date`) VALUES
(2, 'RL-250602', 1, NULL, '2025-06-20', 'นาง', 'กิตติพรรณ', 'บุญช่วย', 'Software Engineer', 'ICT', 'ICT', 'kraiyai', 'A101', '1', '2025-06-20', 'testtt', 5, '2025-06-20 08:00:32', '2025-06-24 03:17:04', 1, 2, NULL, '2025-06-24', 1, 1, NULL, '2025-06-24'),
(3, 'RL-260403', 306, 22, '2026-04-02', 'นาย', 'กิตติพรรณ', 'บุญช่วย', 'วิศวกรซอฟต์แวร์', 'ICT', 'ICT', 'บางใหญ่', '206', '2', '2026-04-02', 'test', 3, '2026-04-01 23:28:46', '2026-04-02 07:48:16', 306, 1, NULL, '2026-04-02', 306, 1, NULL, '2026-04-02'),
(4, 'RL-260404', 306, 6, '2026-04-02', 'นาย', 'กิตติพรรณ', 'บุญช่วย', 'วิศวกรซอฟต์แวร์', 'ICT', 'ICT', 'บางใหญ่', '103', '1', '2026-04-02', 'test', 3, '2026-04-02 01:59:46', '2026-04-02 02:00:36', 306, 1, NULL, '2026-04-02', 306, 1, NULL, '2026-04-02'),
(6, 'RL-260405', 664, 22, '2026-04-16', 'นาย', 'กิตติพัฒน์', 'มานุช', 'Software Specialist', 'Information Communication Technology', 'ICT', 'บางใหญ่', '206', '2', '2026-04-16', 'ก', 3, '2026-04-15 23:42:35', '2026-04-15 23:43:10', 664, 1, NULL, '2026-04-16', 664, 1, NULL, '2026-04-16');

-- --------------------------------------------------------

--
-- Table structure for table `residence_repairs`
--

CREATE TABLE `residence_repairs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `repair_code` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `residence_room_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `technician_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `admin_comment` text DEFAULT NULL,
  `repair_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `technician_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`technician_images`)),
  `technician_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residence_repairs`
--

INSERT INTO `residence_repairs` (`id`, `repair_code`, `user_id`, `residence_room_id`, `title`, `description`, `images`, `technician_id`, `status`, `admin_comment`, `repair_date`, `completion_date`, `technician_images`, `technician_note`, `created_at`, `updated_at`) VALUES
(1, 'RP-260301', 306, 22, 'ไฟดับ', 'test', '[\"uploads\\/housing_repairs\\/1774944895_69cb827fd3637_Gemini_Generated_Image_j0p9bgj0p9bgj0p9.png\",\"uploads\\/housing_repairs\\/1774944895_69cb827fd3b3d_Gemini_Generated_Image_z4wg20z4wg20z4wg.png\"]', 322, 2, NULL, '2026-03-31', '2026-03-31', '[\"uploads\\/housing_repairs\\/1774947182_fin_69cb8b6e6dc64.png\",\"uploads\\/housing_repairs\\/1774947182_fin_69cb8b6e6e062.png\"]', 'test', '2026-03-31 01:14:55', '2026-03-31 01:53:02'),
(2, 'RP-260402', 306, 24, 'test', 'test', '[\"uploads\\/housing_repairs\\/1775123478_69ce3c16ab227_Gemini_Generated_Image_j0p9bgj0p9bgj0p9.png\",\"uploads\\/housing_repairs\\/1775123478_69ce3c16ab9a7_Gemini_Generated_Image_z4wg20z4wg20z4wg.png\",\"uploads\\/housing_repairs\\/1775123478_69ce3c16abd76_Gemini_Generated_Image_91r27s91r27s91r2 (1).png\"]', 306, 2, NULL, '2026-04-02', '2026-04-02', '[\"uploads\\/housing_repairs\\/1775123539_fin_69ce3c536770a.png\",\"uploads\\/housing_repairs\\/1775123539_fin_69ce3c5367b82.png\"]', 'หลอดไฟ', '2026-04-02 02:51:18', '2026-04-02 02:52:19'),
(3, 'RP-260403', 306, 24, 'น้ำ', 'น้ำ', '[\"uploads\\/housing_repairs\\/1775123580_69ce3c7c85999_Gemini_Generated_Image_j0p9bgj0p9bgj0p9.png\"]', 306, 2, NULL, '2026-04-02', '2026-04-02', '[\"uploads\\/housing_repairs\\/1775124261_fin_69ce3f25b6b12.png\",\"uploads\\/housing_repairs\\/1775124261_fin_69ce3f25b7733.png\"]', 'test', '2026-04-02 02:53:00', '2026-04-02 03:04:21'),
(4, 'RP-260404', 664, 22, 'test', 'test', '[\"uploads\\/housing_repairs\\/1776315236_69e06b64d45b8_OIP.jpg\"]', 652, 2, NULL, '2026-04-16', '2026-04-16', '[\"uploads\\/housing_repairs\\/1776315309_fin_69e06baddcd67.jpg\"]', 'test', '2026-04-15 21:53:56', '2026-04-15 21:55:09'),
(5, 'RP-260405', 664, 22, 'test', 'test', '[\"uploads\\/housing_repairs\\/1776315339_69e06bcbe1346_14c4c38a7722dc4f65c5069255a0a75d.jpg\"]', 652, 2, NULL, '2026-04-16', '2026-04-16', NULL, NULL, '2026-04-15 21:55:39', '2026-04-15 21:56:46');

-- --------------------------------------------------------

--
-- Table structure for table `residence_requests`
--

CREATE TABLE `residence_requests` (
  `id` int(11) NOT NULL,
  `requests_code` varchar(100) NOT NULL COMMENT 'รหัสการเข้าพัก',
  `request_date` date DEFAULT NULL COMMENT 'วันที่ยื่นคำร้อง',
  `site` varchar(255) DEFAULT NULL COMMENT 'สถานที่ปฏิบัติงาน',
  `title` varchar(10) DEFAULT NULL COMMENT 'คำนำหน้าชื่อ เช่น นาย นาง นางสาว',
  `first_name` varchar(100) DEFAULT NULL COMMENT 'ชื่อพนักงาน',
  `last_name` varchar(100) DEFAULT NULL COMMENT 'นามสกุลพนักงาน',
  `position` varchar(100) DEFAULT NULL COMMENT 'ตำแหน่งงาน',
  `department` varchar(100) DEFAULT NULL COMMENT 'แผนก',
  `section` varchar(100) DEFAULT NULL COMMENT 'ฝ่าย',
  `age_work` varchar(100) DEFAULT NULL COMMENT 'อายุงาน',
  `phone` varchar(20) DEFAULT NULL COMMENT 'เบอร์โทรศัพท์',
  `marital_status` enum('โสด','สมรส') DEFAULT NULL COMMENT 'สถานภาพสมรส',
  `address_original` varchar(255) DEFAULT NULL,
  `address_original_subdistrict` varchar(100) DEFAULT NULL COMMENT 'แขวง/ตำบล ที่อยู่เดิม',
  `address_original_district` varchar(100) DEFAULT NULL COMMENT 'เขต/อำเภอ ที่อยู่เดิม',
  `address_original_province` varchar(100) DEFAULT NULL COMMENT 'จังหวัด ที่อยู่เดิม',
  `address_current` varchar(255) DEFAULT NULL,
  `address_current_subdistrict` varchar(100) DEFAULT NULL COMMENT 'แขวง/ตำบล ที่อยู่ปัจจุบัน',
  `address_current_district` varchar(100) DEFAULT NULL COMMENT 'เขต/อำเภอ ที่อยู่ปัจจุบัน',
  `address_current_province` varchar(100) DEFAULT NULL COMMENT 'จังหวัด ที่อยู่ปัจจุบัน',
  `current_house_type` varchar(255) DEFAULT NULL,
  `spouse_name` varchar(100) DEFAULT NULL COMMENT 'ชื่อคู่สมรส',
  `spouse_occupation` varchar(100) DEFAULT NULL COMMENT 'อาชีพคู่สมรส',
  `spouse_phone` varchar(20) DEFAULT NULL COMMENT 'เบอร์โทรศัพท์คู่สมรส',
  `workplace_spouse` varchar(255) DEFAULT NULL COMMENT 'สถานที่ทำงานของคู่สมรส',
  `number_of_residents` int(11) DEFAULT NULL COMMENT 'จำนวนผู้พักร่วมในบ้านพัก',
  `residence_reason` text DEFAULT NULL,
  `requests_file` text DEFAULT NULL COMMENT 'เอกสารสำเนา',
  `send_status` int(11) NOT NULL DEFAULT 0 COMMENT 'สถานะ\r\n0 = รอผู้บังคับบัญชาอนุมัติ\r\n1 = รอผู้จัดการแผนกจัดการและบํารุงอาคารอนุมัติ\r\n2 = รอกรรมการบ้านพักตรวจสอบ\r\n3 = ดำเนินการเสร็จสิ้น\r\n4 = ส่งกลับแก้ไข\r\n5 = ยกเลิก',
  `user_id` int(11) NOT NULL COMMENT 'id ผู้จอง',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `commander_id` int(11) DEFAULT NULL COMMENT 'ผู้บังคับบัญชา',
  `commander_status` int(11) NOT NULL DEFAULT 0,
  `commander_comment` text DEFAULT NULL,
  `commander_date` date DEFAULT NULL,
  `managerhams_id` int(11) DEFAULT NULL COMMENT 'ผู้จัดการแผนกจัดการและบํารุงอาคาร',
  `managerhams_status` int(11) NOT NULL DEFAULT 0,
  `managerhams_comment` text DEFAULT NULL,
  `managerhams_date` date DEFAULT NULL,
  `Committee_id` int(11) DEFAULT NULL COMMENT 'กรรมการบ้านพก',
  `Committee_status` int(11) NOT NULL DEFAULT 0,
  `Committee_comment` text DEFAULT NULL,
  `Committee_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='คำร้องขอเข้าพักบ้านพักพนักงาน';

--
-- Dumping data for table `residence_requests`
--

INSERT INTO `residence_requests` (`id`, `requests_code`, `request_date`, `site`, `title`, `first_name`, `last_name`, `position`, `department`, `section`, `age_work`, `phone`, `marital_status`, `address_original`, `address_original_subdistrict`, `address_original_district`, `address_original_province`, `address_current`, `address_current_subdistrict`, `address_current_district`, `address_current_province`, `current_house_type`, `spouse_name`, `spouse_occupation`, `spouse_phone`, `workplace_spouse`, `number_of_residents`, `residence_reason`, `requests_file`, `send_status`, `user_id`, `created_at`, `updated_at`, `commander_id`, `commander_status`, `commander_comment`, `commander_date`, `managerhams_id`, `managerhams_status`, `managerhams_comment`, `managerhams_date`, `Committee_id`, `Committee_status`, `Committee_comment`, `Committee_date`) VALUES
(23, 'RR-260323', '2026-03-31', 'โรงงานบางใหญ่', 'นาย', 'กิตติพรรณ', 'บุญช่วย', 'วิศวกรซอฟต์แวร์', 'ICT', 'ICT', '0', '0968861758', 'โสด', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'บ้านเช่า', NULL, NULL, NULL, NULL, 1, 'test', '[\"1774924793_69cb33f94a962_hr_requests_20260226_083621.pdf\"]', 6, 306, '2026-03-30 19:39:53', '2026-03-31 00:50:14', 306, 1, NULL, '2026-03-31', 306, 1, NULL, '2026-03-31', NULL, 0, NULL, NULL),
(25, 'RR-260424', '2026-04-02', 'โรงงานบางใหญ่', 'นาย', 'กิตติพรรณ', 'บุญช่วย', 'วิศวกรซอฟต์แวร์', 'ICT', 'ICT', '1', '0968861758', 'โสด', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'บ้านเช่า', NULL, NULL, NULL, NULL, 1, 'test', '[\"1775112610_69ce11a2b2d12_requisition_26030008.pdf\"]', 6, 306, '2026-04-01 23:50:10', '2026-04-02 01:56:19', 306, 1, NULL, '2026-04-02', 306, 1, NULL, '2026-04-02', 306, 1, NULL, '2026-04-02'),
(26, 'RR-260426', '2026-04-02', 'โรงงานไทรใหญ่', 'นาย', 'กิตติพรรณ', 'บุญช่วย', 'วิศวกรซอฟต์แวร์', 'ICT', 'ICT', '1', '0968861758', 'โสด', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'บ้านเช่า', NULL, NULL, NULL, NULL, 1, 'test', '[\"1775120828_69ce31bc7612f_requisition_26030008.pdf\"]', 6, 306, '2026-04-02 02:07:08', '2026-04-02 02:48:26', 306, 1, NULL, '2026-04-02', 306, 1, NULL, '2026-04-02', 306, 1, NULL, '2026-04-02'),
(28, 'RR-260428', '2026-04-10', 'โรงงานบางใหญ่', 'นาย', 'กิตติพัฒน์', 'มานุช', 'Software Specialist', 'Information Communication Technology', 'ICT', '1', '0968861758', 'โสด', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'บ้านเช่า', NULL, NULL, NULL, NULL, 1, 'test1', '[\"1775812493_69d8bf8da8cc9_requisitions_report_20260410_054713.pdf\"]', 6, 664, '2026-04-10 02:14:53', '2026-04-15 21:08:20', 664, 1, NULL, '2026-04-10', 664, 1, NULL, '2026-04-10', 664, 1, NULL, '2026-04-10'),
(29, 'RR-260429', '2026-04-16', 'โรงงานบางใหญ่', 'นาย', 'กิตติพัฒน์', 'มานุช', 'Software Specialist', 'Information Communication Technology', 'ICT', '1', '0968861759', 'โสด', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'บ้านเช่า', NULL, NULL, NULL, NULL, 3, 'บ้านไกลที่ทำงาน', '[\"1776323812_69e08ce4d6f38_requisitions_report_20260416_014147.pdf\"]', 8, 664, '2026-04-16 00:16:52', '2026-04-16 01:17:19', 664, 1, NULL, '2026-04-16', 664, 2, 'ด', '2026-04-16', NULL, 0, NULL, NULL),
(30, 'RR-260430', '2026-04-16', 'โรงงานบางใหญ่', 'นาย', 'กิตติพัฒน์', 'มานุช', 'Software Specialist', 'Information Communication Technology', 'ICT', '1', '0968861759', 'โสด', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'test1', 'บ้านเช่า', NULL, NULL, NULL, NULL, 1, 'ไ', '[\"1776327465_69e09b2957a0e_requisitions_report_20260416_014147.pdf\"]', 6, 664, '2026-04-16 01:17:45', '2026-04-16 20:16:20', 669, 1, NULL, '2026-04-17', 664, 1, NULL, '2026-04-17', 664, 1, NULL, '2026-04-17');

-- --------------------------------------------------------

--
-- Table structure for table `residence_resident`
--

CREATE TABLE `residence_resident` (
  `residence_resident_id` int(11) NOT NULL COMMENT 'รหัสผู้อยู่อาศัย',
  `employee_code` varchar(20) NOT NULL COMMENT 'รหัสพนักงาน',
  `full_name` varchar(100) NOT NULL COMMENT 'ชื่อ-สกุลของพนักงาน',
  `phone` varchar(20) DEFAULT NULL COMMENT 'เบอร์โทรติดต่อ',
  `department` varchar(100) DEFAULT NULL COMMENT 'แผนกของพนักงาน',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'วันที่บันทึกข้อมูล',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ext_number` int(11) DEFAULT NULL COMMENT 'เบอร์ภายใน',
  `user_createdid` int(11) DEFAULT NULL COMMENT 'คนเพิ่มข้อมูล',
  `user_updateid` int(11) DEFAULT NULL COMMENT 'คนแก้ไขข้อมูล',
  `residence_stay_id` int(11) DEFAULT NULL COMMENT 'รหัสการเข้าพัก',
  `status` int(11) DEFAULT NULL COMMENT '0 เปิดใช้ 1ไม่เปิดใช้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางเก็บข้อมูลพนักงานที่พักอยู่ในบ้านพัก' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `residence_room`
--

CREATE TABLE `residence_room` (
  `residence_room_id` int(11) NOT NULL COMMENT 'รหัสห้องพัก',
  `residence_id` int(11) NOT NULL COMMENT 'รหัสบ้านพักที่ห้องนี้อยู่',
  `room_number` varchar(20) NOT NULL COMMENT 'หมายเลขห้อง เช่น 101, 202',
  `floor` int(11) NOT NULL COMMENT 'ชั้นของห้อง เช่น ชั้น 1 หรือ 2',
  `residence_room_status` int(11) DEFAULT 0 COMMENT 'สถานะห้อง เช่น 0 ว่าง /1 ไม่ว่าง /2 ซ่อม/ปรับปรุง 3/ยกเลิก',
  `note` text DEFAULT NULL COMMENT 'หมายเหตุเพิ่มเติมของห้องพัก',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'วันที่บันทึกข้อมูล',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_createdid` int(11) DEFAULT NULL COMMENT 'คนเพิ่มข้อมูล',
  `user_updateid` int(11) DEFAULT NULL COMMENT 'คนแก้ไขข้อมูล',
  `image` text DEFAULT NULL COMMENT 'รูปภาพ',
  `price` decimal(10,2) DEFAULT NULL COMMENT 'ราคาห้อง',
  `capacity` int(11) DEFAULT NULL COMMENT 'ความจุ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางเก็บข้อมูลห้องพักในแต่ละบ้านพัก' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `residence_room`
--

INSERT INTO `residence_room` (`residence_room_id`, `residence_id`, `room_number`, `floor`, `residence_room_status`, `note`, `created_at`, `updated_at`, `user_createdid`, `user_updateid`, `image`, `price`, `capacity`) VALUES
(4, 1, '106', 1, 0, 'test', '2025-06-04 16:07:52', '2026-06-26 09:01:02', 2, 652, 'images/residence_room/1749028072_68400ce80ff55.jpg', 2000.00, 4),
(5, 1, '105', 1, 1, 'test', '2025-06-04 16:38:49', '2026-06-26 09:01:02', 2, 652, 'images/residence_room/1749029929_684014298ebb5.jpg', 2000.00, 2),
(6, 1, '104', 1, 0, 'test', '2025-06-04 16:39:17', '2026-06-26 09:01:02', 2, 652, 'images/residence_room/1749029957_6840144552399.jpg', 2000.00, 2),
(7, 1, '103', 1, 0, 'test', '2025-06-04 16:39:29', '2026-06-26 09:01:02', 2, 652, 'images/residence_room/1749029969_684014518e88d.jpg', 2000.00, 2),
(8, 1, '102', 1, 1, 'test', '2025-06-04 16:40:32', '2026-06-26 09:01:02', 2, 652, 'images/residence_room/1749030032_684014909ec6c.jpg', 2000.00, 2),
(9, 1, '101', 1, 0, 'test', '2025-06-04 16:40:54', '2026-06-26 09:01:02', 2, 652, 'images/residence_room/1749030054_684014a6a49cf.jpg', 2000.00, 2),
(10, 1, '206', 2, 0, 'test', '2025-06-04 16:41:11', '2026-06-26 09:01:02', 2, 652, 'images/residence_room/1749030071_684014b785d4c.jpg', 2000.00, 2),
(12, 2, '201', 2, 0, 'test', '2025-06-04 17:02:53', '2026-06-26 10:08:42', 2, 652, 'images/residence_room/1749031373_684019cd8d24a.jpg', 2000.00, 2),
(13, 2, '301', 3, 0, 'test', '2025-06-04 17:03:10', '2026-06-26 10:08:42', 2, 652, 'images/residence_room/1749031390_684019deafb53.jpg', 2000.00, 2),
(18, 1, '205', 2, 0, 'ห้องพัก', '2025-09-17 10:11:57', '2026-06-26 09:01:02', 1, 652, NULL, 1.00, 2),
(19, 1, '204', 2, 1, 'ห้องพัก', '2025-09-17 10:12:13', '2026-06-26 09:01:02', 1, 652, NULL, 1.00, 2),
(20, 1, '203', 2, 0, 'ห้องพัก', '2025-09-17 10:12:19', '2026-06-26 09:01:02', 1, 652, NULL, 1.00, 2),
(21, 1, '202', 2, 0, 'ห้องพัก', '2025-09-17 10:12:24', '2026-06-26 09:01:02', 1, 652, NULL, 1.00, 2),
(22, 1, '201', 2, 0, 'ห้องพัก', '2025-09-17 10:12:30', '2026-06-26 09:01:02', 1, 652, NULL, 1.00, 2),
(23, 2, '202', 2, 0, 'ห้องพัก', '2025-09-17 10:16:26', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(24, 2, '203', 2, 1, 'ห้องพัก', '2025-09-17 10:16:47', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(25, 2, '204', 2, 0, 'ห้องพัก', '2025-09-17 10:17:01', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(26, 2, '205', 2, 0, 'ห้องพัก', '2025-09-17 10:17:07', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(27, 2, '206', 2, 0, 'ห้องพัก', '2025-09-17 10:17:15', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(28, 2, '207', 2, 0, 'ห้องพัก', '2025-09-17 10:17:34', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(29, 2, '208', 2, 0, 'ห้องพัก', '2025-09-17 10:17:40', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(30, 2, '302', 3, 0, 'ห้องพัก', '2025-09-17 10:17:57', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(31, 2, '303', 3, 0, 'ห้องพัก', '2025-09-17 10:18:03', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(32, 2, '304', 3, 0, 'ห้องพัก', '2025-09-17 10:18:09', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(33, 2, '305', 3, 0, 'ห้องพัก', '2025-09-17 10:18:33', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(34, 2, '306', 3, 0, 'ห้องพัก', '2025-09-17 10:18:40', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(35, 2, '307', 3, 0, 'ห้องพัก', '2025-09-17 10:18:48', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(36, 2, '308', 3, 0, 'ห้องพัก', '2025-09-17 10:18:53', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(37, 2, '401', 4, 0, 'ห้องพัก', '2025-09-17 10:19:11', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(38, 2, '402', 4, 0, 'ห้องพัก', '2025-09-17 10:19:18', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(39, 2, '403', 4, 0, 'ห้องพัก', '2025-09-17 10:19:25', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(40, 2, '404', 4, 0, 'ห้องพัก', '2025-09-17 10:19:32', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(41, 2, '405', 4, 0, 'ห้องพัก', '2025-09-17 10:19:37', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(42, 2, '406', 4, 0, 'ห้องพัก', '2025-09-17 10:19:45', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(43, 2, '407', 4, 0, 'ห้องพัก', '2025-09-17 10:19:54', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2),
(44, 2, '408', 4, 0, 'ห้องพัก', '2025-09-17 10:20:01', '2026-06-26 10:08:42', 1, 652, NULL, 1.00, 2);

-- --------------------------------------------------------

--
-- Table structure for table `residence_stay`
--

CREATE TABLE `residence_stay` (
  `residence_stay_id` bigint(20) UNSIGNED NOT NULL,
  `residence_room_id` int(11) NOT NULL COMMENT 'รหัสห้องที่เข้าพัก',
  `residence_resident_id` text NOT NULL COMMENT 'รหัสพนักงานที่เข้าพัก',
  `check_in` date NOT NULL COMMENT 'วันที่เข้าพัก',
  `check_out` date DEFAULT NULL COMMENT 'วันที่ย้ายออก (ถ้ายังไม่ออกให้เว้นว่าง)',
  `is_current` tinyint(1) DEFAULT 1 COMMENT 'ระบุว่าปัจจุบันยังพักอยู่หรือไม่',
  `tel_phone` varchar(20) DEFAULT NULL COMMENT 'เบอร์โทรศัพทฺ์',
  `note` text DEFAULT NULL COMMENT 'หมายเหตุเพิ่มเติม',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'วันที่บันทึกข้อมูล',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_createdid` int(11) DEFAULT NULL COMMENT 'คนเพิ่มข้อมูล',
  `user_updateid` int(11) DEFAULT NULL COMMENT 'คนแก้ไขข้อมูล',
  `status` int(11) DEFAULT 0 COMMENT 'สถานะ',
  `send_status` int(11) DEFAULT 0,
  `reason_leave` text DEFAULT NULL COMMENT 'เหตุผลในการย้ายออก',
  `residence_stay_date` datetime DEFAULT NULL COMMENT 'วันที่เพิ่ม'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางเก็บประวัติการเข้าพักของพนักงานแต่ละคนในห้องต่างๆ' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `residence_stay`
--

INSERT INTO `residence_stay` (`residence_stay_id`, `residence_room_id`, `residence_resident_id`, `check_in`, `check_out`, `is_current`, `tel_phone`, `note`, `created_at`, `updated_at`, `user_createdid`, `user_updateid`, `status`, `send_status`, `reason_leave`, `residence_stay_date`) VALUES
(11, 7, '\"[1,2]\"', '2025-06-13', '2025-06-13', 0, NULL, 'ttttttttttttttttt', '2025-06-13 11:10:59', '2025-06-13 11:32:32', 1, 1, 0, 0, NULL, '2025-06-13 11:10:59'),
(14, 5, '\"[1]\"', '2025-06-13', NULL, 1, '0638861802', 'testtt', '2025-06-13 11:44:52', '2025-06-16 10:19:39', 1, 1, 0, 0, NULL, '2025-06-13 11:44:52'),
(15, 8, '\"[1,2]\"', '2025-06-18', NULL, 1, '0638861802', 'tttttttttt', '2025-06-16 15:38:50', '2025-06-16 15:38:50', 1, NULL, 0, 0, NULL, '2025-06-16 15:38:50'),
(18, 6, '306', '2026-04-02', '2026-04-02', 0, NULL, NULL, '2026-04-02 08:42:09', '2026-04-02 09:00:36', 306, NULL, 0, 0, 'test', '2026-04-02 08:42:09'),
(19, 24, '306', '2026-04-02', NULL, 1, NULL, NULL, '2026-04-02 09:10:41', '2026-04-02 09:10:41', 306, NULL, 0, 0, NULL, '2026-04-02 09:10:41'),
(20, 23, '664', '2026-04-06', '2026-04-16', 0, NULL, NULL, '2026-04-06 08:53:29', '2026-04-16 03:35:31', 664, NULL, 0, 0, 'd', '2026-04-06 08:53:29'),
(21, 22, '664', '2026-04-16', '2026-04-16', 0, NULL, NULL, '2026-04-16 03:25:54', '2026-04-16 06:43:10', 664, NULL, 0, 0, 'ก', '2026-04-16 03:25:54'),
(22, 19, '664', '2026-04-17', NULL, 1, NULL, NULL, '2026-04-17 02:33:47', '2026-04-17 02:33:47', 664, NULL, 0, 0, NULL, '2026-04-17 02:33:47');

-- --------------------------------------------------------

--
-- Table structure for table `resident_guest_members`
--

CREATE TABLE `resident_guest_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `guest_request_id` int(10) UNSIGNED NOT NULL COMMENT 'อ้างอิงถึง resident_guest_requests.id',
  `full_name` varchar(255) NOT NULL COMMENT 'ชื่อ-นามสกุลของญาติ',
  `age` int(11) DEFAULT NULL COMMENT 'อายุ',
  `relation` varchar(100) DEFAULT NULL COMMENT 'เบอร์โทรศัพท์',
  `phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='รายชื่อญาติที่ขอเข้าพักในบ้านพักพนักงาน';

--
-- Dumping data for table `resident_guest_members`
--

INSERT INTO `resident_guest_members` (`id`, `guest_request_id`, `full_name`, `age`, `relation`, `phone`, `created_at`, `updated_at`) VALUES
(1, 1, 'public user', 22, 'เพื่อน', NULL, '2025-06-20 07:43:34', '2025-06-20 07:43:34'),
(2, 2, 'public user', 22, 'เพื่อน', NULL, '2025-06-20 07:43:58', '2025-06-20 07:43:58'),
(3, 4, 'สิริ', 1, 'ใครไม่รู้', '0999584', '2026-04-15 21:31:12', '2026-04-15 21:31:12');

-- --------------------------------------------------------

--
-- Table structure for table `resident_guest_requests`
--

CREATE TABLE `resident_guest_requests` (
  `resident_guest_id` int(11) NOT NULL,
  `resident_guest_code` varchar(100) DEFAULT NULL COMMENT 'เลขที่',
  `user_id` int(11) DEFAULT NULL COMMENT 'รหัสพนักงาน',
  `request_date` date NOT NULL COMMENT 'วันที่กรอกแบบฟอร์ม',
  `prefix` varchar(10) DEFAULT NULL COMMENT 'คำนำหน้า',
  `first_name` varchar(100) NOT NULL COMMENT 'ชื่อ',
  `last_name` varchar(100) NOT NULL COMMENT 'นามสกุล',
  `position` varchar(100) NOT NULL COMMENT 'ตำแหน่ง',
  `department` varchar(100) NOT NULL COMMENT 'แผนก',
  `section` varchar(100) NOT NULL COMMENT 'ฝ่าย',
  `residence_type` varchar(50) NOT NULL COMMENT 'ประเภทบ้านพัก: บางใหญ่ หรือ ไทรใหญ่',
  `room_number` varchar(20) DEFAULT NULL COMMENT 'หมายเลขห้อง',
  `relationship` varchar(100) DEFAULT NULL COMMENT 'ความสัมพันธ์กับบุคคลที่นำเข้าพัก',
  `start_date` date NOT NULL COMMENT 'วันที่เริ่มเข้าพัก',
  `start_time` time NOT NULL COMMENT 'เวลาที่เริ่ม',
  `end_date` date NOT NULL COMMENT 'วันที่สิ้นสุด',
  `end_time` time NOT NULL COMMENT 'เวลาที่สิ้นสุด',
  `total_days` int(11) NOT NULL COMMENT 'จำนวนวันทั้งหมด',
  `send_status` int(11) NOT NULL DEFAULT 0 COMMENT 'สถานะ\r\n0 = รอผู้บังคับบัญชาอนุมัติ\r\n1 = รอผู้จัดการแผนกจัดการและบํารุงอาคารอนุมัติ\r\n2 = รอกรรมการบ้านพักตรวจสอบ\r\n3 = ดำเนินการเสร็จสิ้น\r\n4 = ส่งกลับแก้ไข\r\n5 = ยกเลิก',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `commander_id` int(11) DEFAULT NULL COMMENT 'ผู้บังคับบัญชา',
  `commander_status` int(11) NOT NULL DEFAULT 0,
  `commander_comment` text DEFAULT NULL,
  `commander_date` date DEFAULT NULL,
  `managerhams_id` int(11) DEFAULT NULL COMMENT 'ผู้จัดการแผนกจัดการและบํารุงอาคาร',
  `managerhams_status` int(11) NOT NULL DEFAULT 0,
  `managerhams_comment` text DEFAULT NULL,
  `managerhams_date` date DEFAULT NULL,
  `Committee_id` int(11) DEFAULT NULL COMMENT 'กรรมการบ้านพก',
  `Committee_status` int(11) NOT NULL DEFAULT 0,
  `Committee_comment` text DEFAULT NULL,
  `Committee_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='แบบฟอร์มขออนุญาตนำญาติเข้าพักอาศัย';

--
-- Dumping data for table `resident_guest_requests`
--

INSERT INTO `resident_guest_requests` (`resident_guest_id`, `resident_guest_code`, `user_id`, `request_date`, `prefix`, `first_name`, `last_name`, `position`, `department`, `section`, `residence_type`, `room_number`, `relationship`, `start_date`, `start_time`, `end_date`, `end_time`, `total_days`, `send_status`, `created_at`, `updated_at`, `commander_id`, `commander_status`, `commander_comment`, `commander_date`, `managerhams_id`, `managerhams_status`, `managerhams_comment`, `managerhams_date`, `Committee_id`, `Committee_status`, `Committee_comment`, `Committee_date`) VALUES
(1, 'RQ-250601', 1, '2025-06-20', 'นาย', 'กิตติพรรณ', 'บุญช่วย', 'Software Engineer', 'HAMS', 'HAMS', 'kraiyai', 'A101', 'เพื่อน', '2025-06-20', '10:33:00', '2025-06-30', '05:33:00', 10, 4, '2025-06-20 07:43:34', '2026-04-06 02:11:11', 664, 2, NULL, '2026-04-06', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL),
(2, 'RQ-250602', 1, '2025-06-20', 'นาย', 'กิตติพรรณ', 'บุญช่วย', 'Software Engineer', 'HAMS', 'HAMS', 'kraiyai', 'A101', 'เพื่อน', '2025-06-20', '10:33:00', '2025-06-30', '05:33:00', 10, 4, '2025-06-20 07:43:58', '2026-04-06 02:11:15', 306, 1, NULL, '2026-03-26', 664, 2, NULL, '2026-04-06', NULL, 0, NULL, NULL),
(3, 'RQ-260403', 664, '2026-04-16', 'นาย', 'กิตติพัฒน์', 'มานุช', 'Software Specialist', 'Information Communication Technology', 'ICT', 'บางใหญ่', '206', NULL, '2026-04-16', '08:00:00', '2026-04-23', '17:00:00', 8, 3, '2026-04-15 21:27:56', '2026-04-16 20:10:27', 664, 1, NULL, '2026-04-17', 664, 1, NULL, '2026-04-17', 664, 1, NULL, '2026-04-17'),
(4, 'RQ-260404', 664, '2026-04-16', 'นาย', 'กิตติพัฒน์', 'มานุช', 'Software Specialist', 'Information Communication Technology', 'ICT', 'บางใหญ่', '206', NULL, '2026-04-16', '08:00:00', '2026-04-23', '17:00:00', 8, 3, '2026-04-15 21:31:12', '2026-04-15 21:31:50', 664, 1, NULL, '2026-04-16', 664, 1, NULL, '2026-04-16', 664, 1, NULL, '2026-04-16');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL COMMENT 'รหัสห้องประชุม',
  `room_name` varchar(100) NOT NULL COMMENT 'ชื่อห้องประชุม',
  `room_type` varchar(100) NOT NULL COMMENT 'ประเภทห้อง',
  `capacity` int(11) NOT NULL COMMENT 'ความจุ (จำนวนคน)',
  `location` varchar(255) DEFAULT 'สำนักงานใหญ่' COMMENT 'สถานที่',
  `floor` varchar(50) DEFAULT NULL COMMENT 'ชั้น',
  `images` text DEFAULT NULL COMMENT 'รูปภาพห้อง',
  `description` text DEFAULT NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT 'สถานะห้อง',
  `has_projector` tinyint(1) DEFAULT 0 COMMENT 'มีโปรเจกเตอร์หรือไม่',
  `has_video_conf` tinyint(1) DEFAULT 0 COMMENT 'มีวิดีโอคอลหรือไม่',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'วันที่เพิ่มข้อมูลห้อง',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_name`, `room_type`, `capacity`, `location`, `floor`, `images`, `description`, `status`, `has_projector`, `has_video_conf`, `created_at`, `updated_at`) VALUES
(1, 'NEWTON', 'ห้องประชุมขนาดเล็ก', 7, 'สำนักงานใหญ่	', '2.1', '[\"1753156033_687f09c1d6a24.jpg\"]', 'testtttttt', 1, 0, 0, '2025-06-18 13:53:13', '2025-07-22 03:47:13'),
(2, 'FRANKLIN', 'ห้องประชุมขนาดเล็ก', 7, 'สำนักงานใหญ่', '2.2', '[\"1753156074_687f09ea7bf4a.jpg\"]', 'testtt', 1, 0, 0, '2025-06-11 15:39:21', '2025-07-22 03:47:54'),
(3, 'MAXWELL', 'ห้องประชุมขนาดกลาง', 4, 'สำนักงานใหญ่', '2.3', '[\"1753157157_687f0e25e132c.jpg\"]', 'testtt', 1, 0, 0, '2025-06-11 15:39:45', '2025-07-22 04:05:57'),
(5, 'EISTIEN', 'ห้องประชุมขนาดกลาง', 13, 'สำนักงานใหญ่', '2.4', '[\"1753154694_687f04863d56e.jpg\"]', 'มีเก้าอี้ 13 ตัว สามารถเพิ่มเติมและรับรองได้ประมาณ 20 คน', 1, 0, 0, '2025-06-24 15:14:09', '2025-07-22 03:24:54'),
(6, 'COULOMB', 'ห้องประชุมขนาดกลาง', 10, 'สำนักงานใหญ่', '2.5', '[\"1750752940_685a5eac19d09.jpg\"]', 'test', 1, 0, 0, '2025-06-24 15:15:40', '2025-06-24 08:15:40'),
(7, 'FARADAY', 'ห้องประชุมขนาดกลาง', 23, 'สำนักงานใหญ่', '3.1', '[\"1753155005_687f05bd5dec1.jpg\"]', 'test', 1, 0, 0, '2025-06-24 15:16:12', '2025-07-22 03:30:05'),
(8, 'VLOTA', 'ห้องประชุมขนาดกลาง', 11, 'สำนักงานใหญ่', '3.2', '[\"1753155236_687f06a483318.jpg\"]', 'มีเก้าอี้เสริมอีก 10 ตัว', 1, 0, 0, '2025-06-24 15:17:00', '2025-07-22 03:33:56'),
(9, 'AMPERE', 'ห้องประชุมขนาดกลาง', 9, 'สำนักงานใหญ่', '3.3', '[\"1753155339_687f070b900a6.jpg\"]', 'test', 1, 0, 0, '2025-06-24 15:17:39', '2025-07-22 03:35:39'),
(10, 'OHM', 'ห้องประชุมขนาดกลาง', 13, 'สำนักงานใหญ่', '3.4', '[\"1753155057_687f05f146154.jpg\"]', 'test', 1, 0, 0, '2025-06-24 15:18:52', '2025-07-22 03:30:57'),
(11, 'ARCHIMEDES', 'ห้องประชุมขนาดเล็ก', 6, 'สำนักงานใหญ่', '3.5', '[\"1753155415_687f07572c15f.jpg\"]', 'test', 1, 0, 0, '2025-06-24 15:19:21', '2025-07-22 03:36:55'),
(12, 'HERTZ', 'ห้องประชุมขนาดเล็ก', 8, 'สำนักงานใหญ่', '5.1', '[\"1750753245_685a5fdd75b6c.jpg\"]', 'test', 1, 0, 0, '2025-06-24 15:20:45', '2025-06-24 08:20:45'),
(13, 'GALILEO', 'ห้องหนังสือ', 8, 'สำนักงานใหญ่', '5.2', '[\"1750753304_685a60184979c.png\"]', 'testt', 1, 0, 0, '2025-06-24 15:21:44', '2025-06-24 08:21:44'),
(15, 'KUMWELL ACADAMY', 'ห้องประชุมขนาดใหญ่', 96, 'สำนักงานใหญ่', '6', '[\"1753156796_687f0cbc6235a.jpg\"]', 'สามารถเพิ่มเก้าอี้ได้ถึง 115 ตัว หรือสามารถเข้าร่วมได้ 115 คน', 0, 0, 0, '2025-06-24 15:24:52', '2026-04-15 19:24:49');

-- --------------------------------------------------------

--
-- Table structure for table `sects`
--

CREATE TABLE `sects` (
  `sect_id` int(11) NOT NULL COMMENT 'รหัสฝ่าย',
  `sect_name` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 0 COMMENT '0 เปิดใช้ 1ไม่เปิดใช้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('n0sVhpyynSb06b5O7LmkgaY1xuJexcu3wHwEl6sj', 652, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoid1VPMWxlYUxQVG1tQVR0aTZiM0N2clZ3ZHFNV0lSeEJQa2VsZ2F2MCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob3VzaW5nL2hvdXNlbGlzdCI7czo1OiJyb3V0ZSI7czoxNzoiaG91c2luZy5ob3VzZWxpc3QiO31zOjEyOiJtYXRoX2NhcHRjaGEiO2k6MTE7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NjUyO30=', 1782890529);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `ext_number` int(11) DEFAULT NULL,
  `user_role` int(11) DEFAULT 2 COMMENT 'ระดับของผู้ใช้\r\n0 = admin\r\n1 = ผู้อนุมัติ\r\n2 = ผู้จัดการ\r\n5 = ผู้ใช้งานทั่วไป',
  `status` int(11) DEFAULT 0 COMMENT '0 = ใช้งาน 1 = ปิดใช้งาน',
  `user_per` decimal(10,2) DEFAULT 100.00,
  `user_code` varchar(50) DEFAULT NULL COMMENT 'รหัสพนักงาน',
  `position` varchar(255) DEFAULT NULL,
  `api_id` int(11) DEFAULT NULL,
  `user_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `department_id`, `ext_number`, `user_role`, `status`, `user_per`, `user_code`, `position`, `api_id`, `user_status`) VALUES
(0, 'superadmin', 'superadmin@gmail.com', NULL, '$2y$12$qZtmT/vuHFH9oBUHoB1cTeoIqz4fxkf2147D/2YIHxcqnN1pRQn8y', NULL, '2025-07-01 04:01:50', '2025-08-19 03:01:16', 14, 1011, 0, 0, 0.00, '000000', NULL, NULL, 'admin'),
(1, 'กิตติพรรณ บุญช่วย', 'kittiphan.bu@kumwell.com', NULL, '$2y$12$1nsBwaQtiGS9J3q1dSaWQupNPMkabTrG5IzhHWEiHQa/UnbX.vx0u', NULL, '2025-06-12 18:06:09', '2025-08-11 03:35:50', 14, 3544, 0, 0, 1000.00, NULL, 'Sofware Enginer', NULL, 'user'),
(2, 'บุญเสริม กระจง', 'boonsurm.kr@kumwell.com', NULL, '$2y$12$kjGQXZe3lsmv7yAVQyCLGeXDFAPNzVGx0vMi48s8o2e6HzNs0FSLa', NULL, '2025-06-02 00:35:32', '2025-08-11 03:35:50', 14, 3555, 0, 0, 1000.00, NULL, 'Sofware Enginer Herdsaction', NULL, 'admin'),
(5, 'publicuser', 'publicuser@kumwell.com', NULL, '$2y$12$FUlN4TseNQcUN6rjnYt2duXVBz4mVnCZsoBqQsC4O42hupbpjqbQ.', NULL, '2025-05-20 02:56:07', '2025-08-11 03:35:50', 2, 1111, 2, 0, 1000.00, '000000', '', NULL, NULL),
(6, 'approvetest', 'approvetest@kumwell.com', NULL, '$2y$12$rQhgXcyNXJh0lARxsfGCUeaNkUknMQXtfwR/Mp4ZW148XuvJzeX2S', NULL, '2025-05-21 01:00:15', '2025-08-11 03:35:50', 7, 8765, 1, 0, 1000.00, '', '', NULL, NULL),
(7, 'preparation', 'preparation@kumwell.com', NULL, '$2y$12$R/d/vDYMevdp1PHYIMsCVOsOpeCFpnVfW2cKWeLZrSJX.ijhUwE52', NULL, '2025-05-25 18:44:19', '2025-08-11 03:35:50', 12, 2222, 4, 0, 1000.00, 'test11', '', NULL, NULL),
(11, 'ดนุพงษ์ แพงพันตอง', 'danupong.pa@kumwell.com', NULL, '$2y$12$ALyoM0K1qB4v6t9439PLeeu3e9GO8Hlm./d2aVqkCUNo19s4PMuEe', NULL, '2025-07-04 02:54:39', '2025-08-11 03:35:50', 14, 3599, 2, 0, 1000.00, NULL, 'IT', NULL, 'user'),
(12, 'นภาพรรณ สุดใจชื้น', 'napapan.so@kumwell.com', NULL, '$2y$12$V4RK9UpBOWMwUTYC7t.v4uV5YMqjXQlzG82SavkUo1O.IzX5E7Nau', NULL, '2025-07-14 10:27:32', '2025-08-11 03:35:50', 14, 3522, 2, 0, 1000.00, NULL, 'IT Admin', NULL, 'user'),
(13, 'วรานันทน์ เถาธรรมพิทักษ์', 'waranan.to@kumwell.com', NULL, '$2y$12$fLMrzxNIV9HewXoXk7o9O.I1WWFtMCw3hg.ruoxmnXNqWIs.7N0Ia', NULL, '2025-08-06 07:10:44', '2025-08-11 03:35:50', 12, 3321, 4, 0, 1000.00, NULL, '-', NULL, 'admin'),
(14, 'วานิสา อารมณ์ชื่น', 'Wanisa.Ar@kumwell.com', NULL, '$2y$12$T3i3y67I1YXqfK0wPi/rRetOekWQwyB8ZoujrVltEqxjFBt2o3Hni', NULL, '2025-08-06 07:38:19', '2025-08-11 03:35:50', 12, 3330, 4, 0, 1000.00, NULL, 'Administration Officer (GA)', NULL, 'admin'),
(15, 'ชลธิชา เปาชม', 'cholticha.po@kumwell.com', NULL, '$2y$12$8ZUl8/YH1eHjc1GOzE1XouwiKhyfpnV935s76zPFCvvJwKfJvnmp2', NULL, '2025-08-06 07:38:45', '2025-08-11 03:35:50', 12, 3338, 4, 0, 1000.00, NULL, 'Infrastructure Administrator', NULL, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `usertypes`
--

CREATE TABLE `usertypes` (
  `type_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `usertypes`
--

INSERT INTO `usertypes` (`type_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(0, 'admin', NULL, 0, '2025-06-06 02:35:50', '2025-06-06 02:35:50'),
(1, 'ผู้อนุมัติ', NULL, 0, NULL, '2025-05-26 01:36:13'),
(2, 'ผู้ใช้งานทั่วไป', NULL, 0, NULL, NULL),
(4, 'พนักงานแผนก HAMS', NULL, 0, NULL, NULL),
(5, 'commander', NULL, 0, '2025-05-28 02:35:46', '2025-05-28 02:35:46'),
(6, 'ผู้จัดการแผนก', NULL, 0, NULL, '2025-06-06 01:54:45'),
(7, 'ผู้บริหาร', NULL, 0, NULL, NULL),
(8, 'หัวหน้าฝ่าย', NULL, 0, NULL, NULL),
(10, 'แม่บ้าน', NULL, 0, '2025-06-05 09:34:40', '2025-06-05 09:34:40');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL COMMENT 'รหัสรถ',
  `name` varchar(100) NOT NULL COMMENT 'ชื่อหรือทะเบียนรถ',
  `model_name` varchar(255) NOT NULL COMMENT 'ชื่อรุ่น',
  `brand` varchar(50) DEFAULT NULL COMMENT 'ยี่ห้อรถ เช่น Toyota, Ford',
  `type` varchar(50) DEFAULT NULL COMMENT 'ประเภทรถ เช่น รถตู้, รถเก๋ง',
  `year` varchar(4) NOT NULL COMMENT 'ปีรถ',
  `seat` int(11) DEFAULT NULL COMMENT 'จำนวนที่นั่งของรถ',
  `filling_volume` varchar(20) NOT NULL COMMENT 'ปริมาณในการเติมน้ำมัน',
  `filling_type` varchar(50) NOT NULL COMMENT 'ประเภทน้ำมันที่เติม',
  `desciption` text DEFAULT NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `status` enum('available','maintenance','inactive') DEFAULT 'available' COMMENT 'สถานะรถ: ว่าง, เข้าซ่อม, หรือเลิกใช้',
  `status_vehicles` int(11) NOT NULL DEFAULT 0 COMMENT '0 = รถเจ้านาย\r\n1 = รถทั่วไป',
  `latest_mileage` int(11) DEFAULT NULL COMMENT 'เลขไมล์ล่าสุดที่บันทึก (กิโลเมตร)',
  `last_maintenance_mileage` int(11) NOT NULL DEFAULT 0,
  `next_maintenance_mileage` int(11) NOT NULL DEFAULT 10000,
  `last_maintenance_date` date DEFAULT NULL COMMENT 'วันที่ตรวจเช็คระยะล่าสุดของรถ',
  `images` varchar(255) DEFAULT NULL COMMENT 'รูปรถ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้างรายการจอง',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางเก็บข้อมูลรถส่วนกลางทั้งหมด' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `name`, `model_name`, `brand`, `type`, `year`, `seat`, `filling_volume`, `filling_type`, `desciption`, `status`, `status_vehicles`, `latest_mileage`, `last_maintenance_mileage`, `next_maintenance_mileage`, `last_maintenance_date`, `images`, `created_at`, `updated_at`) VALUES
(4, '5ขด 8200', 'DEEPAL S07', 'CHANGAN', 'เก๋ง', '2024', 5, '40', 'แก๊สซอฮอล์ 95', 'testtt', 'available', 0, 1111, 0, 10000, '2025-06-11', 'vehicle_1752746729_6878cae9cef8c.jpg', '2025-06-09 08:44:59', '2026-03-24 19:03:32'),
(5, 'ศก 333', 'ALPHARD 2.4 G', 'TOYOTA', 'รถตู้', '2012', 8, '40', 'เบนซิน 95', NULL, 'available', 0, 0, 0, 10000, '2025-06-10', 'vehicle_1752746180_6878c8c45d465.jpg', '2025-06-09 08:44:59', '2026-03-24 00:19:46'),
(6, '3ขษ 1209', 'HILUX REVO 2.4 MID PRERUNNER DOUBLE CAB', 'TOYOTA', 'กระบะ', '2024', 5, '40', 'ดีเซล B7', 'testtt', 'available', 1, 0, 0, 10000, '2025-07-28', 'vehicle_1752746265_6878c919c4caa.jpg', '2025-06-11 03:42:09', '2026-03-24 00:19:27'),
(9, '1กฒ 7670', 'PAJERO SPORT 2.5 GT AT', 'MITSUBISHI', 'เก๋ง', '2012', 4, '40', 'ดีเซล B7', NULL, 'available', 1, 0, 0, 10000, '2025-07-17', 'vehicle_1752746417_6878c9b1da475.jpg', '2025-07-17 09:38:16', '2026-03-24 00:19:19'),
(10, '3ขภ 3692', 'ZS EV X', 'MG', 'เก๋ง', '2022', 5, '40', 'EV', '', 'available', 0, 0, 0, 10000, NULL, 'vehicle_1752746996_6878cbf4535c9.jpg', '2025-07-17 10:09:56', '2026-03-24 00:10:07'),
(11, '8กฬ 2579', 'RANGER DOUBLE CAP2.0L XLT 4x2 HR 6 AT', 'FORD', 'กระบะ', '2019', 5, '40', 'ดีเซล B7', NULL, 'available', 1, 45500, 0, 10000, '2025-07-17', 'vehicle_1752747264_6878cd0021472.jpg', '2025-07-17 10:14:24', '2026-04-10 02:08:59'),
(12, 'ฮษ 8133', 'H-1 2.5 DELUXE', 'HUNDAI', 'เก๋ง', '2017', 7, '40', 'ดีเซล B7', NULL, 'available', 1, 154661, 0, 10000, '2026-03-25', '[\"1774327123_69c21553b0aef.jpg\"]', '2025-07-17 10:22:27', '2026-03-24 23:51:07'),
(13, 'บบ 4622', 'Hilux Vigo Champ Smart Cab Prerunner 3.0G', 'TOYOTA', 'กระบะ', '', 5, '40', 'ดีเซล B7', NULL, 'available', 1, 25681, 0, 10000, '2026-03-25', '[\"1774318276_69c1f2c48bb58.jpg\"]', '2025-07-17 10:27:44', '2026-03-24 19:49:20');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_bookings`
--

CREATE TABLE `vehicle_bookings` (
  `booking_id` int(11) NOT NULL,
  `booking_code` varchar(25) NOT NULL COMMENT 'เลขที่ใบจอง',
  `user_id` int(11) NOT NULL COMMENT 'รหัสผู้จอง (อ้างอิงตาราง users)',
  `vehicle_id` int(11) NOT NULL COMMENT 'รหัสรถที่จอง (อ้างอิงตาราง vehicles)',
  `bookings_date` date DEFAULT NULL COMMENT 'วันที่ทำการจอง',
  `booking_date` date DEFAULT NULL COMMENT 'วันที่เดินทาง',
  `start_time` datetime NOT NULL COMMENT 'เวลาออกเดินทาง',
  `end_time` datetime NOT NULL COMMENT 'เวลากลับโดยประมาณ',
  `destination` varchar(255) NOT NULL COMMENT 'จุดหมายปลายทางของการเดินทาง',
  `district` varchar(255) NOT NULL COMMENT 'อำเภอที่เดินทางไป',
  `province` varchar(100) NOT NULL COMMENT 'จังหวัดที่จะเดินทางไป',
  `requester_name` varchar(100) DEFAULT NULL COMMENT 'ชื่อเจ้าของงาน / ผู้มอบหมายภารกิจ',
  `passenger_count` int(11) DEFAULT NULL COMMENT 'จำนวนผู้โดยสาร',
  `purpose` text DEFAULT NULL COMMENT 'วัตถุประสงค์ของการใช้รถ',
  `mileage_before` int(11) DEFAULT NULL COMMENT 'เลขไมล์ก่อนเดินทาง',
  `mileage_after` int(11) DEFAULT NULL COMMENT 'เลขไมล์หลังเดินทาง',
  `note_returning` text DEFAULT NULL COMMENT 'หมายเหตุคืนรถ',
  `attachment` text DEFAULT NULL COMMENT 'แนบไฟล์',
  `attachment_going` text DEFAULT NULL COMMENT 'แนบรูปภาพรถตอนไป',
  `attachment_returning` text DEFAULT NULL COMMENT 'แนบรูปภาพรถตอนกลับ',
  `returned_at` datetime DEFAULT NULL COMMENT 'วันเวลาที่ส่งคืนรถจริง',
  `return_status` enum('ยังไม่ส่งคืน','ส่งคืนแล้ว','มีปัญหา') NOT NULL DEFAULT 'ยังไม่ส่งคืน' COMMENT 'สถานะการส่งคืนรถ',
  `status` enum('รออนุมัติ','อนุมัติแล้ว','ไม่อนุมัติ','ยกเลิก') DEFAULT 'รออนุมัติ' COMMENT 'สถานะของการจองรถ',
  `approved_by` int(11) DEFAULT NULL COMMENT 'ผู้อนุมัติการจอง (อ้างอิงตาราง users)',
  `approved_status` int(11) NOT NULL DEFAULT 0 COMMENT 'สถานะการรับทราบ',
  `approved_at` datetime DEFAULT NULL COMMENT 'วันที่และเวลาที่อนุมัติ',
  `driver_request` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='เก็บข้อมูลการจองรถส่วนกลางแต่ละครั้ง';

--
-- Dumping data for table `vehicle_bookings`
--

INSERT INTO `vehicle_bookings` (`booking_id`, `booking_code`, `user_id`, `vehicle_id`, `bookings_date`, `booking_date`, `start_time`, `end_time`, `destination`, `district`, `province`, `requester_name`, `passenger_count`, `purpose`, `mileage_before`, `mileage_after`, `note_returning`, `attachment`, `attachment_going`, `attachment_returning`, `returned_at`, `return_status`, `status`, `approved_by`, `approved_status`, `approved_at`, `driver_request`, `created_at`, `updated_at`) VALUES
(39, 'BK-250901', 14, 12, NULL, '2025-09-10', '2025-09-10 12:00:00', '2025-09-10 16:00:00', 'โรงงานไทรใหญ่', 'ไทรน้อย', 'นนทบุรี', 'ไปโรงงาน', 3, 'ไปประชุม ขอคนขับรถด้วยนะคะ', 2000, 111111, 'ทำความสะอาดเรียบร้อย', NULL, NULL, '[\"1757070933_68bac655f0891.jpg\"]', '2025-09-10 15:00:00', 'ส่งคืนแล้ว', 'อนุมัติแล้ว', 14, 1, '2025-09-05 18:12:53', 0, '2025-09-05 18:11:02', '2025-09-05 18:15:33'),
(40, 'BK-250902', 7, 11, NULL, '2025-09-11', '2025-09-11 08:00:00', '2025-09-11 15:00:00', 'เมืองทองธานี', 'ปากเกร็ด', 'นนทบุรี', 'ธนากร', 2, 'ส่งเครื่องมือทดสอบ', 11111, 168902, NULL, NULL, NULL, '[\"1757585638_68c2a0e6e3ff7.jpg\"]', '2025-09-11 12:00:00', 'ส่งคืนแล้ว', 'อนุมัติแล้ว', 7, 1, '2025-09-10 14:47:21', 0, '2025-09-10 14:45:52', '2025-09-11 17:13:58'),
(41, 'BK-250903', 306, 12, NULL, '2025-09-26', '2025-09-25 12:00:00', '2025-09-27 12:00:00', 'เมืองทองธานี', 'เมืองนนทบุรี', 'นนทบุรี', 'KTPPUz', 2, 'icttest', 111111, NULL, NULL, NULL, NULL, NULL, NULL, 'ยังไม่ส่งคืน', 'ยกเลิก', NULL, 0, NULL, 0, '2025-09-25 09:28:29', '2025-09-26 11:34:41'),
(42, 'BK-250904', 306, 11, NULL, '2025-09-29', '2025-09-28 11:00:00', '2025-09-30 12:00:00', 'สำนักงานย่อย', 'เมือง', 'เชียงใหม่', 'KTPPUz', 2, 'ทดสอบระบบ', 168902, NULL, NULL, NULL, NULL, NULL, NULL, 'ยังไม่ส่งคืน', 'ยกเลิก', NULL, 0, NULL, 0, '2025-09-26 11:14:42', '2025-09-26 11:34:38'),
(43, 'BKC-69A9243A5D0C3', 322, 4, '2026-03-05', '2026-03-05', '2026-03-05 09:25:00', '2026-03-05 13:30:00', 'test', 'test', 'test', 'test', 10, 'test', 10, 10, NULL, 'uploads/bookingcar_attachments/1772692538_Report171068.docx', 'uploads/bookingcar_attachments/1772692538_going_Screenshot_2026-02-25_132851.png', 'uploads/bookingcar_attachments/1772692538_returning_Screenshot_2026-02-25_132851.png', '2026-03-05 08:44:40', 'ส่งคืนแล้ว', 'อนุมัติแล้ว', 306, 1, '2026-03-05 07:03:18', 0, '2026-03-05 06:35:38', '2026-03-05 08:44:40'),
(44, 'BKC-69A9444239FDD', 306, 4, '2026-03-05', '2026-03-05', '2026-03-05 09:30:00', '2026-03-05 16:30:00', 'test', 'test', 'test', 'test', 1, 'test', 10, 10, NULL, 'uploads/bookingcar_attachments/1772700738_suggestion_test.jpg', 'uploads/bookingcar_attachments/1772700738_going_avatar.png', 'uploads/bookingcar_attachments/1772700738_returning_suggestion_test.jpg', NULL, 'ยังไม่ส่งคืน', 'ไม่อนุมัติ', 306, 2, '2026-03-23 01:07:51', 0, '2026-03-05 08:52:18', '2026-03-23 01:07:51'),
(45, 'BKC-69C0B94205569', 322, 4, '2026-03-23', '2026-03-23', '2026-03-23 08:30:00', '2026-03-24 17:00:00', 'test ระบบ3', 'test ระบบ3', 'test ระบบ3', 'test ระบบ3', 5, 'test ระบบ3', 111, 1111, 'ไม่ไปแล้ว', 'uploads/bookingcar_attachments/1774238018_hr_requests_20260226_083621.pdf', '[\"uploads\\/bookingcar_attachments\\/1774239390_going_69c0be9e92eec_Gemini_Generated_Image_z06tg6z06tg6z06t.png\"]', '[\"uploads\\/bookingcar_attachments\\/1774239390_returning_69c0be9e9378b_Gemini_Generated_Image_z06tg6z06tg6z06t.png\"]', '2026-03-23 04:16:30', 'ส่งคืนแล้ว', 'อนุมัติแล้ว', 306, 1, '2026-03-23 04:03:45', 0, '2026-03-23 03:53:38', '2026-03-23 04:16:30'),
(46, 'BKC-69C0C10F371AF', 322, 5, '2026-03-23', '2026-03-23', '2026-03-23 08:30:00', '2026-03-24 17:00:00', 'test ระบบ4', 'test ระบบ4', 'test ระบบ4', 'test ระบบ4', 5, 'test ระบบ4', NULL, NULL, NULL, 'uploads/bookingcar_attachments/1774240015_hr_requests_20260226_083621.pdf', NULL, NULL, NULL, 'ยังไม่ส่งคืน', 'ยกเลิก', NULL, 0, NULL, 0, '2026-03-23 04:26:55', '2026-03-23 04:27:15'),
(47, 'BKC-69C0C30B1EB60', 322, 10, '2026-03-23', '2026-03-23', '2026-03-23 08:30:00', '2026-03-25 17:00:00', 'test ระบบ4', 'test ระบบ4', 'test ระบบ4', 'test ระบบ4', 5, 'test ระบบ4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ยังไม่ส่งคืน', 'ยกเลิก', 306, 1, '2026-03-23 04:42:46', 0, '2026-03-23 04:35:23', '2026-03-23 06:00:08'),
(48, 'BKC-69C0C82D4BD70', 322, 4, '2026-03-23', '2026-03-26', '2026-03-26 08:30:00', '2026-03-27 17:00:00', '333', '333', '333', '333', 3, '333', NULL, NULL, NULL, 'uploads/bookingcar_attachments/1774241837_hr_requests_20260226_083621.pdf', NULL, NULL, '2026-03-25 02:03:32', 'ส่งคืนแล้ว', 'อนุมัติแล้ว', 306, 1, '2026-03-23 09:25:21', 0, '2026-03-23 04:57:17', '2026-03-25 02:03:32'),
(49, 'BKC-69C342BF38056', 306, 13, '2026-03-25', '2026-03-25', '2026-03-25 08:30:00', '2026-03-25 17:00:00', 'testtest', 'testtest', 'testtest', 'testtest', 5, 'testtest', 25641, 25681, 'ชนมานิดนึงนะ', 'uploads/bookingcar_attachments/1774404287_hr_requests_20260226_083621.pdf', '[\"uploads\\/bookingcar_attachments\\/1774406960_going_69c34d302f9c9_Gemini_Generated_Image_j0p9bgj0p9bgj0p9.png\",\"uploads\\/bookingcar_attachments\\/1774406960_going_69c34d3030449_Gemini_Generated_Image_z4wg20z4wg20z4wg.png\"]', '[\"uploads\\/bookingcar_attachments\\/1774406960_returning_69c34d3030d84_Gemini_Generated_Image_j0p9bgj0p9bgj0p9.png\",\"uploads\\/bookingcar_attachments\\/1774406960_returning_69c34d303165e_Gemini_Generated_Image_z4wg20z4wg20z4wg.png\"]', '2026-03-25 02:49:20', 'ส่งคืนแล้ว', 'อนุมัติแล้ว', NULL, 0, NULL, 0, '2026-03-25 02:04:47', '2026-03-25 07:10:56'),
(50, 'BKC-69CF26C995A0B', 306, 4, '2026-04-03', '2026-04-03', '2026-04-03 08:30:00', '2026-04-03 17:00:00', 'test1', 'เมืองเพชรบูรณ์', 'เพชรบูรณ์', 'test1', 5, 'test1', NULL, NULL, NULL, 'uploads/bookingcar_attachments/1775183561_requisition_26030008.pdf', NULL, NULL, '2026-04-08 10:37:48', 'ส่งคืนแล้ว', 'อนุมัติแล้ว', NULL, 0, NULL, 0, '2026-04-03 02:32:41', '2026-04-08 10:37:48'),
(51, 'BKC-69CF2913EC09F', 306, 4, '2026-04-03', '2026-04-04', '2026-04-04 08:30:00', '2026-04-04 17:00:00', 'test2', 'เมืองกระบี่', 'กระบี่', 'test2', 4, 'test2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ยังไม่ส่งคืน', 'ยกเลิก', NULL, 0, NULL, 0, '2026-04-03 02:42:27', '2026-04-03 03:12:25'),
(52, 'BKC-69CF328F0C1DE', 306, 4, '2026-04-03', '2026-04-04', '2026-04-04 08:30:00', '2026-04-04 17:00:00', 'test2', 'เมืองกระบี่', 'กระบี่', 'test2', 1, 'ก', NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-08 10:37:56', 'ส่งคืนแล้ว', 'อนุมัติแล้ว', 306, 1, '2026-04-03 04:11:31', 0, '2026-04-03 03:22:55', '2026-04-08 10:37:56'),
(57, 'BKC-6A334B0965029', 652, 4, '2026-06-18', '2026-06-18', '2026-06-18 08:30:00', '2026-06-19 17:00:00', 'test', 'เขาพนม', 'กระบี่', 'test', 1, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ยังไม่ส่งคืน', 'ไม่อนุมัติ', 652, 2, '2026-06-18 01:52:26', 0, '2026-06-18 01:34:01', '2026-06-18 01:52:26'),
(58, 'BKC-6A335086626F0', 652, 5, '2026-06-18', '2026-06-18', '2026-06-18 08:30:00', '2026-06-19 17:00:00', 'test', 'เขาพนม', 'กระบี่', 'test', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ยังไม่ส่งคืน', 'ยกเลิก', NULL, 0, NULL, 0, '2026-06-18 01:57:26', '2026-06-18 02:21:54'),
(59, 'BKC-6A3355D16DF3C', 652, 4, '2026-06-18', '2026-06-25', '2026-06-25 08:30:00', '2026-06-29 17:00:00', 'test', 'เมืองกระบี่', 'กระบี่', 'test', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ยังไม่ส่งคืน', 'ยกเลิก', NULL, 0, NULL, 0, '2026-06-18 02:20:01', '2026-06-18 02:20:22');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_inspections`
--

CREATE TABLE `vehicle_inspections` (
  `inspection_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL COMMENT 'รหัสรถ',
  `inspection_date` date NOT NULL COMMENT 'วันที่ตรวจเช็ค',
  `mileage` int(11) DEFAULT NULL COMMENT 'เลขไมล์ขณะตรวจเช็ค (กิโลเมตร)',
  `inspector_name` varchar(100) DEFAULT NULL COMMENT 'ชื่อผู้ตรวจเช็ค',
  `location` varchar(255) DEFAULT NULL COMMENT 'สถานที่ไปตรวจ',
  `district` varchar(255) DEFAULT NULL COMMENT 'อำเภอ',
  `province` varchar(255) DEFAULT NULL COMMENT 'จังหวัด',
  `description` text DEFAULT NULL COMMENT 'รายละเอียดการตรวจเช็ค',
  `next_mileage` int(11) DEFAULT NULL,
  `next_maintenance_date` date DEFAULT NULL COMMENT 'วันที่ควรเข้าตรวจครั้งถัดไป',
  `file_vehicle` text DEFAULT NULL COMMENT 'เอกสารรถ',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0 = ดำเนินการเสร็จสิ้น \r\n1 = รอดำเนินการ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่เพิ่มข้อมูล',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วันที่แก้ไขล่าสุด'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `vehicle_inspections`
--

INSERT INTO `vehicle_inspections` (`inspection_id`, `vehicle_id`, `inspection_date`, `mileage`, `inspector_name`, `location`, `district`, `province`, `description`, `next_mileage`, `next_maintenance_date`, `file_vehicle`, `status`, `created_at`, `updated_at`) VALUES
(8, 6, '2025-07-28', 20000, 'kittiphan1', 'สำนักงานใหญ่', NULL, NULL, 'test', 40000, NULL, '[\"1753413830_6882f8c63e2bc_\\u0e23\\u0e32\\u0e22\\u0e07\\u0e32\\u0e19\\u0e2a\\u0e16\\u0e34\\u0e15\\u0e34\\u0e01\\u0e32\\u0e23\\u0e08\\u0e2d\\u0e07\\u0e2b\\u0e49\\u0e2d\\u0e07\\u0e1b\\u0e23\\u0e30\\u0e0a\\u0e38\\u0e21_2025-07-25.pdf\"]', 0, '2025-07-25 03:23:50', '2026-03-23 23:41:44'),
(16, 13, '2026-03-24', 500, 'กิตติพรรณ บุญช่วย', 'สำนักงานใหญ่', NULL, NULL, 'เช็คระยะ', 10000, NULL, '1774337025_hr_requests_20260226_083621.pdf', 0, '2026-03-24 00:23:45', '2026-03-24 00:23:45'),
(19, 12, '2026-03-24', 10000, 'กิตติพรรณ บุญช่วย', 'สำนักงานใหญ่', NULL, NULL, 'test', 20000, NULL, '1774344691_hr_requests_20260226_083621.pdf', 0, '2026-03-24 02:31:31', '2026-03-24 02:31:31'),
(20, 12, '2026-03-24', 20000, 'กิตติพรรณ บุญช่วย', 'สำนักงานใหญ่', NULL, NULL, 'iii', 30000, NULL, '1774344713_hr_requests_20260226_083621.pdf', 0, '2026-03-24 02:31:53', '2026-03-24 02:31:53'),
(21, 13, '2026-03-25', 9000, 'กิตติพรรณ บุญช่วย', 'สำนักงานใหญ่', NULL, NULL, 'เช็คลมยาง', 20000, NULL, '1774404103_hr_requests_20260226_083621.pdf', 0, '2026-03-24 19:01:43', '2026-03-24 19:01:43'),
(22, 13, '2026-03-25', 11000, 'กิตติพรรณ บุญช่วย', 'สำนักงานใหญ่', NULL, NULL, 'เช็คระยะ', 30000, NULL, '1774404174_hr_requests_20260226_083621.pdf', 0, '2026-03-24 19:02:54', '2026-03-24 19:02:54'),
(23, 12, '2026-03-25', 154661, 'กิตติพรรณ บุญช่วย', 'สำนักงานใหญ่', NULL, NULL, 'เติมลม', 164661, NULL, '1774421467_Gemini_Generated_Image_91r27s91r27s91r2 (1).png', 0, '2026-03-24 23:51:07', '2026-03-24 23:51:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activitys`
--
ALTER TABLE `activitys`
  ADD PRIMARY KEY (`activitys_id`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `approval_settings`
--
ALTER TABLE `approval_settings`
  ADD PRIMARY KEY (`approval_settings_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`bookings_id`) USING BTREE,
  ADD KEY `vehicle_id` (`vehicle_id`) USING BTREE;

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `cart_items_user_id_index` (`user_id`);

--
-- Indexes for table `hams_permissions`
--
ALTER TABLE `hams_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hams_permissions_user_id_index` (`user_id`);

--
-- Indexes for table `hams_permission_logs`
--
ALTER TABLE `hams_permission_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hams_permission_logs_target_user_id_index` (`target_user_id`),
  ADD KEY `hams_permission_logs_granted_by_user_id_index` (`granted_by_user_id`);

--
-- Indexes for table `hams_special_rights`
--
ALTER TABLE `hams_special_rights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hams_special_rights_user_id_index` (`user_id`);

--
-- Indexes for table `housing_committees`
--
ALTER TABLE `housing_committees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `items_type`
--
ALTER TABLE `items_type`
  ADD PRIMARY KEY (`item_type_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `news_logs`
--
ALTER TABLE `news_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_id` (`news_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `policy`
--
ALTER TABLE `policy`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `requisitionitem_list`
--
ALTER TABLE `requisitionitem_list`
  ADD PRIMARY KEY (`requistionitemlist_id`);

--
-- Indexes for table `requisitions`
--
ALTER TABLE `requisitions`
  ADD PRIMARY KEY (`requisitions_id`),
  ADD KEY `requisitions_approve_status_index` (`approve_status`),
  ADD KEY `requisitions_status_index` (`status`),
  ADD KEY `requisitions_requester_id_index` (`requester_id`),
  ADD KEY `requisitions_packing_staff_status_index` (`packing_staff_status`);

--
-- Indexes for table `requisition_items`
--
ALTER TABLE `requisition_items`
  ADD PRIMARY KEY (`requistionitem_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`);

--
-- Indexes for table `reservation_meal_items`
--
ALTER TABLE `reservation_meal_items`
  ADD PRIMARY KEY (`reservation_meal_id`);

--
-- Indexes for table `residence_agreements`
--
ALTER TABLE `residence_agreements`
  ADD PRIMARY KEY (`agreement_id`);

--
-- Indexes for table `residence_leaves`
--
ALTER TABLE `residence_leaves`
  ADD PRIMARY KEY (`residence_leaves_id`);

--
-- Indexes for table `residence_repairs`
--
ALTER TABLE `residence_repairs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `repair_code` (`repair_code`);

--
-- Indexes for table `residence_requests`
--
ALTER TABLE `residence_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `residence_stay`
--
ALTER TABLE `residence_stay`
  ADD PRIMARY KEY (`residence_stay_id`);

--
-- Indexes for table `resident_guest_members`
--
ALTER TABLE `resident_guest_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resident_guest_requests`
--
ALTER TABLE `resident_guest_requests`
  ADD PRIMARY KEY (`resident_guest_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `vehicle_bookings`
--
ALTER TABLE `vehicle_bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `vehicle_inspections`
--
ALTER TABLE `vehicle_inspections`
  ADD PRIMARY KEY (`inspection_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `announcement_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=338;

--
-- AUTO_INCREMENT for table `hams_permissions`
--
ALTER TABLE `hams_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hams_permission_logs`
--
ALTER TABLE `hams_permission_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hams_special_rights`
--
ALTER TABLE `hams_special_rights`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `housing_committees`
--
ALTER TABLE `housing_committees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `items_type`
--
ALTER TABLE `items_type`
  MODIFY `item_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `news_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'รหัสข่าวสาร', AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `news_logs`
--
ALTER TABLE `news_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `policy`
--
ALTER TABLE `policy`
  MODIFY `policy_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `requisitionitem_list`
--
ALTER TABLE `requisitionitem_list`
  MODIFY `requistionitemlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `requisitions`
--
ALTER TABLE `requisitions`
  MODIFY `requisitions_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `requisition_items`
--
ALTER TABLE `requisition_items`
  MODIFY `requistionitem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสการจอง', AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `reservation_meal_items`
--
ALTER TABLE `reservation_meal_items`
  MODIFY `reservation_meal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `residence_agreements`
--
ALTER TABLE `residence_agreements`
  MODIFY `agreement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `residence_leaves`
--
ALTER TABLE `residence_leaves`
  MODIFY `residence_leaves_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `residence_repairs`
--
ALTER TABLE `residence_repairs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `residence_requests`
--
ALTER TABLE `residence_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `residence_stay`
--
ALTER TABLE `residence_stay`
  MODIFY `residence_stay_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `resident_guest_members`
--
ALTER TABLE `resident_guest_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `resident_guest_requests`
--
ALTER TABLE `resident_guest_requests`
  MODIFY `resident_guest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสห้องประชุม', AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `vehicle_bookings`
--
ALTER TABLE `vehicle_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `vehicle_inspections`
--
ALTER TABLE `vehicle_inspections`
  MODIFY `inspection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
