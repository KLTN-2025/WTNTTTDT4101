-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 09:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taodethi`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `academic_year` varchar(255) DEFAULT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_users`
--

CREATE TABLE `class_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('student','assistant_teacher') NOT NULL DEFAULT 'student',
  `student_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `difficulties`
--

CREATE TABLE `difficulties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `difficulties`
--

INSERT INTO `difficulties` (`id`, `name`, `slug`, `level`, `created_at`, `updated_at`) VALUES
(1, 'Dễ', 'easy', 1, '2025-11-21 22:56:32', '2025-11-21 22:56:32'),
(2, 'Trung bình', 'medium', 2, '2025-11-21 22:56:32', '2025-11-21 22:56:32'),
(3, 'Khó', 'hard', 3, '2025-11-21 22:56:32', '2025-11-21 22:56:32');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `due_date` datetime NOT NULL,
  `status` enum('upcoming','in_progress','submitted','graded') NOT NULL DEFAULT 'upcoming',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `title`, `subject`, `description`, `start_date`, `due_date`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Bài kiểm tra tổng hợp - Tất cả loại câu hỏi', 'Tổng hợp', 'Bài kiểm tra bao gồm tất cả các loại câu hỏi: trắc nghiệm, nhiều đáp án, đúng/sai, điền từ, sắp xếp, ghép cặp, tự luận', '2025-11-22 08:20:17', '2025-11-29 08:20:17', 'in_progress', 2, '2025-11-22 01:20:17', '2025-11-22 01:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `exam_answers`
--

CREATE TABLE `exam_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `answer` text DEFAULT NULL,
  `selected_options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`selected_options`)),
  `points_earned` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_correct` tinyint(1) DEFAULT NULL,
  `teacher_feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam_answers`
--

