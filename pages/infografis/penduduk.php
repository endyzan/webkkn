<?php
include '../../db.php'; // Pastikan path sesuai

// Ambil data statistik dari tabel statistik_penduduk
$statistik = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM statistik_penduduk LIMIT 1"));

// Jika tidak ada data, buat default
if (!$statistik) {
    $statistik = [
        'total_penduduk' => 0,
        'kepala_keluarga' => 0,
        'perempuan' => 0,
        'laki_laki' => 0
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Penduduk - Desa Brakas Dajah</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.status-card {
    transition: transform 0.3s ease;
    cursor: pointer;
}

.status-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.dusun-info small {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
    display: block;
}

/* Perbaikan untuk icon FontAwesome */
.dusun-icon i {
    font-size: 40px;
}

.dusun-card.status-card {
    display: flex;
    align-items: center;
    gap: 15px;
}
</style>
</head>
<body>

<!-- navbar -->
<nav class="navbar">
    <div class="nav-left">
        <a href="../../index.php" style="display:flex; align-items:center; gap:10px; text-decoration:none; color:inherit;">
            <img src="../../assets/img/logonew.png" alt="Logo Desa" />

            <div class="text">
            <strong>Desa Brakas Dajah</strong><br />
            Kabupaten Bangkalan
            </div>
        </a>
    </div>


  <!-- HAMBURGER -->
  <button class="hamburger" id="hamburger" aria-label="Menu">
    ☰
  </button>

  <div class="nav-right" id="navMenu">
    <ul class="nav-menu">
      <li><a href="../../index.php">Home</a></li>
      <li><a href="../profil-desa.php">Profil Desa</a></li>

      <li class="dropdown">
        <a href="#" class="dropdown-toggle">Infografis
        <span class="arrow">▼</span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="./penduduk.php">Penduduk</a></li>
          <li><a href="./apbdes.php">APBDes</a></li>
          <li><a href="./bansos.php">Bansos</a></li>
        </ul>
      </li>

      <li><a href="../berita.php">Berita</a></li>
      <li><a href="../galeri.php">Galeri</a></li>
    </ul>
  </div>
</nav>



    <!-- HERO INFOGRAFIS PENDUDUK -->
  <section class="hero-infografis">
    <div class="hero-container">

      <!-- TEKS -->
      <div class="hero-text">
        <h1>DEMOGRAFI PENDUDUK</h1>
        <p>
          Memberikan informasi lengkap mengenai karakteristik demografi penduduk
          suatu wilayah. Mulai dari jumlah penduduk, usia, jenis kelamin, tingkat
          pendidikan, pekerjaan, agama, dan aspek penting lainnya yang
          menggambarkan komposisi populasi secara rinci.
        </p>
      </div>

      <!-- ILUSTRASI -->
      <div class="hero-image">
        <img src="../../assets/img/konten-hero-penduduk.png"
            alt="Ilustrasi Demografi Penduduk">
      </div>

    </div>
  </section>



  
<!-- ================= STATISTIK PENDUDUK ================= -->
<section class="stat-penduduk">
    <div class="stat-container">

        <h2>Jumlah Penduduk dan Kepala Keluarga</h2>

        <div class="stat-grid">

            <!-- Total Penduduk -->
            <div class="stat-card">
                <div class="stat-icon">
                    <img src="../../assets/img/icon-total-penduduk.svg" alt="Total Penduduk">
                </div>
                <div class="stat-text">
                    <span>TOTAL PENDUDUK</span>
                    <strong><?= number_format($statistik['total_penduduk'] ?? 0) ?> Jiwa</strong>
                </div>
            </div>

            <!-- Kepala Keluarga -->
            <div class="stat-card">
                <div class="stat-icon">
                    <img src="../../assets/img/icon-kepala-keluarga-penduduk.svg" alt="Kepala Keluarga">
                </div>
                <div class="stat-text">
                    <span>KEPALA KELUARGA</span>
                    <strong><?= number_format($statistik['kepala_keluarga'] ?? 0) ?> KK</strong>
                </div>
            </div>

            <!-- Perempuan -->
            <div class="stat-card">
                <div class="stat-icon">
                    <img src="../../assets/img/icon-perempuan-penduduk.svg" alt="Perempuan">
                </div>
                <div class="stat-text">
                    <span>PEREMPUAN</span>
                    <strong><?= number_format($statistik['perempuan'] ?? 0) ?> Jiwa</strong>
                </div>
            </div>

            <!-- Laki-laki -->
            <div class="stat-card">
                <div class="stat-icon">
                    <img src="../../assets/img/icon-laki-penduduk.svg" alt="Laki-laki">
                </div>
                <div class="stat-text">
                    <span>LAKI-LAKI</span>
                    <strong><?= number_format($statistik['laki_laki'] ?? 0) ?> Jiwa</strong>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- DISTRIBUSI STATUS PENDUDUK -->
<section class="dusun-section">
    <div class="dusun-container">

        <h2>Status Penduduk</h2>

        <div class="dusun-grid">
            <?php
            // Query untuk status penduduk
            $query_status = "SELECT 
                SUM(CASE WHEN status_penduduk = 'hidup' THEN 1 ELSE 0 END) as hidup,
                SUM(CASE WHEN status_penduduk = 'meninggal' THEN 1 ELSE 0 END) as meninggal,
                SUM(CASE WHEN status_penduduk = 'pindah' THEN 1 ELSE 0 END) as pindah,
                SUM(CASE WHEN status_penduduk = 'penduduk_sementara' THEN 1 ELSE 0 END) as sementara,
                COUNT(*) as total
            FROM penduduk";
            $result_status = mysqli_query($conn, $query_status);
            $status = mysqli_fetch_assoc($result_status);
            ?>
            
            <div class="dusun-card status-card" style="background: #d4edda;">
                <div class="dusun-icon">
                    <i class="fas fa-heartbeat" style="color: #155724;"></i>
                </div>
                <div class="dusun-info">
                    <p>Penduduk Hidup</p>
                    <h3><?= number_format($status['hidup'] ?? 0) ?> Jiwa</h3>
                    <small><?= $status['total'] > 0 ? round(($status['hidup']/$status['total'])*100, 1) : 0 ?>%</small>
                </div>
            </div>

            <div class="dusun-card status-card" style="background: #f8d7da;">
                <div class="dusun-icon">
                    <i class="fas fa-cross" style="color: #721c24;"></i>
                </div>
                <div class="dusun-info">
                    <p>Meninggal</p>
                    <h3><?= number_format($status['meninggal'] ?? 0) ?> Jiwa</h3>
                    <small><?= $status['total'] > 0 ? round(($status['meninggal']/$status['total'])*100, 1) : 0 ?>%</small>
                </div>
            </div>

            <div class="dusun-card status-card" style="background: #fff3cd;">
                <div class="dusun-icon">
                    <i class="fas fa-truck-moving" style="color: #856404;"></i>
                </div>
                <div class="dusun-info">
                    <p>Mutasi/Pindah</p>
                    <h3><?= number_format($status['pindah'] ?? 0) ?> Jiwa</h3>
                    <small><?= $status['total'] > 0 ? round(($status['pindah']/$status['total'])*100, 1) : 0 ?>%</small>
                </div>
            </div>

            <div class="dusun-card status-card" style="background: #d1ecf1;">
                <div class="dusun-icon">
                    <i class="fas fa-clock" style="color: #0c5460;"></i>
                </div>
                <div class="dusun-info">
                    <p>Penduduk Sementara</p>
                    <h3><?= number_format($status['sementara'] ?? 0) ?> Jiwa</h3>
                    <small><?= $status['total'] > 0 ? round(($status['sementara']/$status['total'])*100, 1) : 0 ?>%</small>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- PIRAMIDA PENDUDUK -->
<section class="umur-section">
    <div class="umur-container">

        <h2>Berdasarkan Kelompok Umur</h2>

        <!-- CHART -->
        <div class="piramida-card">
            <div class="piramida-header">
                <span>Laki-laki</span>
                <span>Perempuan</span>
            </div>

            <div class="piramida-chart">
                <?php
                // Query untuk piramida penduduk berdasarkan umur
                $umur_groups = [
                    '85+' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) >= 85",
                    '80-84' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 80 AND 84",
                    '75-79' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 75 AND 79",
                    '70-74' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 70 AND 74",
                    '65-69' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 65 AND 69",
                    '60-64' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 60 AND 64",
                    '55-59' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 55 AND 59",
                    '50-54' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 50 AND 54",
                    '45-49' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 45 AND 49",
                    '40-44' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 40 AND 44",
                    '35-39' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 35 AND 39",
                    '30-34' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 30 AND 34",
                    '25-29' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 25 AND 29",
                    '20-24' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 20 AND 24",
                    '15-19' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 15 AND 19",
                    '10-14' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 10 AND 14",
                    '5-9' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 5 AND 9",
                    '0-4' => "YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 0 AND 4"
                ];

                foreach ($umur_groups as $label => $condition) {
                    $query_laki = "SELECT COUNT(*) as total FROM penduduk 
                                  WHERE $condition AND jenis_kelamin = 'L' AND status_penduduk = 'hidup'";
                    $query_perempuan = "SELECT COUNT(*) as total FROM penduduk 
                                       WHERE $condition AND jenis_kelamin = 'P' AND status_penduduk = 'hidup'";
                    
                    $result_laki = mysqli_query($conn, $query_laki);
                    $result_perempuan = mysqli_query($conn, $query_perempuan);
                    
                    $laki = $result_laki ? mysqli_fetch_assoc($result_laki)['total'] : 0;
                    $perempuan = $result_perempuan ? mysqli_fetch_assoc($result_perempuan)['total'] : 0;
                    
                    // Cari jumlah maksimum untuk scaling
                    $max_query = "SELECT COUNT(*) as max_count FROM penduduk WHERE status_penduduk = 'hidup' GROUP BY jenis_kelamin ORDER BY max_count DESC LIMIT 1";
                    $max_result = mysqli_query($conn, $max_query);
                    $max_row = mysqli_fetch_assoc($max_result);
                    $max_population = $max_row ? max($max_row['max_count'], 1) : 1;
                    
                    // Hitung persentase
                    $percent_laki = $laki > 0 ? ($laki / $max_population) * 100 : 0;
                    $percent_perempuan = $perempuan > 0 ? ($perempuan / $max_population) * 100 : 0;
                    
                    // Batasi maksimum 100%
                    $percent_laki = min($percent_laki, 100);
                    $percent_perempuan = min($percent_perempuan, 100);
                    ?>
                    <div class="umur-row">
                        <span class="umur-label"><?= $label ?></span>
                        <div class="bar laki" style="width:<?= $percent_laki ?>%">
                            <?php if ($laki > 0): ?>
                                <span><?= $laki ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="bar perempuan" style="width:<?= $percent_perempuan ?>%">
                            <?php if ($perempuan > 0): ?>
                                <span><?= $perempuan ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- KESIMPULAN -->
        <?php
        // Query untuk kesimpulan
        $query_max_laki = "SELECT 
            CASE 
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) >= 85 THEN '85+'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 80 AND 84 THEN '80-84'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 75 AND 79 THEN '75-79'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 70 AND 74 THEN '70-74'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 65 AND 69 THEN '65-69'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 60 AND 64 THEN '60-64'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 55 AND 59 THEN '55-59'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 50 AND 54 THEN '50-54'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 45 AND 49 THEN '45-49'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 40 AND 44 THEN '40-44'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 35 AND 39 THEN '35-39'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 30 AND 34 THEN '30-34'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 25 AND 29 THEN '25-29'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 20 AND 24 THEN '20-24'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 15 AND 19 THEN '15-19'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 10 AND 14 THEN '10-14'
                WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 5 AND 9 THEN '5-9'
                ELSE '0-4'
            END as kelompok_umur,
            COUNT(*) as jumlah,
            ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM penduduk WHERE jenis_kelamin = 'L' AND status_penduduk = 'hidup'), 2) as persentase
        FROM penduduk 
        WHERE jenis_kelamin = 'L' AND status_penduduk = 'hidup'
        GROUP BY kelompok_umur
        ORDER BY jumlah DESC LIMIT 1";
        
        $query_min_laki = str_replace("DESC LIMIT 1", "ASC LIMIT 1", $query_max_laki);
        
        $max_laki_result = mysqli_query($conn, $query_max_laki);
        $min_laki_result = mysqli_query($conn, $query_min_laki);
        
        $max_laki = $max_laki_result ? mysqli_fetch_assoc($max_laki_result) : ['kelompok_umur' => 'N/A', 'jumlah' => 0, 'persentase' => 0];
        $min_laki = $min_laki_result ? mysqli_fetch_assoc($min_laki_result) : ['kelompok_umur' => 'N/A', 'jumlah' => 0, 'persentase' => 0];
        
        // Query untuk perempuan
        $query_max_perempuan = str_replace("jenis_kelamin = 'L'", "jenis_kelamin = 'P'", $query_max_laki);
        $query_min_perempuan = str_replace("jenis_kelamin = 'L'", "jenis_kelamin = 'P'", $query_min_laki);
        
        $max_perempuan_result = mysqli_query($conn, $query_max_perempuan);
        $min_perempuan_result = mysqli_query($conn, $query_min_perempuan);
        
        $max_perempuan = $max_perempuan_result ? mysqli_fetch_assoc($max_perempuan_result) : ['kelompok_umur' => 'N/A', 'jumlah' => 0, 'persentase' => 0];
        $min_perempuan = $min_perempuan_result ? mysqli_fetch_assoc($min_perempuan_result) : ['kelompok_umur' => 'N/A', 'jumlah' => 0, 'persentase' => 0];
        ?>

        <div class="analisis laki">
            Untuk jenis kelamin laki-laki, kelompok umur <b><?= $max_laki['kelompok_umur'] ?></b> 
            merupakan kelompok tertinggi dengan jumlah <b><?= $max_laki['jumlah'] ?> orang</b> 
            atau <b><?= $max_laki['persentase'] ?>%</b>. 
            Sedangkan kelompok umur <b><?= $min_laki['kelompok_umur'] ?></b> 
            merupakan yang terendah dengan jumlah <b><?= $min_laki['jumlah'] ?> orang</b> 
            atau <b><?= $min_laki['persentase'] ?>%</b>.
        </div>

        <div class="analisis perempuan">
            Untuk jenis kelamin perempuan, kelompok umur <b><?= $max_perempuan['kelompok_umur'] ?></b> 
            merupakan kelompok tertinggi dengan jumlah <b><?= $max_perempuan['jumlah'] ?> orang</b> 
            atau <b><?= $max_perempuan['persentase'] ?>%</b>. 
            Sedangkan kelompok umur <b><?= $min_perempuan['kelompok_umur'] ?></b> 
            merupakan yang terendah dengan jumlah <b><?= $min_perempuan['jumlah'] ?> orang</b> 
            atau <b><?= $min_perempuan['persentase'] ?>%</b>.
        </div>

    </div>
