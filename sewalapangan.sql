SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `formsewa` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `id_lapangan` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `total_bayar` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `formsewa` (`id`, `order_id`, `nama`, `id_lapangan`, `tanggal`, `jam_mulai`, `jam_selesai`, `total_bayar`) VALUES
(9, '1577602588', 'FAHMI NABIL KHAIRI', 1, '2024-07-24', '13:00:00', '15:00:00', 240000);

CREATE TABLE `lapangan` (
  `id_lapangan` int(11) NOT NULL,
  `nama_lapangan` varchar(50) NOT NULL,
  `harga_sewa` int(50) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `lapangan` (`id_lapangan`, `nama_lapangan`, `harga_sewa`, `gambar`) VALUES
(1, 'lapangan 1', 120000, 'img/awk.jpg');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `usertype` varchar(50) NOT NULL DEFAULT 'user',
  `email` varchar(50) NOT NULL,
  `notelp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `name`, `username`, `password`, `usertype`, `email`, `notelp`) VALUES
(1, 'admin', 'admin', '1234', 'admin', 'admin@gmail.com', '01234567'),
(8, 'Fahmi', 'libaniu', '1234', 'user', '', '');

ALTER TABLE `formsewa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lapangan` (`id_lapangan`);

ALTER TABLE `lapangan`
  ADD PRIMARY KEY (`id_lapangan`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `formsewa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `lapangan`
  MODIFY `id_lapangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;
