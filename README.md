# webkkn
Repository ini tempat kolaborasi pembuatan website desa brakas dajah kecamatan modung



https://kersik.desa.id/
https://www.nataibaru.desa.id/



CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `admin` (`id`, `username`, `password`, `nama`) VALUES
(1, 'admin', '$2y$10$BLp3OYl8.4IjPk5IFP6PZ.86QbU1D6cmwzdDKvNuMy1LpHChWob0y', 'Administrator Desa');
CREATE TABLE `apbdes` (
  `id` int NOT NULL,
  `tahun` year NOT NULL,
  `pendapatan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `belanja` decimal(15,2) NOT NULL DEFAULT '0.00',
  `pembiayaan_penerimaan` decimal(15,2) DEFAULT '0.00',
  `pembiayaan_pengeluaran` decimal(15,2) DEFAULT '0.00',
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `apbdes` (`id`, `tahun`, `pendapatan`, `belanja`, `pembiayaan_penerimaan`, `pembiayaan_pengeluaran`, `status`, `created_at`, `updated_at`) VALUES
(1, 2026, '412887408.00', '100000000.00', '0.00', '0.00', 'aktif', '2026-01-30 12:44:34', '2026-01-30 12:44:34');
CREATE TABLE `apbdes_belanja` (
  `id` int NOT NULL,
  `apbdes_id` int NOT NULL,
  `jenis` varchar(150) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT '0.00',
  `persentase` decimal(5,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `apbdes_belanja` (`id`, `apbdes_id`, `jenis`, `jumlah`, `persentase`, `created_at`) VALUES
(1, 1, 'Pelaksanaan Pembangunan Desa', '100000.00', '2.00', '2026-01-30 12:45:37');
CREATE TABLE `apbdes_pembiayaan` (
  `id` int NOT NULL,
  `apbdes_id` int NOT NULL,
  `jenis` enum('penerimaan','pengeluaran') NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT '0.00',
  `persentase` decimal(5,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `apbdes_pembiayaan` (`id`, `apbdes_id`, `jenis`, `jumlah`, `persentase`, `created_at`) VALUES
(1, 1, 'pengeluaran', '1000000.00', '5.00', '2026-01-30 12:45:55');
CREATE TABLE `apbdes_pendapatan` (
  `id` int NOT NULL,
  `apbdes_id` int NOT NULL,
  `jenis` varchar(100) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT '0.00',
  `persentase` decimal(5,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `apbdes_pendapatan` (`id`, `apbdes_id`, `jenis`, `jumlah`, `persentase`, `created_at`) VALUES
(1, 1, 'Pendapatan Transfer', '100000000.00', '80.00', '2026-01-30 12:45:16');
CREATE TABLE `bagan_desa` (
  `id` int NOT NULL,
  `judul` varchar(150) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tipe` enum('pemdes','bpd') DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `bagan_desa` (`id`, `judul`, `gambar`, `tipe`, `status`) VALUES
(1, 'Struktur Organisasi Pemerintahan Desa', '1768457680_hero-bg.jpeg', 'pemdes', 'aktif'),
(2, 'Struktur Organisasi Badan Permusyawaratan Desa', '1768457685_hero-bg.jpeg', 'bpd', 'aktif');
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
INSERT INTO `berita` (`id`, `judul`, `isi`, `gambar`, `penulis`, `dilihat`, `tanggal`, `status`) VALUES
(3, 'laptop dari brakas dajah', 'asdasdastesttt', 'hero-bg.jpeg', 'Administrator', 0, '2026-01-15', 'publish'),
(4, 'hp dari brakas dajah modung', 'asdasdasd testtt', 'abdulrohman.jpeg', 'Administrator', 0, '2026-01-15', 'publish'),
(8, 'asdasd', 'asdasd', 'WhatsApp Image 2026-01-20 at 20.19.04.jpeg', 'Administrator', 1, '2026-01-26', 'publish'),
(9, 'Pemkab Bangkalan Luncurkan Program Digitalisasi Layanan Publik 2033', '<p data-start=\"73\" data-end=\"423\"><strong data-start=\"73\" data-end=\"103\">Bangkalan, 3 Februari 2026</strong> &mdash; Pemerintah Kabupaten Bangkalan resmi meluncurkan program digitalisasi layanan publik sebagai bagian dari upaya meningkatkan efisiensi birokrasi dan kualitas pelayanan kepada masyarakat. Program ini ditandai dengan peresmian portal layanan terpadu yang memungkinkan warga mengakses berbagai administrasi secara daring.</p>\r\n<p data-start=\"425\" data-end=\"740\">Bupati Bangkalan menyampaikan bahwa digitalisasi menjadi langkah strategis untuk menyesuaikan pelayanan pemerintah dengan kebutuhan masyarakat modern. Melalui sistem baru ini, warga dapat mengurus dokumen kependudukan, perizinan usaha, hingga pengaduan masyarakat tanpa harus datang langsung ke kantor pemerintahan.</p>\r\n<p data-start=\"742\" data-end=\"933\">&ldquo;Transformasi digital bukan sekadar modernisasi teknologi, tetapi perubahan budaya pelayanan agar lebih cepat, transparan, dan akuntabel,&rdquo; ujar Bupati dalam sambutannya saat acara peluncuran.</p>\r\n<p data-start=\"935\" data-end=\"1194\">Kepala Dinas Komunikasi dan Informatika menambahkan bahwa sistem telah dirancang dengan standar keamanan data yang ketat. Selain itu, pemerintah daerah juga menyiapkan pusat bantuan untuk mendampingi masyarakat yang belum terbiasa menggunakan layanan digital.</p>\r\n<p data-start=\"1196\" data-end=\"1461\">Program ini diharapkan mampu memangkas waktu pengurusan administrasi hingga 50 persen serta mengurangi antrean di kantor pelayanan publik. Pemerintah juga berencana mengintegrasikan portal tersebut dengan sistem provinsi dan nasional agar layanan semakin terhubung.</p>\r\n<p data-start=\"1463\" data-end=\"1755\" data-is-last-node=\"\" data-is-only-node=\"\">Dengan peluncuran ini, Bangkalan menargetkan menjadi salah satu daerah percontohan dalam penerapan pemerintahan berbasis digital di tingkat regional. Pemerintah optimistis langkah ini akan mendorong partisipasi masyarakat sekaligus meningkatkan kepercayaan publik terhadap layanan pemerintah.</p>', 'Gemini_Generated_Image_3b9thd3b9thd3b9t-removebg-preview.png', 'Administrator', 41, '2026-01-27', 'publish'),
(10, 'berita 2', 'kajlsndkjashdkjasbndkj', 'WhatsApp Image 2026-01-20 at 20.36.06.jpeg', 'Administrator', 19, '2026-01-27', 'publish'),
(11, 'berita 3', 'asdjlasndas', 'Gemini_Generated_Image_3b9thd3b9thd3b9t-removebg-preview.png', 'Administrator', 5, '2026-01-27', 'publish'),
(12, 'berita 4', 'alskjdklas dnasjldnasd', 'Gemini_Generated_Image_3b9thd3b9thd3b9t.png', 'Administrator', 1, '2026-01-27', 'publish'),
(13, 'berita 5 jiasodjaskld jlkajsdljaslidjaisl djialsjd ialsjdilaj sldija slidjsali djliajdjiak ktia melaksdkl asdkakt ia aahamd andi zianur jiasdjia sdiasdijas diajsdiajsd asjdiajd dija dijasd aisj daj d jais ass', 'alskjdjasd jnjhsad', 'Gemini_Generated_Image_3b9thd3b9thd3b9t-removebg-preview.png', 'Administrator', 8, '2026-01-27', 'publish'),
(14, 'berita 6', 'asdasidpas duash dshadiuh aisudh asd', 'WhatsApp Image 2026-01-20 at 20.19.05.jpeg', 'Administrator', 1, '2026-01-27', 'publish'),
(15, 'berita 7', 'klasdu asdhajs dhajsdasjd ashdjasd', 'WhatsApp Image 2026-01-20 at 20.19.03.jpeg', 'Administrator', 1, '2026-01-27', 'publish');
CREATE TABLE `galeri` (
  `id` int NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text,
  `gambar` varchar(255) NOT NULL,
  `kategori` enum('foto_random','agenda','kegiatan') DEFAULT 'foto_random',
  `tanggal` date NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `galeri` (`id`, `judul`, `deskripsi`, `gambar`, `kategori`, `tanggal`, `status`, `created_at`) VALUES
(1, 'asdasd3qd2d32', 'acscvsfgvrgsdfsdc', '1769510700_6978972c5f3e0.jpeg', 'foto_random', '2026-01-27', 'aktif', '2026-01-27 10:45:00'),
(2, 'agenda tahlilan', 'akan diadakan di daerah rumah nya si a', '1769510827_697897abcf953.png', 'agenda', '2026-01-29', 'aktif', '2026-01-27 10:47:07');
CREATE TABLE `hero` (
  `id` int NOT NULL,
  `judul` varchar(200) DEFAULT NULL,
  `subjudul` varchar(300) DEFAULT NULL,
  `deskripsi` text,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `hero` (`id`, `judul`, `subjudul`, `deskripsi`, `gambar`, `status`, `created_at`) VALUES
(1, 'Selamat Datang', 'Website Resmi Desa Brakas Dajah', 'Sumber informasi terbaru tentang pemerintahan dan kegiatan masyarakat di Desa Brakas Dajah.', '1770120643_6981e5c3b7c47.jpeg', 'aktif', '2026-02-03 08:38:20');
CREATE TABLE `jenis_bansos` (
  `id` int NOT NULL,
  `nama_bansos` varchar(100) NOT NULL,
  `keterangan` text,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `jenis_bansos` (`id`, `nama_bansos`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BPJS PBI Ketenagakerjaan', 'BPJS Pemberi Bantuan Iuran Ketenagakerjaan', 'aktif', '2026-01-31 10:56:03', '2026-02-01 06:43:55'),
(2, 'PKH', 'Program Keluarga Harapan', 'aktif', '2026-01-31 10:56:03', '2026-02-01 06:43:55'),
(3, 'BPNT', 'Bantuan Pangan Non Tunai', 'aktif', '2026-01-31 10:56:03', '2026-02-01 06:43:55'),
(4, 'BLT 2024', 'Bantuan Langsung Tunai Tahun 2024', 'aktif', '2026-01-31 10:56:03', '2026-02-01 06:43:55'),
(5, 'PSTN', 'Program Sembako Terpadu Nasional', 'aktif', '2026-01-31 10:56:03', '2026-02-01 06:53:36');
CREATE TABLE `penduduk` (
  `id` int NOT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `alamat` text,
  `dusun` varchar(50) DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `pendidikan` varchar(50) DEFAULT NULL,
  `pekerjaan` varchar(50) DEFAULT NULL,
  `status_perkawinan` varchar(20) DEFAULT NULL,
  `status_keluarga` varchar(20) DEFAULT NULL COMMENT 'Kepala Keluarga/Anggota',
  `kk` varchar(20) DEFAULT NULL COMMENT 'Nomor KK',
  `status_penduduk` enum('hidup','meninggal','pindah','penduduk_sementara') DEFAULT 'hidup',
  `tanggal_status` date DEFAULT NULL COMMENT 'Tanggal perubahan status',
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `penduduk` (`id`, `nama`, `nik`, `updated_at`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `dusun`, `agama`, `pendidikan`, `pekerjaan`, `status_perkawinan`, `status_keluarga`, `kk`, `status_penduduk`, `tanggal_status`, `keterangan`) VALUES
(2, 'user satu', '1000000000000001', '2026-01-28 11:27:17', 'Bangkalan', '1999-02-28', 'L', 'Desa Brakas Dajah, Kecamatan Modung, Kabupaten Bangkalan, Provinsi Jawa Timur, Indonesia', 'Takabuh Tengah', 'Islam', 'SMA/SMK', 'Nelayan', 'Kawin', 'Kepala Keluarga', '1000000000000010', 'hidup', '2026-01-28', ''),
(3, 'user dua', '1000000000000002', '2026-01-28 11:35:27', 'Bangkalan', '2007-01-09', 'P', 'Desa Brakas Dajah, Kecamatan Modung, Kabupaten Bangkalan, Provinsi Jawa Timur, Indonesia.', 'Takabuh Timur', 'Islam', 'S1', 'Tidak Bekerja', 'Kawin', 'Istri', '1000000000000010', 'hidup', '2026-01-28', ''),
(4, 'user tiga', '1000000000000003', '2026-02-03 03:43:42', 'Bangkalan', '2017-02-16', 'L', 'brakas dajah', 'Takabuh Tengah', 'Islam', 'SMA/SMK', 'Pelajar/Mahasiswa', 'Belum Kawin', 'Anak', '1000000000000010', 'hidup', '2026-02-03', '');
CREATE TABLE `penerima_bansos` (
  `id` int NOT NULL,
  `id_penduduk` int DEFAULT NULL,
  `id_jenis_bansos` int NOT NULL,
  `tahun` varchar(4) NOT NULL,
  `bulan` varchar(2) DEFAULT NULL,
  `status_penerimaan` enum('diterima','ditolak','proses') DEFAULT 'proses',
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `penerima_bansos` (`id`, `id_penduduk`, `id_jenis_bansos`, `tahun`, `bulan`, `status_penerimaan`, `keterangan`, `created_at`, `updated_at`) VALUES
(2, 3, 1, '2025', '', 'diterima', '', '2026-02-01 05:36:20', '2026-02-01 06:30:34'),
(3, 4, 4, '2025', '', 'diterima', '', '2026-02-03 03:44:42', '2026-02-03 03:44:42');
CREATE TABLE `sambutan` (
  `id` int NOT NULL,
  `nama_kades` varchar(100) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `isi` text,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `sambutan` (`id`, `nama_kades`, `jabatan`, `foto`, `isi`, `status`, `created_at`) VALUES
(1, 'BAHRUDIN', 'Kepala Desa Brakas Dejeh', '1768435417_logonew.png', '<p data-path-to-node=\"3\"><strong data-path-to-node=\"3\" data-index-in-node=\"0\">Assalamualaikum Warahmatullahi Wabarakatuh,</strong> <strong data-path-to-node=\"3\" data-index-in-node=\"45\">Salam Sejahtera bagi kita semua,</strong></p>\r\n<p data-path-to-node=\"4,0\">Website ini hadir sebagai wujud transformasi <strong data-path-to-node=\"4,0\" data-index-in-node=\"46\">Desa Brakas Dejeh</strong> dalam mengadopsi teknologi informasi dan komunikasi yang terintegrasi. Melalui platform ini, kami berkomitmen meningkatkan keterbukaan informasi publik, kualitas pelayanan, serta penguatan ekonomi desa.</p>\r\n<p data-path-to-node=\"4,1\">Fokus utama kami adalah mewujudkan Brakas Dejeh sebagai <strong data-path-to-node=\"4,1\" data-index-in-node=\"56\">Desa Wisata berkelanjutan</strong> yang adaptif terhadap perubahan iklim serta mampu menjadi desa yang mandiri. Kami menyampaikan apresiasi dan terima kasih yang mendalam kepada semua pihak yang telah berkontribusi&mdash;baik tenaga, pikiran, maupun doa&mdash;demi kemajuan desa tercinta. Mari bersama kita melangkah menuju masa depan yang lebih baik.</p>\r\n<p data-path-to-node=\"13\"><strong data-path-to-node=\"13\" data-index-in-node=\"0\">Wassalamualaikum Warahmatullahi Wabarakatuh.</strong></p>', 'aktif', '2026-01-14 10:13:09');
CREATE TABLE `sejarah_desa` (
  `id` int NOT NULL,
  `judul` varchar(150) DEFAULT 'Sejarah Desa',
  `foto` varchar(255) DEFAULT NULL,
  `isi` text,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `sejarah_desa` (`id`, `judul`, `foto`, `isi`, `status`, `updated_at`) VALUES
(1, 'Sejarah Desa', '1768458795_abdulrohman.jpeg', '<p data-start=\"293\" data-end=\"751\">Desa <strong data-start=\"298\" data-end=\"314\">Brakas Dajah</strong> merupakan salah satu desa yang telah ada sejak lama dan tumbuh dari kehidupan masyarakat agraris yang sederhana. Pada awal berdirinya, Desa Brakas Dajah merupakan wilayah pemukiman kecil yang dihuni oleh beberapa keluarga yang membuka lahan untuk pertanian dan tempat tinggal. Nama <em data-start=\"597\" data-end=\"611\">Brakas Dajah</em> dipercaya berasal dari bahasa daerah setempat yang memiliki makna khusus, mencerminkan kondisi alam dan kehidupan masyarakat pada masa itu.</p>\r\n<p data-start=\"753\" data-end=\"1102\">Seiring berjalannya waktu, jumlah penduduk Desa Brakas Dajah terus bertambah. Kehidupan sosial masyarakat berkembang dengan kuatnya nilai gotong royong, kebersamaan, dan adat istiadat yang diwariskan secara turun-temurun. Pertanian menjadi mata pencaharian utama masyarakat, yang kemudian didukung oleh sektor lain seiring dengan perkembangan zaman.</p>\r\n<p data-start=\"1104\" data-end=\"1459\">Dalam perjalanannya, Desa Brakas Dajah mengalami berbagai perubahan, baik dari segi pemerintahan, infrastruktur, maupun kehidupan sosial budaya. Meskipun demikian, masyarakat tetap menjaga tradisi dan kearifan lokal sebagai identitas desa. Semangat persatuan dan kerja sama menjadi fondasi utama dalam membangun desa menuju kehidupan yang lebih sejahtera.</p>\r\n<p data-start=\"1461\" data-end=\"1697\">Hingga saat ini, Desa Brakas Dajah terus berbenah dan berkembang, dengan tetap menjunjung tinggi nilai-nilai luhur yang telah diwariskan oleh para leluhur, sebagai bagian dari upaya mewujudkan desa yang maju, mandiri, dan berdaya saing.</p>', 'aktif', '2026-01-15 06:33:58');
CREATE TABLE `sotk` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `urutan` int DEFAULT '0',
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `sotk` (`id`, `nama`, `jabatan`, `foto`, `urutan`, `status`, `created_at`) VALUES
(2, 'BAHRUDIN', 'Kepala Desa', '1768455468_abdulrohman.jpeg', 1, 'aktif', '2026-01-15 05:37:48');
CREATE TABLE `statistik_penduduk` (
  `id` int NOT NULL,
  `total_penduduk` int DEFAULT '0',
  `kepala_keluarga` int DEFAULT '0',
  `perempuan` int DEFAULT '0',
  `laki_laki` int DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `statistik_penduduk` (`id`, `total_penduduk`, `kepala_keluarga`, `perempuan`, `laki_laki`, `updated_at`) VALUES
(1, 3, 1, 1, 2, '2026-02-03 03:43:42');
CREATE TABLE `visi_misi` (
  `id` int NOT NULL,
  `visi` text NOT NULL,
  `misi` text NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `visi_misi` (`id`, `visi`, `misi`, `status`) VALUES
(1, '<div>\"Desa Kersik sebagai Desa Wisata yang mampu mengelolah potensi Desa dan pembangunan berkelanjutan untuk mewujudkan masyarakat yang sejahtera\"</div>', '<ol>\r\n<li>Mewujudkan tata kelola pemerintahan yang baik</li>\r\n<li>Mengembangkan kegiatan keagamaan</li>\r\n<li>Mengembangkan teknologi informasi</li>\r\n<li>Pembangunan infrastruktur, sarana dan prasarana</li>\r\n</ol>', 'aktif');
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);
ALTER TABLE `apbdes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tahun` (`tahun`);
ALTER TABLE `apbdes_belanja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `apbdes_id` (`apbdes_id`);
ALTER TABLE `apbdes_pembiayaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `apbdes_id` (`apbdes_id`);
ALTER TABLE `apbdes_pendapatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `apbdes_id` (`apbdes_id`);
ALTER TABLE `bagan_desa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipe` (`tipe`);
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `hero`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `jenis_bansos`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `penduduk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);
ALTER TABLE `penerima_bansos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jenis_bansos` (`id_jenis_bansos`);
ALTER TABLE `sambutan`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `sejarah_desa`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `sotk`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `statistik_penduduk`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `visi_misi`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `apbdes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `apbdes_belanja`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `apbdes_pembiayaan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `apbdes_pendapatan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `bagan_desa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `berita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
ALTER TABLE `galeri`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `hero`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `jenis_bansos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `penduduk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `penerima_bansos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `sambutan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `sejarah_desa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `sotk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `statistik_penduduk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `visi_misi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `apbdes_belanja`
  ADD CONSTRAINT `apbdes_belanja_ibfk_1` FOREIGN KEY (`apbdes_id`) REFERENCES `apbdes` (`id`) ON DELETE CASCADE;
ALTER TABLE `apbdes_pembiayaan`
  ADD CONSTRAINT `apbdes_pembiayaan_ibfk_1` FOREIGN KEY (`apbdes_id`) REFERENCES `apbdes` (`id`) ON DELETE CASCADE;
ALTER TABLE `apbdes_pendapatan`
  ADD CONSTRAINT `apbdes_pendapatan_ibfk_1` FOREIGN KEY (`apbdes_id`) REFERENCES `apbdes` (`id`) ON DELETE CASCADE;
ALTER TABLE `penerima_bansos`
  ADD CONSTRAINT `penerima_bansos_ibfk_1` FOREIGN KEY (`id_jenis_bansos`) REFERENCES `jenis_bansos` (`id`) ON DELETE CASCADE;
COMMIT;


   

















target kampus minim 20:
1. utm - https://pta.trunojoyo.ac.id/c_search/byprod/10 (-tahun)
2. itb - https://digilib.itb.ac.id/prodi/index/18 (+tahun)
3. uin mlg - http://etheses.uin-malang.ac.id/view/divisions/JTik/ (+tahun)
4. uin bdg - https://digilib.uinsgd.ac.id/view/divisions/prodi=5Finformatika/ (+tahun)
5. uin jkt - https://repository.uinjkt.ac.id/dspace/handle/123456789/57 (+tahun)
6. um - https://repository.um.ac.id/view/divisions/PTIN/ (+tahun)
7. umy - https://repository.umy.ac.id/handle/123456789/206 (+tahun)
8. umsura - https://repository.um-surabaya.ac.id/view/subjects/T1.html (+tahun)
9. ub - https://repository.ub.ac.id/view/divisions/kom=5Fti/ (+tahun)
10. unpam - https://repository.unpam.ac.id/view/subjects/Q1.html (+tahun)
11. unikom - https://repository.unikom.ac.id/view/subjects/UNIK14.html (sampai 2017 +tahun)
12. unindira - https://library.unindra.ac.id/skripsi/index.php?title=&author=&subject=&isbn=&colltype=0&location=0&gmd=Teknik%20Informatika&searchtype=advance&search=search (+tahun)
13. uisu - https://repository.uisu.ac.id/handle/123456789/54 (+tahun)
14. uir - https://repository.uir.ac.id/view/divisions/TI/ (+tahun)
15. unhas - https://digilib.eng.unhas.ac.id/search?q=&study_program=3 (+tahun)
16. telu - https://repository.telkomuniversity.ac.id/home/catalog.html (+tahun)
17. unpad - https://repository.unpad.ac.id/collections/de7ad820-91ac-4313-8fa5-d70444b34216 (+tahun)
18. uajy - https://e-journal.uajy.ac.id/view/subjects/EIS.html (+tahun)
19. unm - https://e-skripsi.jtik.ft.unm.ac.id/index.php/page/skripsi (+tahun)
20. unp - https://dspace.uii.ac.id/handle/123456789/41/browse (+tahun)
