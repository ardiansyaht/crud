-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 28, 2024 at 07:28 AM
-- Server version: 10.5.20-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id21815661_techforge`
--

-- --------------------------------------------------------

--
-- Table structure for table `peserta`
--

CREATE TABLE `peserta` (
  `id_peserta` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `sekolah` varchar(255) NOT NULL,
  `jurusan` varchar(255) NOT NULL,
  `no_hp` char(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `bidang` enum('web-development','data-science','full-stack-development','mobile-app-development','cyber-security','devops','ui-ux-design','game-development') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `peserta`
--

INSERT INTO `peserta` (`id_peserta`, `email`, `nama`, `sekolah`, `jurusan`, `no_hp`, `alamat`, `bidang`) VALUES
(87, 'hiatushiatusx@gmail.com', 'sus', '124124', 'INFORMATIKA', '0819123881', 'bang', 'data-science'),
(93, 'antnjg2306@gmail.com', 'ardi', '222', '666', '222', '12414', 'devops'),
(95, 'mccalister2306@gmail.com', 'b', 'ba', 'b', '124124124', 'b', 'web-development');

-- --------------------------------------------------------

--
-- Table structure for table `tb_login`
--

CREATE TABLE `tb_login` (
  `username` varchar(255) NOT NULL,
  `password` varchar(225) NOT NULL,
  `failed_login_attempts` int(11) DEFAULT NULL,
  `is_locked` int(11) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `role` enum('users','admin','operator') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_login`
--

INSERT INTO `tb_login` (`username`, `password`, `failed_login_attempts`, `is_locked`, `reset_token`, `reset_token_expires`, `role`) VALUES
('7hejejeeigivicvivihogihovivivigivvivi', '$2y$10$nzjC2sy5g3Si89V3Impga.RQlaY4m12QyckN5O5ANzh6h5diBGb7a', NULL, 0, 'some_reset_token_value', NULL, 'users'),
('budigaming', '$2y$10$hnbcNRIz5nazIOv/6HMGLO9K6teM2qAVmZfkfEQoEpNYXDJX0BhJS', 0, 0, 'some_reset_token_value', NULL, 'users'),
('techforgeacademy1234567890', '$2y$10$USI20JOsF4qDlR5TZL3GY.9vDxDEc/khU5jxJ5HEnZBbuM4tuadHW', 0, 0, 'bd3ba4ca0c17ed5c84530c9eb9a5abae0477a07037e6b7ef0a3ec663a94b3895', '2024-01-11 22:38:35', 'admin'),
('user', '21232f297a57a5a743894a0e4a801fc3', 10, 1, '448e0f3c181d4e36411f01fdd8291d8e471f93af374176b392c2a1676640bcac', '2024-01-10 23:43:23', 'users'),
('user10', '990d67a9f94696b1abe2dccf06900322', 2, 0, '', NULL, 'users'),
('user2', '7e58d63b60197ceb55a1c487989a3720', 0, 0, '', NULL, 'users'),
('user3', '92877af70a45fd6a2ed7fe81e1236b78', 5, 0, '', NULL, 'users'),
('user90', '$2y$10$CHfWIPWO4Z1eu3pXpSf2iOVIS77lZzwO5/R7sULQKARQY6puVbleS', 0, 0, 'f9d1f00473f47f3134e74963f80fbfa63b1057f67aa6a2ae2fc3032780b5039d', '2024-01-25 07:42:47', 'users');

-- --------------------------------------------------------

--
-- Table structure for table `tb_login_bc`
--

CREATE TABLE `tb_login_bc` (
  `id` int(11) NOT NULL,
  `auto_username` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registration_time` datetime DEFAULT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expiration` datetime DEFAULT NULL,
  `status` enum('notverified','verified') NOT NULL,
  `failed_login_attempts` int(11) DEFAULT NULL,
  `is_locked` tinyint(4) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `role` enum('users') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_login_bc`
--

INSERT INTO `tb_login_bc` (`id`, `auto_username`, `phone_number`, `email`, `password`, `registration_time`, `otp_code`, `otp_expiration`, `status`, `failed_login_attempts`, `is_locked`, `reset_token`, `reset_token_expires`, `role`) VALUES
(62, 'awdaw', '103131', 'hiatushiatusx@gmail.com', '$2y$10$UNehAOuYrw.jRPwuRQMGBOb/fAOWmVW1lyt5jmNuasLzp7wwACmcW', '2024-01-12 11:22:32', '412743', '2024-01-12 11:32:32', 'verified', 0, 0, '', NULL, 'users'),
(82, 'ardiansyah', '9', 'ardiansyah3151@gmail.com', '$2y$10$DKjDiKvVlo/6SfLGvnbC/O9hGGoJFpUMdsKaWcE8dLOZBHFLoQ6OC', '2024-01-25 18:36:23', '213947', '2024-01-25 18:38:23', 'verified', NULL, NULL, NULL, NULL, 'users'),
(90, 'budigaming', '081912388170', 'mccalister2306@gmail.com', '$2y$10$02AFo/MA7.dohWeLm.dXUuU47csGEM2AjE5XfkxDJ9btuLjQ1x7pG', '2024-01-27 20:57:40', '844268', '2024-01-27 23:21:29', 'verified', 0, NULL, NULL, NULL, 'users');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`id_peserta`);

--
-- Indexes for table `tb_login`
--
ALTER TABLE `tb_login`
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tb_login_bc`
--
ALTER TABLE `tb_login_bc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id_peserta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `tb_login_bc`
--
ALTER TABLE `tb_login_bc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