</section>

<!-- BERDASARKAN DUSUN -->
<section class="dusun-section">
    <div class="dusun-container">

        <h2>Berdasarkan Dusun</h2>

        <div class="dusun-grid">
            <?php
            // Query untuk data dusun
            $query_dusun = "SELECT 
                dusun,
                COUNT(*) as jumlah
            FROM penduduk 
            WHERE status_penduduk = 'hidup' 
            GROUP BY dusun 
            ORDER BY jumlah DESC";
            
            $result_dusun = mysqli_query($conn, $query_dusun);
            
            if ($result_dusun && mysqli_num_rows($result_dusun) > 0):
                while ($dusun = mysqli_fetch_assoc($result_dusun)):
            ?>
                <div class="dusun-card">
                    <div class="dusun-icon">
                        <img src="../../assets/img/dusun.png" alt="Dusun">
                    </div>
                    <div class="dusun-info">
                        <p><?= htmlspecialchars($dusun['dusun'] ?: 'Belum Terdata') ?></p>
                        <h3><?= number_format($dusun['jumlah']) ?> Jiwa</h3>
                    </div>
                </div>
            <?php 
                endwhile;
            else:
            ?>
                <div class="dusun-card">
                    <div class="dusun-icon">
                        <img src="../../assets/img/dusun.png" alt="Dusun">
                    </div>
                    <div class="dusun-info">
                        <p>Data Dusun</p>
                        <h3>0 Jiwa</h3>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- KETERANGAN -->
        <div class="dusun-keterangan">
            Data jumlah penduduk berdasarkan dusun menunjukkan sebaran penduduk
            di wilayah Desa secara administratif.
        </div>

    </div>