INSERT INTO `exam_answers` (`id`, `user_id`, `exam_id`, `question_id`, `answer`, `selected_options`, `points_earned`, `is_correct`, `teacher_feedback`, `created_at`, `updated_at`) VALUES
(21, 1, 1, 25, ':', NULL, 0.00, 0, NULL, '2025-11-22 01:24:06', '2025-11-22 01:24:06'),
(22, 1, 1, 26, '\"', NULL, 0.00, 0, NULL, '2025-11-22 01:24:06', '2025-11-22 01:24:06'),
(23, 1, 1, 27, 't', NULL, 0.00, 0, NULL, '2025-11-22 01:24:06', '2025-11-22 01:24:06'),
(24, 1, 1, 28, 'r', NULL, 0.00, 0, NULL, '2025-11-22 01:24:06', '2025-11-22 01:24:06'),
(25, 1, 1, 29, 'u', NULL, 0.00, 0, NULL, '2025-11-22 01:24:06', '2025-11-22 01:24:06'),
(26, 1, 1, 30, 'e', NULL, 0.00, 0, NULL, '2025-11-22 01:24:06', '2025-11-22 01:24:06'),
(27, 1, 1, 31, '\"', NULL, 0.00, 0, NULL, '2025-11-22 01:24:06', '2025-11-22 01:24:06'),
(28, 1, 1, 32, ',', NULL, 0.00, 0, NULL, '2025-11-22 01:24:06', '2025-11-22 01:24:06'),
(29, 1, 1, 33, '\"', NULL, 0.00, 0, NULL, '2025-11-22 01:24:06', '2025-11-22 01:24:06'),
(30, 1, 1, 34, '2', NULL, 0.00, 0, NULL, '2025-11-22 01:24:06', '2025-11-22 01:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `exam_essay_grades`
--

CREATE TABLE `exam_essay_grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `exam_answer_id` bigint(20) UNSIGNED NOT NULL,
  `graded_by` bigint(20) UNSIGNED NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `max_score` decimal(5,2) NOT NULL,
  `rubric_scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rubric_scores`)),
  `feedback` text DEFAULT NULL,
  `ai_suggestion` text DEFAULT NULL,
  `status` enum('pending','graded','reviewed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_questions`
--

CREATE TABLE `exam_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `points` decimal(5,2) NOT NULL DEFAULT 1.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam_questions`
--

INSERT INTO `exam_questions` (`id`, `exam_id`, `question_id`, `order`, `points`, `created_at`, `updated_at`) VALUES
(1, 1, 25, 1, 1.00, '2025-11-22 01:20:17', '2025-11-22 01:20:17'),
(2, 1, 26, 2, 1.00, '2025-11-22 01:20:17', '2025-11-22 01:20:17'),
(3, 1, 27, 3, 1.00, '2025-11-22 01:20:17', '2025-11-22 01:20:17'),
(4, 1, 28, 4, 1.00, '2025-11-22 01:20:17', '2025-11-22 01:20:17'),
(5, 1, 29, 5, 1.00, '2025-11-22 01:20:17', '2025-11-22 01:20:17'),
(6, 1, 30, 6, 1.00, '2025-11-22 01:20:17', '2025-11-22 01:20:17'),
(7, 1, 31, 7, 1.00, '2025-11-22 01:20:17', '2025-11-22 01:20:17'),
(8, 1, 32, 8, 1.00, '2025-11-22 01:20:17', '2025-11-22 01:20:17'),
(9, 1, 33, 9, 1.00, '2025-11-22 01:20:17', '2025-11-22 01:20:17'),
(10, 1, 34, 10, 1.00, '2025-11-22 01:20:17', '2025-11-22 01:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `exam_schedules`
--

CREATE TABLE `exam_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `exam_template_id` bigint(20) UNSIGNED DEFAULT NULL,
  `exam_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 60,
  `max_attempts` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam_schedules`
--

INSERT INTO `exam_schedules` (`id`, `exam_template_id`, `exam_id`, `name`, `description`, `start_time`, `end_time`, `duration_minutes`, `max_attempts`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Đề toán lớp 12', NULL, '2025-11-23 14:16:00', '2025-11-24 14:16:00', 60, 1, 1, 2, '2025-11-22 00:17:09', '2025-11-22 00:17:09');

-- --------------------------------------------------------

--
-- Table structure for table `exam_sessions`
--

CREATE TABLE `exam_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `question_order` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`question_order`)),
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`answers`)),
  `remaining_seconds` int(11) DEFAULT NULL,
  `camera_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `camera_locked` tinyint(1) NOT NULL DEFAULT 0,
  `started_at` datetime DEFAULT NULL,
  `paused_at` datetime DEFAULT NULL,
  `resumed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_templates`
--

CREATE TABLE `exam_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `structure` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`structure`)),
  `difficulty_distribution` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`difficulty_distribution`)),
  `randomize_questions` tinyint(1) NOT NULL DEFAULT 0,
  `randomize_options` tinyint(1) NOT NULL DEFAULT 0,
  `unique_per_student` tinyint(1) NOT NULL DEFAULT 0,
  `total_questions` int(11) NOT NULL DEFAULT 0,
  `total_points` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam_templates`
--

INSERT INTO `exam_templates` (`id`, `name`, `description`, `structure`, `difficulty_distribution`, `randomize_questions`, `randomize_options`, `unique_per_student`, `total_questions`, `total_points`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Đề toán lớp 12', 'tạo mã đề toán cho lớp 12 học kỳ 1', '[{\"tag\":\"algebra\",\"count\":10,\"points\":1},{\"tag\":\"geometry\",\"count\":5,\"points\":2}]', '{\"easy\":40,\"medium\":40,\"hard\":20}', 1, 1, 1, 15, 20.00, 2, '2025-11-22 00:20:28', '2025-11-22 00:20:28'),
(2, 'Đề toán lớp 12', 'tạo mã đề toán cho lớp 12 học kỳ 1', '[{\"tag\":\"algebra\",\"count\":10,\"points\":1},{\"tag\":\"geometry\",\"count\":5,\"points\":2}]', '{\"easy\":40,\"medium\":40,\"hard\":20}', 1, 1, 1, 15, 20.00, 2, '2025-11-22 00:21:51', '2025-11-22 00:21:51');

-- --------------------------------------------------------

--
-- Table structure for table `exam_template_questions`
--

CREATE TABLE `exam_template_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `exam_template_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `points` decimal(5,2) NOT NULL DEFAULT 1.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2025_10_31_062210_create_sessions_table', 1),
(7, '2025_10_31_065018_add_role_to_users_table', 1),
(8, '2025_10_31_070000_create_exams_table', 1),
(9, '2025_10_31_070001_create_scores_table', 1),
(10, '2025_10_31_070002_create_study_progress_table', 1),
(11, '2025_10_31_070003_create_study_suggestions_table', 1),
(12, '2025_10_31_070004_create_user_exams_table', 1),
(13, '2025_10_31_080000_create_tags_table', 2),
(14, '2025_10_31_080001_create_difficulties_table', 2),
(15, '2025_10_31_080002_create_skills_table', 2),
(16, '2025_10_31_080003_create_questions_table', 2),
(17, '2025_10_31_080004_create_question_tags_table', 2),
(18, '2025_10_31_080005_create_question_skills_table', 2),
(19, '2025_10_31_080006_create_skill_matrix_table', 2),
(20, '2025_10_31_080007_create_question_versions_table', 2),
(21, '2025_10_31_090000_create_exam_templates_table', 3),
(22, '2025_10_31_090001_create_exam_template_questions_table', 3),
(23, '2025_10_31_090002_create_exam_schedules_table', 3),
(24, '2025_10_31_090003_create_exam_questions_table', 4),
(25, '2025_11_22_063655_create_classes_table', 5),
(26, '2025_11_22_063709_create_class_users_table', 5),
(27, '2025_11_22_063722_create_exam_answers_table', 5),
(28, '2025_11_22_063736_create_exam_essay_grades_table', 5),
(29, '2025_11_22_065929_add_status_to_users_table', 6),
(30, '2025_11_22_075508_create_exam_sessions_table', 7),
(31, '2025_11_22_075522_create_question_feedback_table', 7),
(32, '2025_11_22_075536_add_exam_session_fields_to_user_exams_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `type` enum('single','multi','boolean','text','order','match','essay') NOT NULL DEFAULT 'single',
  `difficulty_id` bigint(20) UNSIGNED DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `correct_answer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`correct_answer`)),
  `explanation` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `audio_url` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `title`, `content`, `type`, `difficulty_id`, `options`, `correct_answer`, `explanation`, `image_url`, `audio_url`, `video_url`, `is_flagged`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2 + 2 = ?', 'Tính kết quả của phép tính: \\(2 + 2\\)', 'single', 1, '[\"2\",\"3\",\"4\",\"5\"]', '[2]', 'Phép cộng đơn giản: 2 + 2 = 4', NULL, NULL, NULL, 0, 1, 1, '2025-11-21 23:04:21', '2025-11-21 23:04:21', NULL),
(2, 'Số nguyên tố nhỏ hơn 10', 'Chọn tất cả các số nguyên tố nhỏ hơn 10:', 'multi', 2, '[\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\"]', '[0,1,3,5]', 'Số nguyên tố nhỏ hơn 10 là: 2, 3, 5, 7', NULL, NULL, NULL, 0, 1, 1, '2025-11-21 23:04:21', '2025-11-21 23:04:21', NULL),
(3, 'Hà Nội là thủ đô của Việt Nam', 'Câu hỏi đúng/sai: Hà Nội là thủ đô của Việt Nam', 'boolean', 1, NULL, '\"true\"', 'Hà Nội là thủ đô của Việt Nam từ năm 1976', NULL, NULL, NULL, 0, 1, 1, '2025-11-21 23:04:21', '2025-11-21 23:04:21', NULL),
(4, 'Điền từ vào chỗ trống', 'Việt Nam nằm ở khu vực Đông Nam ___.', 'text', 1, NULL, '[\"\\u00c1\",\"A\"]', 'Việt Nam nằm ở khu vực Đông Nam Á', NULL, NULL, NULL, 0, 1, 1, '2025-11-21 23:04:21', '2025-11-21 23:04:21', NULL),
(5, 'Phép tính cơ bản', 'Tính kết quả của phép tính: \\(2 + 2 = ?\\)', 'single', 1, '[\"2\",\"3\",\"4\",\"5\"]', '[2]', 'Phép cộng đơn giản: 2 + 2 = 4', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:01', '2025-11-22 01:20:01', NULL),
(6, 'Số nguyên tố', 'Chọn tất cả các số nguyên tố nhỏ hơn 10:', 'multi', 2, '[\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\"]', '[0,1,3,5]', 'Số nguyên tố nhỏ hơn 10 là: 2, 3, 5, 7', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:01', '2025-11-22 01:20:01', NULL),
(7, 'Thủ đô Việt Nam', 'Hà Nội là thủ đô của Việt Nam', 'boolean', 1, NULL, '\"true\"', 'Hà Nội là thủ đô của Việt Nam từ năm 1976', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:01', '2025-11-22 01:20:01', NULL),
(8, 'Điền từ - Địa lý', 'Việt Nam nằm ở khu vực Đông Nam ___.', 'text', 1, NULL, '[\"\\u00c1\",\"A\"]', 'Việt Nam nằm ở khu vực Đông Nam Á', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:01', '2025-11-22 01:20:01', NULL),
(9, 'Sắp xếp số', 'Sắp xếp các số sau theo thứ tự tăng dần:', 'order', 1, '[\"8\",\"3\",\"5\",\"1\"]', '[\"1\",\"3\",\"5\",\"8\"]', 'Thứ tự tăng dần: 1, 3, 5, 8', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:01', '2025-11-22 01:20:01', NULL),
(10, 'Ghép cặp thành phố', 'Ghép cặp các thành phố với miền tương ứng:', 'match', 2, '{\"left\":{\"HN\":\"H\\u00e0 N\\u1ed9i\",\"HCM\":\"TP.HCM\",\"DN\":\"\\u0110\\u00e0 N\\u1eb5ng\"},\"right\":{\"MB\":\"Mi\\u1ec1n B\\u1eafc\",\"MN\":\"Mi\\u1ec1n Nam\",\"MT\":\"Mi\\u1ec1n Trung\"}}', '{\"HN\":\"MB\",\"HCM\":\"MN\",\"DN\":\"MT\"}', 'Hà Nội thuộc Miền Bắc, TP.HCM thuộc Miền Nam, Đà Nẵng thuộc Miền Trung', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:01', '2025-11-22 01:20:01', NULL),
(11, 'Câu hỏi tự luận', 'Trình bày cảm nhận của bạn về một thói quen học tập hiệu quả (8-10 câu).', 'essay', 3, NULL, NULL, 'Câu tự luận sẽ được giáo viên chấm thủ công', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:01', '2025-11-22 01:20:01', NULL),
(12, 'Công thức toán học', 'Công thức định lý Pythagoras: \\(a^2 + b^2 = c^2\\). Câu này đúng hay sai?', 'single', 2, '[\"Sai\",\"Kh\\u00f4ng \\u0111\\u1ee7 d\\u1eef ki\\u1ec7n\",\"\\u0110\\u00fang\"]', '[2]', 'Công thức định lý Pythagoras: \\(a^2 + b^2 = c^2\\) là đúng', 'https://via.placeholder.com/300x180?text=Hinh+minh+hoa', NULL, NULL, 0, 2, 2, '2025-11-22 01:20:01', '2025-11-22 01:20:01', NULL),
(13, 'Phép nhân', 'Tính: \\(3 \\times 4 = ?\\)', 'single', 1, '[\"10\",\"11\",\"12\",\"13\"]', '[2]', 'Phép nhân: 3 × 4 = 12', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:01', '2025-11-22 01:20:01', NULL),
(14, 'Phân số', 'Chọn các phân số bằng \\(\\frac{1}{2}\\):', 'multi', 2, '[\"\\\\(\\\\frac{2}{4}\\\\)\",\"\\\\(\\\\frac{3}{6}\\\\)\",\"\\\\(\\\\frac{1}{3}\\\\)\",\"\\\\(\\\\frac{5}{10}\\\\)\"]', '[0,1,3]', 'Các phân số bằng \\(\\frac{1}{2}\\) là: \\(\\frac{2}{4}\\), \\(\\frac{3}{6}\\), \\(\\frac{5}{10}\\)', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:01', '2025-11-22 01:20:01', NULL),
(15, 'Phép tính cơ bản', 'Tính kết quả của phép tính: \\(2 + 2 = ?\\)', 'single', 1, '[\"2\",\"3\",\"4\",\"5\"]', '[2]', 'Phép cộng đơn giản: 2 + 2 = 4', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:11', '2025-11-22 01:20:11', NULL),
(16, 'Số nguyên tố', 'Chọn tất cả các số nguyên tố nhỏ hơn 10:', 'multi', 2, '[\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\"]', '[0,1,3,5]', 'Số nguyên tố nhỏ hơn 10 là: 2, 3, 5, 7', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:11', '2025-11-22 01:20:11', NULL),
(17, 'Thủ đô Việt Nam', 'Hà Nội là thủ đô của Việt Nam', 'boolean', 1, NULL, '\"true\"', 'Hà Nội là thủ đô của Việt Nam từ năm 1976', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:11', '2025-11-22 01:20:11', NULL),
(18, 'Điền từ - Địa lý', 'Việt Nam nằm ở khu vực Đông Nam ___.', 'text', 1, NULL, '[\"\\u00c1\",\"A\"]', 'Việt Nam nằm ở khu vực Đông Nam Á', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:11', '2025-11-22 01:20:11', NULL),
(19, 'Sắp xếp số', 'Sắp xếp các số sau theo thứ tự tăng dần:', 'order', 1, '[\"8\",\"3\",\"5\",\"1\"]', '[\"1\",\"3\",\"5\",\"8\"]', 'Thứ tự tăng dần: 1, 3, 5, 8', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:11', '2025-11-22 01:20:11', NULL),
(20, 'Ghép cặp thành phố', 'Ghép cặp các thành phố với miền tương ứng:', 'match', 2, '{\"left\":{\"HN\":\"H\\u00e0 N\\u1ed9i\",\"HCM\":\"TP.HCM\",\"DN\":\"\\u0110\\u00e0 N\\u1eb5ng\"},\"right\":{\"MB\":\"Mi\\u1ec1n B\\u1eafc\",\"MN\":\"Mi\\u1ec1n Nam\",\"MT\":\"Mi\\u1ec1n Trung\"}}', '{\"HN\":\"MB\",\"HCM\":\"MN\",\"DN\":\"MT\"}', 'Hà Nội thuộc Miền Bắc, TP.HCM thuộc Miền Nam, Đà Nẵng thuộc Miền Trung', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:11', '2025-11-22 01:20:11', NULL),
(21, 'Câu hỏi tự luận', 'Trình bày cảm nhận của bạn về một thói quen học tập hiệu quả (8-10 câu).', 'essay', 3, NULL, NULL, 'Câu tự luận sẽ được giáo viên chấm thủ công', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:11', '2025-11-22 01:20:11', NULL),
(22, 'Công thức toán học', 'Công thức định lý Pythagoras: \\(a^2 + b^2 = c^2\\). Câu này đúng hay sai?', 'single', 2, '[\"Sai\",\"Kh\\u00f4ng \\u0111\\u1ee7 d\\u1eef ki\\u1ec7n\",\"\\u0110\\u00fang\"]', '[2]', 'Công thức định lý Pythagoras: \\(a^2 + b^2 = c^2\\) là đúng', 'https://via.placeholder.com/300x180?text=Hinh+minh+hoa', NULL, NULL, 0, 2, 2, '2025-11-22 01:20:11', '2025-11-22 01:20:11', NULL),
(23, 'Phép nhân', 'Tính: \\(3 \\times 4 = ?\\)', 'single', 1, '[\"10\",\"11\",\"12\",\"13\"]', '[2]', 'Phép nhân: 3 × 4 = 12', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:11', '2025-11-22 01:20:11', NULL),
(24, 'Phân số', 'Chọn các phân số bằng \\(\\frac{1}{2}\\):', 'multi', 2, '[\"\\\\(\\\\frac{2}{4}\\\\)\",\"\\\\(\\\\frac{3}{6}\\\\)\",\"\\\\(\\\\frac{1}{3}\\\\)\",\"\\\\(\\\\frac{5}{10}\\\\)\"]', '[0,1,3]', 'Các phân số bằng \\(\\frac{1}{2}\\) là: \\(\\frac{2}{4}\\), \\(\\frac{3}{6}\\), \\(\\frac{5}{10}\\)', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:11', '2025-11-22 01:20:11', NULL),
(25, 'Phép tính cơ bản', 'Tính kết quả của phép tính: \\(2 + 2 = ?\\)', 'single', 1, '[\"2\",\"3\",\"4\",\"5\"]', '[2]', 'Phép cộng đơn giản: 2 + 2 = 4', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:17', '2025-11-22 01:20:17', NULL),
(26, 'Số nguyên tố', 'Chọn tất cả các số nguyên tố nhỏ hơn 10:', 'multi', 2, '[\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\"]', '[0,1,3,5]', 'Số nguyên tố nhỏ hơn 10 là: 2, 3, 5, 7', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:17', '2025-11-22 01:20:17', NULL),
(27, 'Thủ đô Việt Nam', 'Hà Nội là thủ đô của Việt Nam', 'boolean', 1, NULL, '\"true\"', 'Hà Nội là thủ đô của Việt Nam từ năm 1976', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:17', '2025-11-22 01:20:17', NULL),
(28, 'Điền từ - Địa lý', 'Việt Nam nằm ở khu vực Đông Nam ___.', 'text', 1, NULL, '[\"\\u00c1\",\"A\"]', 'Việt Nam nằm ở khu vực Đông Nam Á', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:17', '2025-11-22 01:20:17', NULL),
(29, 'Sắp xếp số', 'Sắp xếp các số sau theo thứ tự tăng dần:', 'order', 1, '[\"8\",\"3\",\"5\",\"1\"]', '[\"1\",\"3\",\"5\",\"8\"]', 'Thứ tự tăng dần: 1, 3, 5, 8', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:17', '2025-11-22 01:20:17', NULL),
(30, 'Ghép cặp thành phố', 'Ghép cặp các thành phố với miền tương ứng:', 'match', 2, '{\"left\":{\"HN\":\"H\\u00e0 N\\u1ed9i\",\"HCM\":\"TP.HCM\",\"DN\":\"\\u0110\\u00e0 N\\u1eb5ng\"},\"right\":{\"MB\":\"Mi\\u1ec1n B\\u1eafc\",\"MN\":\"Mi\\u1ec1n Nam\",\"MT\":\"Mi\\u1ec1n Trung\"}}', '{\"HN\":\"MB\",\"HCM\":\"MN\",\"DN\":\"MT\"}', 'Hà Nội thuộc Miền Bắc, TP.HCM thuộc Miền Nam, Đà Nẵng thuộc Miền Trung', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:17', '2025-11-22 01:20:17', NULL),
(31, 'Câu hỏi tự luận', 'Trình bày cảm nhận của bạn về một thói quen học tập hiệu quả (8-10 câu).', 'essay', 3, NULL, NULL, 'Câu tự luận sẽ được giáo viên chấm thủ công', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:17', '2025-11-22 01:20:17', NULL),
(32, 'Công thức toán học', 'Công thức định lý Pythagoras: \\(a^2 + b^2 = c^2\\). Câu này đúng hay sai?', 'single', 2, '[\"Sai\",\"Kh\\u00f4ng \\u0111\\u1ee7 d\\u1eef ki\\u1ec7n\",\"\\u0110\\u00fang\"]', '[2]', 'Công thức định lý Pythagoras: \\(a^2 + b^2 = c^2\\) là đúng', 'https://via.placeholder.com/300x180?text=Hinh+minh+hoa', NULL, NULL, 0, 2, 2, '2025-11-22 01:20:17', '2025-11-22 01:20:17', NULL),
(33, 'Phép nhân', 'Tính: \\(3 \\times 4 = ?\\)', 'single', 1, '[\"10\",\"11\",\"12\",\"13\"]', '[2]', 'Phép nhân: 3 × 4 = 12', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:17', '2025-11-22 01:20:17', NULL),
(34, 'Phân số', 'Chọn các phân số bằng \\(\\frac{1}{2}\\):', 'multi', 2, '[\"\\\\(\\\\frac{2}{4}\\\\)\",\"\\\\(\\\\frac{3}{6}\\\\)\",\"\\\\(\\\\frac{1}{3}\\\\)\",\"\\\\(\\\\frac{5}{10}\\\\)\"]', '[0,1,3]', 'Các phân số bằng \\(\\frac{1}{2}\\) là: \\(\\frac{2}{4}\\), \\(\\frac{3}{6}\\), \\(\\frac{5}{10}\\)', NULL, NULL, NULL, 0, 2, 2, '2025-11-22 01:20:17', '2025-11-22 01:20:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `question_feedback`
--

CREATE TABLE `question_feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('error','clarification','note') NOT NULL DEFAULT 'note',
  `message` text NOT NULL,
  `status` enum('pending','reviewed','resolved') NOT NULL DEFAULT 'pending',
  `admin_response` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_skills`
--

CREATE TABLE `question_skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `skill_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_skills`
--

INSERT INTO `question_skills` (`id`, `question_id`, `skill_id`, `created_at`, `updated_at`) VALUES
(1, 1, 5, NULL, NULL),
(2, 2, 5, NULL, NULL),
(3, 3, 1, NULL, NULL),
(4, 4, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `question_tags`
--

CREATE TABLE `question_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_tags`
--

INSERT INTO `question_tags` (`id`, `question_id`, `tag_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 2, 1, NULL, NULL),
(4, 3, 3, NULL, NULL),
(5, 4, 3, NULL, NULL),
(6, 5, 1, NULL, NULL),
(7, 6, 1, NULL, NULL),
(8, 7, 4, NULL, NULL),
(9, 8, 4, NULL, NULL),
(10, 9, 1, NULL, NULL),
(11, 10, 4, NULL, NULL),
(12, 11, 3, NULL, NULL),
(13, 12, 1, NULL, NULL),
(14, 13, 1, NULL, NULL),
(15, 14, 1, NULL, NULL),
(16, 15, 1, NULL, NULL),
(17, 16, 1, NULL, NULL),
(18, 17, 4, NULL, NULL),
(19, 18, 4, NULL, NULL),
(20, 19, 1, NULL, NULL),
(21, 20, 4, NULL, NULL),
(22, 21, 3, NULL, NULL),
(23, 22, 1, NULL, NULL),
(24, 23, 1, NULL, NULL),
(25, 24, 1, NULL, NULL),
(26, 25, 1, NULL, NULL),
(27, 26, 1, NULL, NULL),
(28, 27, 4, NULL, NULL),
(29, 28, 4, NULL, NULL),
(30, 29, 1, NULL, NULL),
(31, 30, 4, NULL, NULL),
(32, 31, 3, NULL, NULL),
(33, 32, 1, NULL, NULL),
(34, 33, 1, NULL, NULL),
(35, 34, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `question_versions`
--

CREATE TABLE `question_versions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `version_number` int(11) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `change_note` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_versions`
--

INSERT INTO `question_versions` (`id`, `question_id`, `version_number`, `data`, `change_note`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '{\"title\":\"2 + 2 = ?\",\"content\":\"T\\u00ednh k\\u1ebft qu\\u1ea3 c\\u1ee7a ph\\u00e9p t\\u00ednh: \\\\(2 + 2\\\\)\",\"type\":\"single\",\"difficulty_id\":1,\"options\":[\"2\",\"3\",\"4\",\"5\"],\"correct_answer\":[2],\"explanation\":\"Ph\\u00e9p c\\u1ed9ng \\u0111\\u01a1n gi\\u1ea3n: 2 + 2 = 4\",\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2025-11-22T06:04:21.000000Z\",\"created_at\":\"2025-11-22T06:04:21.000000Z\",\"id\":1}', 'Tạo mới câu hỏi', 1, '2025-11-21 23:04:21', '2025-11-21 23:04:21'),
(2, 2, 1, '{\"title\":\"S\\u1ed1 nguy\\u00ean t\\u1ed1 nh\\u1ecf h\\u01a1n 10\",\"content\":\"Ch\\u1ecdn t\\u1ea5t c\\u1ea3 c\\u00e1c s\\u1ed1 nguy\\u00ean t\\u1ed1 nh\\u1ecf h\\u01a1n 10:\",\"type\":\"multi\",\"difficulty_id\":2,\"options\":[\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\"],\"correct_answer\":[0,1,3,5],\"explanation\":\"S\\u1ed1 nguy\\u00ean t\\u1ed1 nh\\u1ecf h\\u01a1n 10 l\\u00e0: 2, 3, 5, 7\",\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2025-11-22T06:04:21.000000Z\",\"created_at\":\"2025-11-22T06:04:21.000000Z\",\"id\":2}', 'Tạo mới câu hỏi', 1, '2025-11-21 23:04:21', '2025-11-21 23:04:21'),
(3, 3, 1, '{\"title\":\"H\\u00e0 N\\u1ed9i l\\u00e0 th\\u1ee7 \\u0111\\u00f4 c\\u1ee7a Vi\\u1ec7t Nam\",\"content\":\"C\\u00e2u h\\u1ecfi \\u0111\\u00fang\\/sai: H\\u00e0 N\\u1ed9i l\\u00e0 th\\u1ee7 \\u0111\\u00f4 c\\u1ee7a Vi\\u1ec7t Nam\",\"type\":\"boolean\",\"difficulty_id\":1,\"options\":null,\"correct_answer\":\"true\",\"explanation\":\"H\\u00e0 N\\u1ed9i l\\u00e0 th\\u1ee7 \\u0111\\u00f4 c\\u1ee7a Vi\\u1ec7t Nam t\\u1eeb n\\u0103m 1976\",\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2025-11-22T06:04:21.000000Z\",\"created_at\":\"2025-11-22T06:04:21.000000Z\",\"id\":3}', 'Tạo mới câu hỏi', 1, '2025-11-21 23:04:21', '2025-11-21 23:04:21'),
(4, 4, 1, '{\"title\":\"\\u0110i\\u1ec1n t\\u1eeb v\\u00e0o ch\\u1ed7 tr\\u1ed1ng\",\"content\":\"Vi\\u1ec7t Nam n\\u1eb1m \\u1edf khu v\\u1ef1c \\u0110\\u00f4ng Nam ___.\",\"type\":\"text\",\"difficulty_id\":1,\"options\":null,\"correct_answer\":[\"\\u00c1\",\"A\"],\"explanation\":\"Vi\\u1ec7t Nam n\\u1eb1m \\u1edf khu v\\u1ef1c \\u0110\\u00f4ng Nam \\u00c1\",\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2025-11-22T06:04:21.000000Z\",\"created_at\":\"2025-11-22T06:04:21.000000Z\",\"id\":4}', 'Tạo mới câu hỏi', 1, '2025-11-21 23:04:21', '2025-11-21 23:04:21');

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `score` decimal(4,2) NOT NULL,
  `test_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `slug`, `category`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Đọc hiểu', 'reading', 'language', NULL, '2025-11-21 22:56:32', '2025-11-21 22:56:32'),
(2, 'Viết', 'writing', 'language', NULL, '2025-11-21 22:56:32', '2025-11-21 22:56:32'),
(3, 'Nghe', 'listening', 'language', NULL, '2025-11-21 22:56:32', '2025-11-21 22:56:32'),
(4, 'Nói', 'speaking', 'language', NULL, '2025-11-21 22:56:32', '2025-11-21 22:56:32'),
(5, 'Đại số', 'algebra', 'math', NULL, '2025-11-21 22:56:32', '2025-11-21 22:56:32'),
(6, 'Hình học', 'geometry', 'math', NULL, '2025-11-21 22:56:32', '2025-11-21 22:56:32');

-- --------------------------------------------------------

--
-- Table structure for table `skill_matrix`
--

CREATE TABLE `skill_matrix` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `skill_id` bigint(20) UNSIGNED NOT NULL,
  `weight` int(11) NOT NULL DEFAULT 1,
  `level` int(11) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_progress`
--

CREATE TABLE `study_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `progress_percentage` int(11) NOT NULL DEFAULT 0,
  `total_lessons` int(11) NOT NULL DEFAULT 0,
  `completed_lessons` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_suggestions`
--

CREATE TABLE `study_suggestions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `type` enum('vocabulary','practice','reading','listening','review') NOT NULL DEFAULT 'practice',
  `priority` int(11) NOT NULL DEFAULT 1,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Toán', 'toan', NULL, '2025-11-21 23:04:21', '2025-11-21 23:04:21'),
(2, 'Cơ bản', 'co-ban', NULL, '2025-11-21 23:04:21', '2025-11-21 23:04:21'),
(3, 'Văn', 'van', NULL, '2025-11-21 23:04:21', '2025-11-21 23:04:21'),
(4, 'Địa lý', 'dia', NULL, '2025-11-22 01:20:01', '2025-11-22 01:20:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'student',
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `status`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'Student Demo', 'student@example.com', 'student', 'active', '2025-11-21 22:50:27', '$2y$10$7zB9.URsIaLB7bxPE9Ah6u9suq2j./qN0.kGPdlmhUXbvORdpu58K', NULL, NULL, NULL, 'eboNwpQiywXvy2ATSZdN6NXQZWPCldW9bBo9wZLv8gmRQ3xY2QpnkW4WpN2j', NULL, NULL, '2025-11-21 22:50:27', '2025-11-21 22:50:27'),
(2, 'Teacher Demo', 'teacher@example.com', 'teacher', 'active', '2025-11-21 22:50:27', '$2y$10$ApEIVizRDBss.QbimAF0TebV1OSFduaGCd8vN/NZNe/2EeauTR356', NULL, NULL, NULL, 'ZUChSYrXelRqQbaTKQobhitmoXfMMOW4qmUx2kPHzF4EiYZiovSKRm2VAwk4', NULL, NULL, '2025-11-21 22:50:27', '2025-11-21 22:50:27'),
(3, 'Admin Demo', 'admin@example.com', 'admin', 'active', '2025-11-21 22:50:27', '$2y$10$E.PEjIycdrNvUrup/fWQaulr9587Np5JDnfknCB03CmE12uHGtJlK', NULL, NULL, NULL, '3Lz752L2v8mOyZoGBcIrD46SFpueu0gKUESpapavmgEQILLA97XkLRRZnOJJ', NULL, NULL, '2025-11-21 22:50:27', '2025-11-22 00:13:14'),
(4, 'Nguyen duc anh', 'k40modgam@gmail.com', 'admin', 'active', NULL, '$2y$10$rK2UoaDPcFELgmHZ6jlcO.c2OdAeJOlbJ4fW78XrLZcTiJ86RHG4W', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-22 00:09:27', '2025-11-22 00:12:56');

-- --------------------------------------------------------

--
-- Table structure for table `user_exams`
--

CREATE TABLE `user_exams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('not_started','in_progress','submitted','graded') NOT NULL DEFAULT 'not_started',
  `duration_minutes` int(11) DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `max_score` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_exams`
--

INSERT INTO `user_exams` (`id`, `user_id`, `exam_id`, `status`, `duration_minutes`, `submitted_at`, `started_at`, `completed_at`, `score`, `max_score`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'submitted', 60, '2025-11-22 08:24:06', '2025-11-22 08:20:46', '2025-11-22 08:24:06', 0.00, 10.00, '2025-11-22 01:20:17', '2025-11-22 01:20:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `classes_code_unique` (`code`),
  ADD KEY `classes_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `class_users`
--
ALTER TABLE `class_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_users_class_id_user_id_unique` (`class_id`,`user_id`),
  ADD KEY `class_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `difficulties`
--
ALTER TABLE `difficulties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `difficulties_slug_unique` (`slug`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exams_created_by_foreign` (`created_by`);

--
-- Indexes for table `exam_answers`
--
ALTER TABLE `exam_answers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exam_answers_user_id_exam_id_question_id_unique` (`user_id`,`exam_id`,`question_id`),
  ADD KEY `exam_answers_exam_id_foreign` (`exam_id`),
  ADD KEY `exam_answers_question_id_foreign` (`question_id`);

--
-- Indexes for table `exam_essay_grades`
--
ALTER TABLE `exam_essay_grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_essay_grades_exam_answer_id_foreign` (`exam_answer_id`),
  ADD KEY `exam_essay_grades_graded_by_foreign` (`graded_by`);

--
-- Indexes for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_questions_exam_id_foreign` (`exam_id`),
  ADD KEY `exam_questions_question_id_foreign` (`question_id`);

--
-- Indexes for table `exam_schedules`
--
ALTER TABLE `exam_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_schedules_exam_template_id_foreign` (`exam_template_id`),
  ADD KEY `exam_schedules_exam_id_foreign` (`exam_id`),
  ADD KEY `exam_schedules_created_by_foreign` (`created_by`);

--
-- Indexes for table `exam_sessions`
--
ALTER TABLE `exam_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exam_sessions_user_id_exam_id_unique` (`user_id`,`exam_id`),
  ADD KEY `exam_sessions_exam_id_foreign` (`exam_id`),
  ADD KEY `exam_sessions_user_id_exam_id_index` (`user_id`,`exam_id`);

--
-- Indexes for table `exam_templates`
--
ALTER TABLE `exam_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_templates_created_by_foreign` (`created_by`);

--
-- Indexes for table `exam_template_questions`
--
ALTER TABLE `exam_template_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_template_questions_exam_template_id_foreign` (`exam_template_id`),
  ADD KEY `exam_template_questions_question_id_foreign` (`question_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_difficulty_id_foreign` (`difficulty_id`),
  ADD KEY `questions_created_by_foreign` (`created_by`),
  ADD KEY `questions_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `question_feedback`
--
ALTER TABLE `question_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_feedback_exam_id_foreign` (`exam_id`),
  ADD KEY `question_feedback_question_id_foreign` (`question_id`),
  ADD KEY `question_feedback_user_id_exam_id_question_id_index` (`user_id`,`exam_id`,`question_id`);

--
-- Indexes for table `question_skills`
--
ALTER TABLE `question_skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `question_skills_question_id_skill_id_unique` (`question_id`,`skill_id`),
  ADD KEY `question_skills_skill_id_foreign` (`skill_id`);

--
-- Indexes for table `question_tags`
--
ALTER TABLE `question_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `question_tags_question_id_tag_id_unique` (`question_id`,`tag_id`),
  ADD KEY `question_tags_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `question_versions`
--
ALTER TABLE `question_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_versions_question_id_foreign` (`question_id`),
  ADD KEY `question_versions_created_by_foreign` (`created_by`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scores_user_id_foreign` (`user_id`),
  ADD KEY `scores_exam_id_foreign` (`exam_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skills_slug_unique` (`slug`);

--
-- Indexes for table `skill_matrix`
--
ALTER TABLE `skill_matrix`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skill_matrix_question_id_skill_id_unique` (`question_id`,`skill_id`),
  ADD KEY `skill_matrix_skill_id_foreign` (`skill_id`);

--
-- Indexes for table `study_progress`
--
ALTER TABLE `study_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `study_progress_user_id_subject_unique` (`user_id`,`subject`);

--
-- Indexes for table `study_suggestions`
--
ALTER TABLE `study_suggestions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `study_suggestions_user_id_foreign` (`user_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_name_unique` (`name`),
  ADD UNIQUE KEY `tags_slug_unique` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_exams`
--
ALTER TABLE `user_exams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_exams_user_id_exam_id_unique` (`user_id`,`exam_id`),
  ADD KEY `user_exams_exam_id_foreign` (`exam_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_users`
--
ALTER TABLE `class_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `difficulties`
--
ALTER TABLE `difficulties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `exam_answers`
--
ALTER TABLE `exam_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `exam_essay_grades`
--
ALTER TABLE `exam_essay_grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_questions`
--
ALTER TABLE `exam_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `exam_schedules`
--
ALTER TABLE `exam_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `exam_sessions`
--
ALTER TABLE `exam_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `exam_templates`
--
ALTER TABLE `exam_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `exam_template_questions`
--
ALTER TABLE `exam_template_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `question_feedback`
--
ALTER TABLE `question_feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question_skills`
--
ALTER TABLE `question_skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `question_tags`
--
ALTER TABLE `question_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `question_versions`
--
ALTER TABLE `question_versions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `skill_matrix`
--
ALTER TABLE `skill_matrix`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_progress`
--
ALTER TABLE `study_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_suggestions`
--
ALTER TABLE `study_suggestions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_exams`
--
ALTER TABLE `user_exams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_users`
--
ALTER TABLE `class_users`
  ADD CONSTRAINT `class_users_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `exam_answers`
--
ALTER TABLE `exam_answers`
  ADD CONSTRAINT `exam_answers_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_essay_grades`
--
ALTER TABLE `exam_essay_grades`
  ADD CONSTRAINT `exam_essay_grades_exam_answer_id_foreign` FOREIGN KEY (`exam_answer_id`) REFERENCES `exam_answers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_essay_grades_graded_by_foreign` FOREIGN KEY (`graded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD CONSTRAINT `exam_questions_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_questions_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_schedules`
--
ALTER TABLE `exam_schedules`
  ADD CONSTRAINT `exam_schedules_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `exam_schedules_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `exam_schedules_exam_template_id_foreign` FOREIGN KEY (`exam_template_id`) REFERENCES `exam_templates` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `exam_sessions`
--
ALTER TABLE `exam_sessions`
  ADD CONSTRAINT `exam_sessions_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_templates`
--
ALTER TABLE `exam_templates`
  ADD CONSTRAINT `exam_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `exam_template_questions`
--
ALTER TABLE `exam_template_questions`
  ADD CONSTRAINT `exam_template_questions_exam_template_id_foreign` FOREIGN KEY (`exam_template_id`) REFERENCES `exam_templates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_template_questions_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `questions_difficulty_id_foreign` FOREIGN KEY (`difficulty_id`) REFERENCES `difficulties` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `questions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `question_feedback`
--
ALTER TABLE `question_feedback`
  ADD CONSTRAINT `question_feedback_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `question_feedback_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `question_feedback_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_skills`
--
ALTER TABLE `question_skills`
  ADD CONSTRAINT `question_skills_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `question_skills_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_tags`
--
ALTER TABLE `question_tags`
  ADD CONSTRAINT `question_tags_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `question_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_versions`
--
ALTER TABLE `question_versions`
  ADD CONSTRAINT `question_versions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `question_versions_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `scores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `skill_matrix`
--
ALTER TABLE `skill_matrix`
  ADD CONSTRAINT `skill_matrix_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `skill_matrix_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `study_progress`
--
ALTER TABLE `study_progress`
  ADD CONSTRAINT `study_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `study_suggestions`
--
ALTER TABLE `study_suggestions`
  ADD CONSTRAINT `study_suggestions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_exams`
--
ALTER TABLE `user_exams`
  ADD CONSTRAINT `user_exams_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_exams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
