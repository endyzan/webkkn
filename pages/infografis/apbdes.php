<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>APBDes - Desa Brakas Dejeh</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- navbar -->
<nav class="navbar">
  <div class="nav-left">
    <img src="../../assets/img/logonew.png" alt="Logo Desa" />
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
        <h2>APB Desa Brakas Dejeh Tahun 2025</h2>
        <p>
          Desa Brakas Dejeh, Kecamatan Modung,<br>
          Kabupaten Bangkalan,<br>
          Provinsi Jawa Timur
        </p>
      </div>

      <!-- KANAN -->
      <div class="apbdes-summary">

        <!-- FILTER TAHUN -->
        <div class="apbdes-filter">
          <select>
            <option>2025</option>
            <option disabled>2026</option>
          </select>
        </div>

        <!-- PENDAPATAN & BELANJA -->
        <div class="apbdes-cards">
          <div class="apbdes-card green">
            <span>Pendapatan</span>
            <strong>Rp4.254.715.300,00</strong>
          </div>

          <div class="apbdes-card red">
            <span>Belanja</span>
            <strong>Rp4.235.654.388,75</strong>
          </div>
        </div>

        <!-- PEMBIAYAAN -->
        <div class="apbdes-box">
          <h4>Pembiayaan</h4>

          <div class="apbdes-cards">
            <div class="apbdes-card green">
              <span>Penerimaan</span>
              <strong>Rp125.939.088,75</strong>
            </div>

            <div class="apbdes-card red">
              <span>Pengeluaran</span>
              <strong>Rp145.000.000,00</strong>
            </div>
          </div>
        </div>

        <!-- SURPLUS -->
        <div class="apbdes-surplus">
          Surplus/Defisit <strong>Rp0,00</strong>
        </div>

      </div>
    </div>
  </section>



  <!-- APBDES -->
  <section class="dusun-section">
    <div class="dusun-container">

      <h2>Pendapatan dan Belanja Desa</h2>

      <div class="dusun-card">
        <canvas id="chartApbdes"></canvas>
      </div>

      <div class="dusun-keterangan">
        Grafik ini menampilkan perbandingan pendapatan dan belanja desa dari tahun ke tahun.
        Data ini bertujuan untuk meningkatkan transparansi pengelolaan keuangan desa
        serta memberikan gambaran perkembangan anggaran desa kepada masyarakat.
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

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('chartApbdes').getContext('2d');

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['2021', '2022', '2023', '2024', '2025'],
        datasets: [
          {
            label: 'Pendapatan Desa',
            data: [
              1164117188,
              1336738789,
              4613022000,
              4802205800,
              4254715300
            ],
            backgroundColor: '#2e7d32'
          },
          {
            label: 'Belanja Desa',
            data: [
              0,
              0,
              4796208687,
              4888222679,
              4235654389
            ],
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

  </script>
  <script src="../../assets/js/scripts.js"></script>

  </body>
</html>
