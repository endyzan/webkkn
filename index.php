<?php
include './db.php';
$s = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM sambutan WHERE status='aktif' LIMIT 1")
);
$p = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM penduduk ORDER BY id DESC LIMIT 1")
);

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title> Desa Brakas Dejeh</title>
  <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<nav class="navbar">
  <div class="nav-left">
    <img src="./assets/img/logonew.png" alt="Logo Desa" />
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
      <li><a href="./index.php">Home</a></li>
      <li><a href="./pages/profil-desa.php">Profil Desa</a></li>

      <li class="dropdown">
        <a href="#" class="dropdown-toggle">Infografis</a>
        <ul class="dropdown-menu">
          <li><a href="./pages/infografis/penduduk.php">Penduduk</a></li>
          <li><a href="./pages/infografis/apbdes.php">APBDes</a></li>
          <li><a href="./pages/infografis/bansos.php">Bansos</a></li>
        </ul>
      </li>

      <li><a href="./pages/berita.php">Berita</a></li>
      <li><a href="./pages/galeri.php">Galeri</a></li>
    </ul>
  </div>
</nav>



  <!-- HERO -->
  <section class="hero">
    <div class="hero-content">
      <h1>Selamat Datang</h1>
      <h2>Website Resmi  Desa Brakas Dejeh</h2>
      <p>
        Sumber informasi terbaru tentang pemerintahan dan kegiatan
        masyarakat di  Desa Brakas Dejeh.
      </p>
    </div>


  </section>

  <!-- JELAJAHI DESA -->
  <section class="jelajahi">
    <div class="jelajahi-left">
      <h2>JELAJAHI DESA</h2>
      <p>
        Melalui website ini Anda dapat menjelajahi segala hal yang terkait
        dengan desa. Aspek pemerintahan, penduduk, demografi, potensi desa,
        dan juga berita tentang desa.
      </p>
    </div>

    <div class="jelajahi-right">
      <a href="./pages/profil-desa.php" class="card">
        <div class="icon">🏛️</div>
        <span>PROFIL DESA</span>
      </a>
      <a href="./pages/infografis/penduduk.php" class="card">
        <div class="icon">📈</div>
        <span>INFOGRAFIS</span>
      </a>
      <a href="idm.html" class="card">
        <div class="icon">👍</div>
        <span>APBDes</span>
      </a>
      <a href="ppid.html" class="card">
        <div class="icon">📄</div>
        <span>PPID</span>
      </a>
    </div>
  </section>


  <!-- SAMBUTAN KEPALA DESA -->
  <section class="sambutan">
    <div class="sambutan-container">
      <div class="sambutan-image">
        <img src="./uploads/sambutan/<?= $s['foto']; ?>" alt="Lambang Kabupaten Bangkalan">
      </div>
      <div class="sambutan-content">
        <h2>Sambutan Kepala Desa Brakas Dejeh</h2>
        <h3><?= $s['nama_kades']; ?></h3>
        <small><?= $s['jabatan']; ?></small>
        <div class="sambutan-text">
          <p><?= $s['isi']; ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- SOTK -->
  <section class="sotk">
    <div class="sotk-header">
      <h2>SOTK</h2>
      <p>Struktur Organisasi dan Tata Kerja  Desa Brakas Dejeh</p>
    </div>

    <div class="sotk-cards">
      <div class="sotk-card">
        <img src="./assets/img/abdulrohman.jpeg" alt="aa" />
        <div class="sotk-info">
          <strong>aa</strong>
          <span>Kaur Keuangan</span>
        </div>
      </div>

      <div class="sotk-card">
        <img src="./assets/img/abdulrohman.jpeg" alt="aa" />
        <div class="sotk-info">
          <strong>aa</strong>
          <span>Kepala Seksi Pelayanan dan Kesejahteraan</span>
        </div>
      </div>

      <div class="sotk-card">
        <img src="./assets/img/abdulrohman.jpeg" alt="aa aaa" />
        <div class="sotk-info">
          <strong>aa aaa</strong>
          <span>Kaur Umum dan Perencanaan</span>
        </div>
      </div>

      <div class="sotk-card">
        <img src="./assets/img/abdulrohman.jpeg" alt="aa" />
        <div class="sotk-info">
          <strong>aa</strong>
          <span>Kasi Pemerintahan</span>
        </div>
      </div>
    </div>

    <div class="sotk-more">
      <a href="./pages/sotk.php">📋 LIHAT STRUKTUR LEBIH LENGKAP</a>
    </div>
  </section>

<!-- ADMINISTRASI PENDUDUK -->
<section class="administrasi">
  <div class="administrasi-header">
    <h2>Administrasi Penduduk</h2>
    <p>
      Sistem digital yang berfungsi mempermudah pengelolaan data dan informasi
      terkait dengan kependudukan dan pendayagunaannya untuk pelayanan publik
      yang efektif dan efisien
    </p>
  </div>

  <div class="administrasi-grid">
    <div class="admin-box">
      <span class="angka"><strong><?= number_format($p['total_penduduk'] ?? 0) ?> </strong></span>
      <span class="label">Penduduk</span>
    </div>
    <div class="admin-box">
      <span class="angka"><strong><?= number_format($p['laki_laki'] ?? 0) ?> </strong></span>
      <span class="label">Laki-Laki</span>
    </div>

    <div class="admin-box">
      <span class="angka"><strong><?= number_format($p['kepala_keluarga'] ?? 0) ?> </strong></span>
      <span class="label">Kepala Keluarga</span>
    </div>
    <div class="admin-box">
      <span class="angka"><strong><?= number_format($p['perempuan'] ?? 0) ?> </strong></span>
      <span class="label">Perempuan</span>
    </div>

    <!-- <div class="admin-box">
      <span class="angka">97</span>
      <span class="label">Penduduk Sementara</span>
    </div>
    <div class="admin-box">
      <span class="angka">44</span>
      <span class="label">Mutasi Penduduk</span>
    </div> -->
  </div>
