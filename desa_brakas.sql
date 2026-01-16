-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 15, 2026 at 07:58 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `desa_brakas`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama`) VALUES
(1, 'admin', '$2y$10$BLp3OYl8.4IjPk5IFP6PZ.86QbU1D6cmwzdDKvNuMy1LpHChWob0y', 'Administrator Desa');

-- --------------------------------------------------------

--
-- Table structure for table `bagan_desa`
--

CREATE TABLE `bagan_desa` (
  `id` int NOT NULL,
  `judul` varchar(150) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tipe` enum('pemdes','bpd') DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bagan_desa`
--

INSERT INTO `bagan_desa` (`id`, `judul`, `gambar`, `tipe`, `status`) VALUES
(1, 'Struktur Organisasi Pemerintahan Desa', '1768457680_hero-bg.jpeg', 'pemdes', 'aktif'),
(2, 'Struktur Organisasi Badan Permusyawaratan Desa', '1768457685_hero-bg.jpeg', 'bpd', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `isi` text,
  `gambar` varchar(255) DEFAULT NULL,
  `penulis` varchar(100) DEFAULT NULL,
  `dilihat` int DEFAULT '0',
  `tanggal` date DEFAULT NULL,
  `status` enum('publish','draft') DEFAULT 'publish'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `isi`, `gambar`, `penulis`, `dilihat`, `tanggal`, `status`) VALUES
(3, 'laptop dari brakas dajah', 'asdasdastesttt', 'hero-bg.jpeg', 'Administrator', 0, '2026-01-15', 'publish'),
(4, 'hp dari brakas dajah modung', 'asdasdasd testtt', 'abdulrohman.jpeg', 'Administrator', 0, '2026-01-15', 'publish');

-- --------------------------------------------------------

--
-- Table structure for table `penduduk`
--

CREATE TABLE `penduduk` (
  `id` int NOT NULL,
  `total_penduduk` int NOT NULL,
  `kepala_keluarga` int NOT NULL,
  `perempuan` int NOT NULL,
  `laki_laki` int NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penduduk`
--

INSERT INTO `penduduk` (`id`, `total_penduduk`, `kepala_keluarga`, `perempuan`, `laki_laki`, `updated_at`) VALUES
(1, 1161, 309, 554, 607, '2026-01-15 07:12:21');

-- --------------------------------------------------------

--
-- Table structure for table `sambutan`
--

