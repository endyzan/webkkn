<?php
include '../../db.php';

// Ambil data tahun yang tersedia
$years_query = mysqli_query($conn, "SELECT DISTINCT tahun FROM apbdes ORDER BY tahun DESC");
$years = [];
while($row = mysqli_fetch_assoc($years_query)) {
    $years[] = $row['tahun'];
}

// Tentukan tahun yang dipilih (default tahun terbaru)
$selected_year = isset($_GET['tahun']) ? $_GET['tahun'] : (count($years) > 0 ? $years[0] : date('Y'));

// Ambil data APBDes untuk tahun yang dipilih
$query = "SELECT * FROM apbdes WHERE tahun = '$selected_year'";
$result = mysqli_query($conn, $query);
$apbdes = mysqli_fetch_assoc($result);

// Ambil data untuk chart (5 tahun terakhir)
$chart_query = mysqli_query($conn, "
    SELECT tahun, pendapatan, belanja 
    FROM apbdes 
    WHERE tahun >= YEAR(CURDATE()) - 4
    ORDER BY tahun ASC
");
$chart_data = [];
while($row = mysqli_fetch_assoc($chart_query)) {
    $chart_data[] = $row;
}

// Ambil detail pendapatan jika ada data
$pendapatan_details = [];
if ($apbdes) {
    $pendapatan_query = mysqli_query($conn, "SELECT * FROM apbdes_pendapatan WHERE apbdes_id = '{$apbdes['id']}'");
    while($row = mysqli_fetch_assoc($pendapatan_query)) {
        $pendapatan_details[] = $row;
    }
}

// Ambil detail belanja jika ada data
$belanja_details = [];
if ($apbdes) {
    $belanja_query = mysqli_query($conn, "SELECT * FROM apbdes_belanja WHERE apbdes_id = '{$apbdes['id']}'");
    while($row = mysqli_fetch_assoc($belanja_query)) {
        $belanja_details[] = $row;
    }
}

// Ambil detail pembiayaan jika ada data
$pembiayaan_details = [];
if ($apbdes) {
    $pembiayaan_query = mysqli_query($conn, "SELECT * FROM apbdes_pembiayaan WHERE apbdes_id = '{$apbdes['id']}'");
    while($row = mysqli_fetch_assoc($pembiayaan_query)) {
        $pembiayaan_details[] = $row;
    }
}

// Hitung surplus/defisit
$surplus = 0;
if ($apbdes) {
    $surplus = $apbdes['pendapatan'] - $apbdes['belanja'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>APBDes - Desa Brakas Dajah</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
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

  <!-- APBDES HEADER -->
  <section class="apbdes-header">
    <div class="apbdes-container">

      <!-- KIRI -->
      <div class="apbdes-info">
        <h2>APB Desa Brakas Dajah Tahun <?= $selected_year; ?></h2>
        <p>
          Desa Brakas Dajah, Kecamatan Modung,<br>
          Kabupaten Bangkalan,<br>
          Provinsi Jawa Timur
        </p>
      </div>

      <!-- KANAN -->
      <div class="apbdes-summary">

        <!-- FILTER TAHUN -->
        <div class="apbdes-filter">
          <form method="GET" action="">
            <select name="tahun" onchange="this.form.submit()">
              <?php if(empty($years)): ?>
                <option value="<?= date('Y'); ?>"><?= date('Y'); ?></option>
              <?php else: ?>
                <?php foreach($years as $year): ?>
                  <option value="<?= $year; ?>" <?= $year == $selected_year ? 'selected' : ''; ?>>
                    <?= $year; ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </form>
        </div>

        <?php if($apbdes): ?>
          <!-- PENDAPATAN & BELANJA -->
          <div class="apbdes-cards">
            <div class="apbdes-card green">
              <span>Pendapatan</span>
              <strong>Rp<?= number_format($apbdes['pendapatan'], 0, ',', '.'); ?></strong>
            </div>

            <div class="apbdes-card red">
              <span>Belanja</span>
              <strong>Rp<?= number_format($apbdes['belanja'], 0, ',', '.'); ?></strong>
            </div>
          </div>

          <!-- PEMBIAYAAN -->
          <div class="apbdes-box">
            <h4>Pembiayaan</h4>

            <div class="apbdes-cards">
              <div class="apbdes-card green">
                <span>Penerimaan</span>
                <strong>Rp<?= number_format($apbdes['pembiayaan_penerimaan'], 0, ',', '.'); ?></strong>
              </div>

              <div class="apbdes-card red">
                <span>Pengeluaran</span>
                <strong>Rp<?= number_format($apbdes['pembiayaan_pengeluaran'], 0, ',', '.'); ?></strong>
              </div>
            </div>
          </div>

          <!-- SURPLUS -->
          <div class="apbdes-surplus">
            Surplus/Defisit <strong>Rp<?= number_format($surplus, 0, ',', '.'); ?></strong>
          </div>
        <?php else: ?>
          <div style="text-align: center; padding: 20px; color: #666;">
            Data APBDes tahun <?= $selected_year; ?> belum tersedia.
          </div>
        <?php endif; ?>

      </div>
    </div>
  </section>

  <!-- APBDES CHART -->
  <section class="dusun-section">
    <div class="dusun-container">
      <h2>Pendapatan dan Belanja Desa</h2>
      
      <?php if(!empty($chart_data)): ?>
        <div class="dusun-card">
          <canvas id="chartApbdes"></canvas>
        </div>
      <?php else: ?>
        <div style="text-align: center; padding: 40px; color: #666;">
          Data grafik belum tersedia.
        </div>
      <?php endif; ?>

      <div class="dusun-keterangan">
        Grafik ini menampilkan perbandingan pendapatan dan belanja desa dari tahun ke tahun.
        Data ini bertujuan untuk meningkatkan transparansi pengelolaan keuangan desa
        serta memberikan gambaran perkembangan anggaran desa kepada masyarakat.
      </div>
    </div>
  </section>

<?php if($apbdes): ?>
<!-- DETAIL APBDES -->
<section class="apbdes-detail">
  <div class="apbdes-detail-container">

    <!-- PENDAPATAN -->
    <h3 class="detail-title">Pendapatan Desa <?= $selected_year; ?></h3>
    <div class="detail-card">
      <?php if(!empty($pendapatan_details)): ?>
        <canvas id="chartPendapatan"></canvas>
      <?php endif; ?>

      <div class="detail-list">
        <?php if(!empty($pendapatan_details)): ?>
          <?php foreach($pendapatan_details as $item): ?>
            <div class="detail-item">
              <span><?= htmlspecialchars($item['jenis']); ?></span>
              <div class="progress">
                <div class="progress-bar green" style="width:<?= $item['persentase']; ?>%">
                  <?= $item['persentase']; ?>%
                </div>
              </div>
              <strong>Rp<?= number_format($item['jumlah'], 0, ',', '.'); ?></strong>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div style="text-align: center; padding: 20px; color: #666;">
            Detail pendapatan belum tersedia.
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- BELANJA -->
    <h3 class="detail-title">Belanja Desa <?= $selected_year; ?></h3>
    <div class="detail-card">
      <?php if(!empty($belanja_details)): ?>
        <canvas id="chartBelanja"></canvas>
      <?php endif; ?>

      <div class="detail-list">
        <?php if(!empty($belanja_details)): ?>
          <?php foreach($belanja_details as $item): ?>
            <div class="detail-item">
              <span><?= htmlspecialchars($item['jenis']); ?></span>
              <div class="progress">
                <div class="progress-bar green" style="width:<?= $item['persentase']; ?>%">
                  <?= $item['persentase']; ?>%
                </div>
              </div>
              <strong>Rp<?= number_format($item['jumlah'], 0, ',', '.'); ?></strong>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div style="text-align: center; padding: 20px; color: #666;">
            Detail belanja belum tersedia.
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- PEMBIAYAAN -->
    <h3 class="detail-title">Pembiayaan Desa <?= $selected_year; ?></h3>
    <div class="detail-card">
      <?php if(!empty($pembiayaan_details)): ?>
        <canvas id="chartPembiayaan"></canvas>
      <?php endif; ?>

      <div class="detail-list">
        <?php if(!empty($pembiayaan_details)): ?>
          <?php foreach($pembiayaan_details as $item): ?>
            <div class="detail-item">
              <span><?= ucfirst($item['jenis']); ?></span>
              <div class="progress">
                <div class="progress-bar green" style="width:<?= $item['persentase']; ?>%">
                  <?= $item['persentase']; ?>%
                </div>
              </div>
              <strong>Rp<?= number_format($item['jumlah'], 0, ',', '.'); ?></strong>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div style="text-align: center; padding: 20px; color: #666;">
            Detail pembiayaan belum tersedia.
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</section>
<?php endif; ?>

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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if(!empty($chart_data)): ?>
// Chart APBDes (Pendapatan vs Belanja per tahun)
const ctx = document.getElementById('chartApbdes').getContext('2d');
const years = <?= json_encode(array_column($chart_data, 'tahun')); ?>;
const pendapatanData = <?= json_encode(array_column($chart_data, 'pendapatan')); ?>;
const belanjaData = <?= json_encode(array_column($chart_data, 'belanja')); ?>;

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: years,
    datasets: [
      {
        label: 'Pendapatan Desa',
        data: pendapatanData,
        backgroundColor: '#2e7d32'
      },
      {
        label: 'Belanja Desa',
        data: belanjaData,
        backgroundColor: '#a5eb78'
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top'
      }
    },
    scales: {
      y: {
        ticks: {
          callback: function(value) {
            return 'Rp' + value.toLocaleString('id-ID');
          }
        }
      }
    }
  }
});
<?php endif; ?>