</section>

<!-- BERDASARKAN PENDIDIKAN -->
<section class="pendidikan-section">
    <div class="pendidikan-container">

        <h2>Berdasarkan Pendidikan</h2>

        <div class="pendidikan-card">
            <canvas id="chartPendidikan"></canvas>
        </div>

    </div>
</section>

<!-- BERDASARKAN PERKAWINAN -->
<section class="dusun-section">
    <div class="dusun-container">

        <h2>Berdasarkan Status Perkawinan</h2>

        <div class="dusun-card">
            <canvas id="chartPerkawinan"></canvas>
        </div>

    </div>
</section>

<!-- BERDASARKAN AGAMA -->
<section class="dusun-section">
    <div class="dusun-container">

        <h2>Berdasarkan Agama</h2>

        <div class="dusun-card">
            <canvas id="chartAgama"></canvas>
        </div>

    </div>
</section>

<!-- BERDASARKAN PEKERJAAN -->
<section class="pendidikan-section">
    <div class="pendidikan-container">

        <h2>Berdasarkan Pekerjaan</h2>

        <div class="pendidikan-card">
            <canvas id="chartPekerjaan"></canvas>
        </div>

    </div>
</section>

<!-- DATA TERBARU -->
<!-- <section class="dusun-section">
    <div class="dusun-container">

        <h2>Data Penduduk Terbaru</h2>

        <div style="background: white; border-radius: 10px; padding: 20px; margin-top: 20px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">NIK</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Nama</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Jenis Kelamin</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Dusun</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query untuk data terbaru
                    $query_terbaru = "SELECT nik, nama, jenis_kelamin, dusun, status_penduduk 
                                    FROM penduduk 
                                    WHERE status_penduduk = 'hidup'
                                    ORDER BY updated_at DESC 
                                    LIMIT 10";
                    $result_terbaru = mysqli_query($conn, $query_terbaru);
                    
                    if ($result_terbaru && mysqli_num_rows($result_terbaru) > 0):
                        while ($row = mysqli_fetch_assoc($result_terbaru)):
                    ?>
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 12px;"><?= htmlspecialchars($row['nik'] ?: '-') ?></td>
                            <td style="padding: 12px;"><?= htmlspecialchars($row['nama']) ?></td>
                            <td style="padding: 12px;"><?= $row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                            <td style="padding: 12px;"><?= htmlspecialchars($row['dusun'] ?: '-') ?></td>
                            <td style="padding: 12px;">
                                <?php 
                                $status_text = [
                                    'hidup' => 'Hidup',
                                    'meninggal' => 'Meninggal',
                                    'pindah' => 'Pindah',
                                    'penduduk_sementara' => 'Sementara'
                                ];
                                echo $status_text[$row['status_penduduk']] ?? 'Tidak diketahui';
                                ?>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="5" style="padding: 20px; text-align: center;">Tidak ada data penduduk</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</section> -->




