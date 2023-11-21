-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2023 at 02:00 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms_pupill_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(255) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `class_headTeacher` varchar(255) NOT NULL,
  `class_subjectCount` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL,
  `class_code` varchar(255) NOT NULL,
  `created_at` datetime(6) NOT NULL,
  `updated_at` datetime(6) DEFAULT NULL ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `class_headTeacher`, `class_subjectCount`, `school`, `class_code`, `created_at`, `updated_at`) VALUES
(24, 'Ict XII-12', '2', '7', '5', '7Nmu3', '2023-05-11 22:55:10.000000', NULL),
(25, 'IV-8 Js', '8', '8', '5', 'GH2jg', '2023-05-11 23:35:38.000000', NULL),
(26, 'Test', '2', '3', '5', 'xcE2R', '2023-05-11 23:49:36.000000', NULL),
(27, 'Klasa Test', '2', '5', '5', 'iE45n', '2023-06-05 13:06:25.000000', NULL),
(31, 'IV-5', '2', '5', '5', 's0q0v', '2023-06-13 19:10:57.000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `class_subjects`
--

CREATE TABLE `class_subjects` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `class_subjects`
--

INSERT INTO `class_subjects` (`id`, `class_id`, `subject`, `teacher_id`) VALUES
(60, 24, 'Literature', 8),
(61, 24, 'English', 9),
(62, 24, 'Biology', 2),
(63, 24, 'Physics', 11),
(64, 24, 'Mathematics', 14),
(65, 24, 'Music', 13),
(66, 24, 'PE', 12),
(67, 25, 'History', 2),
(68, 25, 'Chemistry', 8),
(69, 25, 'Mathematics', 14),
(70, 25, 'Sociology', 2),
(71, 25, 'Technology', 10),
(72, 25, 'Art', 11),
(73, 25, 'Music', 13),
(74, 25, 'PE', 12),
(75, 26, 'Chemistry', 0),
(76, 26, 'Mathematics', 0),
(77, 26, 'Sociology', 0),
(78, 27, 'Biology', 0),
(79, 27, 'Psychology', 0),
(80, 27, 'Geography', 0),
(81, 27, 'Sociology', 0),
(82, 27, 'Technology', 0),
(83, 28, 'English', 0),
(84, 28, 'Biology', 0),
(85, 28, 'Physics', 0),
(86, 28, 'Psychology', 0),
(87, 29, 'Literature', 0),
(88, 29, 'English', 0),
(89, 29, 'Biology', 0),
(90, 30, 'Literature', 0),
(91, 30, 'English', 0),
(92, 30, 'Biology', 0),
(93, 31, 'Literature', 0),
(94, 31, 'English', 0),
(95, 31, 'Biology', 0),
(96, 31, 'Physics', 0),
(97, 31, 'Psychology', 0);

-- --------------------------------------------------------

--
-- Table structure for table `completed_homework`
--

CREATE TABLE `completed_homework` (
  `homework_id` int(11) NOT NULL,
  `homework_title` varchar(255) NOT NULL,
  `homework_description` varchar(255) NOT NULL,
  `homework_teacher` int(255) NOT NULL,
  `homework_subject` varchar(255) NOT NULL,
  `studentFullname` varchar(255) NOT NULL,
  `homework_ClassID` int(255) NOT NULL,
  `homework_answer` longtext NOT NULL,
  `updated_at` datetime(6) DEFAULT NULL ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `completed_homework`
--

INSERT INTO `completed_homework` (`homework_id`, `homework_title`, `homework_description`, `homework_teacher`, `homework_subject`, `studentFullname`, `homework_ClassID`, `homework_answer`, `updated_at`) VALUES
(8, 'Format Gjeometrike', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam non pulvinar nibh, at pellentesque erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc tincidunt laoreet sem, aliquam volutpat odio interdum ', 0, 'Biology', 'Labinot Tahiri', 24, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam non pulvinar nibh, at pellentesque erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc tincidunt laoreet sem, aliquam volutpat odio interdum quis. Praesent ut metus suscipit, hendrerit odio quis, ornare libero. Pellentesque porttitor mattis lacus. Ut purus quam, hendrerit quis gravida ac, consequat facilisis mi. Nunc accumsan enim ac tempus viverra. Suspendisse potenti. Donec rutrum nunc id augue tincidunt tempus. Nulla condimentum lorem eu congue imperdiet', NULL),
(9, 'Format Gjeometrike', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam non pulvinar nibh, at pellentesque erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc tincidunt laoreet sem, aliquam volutpat odio interdum ', 0, 'Biology', 'Labinot Tahiri', 24, 'Pergjigja eshte 123', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `grade_value` int(11) NOT NULL,
  `grade_percentage` int(255) NOT NULL,
  `grade_date` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`grade_id`, `student_id`, `subject`, `teacher_id`, `class_id`, `school_id`, `grade_value`, `grade_percentage`, `grade_date`) VALUES
(8, 3, 'Literature', 8, 24, 5, 5, 99, '2023-05-11 21:34:44.000000'),
(9, 3, 'Biology', 2, 24, 5, 10, 78, '2023-05-12 07:32:19.000000'),
(10, 3, 'Mathematics', 2, 24, 5, 3, 85, '2023-09-01 17:46:23.000000');

-- --------------------------------------------------------

--
-- Table structure for table `homework`
--

CREATE TABLE `homework` (
  `homework_id` int(255) NOT NULL,
  `homework_title` varchar(255) NOT NULL,
  `homework_description` longtext NOT NULL,
  `homework_teacher` varchar(255) NOT NULL,
  `homework_subject` varchar(255) NOT NULL,
  `homework_ClassID` varchar(255) NOT NULL,
  `homework_file` longblob DEFAULT NULL,
  `created_at` datetime(6) NOT NULL,
  `due_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `homework`
--

INSERT INTO `homework` (`homework_id`, `homework_title`, `homework_description`, `homework_teacher`, `homework_subject`, `homework_ClassID`, `homework_file`, `created_at`, `due_at`) VALUES
(26, 'Format Gjeometrike', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam non pulvinar nibh, at pellentesque erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc tincidunt laoreet sem, aliquam volutpat odio interdum quis. Praesent ut metus suscipit, hendrerit odio quis, ornare libero. Pellentesque porttitor mattis lacus. Ut purus quam, hendrerit quis gravida ac, consequat facilisis mi. Nunc accumsan enim ac tempus viverra. Suspendisse potenti. Donec rutrum nunc id augue tincidunt tempus. Nulla condimentum lorem eu congue imperdiet', '2', 'Biology', '24', NULL, '2023-05-11 23:51:00.000000', '2023-05-18 21:51:00.000000'),
(27, 'Detyra Shtepie', 'Faqe 23 deri ne faqe 27', '2', 'Biology', '27', NULL, '2023-06-05 13:07:01.000000', '2023-06-12 11:07:01.000000'),
(28, 'Detyrat e Shtepise', 'Sot kemi detyra faqe 12 deri ne faqe 13', '2', 'Mathematics', '24', NULL, '2023-06-13 13:34:47.000000', '2023-06-20 11:34:47.000000'),
(29, 'Detyra Test', 'Detyra shtepie kemi prej faqe 12-15', '2', 'Mathematics', '24', NULL, '2023-06-13 13:48:06.000000', '2023-06-20 11:48:06.000000'),
(30, 'Detyra Test', 'Detyre Shtepie nga faqe 12 deri ne 15', '2', 'Mathematics', '24', NULL, '2023-06-13 19:12:06.000000', '2023-06-20 17:12:06.000000');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `parent_id` int(255) NOT NULL,
  `parent_name` varchar(255) NOT NULL,
  `parent_surname` varchar(255) NOT NULL,
  `parent_email` varchar(255) NOT NULL,
  `parent_password` varchar(255) NOT NULL,
  `parent_identifier` varchar(255) NOT NULL,
  `created_at` datetime(6) NOT NULL,
  `updated_at` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000' ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`parent_id`, `parent_name`, `parent_surname`, `parent_email`, `parent_password`, `parent_identifier`, `created_at`, `updated_at`) VALUES
(2, 'John', 'Big', 'johnbig@example.com', '$2y$10$eO3LbXieH21AcWtlSpioV.22vgB12pI9zApRLjNdQuiFL8ci86rGm', '3gTh8zla0U', '2023-05-11 23:01:39.000000', '0000-00-00 00:00:00.000000'),
(3, 'Boby', 'Brown', 'bobybrown@gmail.com', '$2y$10$7337ZKbnpZ1ZbMj1UI0AS.vFXIGugYyMkQsSTC8SxFwrvYuPj7Mb.', 'aKE3Q3sMPI', '2023-05-23 14:50:44.000000', '0000-00-00 00:00:00.000000');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `school_id` int(255) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `school_email` varchar(255) NOT NULL,
  `school_password` varchar(255) NOT NULL,
  `school_studentCode` varchar(255) NOT NULL,
  `school_teacherCode` varchar(255) NOT NULL,
  `created_at` datetime(6) NOT NULL,
  `updated_at` datetime(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000' ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`school_id`, `school_name`, `school_email`, `school_password`, `school_studentCode`, `school_teacherCode`, `created_at`, `updated_at`) VALUES
(5, 'Shkolla Digjitale', 'shkolladigjitale@example.com', '$2y$10$O/m1f84iR0FoDgvZNfP39.G6zMCNCBG1DqMDIhfUDlkRDtl1t6cBG', 'T43U9r0dyi', 'T43TM4FcVL', '2023-04-23 12:54:29.000000', '0000-00-00 00:00:00.000000');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(255) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_surname` varchar(255) NOT NULL,
  `student_email` varchar(255) NOT NULL,
  `student_password` varchar(255) NOT NULL,
  `student_birthday` varchar(255) NOT NULL,
  `student_parentCode` varchar(255) NOT NULL,
  `student_class` varchar(255) DEFAULT NULL,
  `school_id` varchar(255) NOT NULL,
  `created_at` datetime(6) NOT NULL,
  `updated_at` datetime(6) DEFAULT NULL ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `student_name`, `student_surname`, `student_email`, `student_password`, `student_birthday`, `student_parentCode`, `student_class`, `school_id`, `created_at`, `updated_at`) VALUES
(3, 'Charlie', 'White', 'charliewhite@example.com', '$2y$10$Gnfp7YUiwm8BMypjHQyeXeXyneAwo8Zp/zLd/H8l.HWnC.mXydVWq', '2011-06-11', '3gTh8zla0U', '24', '5', '2023-05-11 22:56:46.000000', '2023-05-23 15:11:28.782605'),
(4, 'Lily', 'Parker', 'lilyparker@example.com', '$2y$10$zuOX9VtjYS1R8ApGefmC7.RzwEl0LmvJUlCF5tE1XVGJuf537f2Gq', '2023-05-11', '', '24', '5', '2023-05-11 22:57:35.000000', '2023-05-11 23:36:35.084359'),
(5, 'Oliver', 'Green', 'olivergreen@example.com', '$2y$10$iSA9YkuLTaqPhsTsrokWCOoXF4xF.bM88EU0FzWIs6DQeJhvkjKdK', '1993-09-24', '', '24', '5', '2023-05-11 22:58:25.000000', '2023-05-11 23:37:18.754806'),
(6, 'Labinot', 'Tahiri', 'labi999@example.com', '$2y$10$lrbwaN7Oi92BIoJRsuUsUewmg71DQ8og3uNaVrXPWytelffyo66bq', '2004-09-22', '', '24', '5', '2023-05-11 22:59:35.000000', '2023-05-12 09:41:23.547379'),
(7, 'Max', 'Brown', 'maxbrown@example.com', '$2y$10$cdRF7zwWnONmEzRI7v/TKuacjompKn0tU4k1vaa5j8ezMoXexoHkm', '2023-05-17', '', '24', '5', '2023-05-11 23:00:03.000000', '2023-05-11 23:37:55.468289'),
(8, 'Isabella', 'Hallerson', 'isabellahall@example.com', '$2y$10$.W60utQKh8pHXtFbQXHsWeNpBjGjzk5sY4Is0DOf6CxnL9ssqOrHi', '2023-05-16', '', '24', '5', '2023-05-11 23:00:33.000000', '2023-05-11 23:38:15.904888'),
(9, 'Noah', 'Rogers', 'noahrogers@example.com', '$2y$10$avj8q6QdNwx6MrSxLys0vu6wTB09pILPWYgzq42QFVFlfY.Zw2P9W', '2023-05-14', '', '24', '5', '2023-05-11 23:01:00.000000', '2023-05-11 23:39:23.463853'),
(10, 'John', 'Cena', 'jcna@example.com', '$2y$10$tCsXLd.SPIbXizaYGe9kx.nkdreEGBYbQkwnSbF/sYpj5Ef7.rF5i', '2023-05-21', '', '25', '5', '2023-05-11 23:01:25.000000', '2023-05-11 23:40:07.677163');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(255) NOT NULL,
  `teacher_name` varchar(255) NOT NULL,
  `teacher_surname` varchar(255) NOT NULL,
  `teacher_email` varchar(255) NOT NULL,
  `teacher_password` varchar(255) NOT NULL,
  `teacher_subject` varchar(255) NOT NULL,
  `teacher_gender` varchar(255) DEFAULT NULL,
  `school_id` varchar(255) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT NULL,
  `updated_at` timestamp(6) NULL DEFAULT NULL ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `teacher_name`, `teacher_surname`, `teacher_email`, `teacher_password`, `teacher_subject`, `teacher_gender`, `school_id`, `created_at`, `updated_at`) VALUES
(2, 'Lirik', 'Rexhepi', 'lirikrexhepi@example.com', '$2y$10$.ZEhgS5PcJFVjEqJ3wxiLu3YEe5I2bz8DiJQTNNn1Td5iCClElabe', 'Mathematics', NULL, '5', '2023-04-23 12:01:41.000000', '2023-06-05 11:08:40.051515'),
(8, 'John', 'Smith', 'johnsmith@example.com', '$2y$10$JpUQb8Ii4UcrpCIBgOBCyuwzPkz1YZ0m/UETO1PkAexwfnzWFmDHW', 'Literature', NULL, '5', '2023-05-11 20:46:25.000000', NULL),
(9, 'Jenna', 'Davis', 'jennadavis@example.com', '$2y$10$NON2GJnH1KeRUrdz.8pJWey9NBPuxQzSfIJwW4Iq7ZDi8wv1kuW5i', 'English', NULL, '5', '2023-05-11 20:46:51.000000', NULL),
(10, 'David', 'Brown', 'davidbrown@example.com', '$2y$10$MtX2zRXS2Q/qskhTcNyQDeWSa1dloeqJN1i1ryKk567zVRuZr1iHW', 'Biology', NULL, '5', '2023-05-11 20:47:26.000000', NULL),
(11, 'Kevin', 'Hart', 'kevinhart@example.com', '$2y$10$sl8JJDPLQJXWo73MFLuxcOaD7zCQB0a15GzulZjOZgI5Bdk6paTX6', 'Physics', NULL, '5', '2023-05-11 20:48:43.000000', '2023-05-11 20:50:11.575810'),
(12, 'Cristiano', 'Ronaldo', 'cristiano7@example.com', '$2y$10$wI5AS/m3hRGASAs0PwaRGOmg31q9vK5DwTe16bdokiGZbdiuMnpJ2', 'PE', NULL, '', '2023-05-11 20:49:16.000000', '2023-06-05 11:08:48.541139'),
(13, 'Lionel', 'Messi', 'leomessi@example.com', '$2y$10$ycLn24T0JSfSN4z0v8RXdeuAFCUxIv/pvWydx7Q/d0BD4mWdEBhl6', 'Music', NULL, '', '2023-05-11 20:49:42.000000', '2023-06-05 11:08:46.342676'),
(14, 'Mathew', 'Wilson', 'matthewwilson@example.com', '$2y$10$osQeMmVYgl2cLUlMHfwiC.M8uAp.CzEOKTLD7wuI99DZW27iw1UiO', 'Mathematics', NULL, '', '2023-05-11 20:51:05.000000', '2023-06-05 11:08:42.984566');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `class_subjects`
--
ALTER TABLE `class_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `completed_homework`
--
ALTER TABLE `completed_homework`
  ADD PRIMARY KEY (`homework_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`);

--
-- Indexes for table `homework`
--
ALTER TABLE `homework`
  ADD PRIMARY KEY (`homework_id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`parent_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`school_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `class_subjects`
--
ALTER TABLE `class_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `completed_homework`
--
ALTER TABLE `completed_homework`
  MODIFY `homework_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `homework`
--
ALTER TABLE `homework`
  MODIFY `homework_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `parent_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `school_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
