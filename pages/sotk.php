<?php
include '../db.php';

$sotk = mysqli_query($conn, "
    SELECT * FROM sotk 
    WHERE status='aktif' 
    ORDER BY urutan ASC
");
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil Desa - Desa Brakas Dejeh</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- NAVBAR -->
  <nav class="navbar">
    <div class="nav-left">
      <img src="../assets/img/logonew.png" alt="Logo Desa" />
      <div class="text">
        <strong>Desa Brakas Dejeh</strong><br />
        Kabupaten Bangkalan
      </div>
    </div>

    <div class="nav-right">
      <ul class="nav-menu">
        <li><a href="../index.php">Home</a></li>
        <li><a href="./profil-desa.php">Profil Desa</a></li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle">Infografis</a>
          <ul class="dropdown-menu">
            <li><a href="./infografis/penduduk.php">Penduduk</a></li>
            <li><a href="./infografis/apbdes.php">APBDes</a></li>
            <li><a href="./infografis/bansos.php">Bansos</a></li>
          </ul>
        </li>

        <li><a href="./berita.php">Berita</a></li>
        <li><a href="./Galeri.php">Galeri</a></li>
      </ul>
    </div>
  </nav>


  <!-- BAGAN DESA -->
<section class="bagan-desa2">
  <div class="bagan-container2">

    <h2>SOTK</h2>
    <p class="bagan-desc2">
      Struktur Organisasi dan Tata Kerja Pemerintah Desa Brakas Dejeh
    </p>
      <!-- Struktur Pemerintahan Desa -->
      <div class="bagan-item2">
        <img
          src="../assets/img/hero-bg.jpeg"
          alt="Struktur Organisasi dan Tata Kerja Desa Kersik"
          class="bagan-thumb2"
          onclick="openImage(this.src)">
      </div>
  </div>

  <!-- IMAGE MODAL -->
  <div id="imageModal" class="image-modal2">
    <span class="close-modal2" onclick="closeImage()">×</span>
    <img id="modalImage" src="" alt="Preview Gambar">
  </div>
</section>


<!-- SOTK FULL -->
<section class="sotk">
  <div class="sotk-header">
    <h2>APARAT PEMERINTAH DESA</h2>
  </div>

  <div class="sotk-cards">

    <?php while($row = mysqli_fetch_assoc($sotk)): ?>
      <div class="sotk-card">
        <img 
          src="../uploads/sotk/<?= $row['foto'] ?: 'default.png'; ?>" 
          alt="<?= $row['nama']; ?>"
        >
        <div class="sotk-info">
          <strong><?= strtoupper($row['nama']); ?></strong>
          <span><?= $row['jabatan']; ?></span>
        </div>
      </div>
    <?php endwhile; ?>

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
