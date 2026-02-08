<?php
include '../db.php';

// ==== PARAMETER ====
$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'terbaru';

// pagination
$limit = 8; // 4 kolom x 2 baris (pas sama grid kamu)
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = ($page < 1) ? 1 : $page;
$offset = ($page - 1) * $limit;

// order
$order = ($filter == 'terlama') ? 'ASC' : 'DESC';

// where
$where = "WHERE status='publish'";
if ($search != '') {
    $where .= " AND judul LIKE '%$search%'";
}

// query data
$query = "SELECT * FROM berita 
          $where 
          ORDER BY tanggal $order 
          LIMIT $limit OFFSET $offset";
$data = mysqli_query($conn, $query);

// total data
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM berita $where");
$totalData  = mysqli_fetch_assoc($totalQuery)['total'];
$totalPage  = ceil($totalData / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Berita - Desa Brakas Dajah</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="nav-left">
    <a href="../index.php" style="display:flex; align-items:center; gap:10px; text-decoration:none; color:inherit;">
      <img src="../assets/img/logonew.png" alt="Logo Desa" />

      <div class="text">
        <strong>Desa Brakas Dajah</strong><br />
        Kabupaten Bangkalan
      </div>
    </a>
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

<!-- ===== BERITA ===== -->
<section class="berita">
  <div class="berita-container">

    <!-- HEADER -->
    <div class="berita-header">
      <h2>Berita Desa</h2>
      <p>
        Menyajikan informasi terbaru tentang peristiwa, berita terkini,
        dan artikel jurnalistik dari Desa Brakas Dajah.
      </p>
    </div>

    <!-- SEARCH & FILTER -->
    <form method="GET" class="berita-tools" style="margin-bottom:30px;display:flex;gap:10px;">
      <input 
        type="text" 
        name="q" 
        placeholder="Cari berita..."
        value="<?= htmlspecialchars($search) ?>"
        style="padding:8px 10px;width:220px;"
      >

      <select name="filter" onchange="this.form.submit()" style="padding:8px;">
        <option value="terbaru" <?= $filter=='terbaru'?'selected':'' ?>>Terbaru</option>
        <option value="terlama" <?= $filter=='terlama'?'selected':'' ?>>Terlama</option>
      </select>

      <button type="submit" style="padding:8px 14px;cursor:pointer;">Cari</button>
    </form>

    <!-- GRID -->
    <div class="berita-grid">

      <?php if(mysqli_num_rows($data) == 0): ?>
        <p>Tidak ada berita.</p>
      <?php endif; ?>

      <?php while($b = mysqli_fetch_assoc($data)): ?>
        <a href="berita_detail.php?id=<?= $b['id'] ?>" style="text-decoration:none;color:inherit;">

        <div class="berita-card">
          <img src="../uploads/berita/<?= htmlspecialchars($b['gambar']) ?>" alt="">
          <div class="berita-content">
            <h3>
            <?= strlen($b['judul']) > 70 
                ? htmlspecialchars(substr($b['judul'], 0, 70)) . '...' 
                : htmlspecialchars($b['judul']); ?>
            </h3>
            <p><?= substr(strip_tags($b['isi']), 0, 120) ?>...</p>
            <div class="berita-meta">
              <span><?= htmlspecialchars($b['penulis']) ?> • Dilihat <?= (int)$b['dilihat'] ?> kali</span>
              <span class="tanggal"><?= date('d M Y', strtotime($b['tanggal'])) ?></span>
            </div>
          </div>
        </div>
      </a>
      <?php endwhile; ?>

    </div>

    <!-- PAGINATION -->
    <?php if($totalPage > 1): ?>
    <div class="pagination" style="margin-top:40px;text-align:center;">
      <?php if($page > 1): ?>
        <a href="?page=<?= $page-1 ?>&q=<?= urlencode($search) ?>&filter=<?= $filter ?>">« Prev</a>
      <?php endif; ?>

      <?php for($i=1; $i<=$totalPage; $i++): ?>
        <a 
          href="?page=<?= $i ?>&q=<?= urlencode($search) ?>&filter=<?= $filter ?>"
          style="margin:0 4px;padding:6px 12px;border:1px solid #ddd;text-decoration:none;
          <?= ($i==$page)?'background:#6cc24a;color:#fff;border-color:#6cc24a':'' ?>"
        >
          <?= $i ?>
        </a>
      <?php endfor; ?>

      <?php if($page < $totalPage): ?>
        <a href="?page=<?= $page+1 ?>&q=<?= urlencode($search) ?>&filter=<?= $filter ?>">Next »</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  </div>
</section>



    <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-container">

      <!-- Kolom 1 -->
      <div class="footer-col">
        <div class="footer-logo">
          <img src="../assets/img/logonew.png" alt="Logo Desa">
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
      © 2026 Pemerintah Desa Brakas Dajah. KKN 2025/2026.
    </div>
  </footer>

  <script src="../assets/js/scripts.js"></script>

  </body>
</html>
