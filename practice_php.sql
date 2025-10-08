-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2025 at 07:26 AM
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
-- Database: `practice_php`
--

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `msgid` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` varchar(300) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `sent_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`msgid`, `sender_id`, `receiver_id`, `message`, `attachment`, `is_read`, `sent_time`) VALUES
(1, 3, 1, 'Wenzel, good morning', 'file_68e4de3c586093.29998695.jpeg', 1, '2025-10-07 09:32:44'),
(2, 1, 3, 'How are you?', 'file_68e4de5842ebe8.98560014.jpeg', 1, '2025-10-07 09:33:12'),
(3, 3, 1, '', '', 1, '2025-10-07 09:36:42'),
(4, 1, 3, 'I am fine how are you', '', 1, '2025-10-07 09:36:52'),
(5, 3, 1, 'You know the gym is about to open', '', 1, '2025-10-07 09:37:10'),
(6, 1, 3, 'kb', '', 1, '2025-10-07 09:37:16'),
(7, 3, 1, 'look into this', 'file_68e4df5737c087.31011056.jpeg', 1, '2025-10-07 09:37:27'),
(8, 3, 1, 'dekho', '', 1, '2025-10-07 09:37:30'),
(9, 2, 3, 'hello brother', '', 1, '2025-10-07 10:33:02'),
(10, 3, 2, 'hi there', '', 1, '2025-10-07 10:33:28'),
(11, 2, 3, 'I miss you', '', 1, '2025-10-07 10:35:55'),
(12, 3, 2, 'where are you ', '', 1, '2025-10-07 10:40:45'),
(13, 2, 3, 'The Gym is opened', 'file_68e4f1b6106de8.91045730.jpeg', 1, '2025-10-07 10:55:50'),
(14, 3, 2, 'oh nice join us then', '', 1, '2025-10-07 10:56:05');

-- --------------------------------------------------------

--
-- Table structure for table `register_user`
--

CREATE TABLE `register_user` (
  `regid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `status` enum('offline','online') NOT NULL DEFAULT 'offline',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register_user`
--

INSERT INTO `register_user` (`regid`, `name`, `email`, `password`, `status`, `created_at`, `updated`, `last_login`) VALUES
(1, 'Segun', 'segun@gmail.com', '$2y$10$fnPXu9yq07devd6/lCB5/e0Ry3Je8cUZVDXqgFZX6XHHlY5XJBWL6', 'offline', '2025-10-06 09:55:55', '2025-10-07 15:21:58', '2025-10-07 15:18:56'),
(2, 'Janet', 'janet@gmail.com', '$2y$10$jTmSg59eBhTpotUs3Epod.wwufMwJ6NfA6Zn88Rhxz2K2PhJDp2u.', 'offline', '2025-10-06 10:12:43', '2025-10-07 17:22:08', '2025-10-07 16:04:33'),
(3, 'Seun', 'seun@gmail.com', '$2y$10$o6tyu7/aAEX.AkOXUNrkMOaSzonfTiftAu3xMugCtVNvLNDnAJFLO', 'offline', '2025-10-06 11:35:04', '2025-10-07 17:21:58', '2025-10-07 16:10:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`msgid`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `register_user`
--
ALTER TABLE `register_user`
  ADD PRIMARY KEY (`regid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `msgid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `register_user`
--
ALTER TABLE `register_user`
  MODIFY `regid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `register_user` (`regid`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `register_user` (`regid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
