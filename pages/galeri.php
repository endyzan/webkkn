<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Galeri - Desa Brakas Dejeh</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- navbar -->
<nav class="navbar">
  <div class="nav-left">
    <img src="../assets/img/logonew.png" alt="Logo Desa" />
    <div class="text">
      <strong>Desa Brakas Dejeh</strong><br />
      Kabupaten Bangkalan
    </div>
  </div>

  <!-- HAMBURGER -->
  <button class="hamburger" id="hamburger" aria-label="Menu">
    ☰
  </button>

  <div class="nav-right" id="navMenu">
    <ul class="nav-menu">
      <li><a href="../index.php">Home</a></li>
      <li><a href="./profil-desa.php">Profil Desa</a></li>

      <li class="dropdown">
        <a href="#" class="dropdown-toggle">Infografis
        <span class="arrow">▼</span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="./infografis/penduduk.php">Penduduk</a></li>
          <li><a href="./infografis/apbdes.php">APBDes</a></li>
          <li><a href="./infografis/bansos.php">Bansos</a></li>
        </ul>
      </li>

      <li><a href="./berita.php">Berita</a></li>
      <li><a href="./galeri.php">Galeri</a></li>
    </ul>
  </div>
</nav>



  <!-- GALERI DESA -->
<section class="galeri">
  <div class="galeri-container">

    <div class="galeri-header">
      <h2>GALERI DESA</h2>
      <p>Menampilkan kegiatan-kegiatan yang berlangsung di desa</p>
    </div>

    <div class="galeri-grid">
      <!-- ROW 1 -->
      <img src="../assets/img/hero-bg.jpeg" alt="Galeri 1">
      <img src="../assets/img/hero-bg.jpeg" alt="Galeri 2">
      <img src="../assets/img/hero-bg.jpeg" alt="Galeri 3">
      <img src="../assets/img/hero-bg.jpeg" alt="Galeri 4">

      <!-- ROW 2 -->
      <img src="../assets/img/hero-bg.jpeg" alt="Galeri 5">
      <img src="../assets/img/hero-bg.jpeg" alt="Galeri 6">
      <img src="../assets/img/hero-bg.jpeg" alt="Galeri 7">
      <img src="../assets/img/hero-bg.jpeg" alt="Galeri 8">

      <!-- ROW 3 -->
      <img src="../assets/img/hero-bg.jpeg" alt="Galeri 9">
      <img src="../assets/img/hero-bg.jpeg" alt="Galeri 10">
      <img src="../assets/img/hero-bg.jpeg" alt="Galeri 11">
      <img src="../assets/img/hero-bg.jpeg" alt="Galeri 12">
    </div>

    <!-- PAGINATION -->
    <div class="galeri-pagination">
      <button>&lsaquo;</button>
      <button class="active">1</button>
      <button>2</button>
      <button>3</button>
      <button>4</button>
      <button>5</button>
      <button>6</button>
      <button>7</button>
      <button>&rsaquo;</button>
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
