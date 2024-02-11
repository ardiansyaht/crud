-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Feb 2024 pada 12.42
-- Versi server: 10.4.22-MariaDB-log
-- Versi PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crud`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `peserta`
--

CREATE TABLE `peserta` (
  `id_peserta` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `sekolah` varchar(50) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `bidang` enum('web-development','data-science','full-stack-development','mobile-app-development','cyber-security','devops','ui-ux-design','game-development') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `peserta`
--

INSERT INTO `peserta` (`id_peserta`, `email`, `nama`, `sekolah`, `jurusan`, `no_hp`, `alamat`, `bidang`) VALUES
(108, 'antnjg2306@gmail.com', 'bang al', 'awda', 'SITU BAGENDIT', '0819123881', 'aadaw', 'data-science'),
(109, 'ardiansyah3151@gmail.com', 'awda', 'awda', 'awda', '0819123881', 'aaa', 'web-development');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_login`
--

CREATE TABLE `tb_login` (
  `username` varchar(255) NOT NULL,
  `password` varchar(225) NOT NULL,
  `failed_login_attempts` int(11) DEFAULT NULL,
  `is_locked` tinyint(4) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `role` enum('users','admin','operator') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_login`
--

INSERT INTO `tb_login` (`username`, `password`, `failed_login_attempts`, `is_locked`, `reset_token`, `reset_token_expires`, `role`) VALUES
('admin', '$2y$10$USI20JOsF4qDlR5TZL3GY.9vDxDEc/khU5jxJ5HEnZBbuM4tuadHW', 0, 0, 'bd3ba4ca0c17ed5c84530c9eb9a5abae0477a07037e6b7ef0a3ec663a94b3895', '2024-01-11 22:38:35', 'admin'),
('ardiansyah', '$2y$10$KWxJiAIdMnu0foQvOGndM.WAnk8VlJXsa3nU8KrPP.p8p5fpQTH86', NULL, 0, '', NULL, 'users'),
('awda', '$2y$10$4pzkWJPTZxxXO5/ldCLz/udWJ89Fyx3uyr3ps9e5MY1/4olwyQHU.', NULL, 0, '', NULL, 'users'),
('user', '21232f297a57a5a743894a0e4a801fc3', 10, 1, '448e0f3c181d4e36411f01fdd8291d8e471f93af374176b392c2a1676640bcac', '2024-01-10 23:43:23', 'users'),
('user10', '990d67a9f94696b1abe2dccf06900322', 2, 0, '', NULL, 'users'),
('user2', '7e58d63b60197ceb55a1c487989a3720', 0, 0, '', NULL, 'users'),
('user3', '92877af70a45fd6a2ed7fe81e1236b78', 5, 0, '', NULL, 'users'),
('user4', '$2y$10$FyWfhu5TDM7dXhZHiUhJ1uvRXEBkNc8NrO.30zKoOpq0R48rA1dES', 0, 0, '', NULL, 'users'),
('user5', '$2y$10$DlgOnxpyP/CZmEfjlPP3COpy9ADqr9poakHY8FRvJWzCNOjQ2.uLu', 0, 0, '', NULL, 'users'),
('user80', '$2y$10$Lv7vtf7dEEj5O8qbb030MOWICxovKkhSUgGSF.DzyaXBsEUKi8itq', 0, 0, '', NULL, 'users'),
('user99', '$2y$10$8pVTDxtm.kAnc3hlAJ7/y.J6Dgr90irOelD7lj09sklk3vUHA1Wt6', 0, 0, '', NULL, 'users');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_login_bc`
--

CREATE TABLE `tb_login_bc` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `registration_time` datetime DEFAULT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expiration` datetime DEFAULT NULL,
  `status` enum('notverified','verified') NOT NULL,
  `failed_login_attempts` int(11) NOT NULL,
  `is_locked` tinyint(4) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `role` enum('users','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_login_bc`
--

INSERT INTO `tb_login_bc` (`id`, `username`, `phone_number`, `email`, `name`, `company`, `password`, `photo_path`, `registration_time`, `otp_code`, `otp_expiration`, `status`, `failed_login_attempts`, `is_locked`, `reset_token`, `reset_token_expires`, `role`) VALUES
(90, 'tarankaa', '081912388170', 'ardiansyah3151@gmail.com', 'si cantik', 'my home', '$2y$10$XO5MzSbmTjLM.rn0Rpu8a.JE6rmSGJMkiKu5lM5/xh8iLhyGJ3M8e', NULL, '2024-02-02 13:04:55', '928046', '2024-02-02 13:06:55', 'verified', 0, 0, '', NULL, 'admin'),
(91, 'antnjg', '0891238123', 'antnjg2306@gmail.com', NULL, NULL, '$2y$10$.xMSSp9yfuSk6aZa3bkoOel9T5PfikQxORu/8GLsIffNvHC6g2nH.', NULL, '2024-02-07 20:52:22', '533167', '2024-02-07 20:54:22', 'verified', 0, 0, '', NULL, 'users');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`id_peserta`);

--
-- Indeks untuk tabel `tb_login`
--
ALTER TABLE `tb_login`
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `tb_login_bc`
--
ALTER TABLE `tb_login_bc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id_peserta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT untuk tabel `tb_login_bc`
--
ALTER TABLE `tb_login_bc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
