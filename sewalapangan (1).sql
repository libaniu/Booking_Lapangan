-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Okt 2023 pada 13.33
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sewalapangan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `formsewa`
--

CREATE TABLE `formsewa` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `id_lapangan` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `formsewa`
--

INSERT INTO `formsewa` (`id`, `nama`, `id_lapangan`, `tanggal`, `jam_mulai`, `jam_selesai`) VALUES
(8, 'ridho', 1, '2023-08-21', '10:00:00', '11:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lapangan`
--

CREATE TABLE `lapangan` (
  `id_lapangan` int(11) NOT NULL,
  `nama_lapangan` varchar(50) NOT NULL,
  `harga_sewa` int(50) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lapangan`
--

INSERT INTO `lapangan` (`id_lapangan`, `nama_lapangan`, `harga_sewa`, `gambar`) VALUES
(1, 'lapangan 1', 120000, 'img/awk.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `usertype` varchar(50) NOT NULL DEFAULT 'user',
  `email` varchar(50) NOT NULL,
  `notelp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `usertype`, `email`, `notelp`) VALUES
(1, 'admin', 'admin', '1234', 'admin', 'admin@gmail.com', '01234567'),
(7, 'ridho', 'ridho7', '1234', 'user', '', '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `formsewa`
--
ALTER TABLE `formsewa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lapangan` (`id_lapangan`);

--
-- Indeks untuk tabel `lapangan`
--
ALTER TABLE `lapangan`
  ADD PRIMARY KEY (`id_lapangan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `formsewa`
--
ALTER TABLE `formsewa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `lapangan`
--
ALTER TABLE `lapangan`
  MODIFY `id_lapangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