<?php if(!empty($pendapatan_details)): ?>
// Chart Pendapatan
const pendapatanLabels = <?= json_encode(array_column($pendapatan_details, 'jenis')); ?>;
const pendapatanValues = <?= json_encode(array_column($pendapatan_details, 'jumlah')); ?>;

new Chart(document.getElementById('chartPendapatan'), {
  type: 'bar',
  data: {
    labels: pendapatanLabels,
    datasets: [{
      data: pendapatanValues,
      backgroundColor: '#2e7d32'
    }]
  },
  options: { 
    plugins: {
      legend: {
        display: false
      }
    },
    scales: {
      y: {
        ticks: {
          callback: function(value) {
            return 'Rp' + value.toLocaleString('id-ID');
          }
        }
      }
    }
  }
});
<?php endif; ?>

<?php if(!empty($belanja_details)): ?>
// Chart Belanja
const belanjaLabels = <?= json_encode(array_column($belanja_details, 'jenis')); ?>;
const belanjaValues = <?= json_encode(array_column($belanja_details, 'jumlah')); ?>;

new Chart(document.getElementById('chartBelanja'), {
  type: 'bar',
  data: {
    labels: belanjaLabels,
    datasets: [{
      data: belanjaValues,
      backgroundColor: '#a5eb78'
    }]
  },
  options: { 
    plugins: {
      legend: {
        display: false
      }
    },
    scales: {
      y: {
        ticks: {
          callback: function(value) {
            return 'Rp' + value.toLocaleString('id-ID');
          }
        }
      }
    }
  }
});
<?php endif; ?>

<?php if(!empty($pembiayaan_details)): ?>
// Chart Pembiayaan
const pembiayaanLabels = <?= json_encode(array_map(function($item) { 
  return ucfirst($item['jenis']); 
}, $pembiayaan_details)); ?>;
const pembiayaanValues = <?= json_encode(array_column($pembiayaan_details, 'jumlah')); ?>;

new Chart(document.getElementById('chartPembiayaan'), {
  type: 'bar',
  data: {
    labels: pembiayaanLabels,
    datasets: [{
      data: pembiayaanValues,
      backgroundColor: '#2e7d32'
    }]
  },
  options: { 
    plugins: {
      legend: {
        display: false
      }
    },
    scales: {
      y: {
        ticks: {
          callback: function(value) {
            return 'Rp' + value.toLocaleString('id-ID');
          }
        }
      }
    }
  }
});
<?php endif; ?>
</script>

<script src="../../assets/js/scripts.js"></script>

</body>
</html>