CREATE TABLE `sambutan` (
  `id` int NOT NULL,
  `nama_kades` varchar(100) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `isi` text,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sambutan`
--

INSERT INTO `sambutan` (`id`, `nama_kades`, `jabatan`, `foto`, `isi`, `status`, `created_at`) VALUES
(1, 'BAHRUDIN', 'Kepala Desa Brakas Dejeh', '1768435417_logonew.png', '<p data-path-to-node=\"3\"><strong data-path-to-node=\"3\" data-index-in-node=\"0\">Assalamualaikum Warahmatullahi Wabarakatuh,</strong> <strong data-path-to-node=\"3\" data-index-in-node=\"45\">Salam Sejahtera bagi kita semua,</strong></p>\r\n<p data-path-to-node=\"4,0\">Website ini hadir sebagai wujud transformasi <strong data-path-to-node=\"4,0\" data-index-in-node=\"46\">Desa Brakas Dejeh</strong> dalam mengadopsi teknologi informasi dan komunikasi yang terintegrasi. Melalui platform ini, kami berkomitmen meningkatkan keterbukaan informasi publik, kualitas pelayanan, serta penguatan ekonomi desa.</p>\r\n<p data-path-to-node=\"4,1\">Fokus utama kami adalah mewujudkan Brakas Dejeh sebagai <strong data-path-to-node=\"4,1\" data-index-in-node=\"56\">Desa Wisata berkelanjutan</strong> yang adaptif terhadap perubahan iklim serta mampu menjadi desa yang mandiri. Kami menyampaikan apresiasi dan terima kasih yang mendalam kepada semua pihak yang telah berkontribusi&mdash;baik tenaga, pikiran, maupun doa&mdash;demi kemajuan desa tercinta. Mari bersama kita melangkah menuju masa depan yang lebih baik.</p>\r\n<p data-path-to-node=\"13\"><strong data-path-to-node=\"13\" data-index-in-node=\"0\">Wassalamualaikum Warahmatullahi Wabarakatuh.</strong></p>', 'aktif', '2026-01-14 10:13:09');

-- --------------------------------------------------------

--
-- Table structure for table `sejarah_desa`
--

CREATE TABLE `sejarah_desa` (
  `id` int NOT NULL,
  `judul` varchar(150) DEFAULT 'Sejarah Desa',
  `foto` varchar(255) DEFAULT NULL,
  `isi` text,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sejarah_desa`
--

INSERT INTO `sejarah_desa` (`id`, `judul`, `foto`, `isi`, `status`, `updated_at`) VALUES
(1, 'Sejarah Desa', '1768458795_abdulrohman.jpeg', '<p data-start=\"293\" data-end=\"751\">Desa <strong data-start=\"298\" data-end=\"314\">Brakas Dajah</strong> merupakan salah satu desa yang telah ada sejak lama dan tumbuh dari kehidupan masyarakat agraris yang sederhana. Pada awal berdirinya, Desa Brakas Dajah merupakan wilayah pemukiman kecil yang dihuni oleh beberapa keluarga yang membuka lahan untuk pertanian dan tempat tinggal. Nama <em data-start=\"597\" data-end=\"611\">Brakas Dajah</em> dipercaya berasal dari bahasa daerah setempat yang memiliki makna khusus, mencerminkan kondisi alam dan kehidupan masyarakat pada masa itu.</p>\r\n<p data-start=\"753\" data-end=\"1102\">Seiring berjalannya waktu, jumlah penduduk Desa Brakas Dajah terus bertambah. Kehidupan sosial masyarakat berkembang dengan kuatnya nilai gotong royong, kebersamaan, dan adat istiadat yang diwariskan secara turun-temurun. Pertanian menjadi mata pencaharian utama masyarakat, yang kemudian didukung oleh sektor lain seiring dengan perkembangan zaman.</p>\r\n<p data-start=\"1104\" data-end=\"1459\">Dalam perjalanannya, Desa Brakas Dajah mengalami berbagai perubahan, baik dari segi pemerintahan, infrastruktur, maupun kehidupan sosial budaya. Meskipun demikian, masyarakat tetap menjaga tradisi dan kearifan lokal sebagai identitas desa. Semangat persatuan dan kerja sama menjadi fondasi utama dalam membangun desa menuju kehidupan yang lebih sejahtera.</p>\r\n<p data-start=\"1461\" data-end=\"1697\">Hingga saat ini, Desa Brakas Dajah terus berbenah dan berkembang, dengan tetap menjunjung tinggi nilai-nilai luhur yang telah diwariskan oleh para leluhur, sebagai bagian dari upaya mewujudkan desa yang maju, mandiri, dan berdaya saing.</p>', 'aktif', '2026-01-15 06:33:58');

-- --------------------------------------------------------

--
-- Table structure for table `sotk`
--

CREATE TABLE `sotk` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `urutan` int DEFAULT '0',
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sotk`
--

INSERT INTO `sotk` (`id`, `nama`, `jabatan`, `foto`, `urutan`, `status`, `created_at`) VALUES
(2, 'BAHRUDIN', 'Kepala Desa', '1768455468_abdulrohman.jpeg', 1, 'aktif', '2026-01-15 05:37:48');

-- --------------------------------------------------------

--
-- Table structure for table `visi_misi`
--

CREATE TABLE `visi_misi` (
  `id` int NOT NULL,
  `visi` text NOT NULL,
  `misi` text NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `visi_misi`
--

INSERT INTO `visi_misi` (`id`, `visi`, `misi`, `status`) VALUES
(1, '<div>\"Desa Kersik sebagai Desa Wisata yang mampu mengelolah potensi Desa dan pembangunan berkelanjutan untuk mewujudkan masyarakat yang sejahtera\"</div>', '<ol>\r\n<li>Mewujudkan tata kelola pemerintahan yang baik</li>\r\n<li>Mengembangkan kegiatan keagamaan</li>\r\n<li>Mengembangkan teknologi informasi</li>\r\n<li>Pembangunan infrastruktur, sarana dan prasarana</li>\r\n</ol>', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bagan_desa`
--
ALTER TABLE `bagan_desa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipe` (`tipe`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penduduk`
--
ALTER TABLE `penduduk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sambutan`
--
ALTER TABLE `sambutan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sejarah_desa`
--
ALTER TABLE `sejarah_desa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sotk`
--
ALTER TABLE `sotk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visi_misi`
--
ALTER TABLE `visi_misi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bagan_desa`
--
ALTER TABLE `bagan_desa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `penduduk`
--
ALTER TABLE `penduduk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sambutan`
--
ALTER TABLE `sambutan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sejarah_desa`
--
ALTER TABLE `sejarah_desa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sotk`
--
ALTER TABLE `sotk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `visi_misi`
--
ALTER TABLE `visi_misi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
