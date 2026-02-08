<?php
include '../db.php';

if (!isset($_GET['id'])) {
    header("Location: berita.php");
    exit;
}

$id = (int) $_GET['id'];

// ambil berita
$query = mysqli_query($conn, "
    SELECT * FROM berita 
    WHERE id = $id AND status='publish'
");

if (mysqli_num_rows($query) == 0) {
    header("Location: berita.php");
    exit;
}

$berita = mysqli_fetch_assoc($query);

// tambah jumlah dilihat
mysqli_query($conn, "UPDATE berita SET dilihat = dilihat + 1 WHERE id = $id");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($berita['judul']) ?> - Berita Desa</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    .berita-content {
      font-size: 16px;
      line-height: 1.8;
      color: #333;
    }

    .berita-content p {
      margin-bottom: 16px;
    }

    .berita-content h1,
    .berita-content h2,
    .berita-content h3 {
      margin: 20px 0 10px;
      font-weight: 700;
    }

    .berita-content ul,
    .berita-content ol {
      margin: 10px 0 10px 20px;
    }

    .berita-content strong {
      font-weight: bold;
    }

    .berita-content img {
      max-width: 100%;
      border-radius: 8px;
      margin: 15px 0;
    }

  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="nav-left">
    <img src="../assets/img/logonew.png" alt="Logo Desa">
    <div class="text">
      <strong>Desa Brakas Dejeh</strong><br>
      Kabupaten Bangkalan
    </div>
  </div>

  <button class="hamburger" id="hamburger">☰</button>

  <div class="nav-right" id="navMenu">
    <ul class="nav-menu">
      <li><a href="../index.php">Home</a></li>
      <li><a href="./profil-desa.php">Profil Desa</a></li>
      <li class="dropdown">
        <a href="#">Infografis <span class="arrow">▼</span></a>
        <ul class="dropdown-menu">
          <li><a href="./infografis/penduduk.php">Penduduk</a></li>
          <li><a href="./infografis/apbdes.php">APBDes</a></li>
          <li><a href="./infografis/bansos.php">Bansos</a></li>
        </ul>
      </li>
      <li><a href="./berita.php" class="active">Berita</a></li>
      <li><a href="./galeri.php">Galeri</a></li>
    </ul>
  </div>
</nav>

<!-- ===== DETAIL BERITA ===== -->
<section class="berita">
    <div class="berita-container" style="max-width:900px">
      <!-- BACK -->
      <div style="margin-top:10px; margin-bottom:20px;">
        <a href="berita.php" style="text-decoration:none;font-weight:700;color:#6cc24a;">
          ← Kembali ke Berita
        </a>
      </div>



    <!-- JUDUL -->
    <h1 style="font-size:28px;font-weight:800;margin-bottom:10px;">
      <?= htmlspecialchars($berita['judul']) ?>
    </h1>

    <!-- META -->
    <div style="font-size:13px;color:#777;margin-bottom:20px;">
      <?= htmlspecialchars($berita['penulis']) ?> • 
      <?= date('d M Y', strtotime($berita['tanggal'])) ?> • 
      Dilihat <?= (int)$berita['dilihat'] + 1 ?> kali
    </div>

    <!-- GAMBAR -->
    <?php if($berita['gambar']): ?>
      <img 
        src="../uploads/berita/<?= htmlspecialchars($berita['gambar']) ?>" 
        style="width:100%;border-radius:8px;margin-bottom:20px; height: 500px; object-fit: cover;"
      >
    <?php endif; ?>

    <!-- ISI -->
    <div class="berita-content">
      <?= $berita['isi'] ?>
    </div>


  </div>
</section>

    <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-container">

      <!-- Kolom 1 -->
      <div class="footer-col">
        <div class="footer-logo">
          <img src="../assets/img/logonew.png" alt="Logo Desa">
          <h3>Pemerintah Desa Brakas Dejeh</h3>
        </div>
        <p>
          Jalan Langseng Dusun Empang RT.003<br>
          Desa Brakas Dejeh, Kecamatan Modung,<br>
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
          <li>✉️ brakasdejeh@bangkalankab.go.id</li>
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
          <li><a href="#">Jumadi / Kades Brakas Dejeh</a></li>
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
      © 2026 Pemerintah Desa Brakas Dejeh. KKN 2025/2026.
    </div>
  </footer>

  <script src="../assets/js/scripts.js"></script>

  </body>
</html>
