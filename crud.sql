-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Jan 2024 pada 06.25
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
  `nama` varchar(50) NOT NULL,
  `sekolah` varchar(50) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `no_hp` char(13) NOT NULL,
  `alamat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `peserta`
--

INSERT INTO `peserta` (`id_peserta`, `nama`, `sekolah`, `jurusan`, `no_hp`, `alamat`) VALUES
(15, 'aa', 'aa', 'aa', 'aa', 'aa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_login`
--

CREATE TABLE `tb_login` (
  `username` varchar(255) NOT NULL,
  `password` varchar(225) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `role` enum('users','admin','operator') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_login`
--

INSERT INTO `tb_login` (`username`, `password`, `reset_token`, `reset_token_expires`, `role`) VALUES
('admin', '186b9c4e15d0dfece1765c0a5cfb8e33', 'd3b8bb48a97db04f1399878aae24f247e1457b58e40bd3485f7e858631e27e2c', '2024-01-10 10:13:26', 'admin'),
('user', '24c9e15e52afc47c225b757e7bee1f9d', '184735764d3802d27323bc3b7f61a2ea70618ea86a07d8e31bef149f57dadf9f', '2024-01-10 11:12:15', 'users'),
('user10', '990d67a9f94696b1abe2dccf06900322', '', NULL, 'users'),
('user2', '7e58d63b60197ceb55a1c487989a3720', '', NULL, 'users');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_login_bc`
--

CREATE TABLE `tb_login_bc` (
  `username` varchar(255) NOT NULL,
  `phone_number` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `role` enum('users') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_login_bc`
--

INSERT INTO `tb_login_bc` (`username`, `phone_number`, `email`, `password`, `reset_token`, `reset_token_expires`, `role`) VALUES
('123', 89123, 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '', NULL, 'users'),
('admin', 8912344, 'mccalister2306@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '47a86a03c83328bf24f804c7c624dfc4cddb5a51a7d270539574879446816a54', '2024-01-10 13:24:42', 'users'),
('bang al', 182391, 'hiatushiatusx@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', NULL, 'users'),
('user', 81293124, 'ardiansyah3151@gmail.com', '24c9e15e52afc47c225b757e7bee1f9d', '', NULL, 'users');

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
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id_peserta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
