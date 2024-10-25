-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 12:39 PM
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
-- Database: `todo_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `to_do_lists`
--

CREATE TABLE `to_do_lists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` enum('incomplete','complete') DEFAULT 'incomplete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `to_do_lists`
--

INSERT INTO `to_do_lists` (`id`, `user_id`, `title`, `description`, `status`) VALUES
(3, 11, '123', '123', 'complete'),
(4, 11, 'Belanja', 'Belanja Di Indomaret', 'incomplete'),
(5, 12, '123', '123', 'incomplete'),
(6, 12, '234', '234', 'incomplete'),
(7, 12, '345', '345', 'incomplete'),
(8, 12, '123', '123', 'complete');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `recovery_question` varchar(255) DEFAULT NULL,
  `recovery_answer` varchar(255) DEFAULT NULL,
  `recovery_question_2` varchar(255) DEFAULT NULL,
  `recovery_answer_2` varchar(255) DEFAULT NULL,
  `failed_login_attempts` int(11) DEFAULT 0,
  `lockout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `recovery_question`, `recovery_answer`, `recovery_question_2`, `recovery_answer_2`, `failed_login_attempts`, `lockout_time`) VALUES
(11, '345', '345@345.com', '$2y$10$5Z.zUhor04o8.pah5My94eefokVIqKxlsQjIsWABBoTRFs7rl6/ta', 'Siapa nama gadis ibu Anda?', '$2y$10$8aPl9zlLt61ej9ixKWrMNOOlpPb1RU3t0a5JGtxJp.2xbtSZppCLO', 'Apa warna favorit Anda?', '$2y$10$rO3OQf3EDE5SJkfPwsCXde13NAoZQnfxekJMAxYHbIBz0E0u0HXrm', 0, NULL),
(12, '123', '123@123.com', '$2y$10$bGULfDSPtSQW0Zfth8QareD68XNMliN5aY5u9WVCxXRPVfeUm0BuO', 'Siapa nama gadis ibu Anda?', '$2y$10$CMnPFyeiT2MDIYfZq3VT9OclocDreuOSUaJctmaKLIGWSYHnkuplO', 'Apa warna favorit Anda?', '$2y$10$wejYXRyDVRUd6E0O8kyU6.IxIQt39LW55pKcCYTqtj.A4ogEn39W.', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `to_do_lists`
--
ALTER TABLE `to_do_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `to_do_lists`
--
ALTER TABLE `to_do_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `to_do_lists`
--
ALTER TABLE `to_do_lists`
  ADD CONSTRAINT `to_do_lists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