<!-- FOOTER -->
<footer class="footer">
    <div class="footer-container">

      <!-- Kolom 1 -->
      <div class="footer-col">
        <div class="footer-logo">
          <img src="../../assets/img/logonew.png" alt="Logo Desa">
          <h3>Pemerintah Desa Brakas Dajah</h3>
        </div>
        <p>
          Jalan Langseng Dusun Empang RT.003<br>
          Desa Brakas Dajah, Kecamatan Modung,<br>
          Kabupaten Bangkalan<br>
          Provinsi Jawa Timur, 69166
        </p>
        <p><strong>Kode Wilayah:</strong> 35.26.17.2005</p>
      </div>
      
      <!-- Kolom 2 -->
      <div class="footer-col">
        <h4>Hubungi Kami</h4>
        <ul class="footer-list">
          <li>📞 082150208664</li>
          <li>✉️ brakasDajah@bangkalankab.go.id</li>
        </ul>
        
        <div class="footer-social">
          <a href="#">🌐</a>
          <a href="#">📘</a>
          <a href="#">🐦</a>
          <a href="#">▶️</a>
          <a href="#">🎵</a>
        </div>
      </div>
      
      <!-- Kolom 3 -->
      <div class="footer-col">
        <h4>Nomor Telepon Penting</h4>
        <ul class="footer-list">
          <li><a href="#">Jumadi / Kades Brakas Dajah</a></li>
          <li><a href="#">Yayan / Ambulan Desa</a></li>
        </ul>
      </div>

      <!-- Kolom 4 -->
      <div class="footer-col">
        <h4>Jelajahi</h4>
        <ul class="footer-list">
          <li><a href="#">Website Kemendesa</a></li>
          <li><a href="#">Website Kemendagri</a></li>
          <li><a href="#">Website Kabupaten Bangkalan</a></li>
          <li><a href="#">Cek DPT Online</a></li>
        </ul>
      </div>

    </div>
    
    <div class="footer-bottom">
      © <?= date('Y'); ?> Pemerintah Desa Brakas Dajah. KKN 2025/2026.
    </div>
  </footer>
  
  <script>
  // Chart untuk Pendidikan
  <?php
  $query_pendidikan = "SELECT pendidikan, COUNT(*) as jumlah 
                      FROM penduduk 
                      WHERE status_penduduk = 'hidup' 
                      GROUP BY pendidikan 
                      ORDER BY jumlah DESC";
  $result_pendidikan = mysqli_query($conn, $query_pendidikan);
  
  $labels_pendidikan = [];
  $data_pendidikan = [];
  $colors_pendidikan = [];
  
  if ($result_pendidikan && mysqli_num_rows($result_pendidikan) > 0) {
      while ($row = mysqli_fetch_assoc($result_pendidikan)) {
          $labels_pendidikan[] = $row['pendidikan'] ?: 'Tidak Terdata';
          $data_pendidikan[] = $row['jumlah'];
          $colors_pendidikan[] = '#' . substr(md5($row['pendidikan'] ?? 'default'), 0, 6);
      }
  } else {
      $labels_pendidikan = ['Tidak ada data'];
      $data_pendidikan = [1];
      $colors_pendidikan = ['#cccccc'];
  }
  ?>
  
  var ctxPendidikan = document.getElementById('chartPendidikan');
  if (ctxPendidikan) {
      var chartPendidikan = new Chart(ctxPendidikan, {
          type: 'doughnut',
          data: {
              labels: <?= json_encode($labels_pendidikan) ?>,
              datasets: [{
                  data: <?= json_encode($data_pendidikan) ?>,
                  backgroundColor: <?= json_encode($colors_pendidikan) ?>,
                  borderWidth: 1
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      position: 'right'
                  }
              }
          }
      });
  }
  
  // Chart untuk Status Perkawinan
  <?php
  $query_perkawinan = "SELECT status_perkawinan, COUNT(*) as jumlah 
                      FROM penduduk 
                      WHERE status_penduduk = 'hidup' 
                      GROUP BY status_perkawinan 
                      ORDER BY jumlah DESC";
  $result_perkawinan = mysqli_query($conn, $query_perkawinan);
  
  $labels_perkawinan = [];
  $data_perkawinan = [];
  $colors_perkawinan = ['#4CAF50', '#2196F3', '#FF9800', '#F44336'];
  
  if ($result_perkawinan && mysqli_num_rows($result_perkawinan) > 0) {
      while ($row = mysqli_fetch_assoc($result_perkawinan)) {
          $labels_perkawinan[] = $row['status_perkawinan'] ?: 'Belum Terdata';
          $data_perkawinan[] = $row['jumlah'];
      }
  } else {
      $labels_perkawinan = ['Tidak ada data'];
      $data_perkawinan = [1];
  }
  ?>
  
  var ctxPerkawinan = document.getElementById('chartPerkawinan');
  if (ctxPerkawinan) {
      var chartPerkawinan = new Chart(ctxPerkawinan, {
          type: 'bar',
          data: {
              labels: <?= json_encode($labels_perkawinan) ?>,
              datasets: [{
                  label: 'Jumlah Penduduk',
                  data: <?= json_encode($data_perkawinan) ?>,
                  backgroundColor: <?= json_encode(array_slice($colors_perkawinan, 0, count($data_perkawinan))) ?>,
                  borderWidth: 1
              }]
          },
          options: {
              responsive: true,
              scales: {
                  y: {
                      beginAtZero: true,
                      ticks: {
                          stepSize: 1
                      }
                  }
              }
          }
      });
  }
  
  // Chart untuk Agama
  <?php
  $query_agama = "SELECT agama, COUNT(*) as jumlah 
                 FROM penduduk 
                 WHERE status_penduduk = 'hidup' 
                 GROUP BY agama 
                 ORDER BY jumlah DESC";
  $result_agama = mysqli_query($conn, $query_agama);
  
  $labels_agama = [];
  $data_agama = [];
  $colors_agama = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
  
  if ($result_agama && mysqli_num_rows($result_agama) > 0) {
      while ($row = mysqli_fetch_assoc($result_agama)) {
          $labels_agama[] = $row['agama'] ?: 'Belum Terdata';
          $data_agama[] = $row['jumlah'];
      }
  } else {
      $labels_agama = ['Tidak ada data'];
      $data_agama = [1];
  }
  ?>
  
  var ctxAgama = document.getElementById('chartAgama');
  if (ctxAgama) {
      var chartAgama = new Chart(ctxAgama, {
          type: 'pie',
          data: {
              labels: <?= json_encode($labels_agama) ?>,
              datasets: [{
                  data: <?= json_encode($data_agama) ?>,
                  backgroundColor: <?= json_encode(array_slice($colors_agama, 0, count($data_agama))) ?>,
                  borderWidth: 1
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      position: 'right'
                  }
              }
          }
      });
  }
  
  // Chart untuk Pekerjaan
  <?php
  $query_pekerjaan = "SELECT pekerjaan, COUNT(*) as jumlah 
                     FROM penduduk 
                     WHERE status_penduduk = 'hidup' 
                     GROUP BY pekerjaan 
                     ORDER BY jumlah DESC 
                     LIMIT 10";
  $result_pekerjaan = mysqli_query($conn, $query_pekerjaan);
  
  $labels_pekerjaan = [];
  $data_pekerjaan = [];
  
  if ($result_pekerjaan && mysqli_num_rows($result_pekerjaan) > 0) {
      while ($row = mysqli_fetch_assoc($result_pekerjaan)) {
          $labels_pekerjaan[] = $row['pekerjaan'] ?: 'Tidak Bekerja';
          $data_pekerjaan[] = $row['jumlah'];
      }
  } else {
      $labels_pekerjaan = ['Tidak ada data'];
      $data_pekerjaan = [1];
  }
  ?>
  
  var ctxPekerjaan = document.getElementById('chartPekerjaan');
  if (ctxPekerjaan) {
      var chartPekerjaan = new Chart(ctxPekerjaan, {
          type: 'bar',
          data: {
              labels: <?= json_encode($labels_pekerjaan) ?>,
              datasets: [{
                  label: 'Jumlah Penduduk',
                  data: <?= json_encode($data_pekerjaan) ?>,
                  backgroundColor: 'rgba(54, 162, 235, 0.7)',
                  borderColor: 'rgba(54, 162, 235, 1)',
                  borderWidth: 1
              }]
          },
          options: {
              responsive: true,
              indexAxis: 'y',
              scales: {
                  x: {
                      beginAtZero: true
                  }
              }
          }
      });
  }
  </script>

  <script src="../../assets/js/scripts.js"></script>



  </body>
</html>