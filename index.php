<?php
include './db.php';

// Ambil data hero aktif
$hero = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM hero WHERE status='aktif' LIMIT 1")
);

$s = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM sambutan WHERE status='aktif' LIMIT 1")
);
$p = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM statistik_penduduk ORDER BY id DESC LIMIT 1")
);
// Ambil status penduduk (sementara & pindah)
$status_query = mysqli_query($conn, "
    SELECT 
        SUM(CASE WHEN status_penduduk = 'penduduk_sementara' THEN 1 ELSE 0 END) as sementara,
        SUM(CASE WHEN status_penduduk = 'pindah' THEN 1 ELSE 0 END) as pindah
    FROM penduduk
");

$status = mysqli_fetch_assoc($status_query);

// ========================== SOTK ====================================
$sotk_index = mysqli_query($conn, "
    SELECT * FROM sotk 
    WHERE status='aktif'
    ORDER BY urutan ASC
    LIMIT 4
");



// ========================== berita ====================================
// ambil 4 berita terbaru
$berita = mysqli_query($conn, "
  SELECT * FROM berita 
  WHERE status='publish' 
  ORDER BY dilihat DESC 
  LIMIT 4
");

// ========================== banner/hero ====================================
// Set default jika hero tidak ada
if (!$hero) {
    $hero = [
        'judul' => 'Selamat Datang',
        'subjudul' => 'Website Resmi Desa Brakas Dajah',
        'deskripsi' => 'Sumber informasi terbaru tentang pemerintahan dan kegiatan masyarakat di Desa Brakas Dajah.',
        'gambar' => 'hero-bg.jpeg'
    ];
}

// ========================== apbdes ====================================
// ambil data APBDes terbaru
$apbdes_query = mysqli_query($conn, "
    SELECT * FROM apbdes 
    ORDER BY tahun DESC 
    LIMIT 1
");
$apbdes = mysqli_fetch_assoc($apbdes_query);

// ambil detail pendapatan APBDes
$pendapatan_data = [];
if ($apbdes) {
    $pendapatan_query = mysqli_query($conn, "
        SELECT * FROM apbdes_pendapatan 
        WHERE apbdes_id = '{$apbdes['id']}'
    ");
    while($row = mysqli_fetch_assoc($pendapatan_query)) {
        $pendapatan_data[] = $row;
    }
}

// ambil detail belanja APBDes
$belanja_data = [];
if ($apbdes) {
    $belanja_query = mysqli_query($conn, "
        SELECT * FROM apbdes_belanja 
        WHERE apbdes_id = '{$apbdes['id']}'
    ");
    while($row = mysqli_fetch_assoc($belanja_query)) {
        $belanja_data[] = $row;
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title> Desa Brakas Dajah</title>
  <link rel="stylesheet" href="./assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">
  <div class="nav-left">
    <a href="./index.php" style="display:flex; align-items:center; gap:10px; text-decoration:none; color:inherit;">
      <img src="./assets/img/logonew.png" alt="Logo Desa" />

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
      <li><a href="./index.php">Home</a></li>
      <li><a href="./pages/profil-desa.php">Profil Desa</a></li>

      <li class="dropdown">
        <a href="#" class="dropdown-toggle">Infografis
        <span class="arrow">▼</span>
        </a>
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


  <!-- HERO SECTION DINAMIS -->
  <section class="hero" style="background: url('./uploads/hero/<?= htmlspecialchars($hero['gambar']) ?>') center/cover no-repeat;">
      <div class="hero-content">
          <h1><?= htmlspecialchars($hero['judul']) ?></h1>
          <h2><?= htmlspecialchars($hero['subjudul']) ?></h2>
          <p><?= htmlspecialchars($hero['deskripsi']) ?></p>
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
        <h2>Sambutan Kepala Desa Brakas Dajah</h2>
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
      <p>Struktur Organisasi dan Tata Kerja  Desa Brakas Dajah</p>
    </div>

    <div class="sotk-cards">

      <?php while($row = mysqli_fetch_assoc($sotk_index)): ?>
        <div class="sotk-card">
          <img 
            src="./uploads/sotk/<?= $row['foto'] ?: 'default.png'; ?>" 
            alt="<?= $row['nama']; ?>"
          >
          <div class="sotk-info">
            <strong><?= strtoupper($row['nama']); ?></strong>
            <span><?= $row['jabatan']; ?></span>
          </div>
        </div>
      <?php endwhile; ?>

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

    <div class="admin-box">
      <span class="angka"><strong><?= number_format($status['sementara'] ?? 0) ?></strong></span>
      <span class="label">Penduduk Sementara</span>
    </div>

    <div class="admin-box">
      <span class="angka"><strong><?= number_format($status['pindah'] ?? 0) ?></strong></span>
      <span class="label">Mutasi Penduduk</span>
    </div>

  </div>
</section>

<!-- APB DESA -->
<section class="apb-desa">
  <div class="apb-container">

    <div class="apb-left">
      <img src="./assets/img/konten-apb.png" alt="APB Desa">
    </div>

    <div class="apb-right">
      <h2>APB DESA <?= isset($apbdes['tahun']) ? $apbdes['tahun'] : date('Y'); ?></h2>
      <p class="apb-desc">
        Akses cepat dan transparan terhadap APB Desa serta proyek pembangunan
      </p>

      <div class="apb-card">
        <span class="apb-label">Pendapatan Desa</span>
        <strong>Rp<?= isset($apbdes['pendapatan']) ? number_format($apbdes['pendapatan'], 0, ',', '.') : '0'; ?>,00</strong>
      </div>

      <div class="apb-card">
        <span class="apb-label">Belanja Desa</span>
        <strong>Rp<?= isset($apbdes['belanja']) ? number_format($apbdes['belanja'], 0, ',', '.') : '0'; ?>,00</strong>
      </div>

      <?php if (isset($apbdes['pendapatan']) && isset($apbdes['belanja'])): ?>
      <div class="apb-card" style="background: #f8f9fa;">
        <span class="apb-label">Surplus/Defisit</span>
        <strong style="color: <?= ($apbdes['pendapatan'] - $apbdes['belanja']) >= 0 ? '#2e7d32' : '#f44336'; ?>">
          Rp<?= number_format($apbdes['pendapatan'] - $apbdes['belanja'], 0, ',', '.'); ?>,00
        </strong>
      </div>
      <?php endif; ?>

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
      artikel-artikel jurnalistik dari Desa Brakas Dajah
    </p>
  </div>

  <div class="berita-grid">

    <!-- Card -->
    <div class="berita-grid">

    <?php while($b = mysqli_fetch_assoc($berita)): ?>
    <a href="./pages/berita_detail.php?id=<?= $b['id'] ?>" style="text-decoration:none;color:inherit;">

    <article class="berita-card">
      <img src="./uploads/berita/<?= htmlspecialchars($b['gambar']) ?>" alt="">

      <div class="berita-content">
        <h3>
          <?= strlen($b['judul']) > 60 
              ? substr($b['judul'],0,60).'...' 
              : $b['judul']; ?>
        </h3>

        <p><?= substr(strip_tags($b['isi']),0,100) ?>...</p>

        <div class="berita-meta">
          <span>👁 Dilihat <?= (int)$b['dilihat'] ?> kali</span>
          <span class="tanggal"><?= date('d M Y', strtotime($b['tanggal'])) ?></span>
        </div>
      </div>
    </article>

    </a>
    <?php endwhile; ?>

    </div>

  </div>

  <div class="berita-more">
    <a href="./pages/berita.php" class="btn-more">
      Lihat Berita Lebih Banyak →
    </a>
  </div>

</section>

<!-- PETA DESA -->
<section class="peta-desa">
  <div class="peta-container">

    <div class="peta-header">
      <h2>Peta Desa</h2>
      <p>
        Lokasi dan wilayah administratif Desa Brakas Dajah, Kecamatan Modung,
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

<!-- Include Chatbot -->
<?php include './chatbot_interface_ai.php'; ?>


<!-- FOOTER -->
<footer class="footer">
  <div class="footer-container">

    <!-- Kolom 1 -->
    <div class="footer-col">
      <div class="footer-logo">
        <img src="./assets/img/logonew.png" alt="Logo Desa">
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


<script src="./assets/js/scripts.js"></script>

</body>
</html>
