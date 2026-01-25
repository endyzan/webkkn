<?php
include '../db.php';

/* VISI MISI */
$data = mysqli_query($conn, "SELECT * FROM visi_misi WHERE status='aktif' LIMIT 1");
$vm = mysqli_fetch_assoc($data);

/* BAGAN DESA */
$q = mysqli_query($conn, "SELECT * FROM bagan_desa WHERE status='aktif'");

$sh = mysqli_query($conn, "SELECT * FROM sejarah_desa WHERE status='aktif' LIMIT 1");
$sejarah = mysqli_fetch_assoc($sh);


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

  <!-- PROFIL DESA - VISI MISI -->
  <section class="profil-visimisi">
    <div class="visimisi-container">

      <!-- VISI -->
      <div class="visimisi-card">
        <h2>Visi</h2>
        <p><?= $vm['visi']; ?></p>
      </div>

      <!-- MISI -->
      <div class="visimisi-card">
        <h2>Misi</h2>
        <ol>
          <?= $vm['misi']; ?>
        </ol>
      </div>

    </div>
  </section>


  <!-- BAGAN DESA -->
<!-- BAGAN DESA -->
<section class="bagan-desa">
  <div class="bagan-container">

    <h2>Bagan Desa</h2>

    <div class="bagan-grid">

      <?php while ($b = mysqli_fetch_assoc($q)) : ?>
        <div class="bagan-item">
          <h3><?= $b['judul']; ?></h3>

          <img src="../uploads/bagan/<?= $b['gambar']; ?>"
               alt="<?= $b['judul']; ?>"
               class="bagan-thumb"
               onclick="openImage(this.src)">
        </div>
      <?php endwhile; ?>

    </div>
  </div>

  <!-- IMAGE MODAL -->
  <div id="imageModal" class="image-modal">
    <span class="close-modal" onclick="closeImage()">×</span>
    <img id="modalImage" src="" alt="Preview Gambar">
  </div>
</section>



  <!-- SEJARAH DESA -->
<?php if ($sejarah): ?>
<section class="sejarah-desa">
  <div class="sejarah-container">

    <h2><?= $sejarah['judul']; ?></h2>

    <div class="sejarah-card">

      <div class="sejarah-foto">
        <img src="../uploads/sejarah/<?= $sejarah['foto']; ?>"
             alt="<?= $sejarah['judul']; ?>"
             class="sejarah-thumb"
             onclick="openImage(this.src)">
      </div>

      <div class="sejarah-text">
        <?= $sejarah['isi']; ?>
      </div>

    </div>

  </div>
</section>
<?php endif; ?>





  <!-- PETA LOKASI DESA -->
  <section class="peta-desa">
    <div class="peta-container">

      <h2>Peta Lokasi Desa</h2>

      <div class="peta-card">

        <!-- INFO DESA -->
        <div class="peta-info">
          <h4>Batas Desa:</h4>

          <div class="batas-grid">
            <div>
              <strong>Utara</strong>
              <p>Desa Pekadan Kec. Galis</p>
            </div>
            <div>
              <strong>Timur</strong>
              <p>Desa Suwaan Kec. Modung</p>
            </div>
            <div>
              <strong>Selatan</strong>
              <p>Desa Modung Kec. Modung</p>
            </div>
            <div>
              <strong>Barat</strong>
              <p>Desa Karang Anyar Kec. Modung</p>
            </div>
          </div>

          <hr>

          <div class="peta-stat">
            <p><strong>Luas Desa:</strong> 4.000.000 m²</p>
            <!-- <p><strong>Jumlah Penduduk:</strong> 1.161 Jiwa</p> -->
          </div>
        </div>

        <!-- MAP -->
        <div class="peta-map">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15051.118686145122!2d112.94018931304178!3d-7.166352228672062!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd81e31e7229e27%3A0xf6fb06135eadf3bb!2sBrakas%20Dajah%2C%20Kec.%20Modung%2C%20Kabupaten%20Bangkalan%2C%20Jawa%20Timur!5e1!3m2!1sid!2sid!4v1768202844000!5m2!1sid!2sid"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>

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
