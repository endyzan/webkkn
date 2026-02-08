<?php
// Koneksi ke database
include '../../db.php';

// Query untuk mengambil data statistik bansos
$query = "SELECT 
            jb.nama_bansos,
            COUNT(pb.id) as jumlah_penerima,
            jb.keterangan
          FROM jenis_bansos jb
          LEFT JOIN penerima_bansos pb ON jb.id = pb.id_jenis_bansos 
            AND pb.status_penerimaan = 'diterima'
          WHERE jb.status = 'aktif'
          GROUP BY jb.id
          ORDER BY jb.nama_bansos";
$result = mysqli_query($conn, $query);

// Simpan data dalam array
$bansos_data = [];
$total_penerima = 0;
while($row = mysqli_fetch_assoc($result)) {
    $bansos_data[] = $row;
    $total_penerima += $row['jumlah_penerima'];
}

// Jika tidak ada data, tampilkan data default
if(empty($bansos_data)) {
    $bansos_data = [
        ['nama_bansos' => 'BPJS PBI Ketenagakerjaan', 'jumlah_penerima' => 67, 'keterangan' => 'BPJS Pemberi Bantuan Iuran Ketenagakerjaan'],
        ['nama_bansos' => 'PKH', 'jumlah_penerima' => 41, 'keterangan' => 'Program Keluarga Harapan'],
        ['nama_bansos' => 'BPNT', 'jumlah_penerima' => 35, 'keterangan' => 'Bantuan Pangan Non Tunai'],
        ['nama_bansos' => 'BLT 2024', 'jumlah_penerima' => 0, 'keterangan' => 'Bantuan Langsung Tunai Tahun 2024'],
        ['nama_bansos' => 'PSTN', 'jumlah_penerima' => 0, 'keterangan' => 'Program Sembako Terpadu Nasional']
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bansos - Desa Brakas Dajah</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <style>
    /* CSS khusus untuk halaman bansos */
    .page-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 60px 20px;
      text-align: center;
      margin-bottom: 40px;
    }
    
    .page-header h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }
    
    .page-header p {
      font-size: 1.1rem;
      opacity: 0.9;
      max-width: 800px;
      margin: 0 auto;
    }
    
    .stat-bansos {
      padding: 40px 20px;
      background: #f8f9fa;
    }
    
    .stat-container {
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .stat-header {
      text-align: center;
      margin-bottom: 40px;
    }
    
    .stat-header h2 {
      color: #333;
      font-size: 2rem;
      margin-bottom: 10px;
    }
    
    .stat-header p {
      color: #666;
      font-size: 1.1rem;
    }
    
    .bansos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
      margin-bottom: 50px;
    }
    
    .bansos-card {
      background: white;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      display: flex;
      flex-direction: column;
    }
    
    .bansos-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .bansos-card-header {
      background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
      color: white;
      padding: 20px;
      text-align: center;
    }
    
    .bansos-card-header h3 {
      font-size: 2.5rem;
      margin: 0;
      font-weight: bold;
    }
    
    .bansos-card-header span {
      font-size: 1rem;
      opacity: 0.9;
    }
    
    .bansos-card-body {
      padding: 25px;
      flex-grow: 1;
    }
    
    .bansos-card-body strong {
      color: #333;
      font-size: 1.3rem;
      margin-bottom: 10px;
      display: block;
    }
    
    .bansos-card-body p {
      color: #666;
      line-height: 1.6;
      margin-bottom: 15px;
    }
    
    .bansos-info {
      background: #fff3cd;
      border-left: 4px solid #ffc107;
      padding: 15px;
      border-radius: 0 5px 5px 0;
      margin-top: 15px;
    }
    
    .bansos-info small {
      color: #856404;
    }
    
    .total-stats {
      background: white;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      text-align: center;
      margin-top: 40px;
    }
    
    .total-stats h3 {
      color: #333;
      font-size: 1.5rem;
      margin-bottom: 20px;
    }
    
    .total-number {
      font-size: 3rem;
      font-weight: bold;
      color: #4CAF50;
      margin-bottom: 10px;
    }
    
    .legend {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 20px;
    }
    
    .legend-item {
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    .legend-color {
      width: 15px;
      height: 15px;
      border-radius: 3px;
    }
    
    .legend-text {
      color: #666;
      font-size: 0.9rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .page-header {
        padding: 40px 20px;
      }
      
      .page-header h1 {
        font-size: 2rem;
      }
      
      .bansos-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }
      
      .total-number {
        font-size: 2.5rem;
      }
    }
    
    @media (max-width: 480px) {
      .page-header h1 {
        font-size: 1.8rem;
      }
      
      .bansos-card-header h3 {
        font-size: 2rem;
      }
      
      .total-number {
        font-size: 2rem;
      }
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

<!-- HEADER HALAMAN -->
<header class="page-header">
  <h1>Bantuan Sosial (Bansos)</h1>
  <p>Informasi terkini mengenai program bantuan sosial yang disalurkan kepada masyarakat Desa Brakas Dajah</p>
</header>

<!-- STATISTIK BANSOS -->
<section class="stat-bansos">
  <div class="stat-container">
    
    <div class="stat-header">
      <h2>Jumlah Penerima Bansos</h2>
      <p>Data penerima bantuan sosial berdasarkan jenis program</p>
    </div>

    <div class="bansos-grid">
      <?php foreach($bansos_data as $index => $bansos): 
        // Warna berbeda untuk setiap kartu
        $colors = [
          ['#4CAF50', '#2E7D32'], // Hijau
          ['#2196F3', '#0D47A1'], // Biru
          ['#FF9800', '#F57C00'], // Oranye
          ['#9C27B0', '#6A1B9A'], // Ungu
          ['#F44336', '#C62828'], // Merah
          ['#00BCD4', '#00838F'], // Cyan
        ];
        $color_index = $index % count($colors);
      ?>
      <div class="bansos-card">
        <div class="bansos-card-header" style="background: linear-gradient(135deg, <?= $colors[$color_index][0] ?> 0%, <?= $colors[$color_index][1] ?> 100%);">
          <h3><?= $bansos['jumlah_penerima'] ?></h3>
          <span>Penduduk</span>
        </div>
        <div class="bansos-card-body">
          <strong><?= htmlspecialchars($bansos['nama_bansos']) ?></strong>
          <p>mendapatkan bantuan dari program pemerintah</p>
          <?php if(!empty($bansos['keterangan'])): ?>
          <div class="bansos-info">
            <small><?= htmlspecialchars($bansos['keterangan']) ?></small>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- TOTAL STATISTIK -->
    <div class="total-stats">
      <h3>Total Penerima Bantuan Sosial</h3>
      <div class="total-number"><?= $total_penerima ?></div>
      <p>Warga Desa Brakas Dajah yang menerima berbagai bentuk bantuan sosial</p>
      
      <div class="legend">
        <div class="legend-item">
          <div class="legend-color" style="background: #4CAF50;"></div>
          <span class="legend-text">BPJS PBI Ketenagakerjaan</span>
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background: #2196F3;"></div>
          <span class="legend-text">PKH</span>
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background: #FF9800;"></div>
          <span class="legend-text">BPNT</span>
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background: #9C27B0;"></div>
          <span class="legend-text">BLT 2024</span>
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background: #F44336;"></div>
          <span class="legend-text">PSTN</span>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- INFORMASI TAMBAHAN -->
<section class="stat-bansos" style="background: white; padding: 60px 20px;">
  <div class="stat-container">
    <div style="text-align: center; max-width: 800px; margin: 0 auto;">
      <h2 style="color: #333; margin-bottom: 20px;">Tentang Program Bansos</h2>
      <p style="color: #666; line-height: 1.8; margin-bottom: 30px;">
        Program Bantuan Sosial (Bansos) merupakan bentuk kepedulian pemerintah terhadap masyarakat yang membutuhkan. 
        Desa Brakas Dajah berkomitmen untuk mendistribusikan bantuan secara transparan, tepat sasaran, dan berkeadilan 
        sesuai dengan ketentuan yang berlaku.
      </p>
      <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
        <div style="text-align: center; padding: 20px; border-radius: 10px; background: #f8f9fa; min-width: 200px;">
          <div style="font-size: 2rem; color: #4CAF50; margin-bottom: 10px;">🎯</div>
          <h3 style="color: #333; margin-bottom: 10px;">Tepat Sasaran</h3>
          <p style="color: #666; font-size: 0.9rem;">Data penerima diverifikasi dengan ketat</p>
        </div>
        <div style="text-align: center; padding: 20px; border-radius: 10px; background: #f8f9fa; min-width: 200px;">
          <div style="font-size: 2rem; color: #2196F3; margin-bottom: 10px;">⚖️</div>
          <h3 style="color: #333; margin-bottom: 10px;">Adil dan Merata</h3>
          <p style="color: #666; font-size: 0.9rem;">Distribusi berdasarkan kebutuhan prioritas</p>
        </div>
        <div style="text-align: center; padding: 20px; border-radius: 10px; background: #f8f9fa; min-width: 200px;">
          <div style="font-size: 2rem; color: #FF9800; margin-bottom: 10px;">📋</div>
          <h3 style="color: #333; margin-bottom: 10px;">Transparan</h3>
          <p style="color: #666; font-size: 0.9rem;">Data terbuka untuk pengawasan publik</p>
        </div>
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
    © 2026 Pemerintah Desa Brakas Dajah. KKN 2025/2026.
  </div>
</footer>

<script src="../../assets/js/scripts.js"></script>
<script>
  // Animasi untuk kartu bansos
  document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.bansos-card');
    
    cards.forEach((card, index) => {
      // Animasi muncul
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      
      setTimeout(() => {
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
      }, index * 100);
    });
    
    // Update tahun di footer secara dinamis
    const footerYear = document.querySelector('.footer-bottom');
    if (footerYear) {
      const currentYear = new Date().getFullYear();
      footerYear.textContent = footerYear.textContent.replace('2026', currentYear);
    }
  });
</script>

</body>
</html>