</section>

<!-- APB DESA -->
<section class="apb-desa">
  <div class="apb-container">

    <div class="apb-left">
      <img src="./assets/img/konten-apb.png" alt="APB Desa">
    </div>

    <div class="apb-right">
      <h2>APB DESA 2025</h2>
      <p class="apb-desc">
        Akses cepat dan transparan terhadap APB Desa serta proyek pembangunan
      </p>

      <div class="apb-card">
        <span class="apb-label">Pendapatan Desa</span>
        <strong>Rp4.254.715.300,00</strong>
      </div>

      <div class="apb-card">
        <span class="apb-label">Belanja Desa</span>
        <strong>Rp4.235.654.388,00</strong>
      </div>

      <a href="./pages/infografis/apbdes.php" class="apb-link">
        📊 LIHAT DATA LEBIH LENGKAP
      </a>
    </div>

  </div>
</section>

<!-- BERITA DESA -->
<section class="berita">
  <div class="berita-header">
    <h2>Berita Desa</h2>
    <p>
      Menyajikan informasi terbaru tentang peristiwa, berita terkini, dan
      artikel-artikel jurnalistik dari Desa Brakas Dejeh
    </p>
  </div>

  <div class="berita-grid">

    <!-- Card -->
    <article class="berita-card">
      <img src="../assets/img/hero-bg.jpeg" alt="Berita Desa">
      <div class="berita-content">
        <h3>POKDARWIS PANTAI BIRU KERSIK TERIMA BANTUAN GAZEBO DARI BANK...</h3>
        <p>
          Kersik – Kelompok Sadar Wisata (POKDARWIS) Pantai Biru
          Kersik menerima bantuan 10 (sepuluh) unit gazebo dari Bank...
        </p>

        <div class="berita-meta">
          <span>👁 Dilihat 60 kali</span>
          <span class="tanggal">18 Dec 2025</span>
        </div>
      </div>
    </article>

    <article class="berita-card">
      <img src="../assets/img/hero-bg.jpeg" alt="Berita Desa">
      <div class="berita-content">
        <h3>KEGIATAN GOTONG ROYONG WARGA RT.002 DESA KERSIK MELALUI BKKM RT</h3>
        <p>
          Kersik – Warga RT.002 Desa Kersik, Kecamatan Marang Kayu,
          Kabupaten Kutai Kartanegara, melaksanakan kegiatan gotong royong...
        </p>

        <div class="berita-meta">
          <span>👁 Dilihat 75 kali</span>
          <span class="tanggal">18 Dec 2025</span>
        </div>
      </div>
    </article>

    <article class="berita-card">
      <img src="../assets/img/hero-bg.jpeg" alt="Berita Desa">
      <div class="berita-content">
        <h3>RT DI DESA KERSIK TINGKATKAN PENJAGAAN KEAMANAN LINGKUNGAN</h3>
        <p>
          Kersik – Dalam upaya menciptakan lingkungan yang aman, tertib,
          dan kondusif, Ketua RT bersama warga Desa Kersik...
        </p>

        <div class="berita-meta">
          <span>👁 Dilihat 67 kali</span>
          <span class="tanggal">18 Dec 2025</span>
        </div>
      </div>
    </article>
  </div>

  <div class="berita-more">
    <a href="./pages/berita.php">📰 LIHAT BERITA LEBIH BANYAK</a>
  </div>
</section>

<!-- PETA DESA -->
<section class="peta-desa">
  <div class="peta-container">

    <div class="peta-header">
      <h2>Peta Desa</h2>
      <p>
        Lokasi dan wilayah administratif Desa Brakas Dejeh, Kecamatan Modung,
        Kabupaten Bangkalan, Jawa Timur
      </p>
    </div>

    <div class="peta-wrapper">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15051.118686145122!2d112.94018931304178!3d-7.166352228672062!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd81e31e7229e27%3A0xf6fb06135eadf3bb!2sBrakas%20Dajah%2C%20Kec.%20Modung%2C%20Kabupaten%20Bangkalan%2C%20Jawa%20Timur!5e1!3m2!1sid!2sid!4v1768202844000!5m2!1sid!2sid"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>

  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-container">

    <!-- Kolom 1 -->
    <div class="footer-col">
      <div class="footer-logo">
        <img src="./assets/img/logonew.png" alt="Logo Desa">
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


<script src="./assets/js/scripts.js"></script>
<script>
document.getElementById("hamburger").onclick = function () {
  document.getElementById("navMenu").classList.toggle("active");
};
</script>

</body>
</html>
