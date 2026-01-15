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

  <!-- PROFIL DESA - VISI MISI -->
  <section class="profil-visimisi">
    <div class="visimisi-container">

      <!-- VISI -->
      <div class="visimisi-card">
        <h2>Visi</h2>
        <p>
          “Desa Kersik sebagai Desa Wisata yang mampu mengelolah potensi Desa dan
          pembangunan berkelanjutan untuk mewujudkan masyarakat yang sejahtera”
        </p>
      </div>

      <!-- MISI -->
      <div class="visimisi-card">
        <h2>Misi</h2>
        <ol>
          <li>Mewujudkan tata kelola pemerintahan yang baik</li>
          <li>Mengembangkan kegiatan keagamaan</li>
          <li>Meningkatkan kualitas pendidikan dan sumber daya manusia</li>
          <li>Mengembangkan teknologi informasi</li>
          <li>Pembangunan infrastruktur, sarana dan prasarana</li>
        </ol>
      </div>

    </div>
  </section>

  <!-- BAGAN DESA -->
  <section class="bagan-desa">
    <div class="bagan-container">

      <h2>Bagan Desa</h2>

      <div class="bagan-grid">

        <!-- Struktur Pemerintahan Desa -->
        <div class="bagan-item">
          <h3>Struktur Organisasi Pemerintahan Desa</h3>
          <img src="../assets/img/hero-bg.jpeg"
              alt="Struktur Organisasi Pemerintahan Desa"
              class="bagan-thumb"
              onclick="openImage(this.src)">
        </div>

        <!-- Struktur BPD -->
        <div class="bagan-item">
          <h3>Struktur Organisasi Badan Permusyawaratan Desa</h3>
          <img src="../assets/img/abdulrohman.jpeg"
              alt="Struktur Organisasi Badan Permusyawaratan Desa"
              class="bagan-thumb"
              onclick="openImage(this.src)">
        </div>
      </div>
    </div>

      <!-- IMAGE MODAL -->
    <div id="imageModal" class="image-modal">
      <span class="close-modal" onclick="closeImage()">×</span>
      <img id="modalImage" src="" alt="Preview Gambar">
    </div>
  </section>


  <!-- SEJARAH DESA -->
  <section class="sejarah-desa">
    <div class="sejarah-container">

      <h2>Sejarah Desa</h2>

      <div class="sejarah-card">

        <!-- FOTO SEJARAH -->
        <div class="sejarah-foto">
          <img src="../assets/img/abdulrohman.jpeg"
              alt="Sejarah Desa Brakas Dejeh"
              class="sejarah-thumb"
              onclick="openImage(this.src)">
        </div>

        <!-- TEKS SEJARAH -->
        <div class="sejarah-text">
          <p>
            Desa Brakas Dejeh merupakan salah satu desa yang berada di wilayah
            Kecamatan Modung, Kabupaten Bangkalan. Berdasarkan cerita turun-temurun
            masyarakat setempat, nama Brakas Dejeh berasal dari kondisi geografis
            desa yang dahulu dikenal sebagai wilayah pertanian dan ladang rakyat.
          </p>

          <p>
            Pada awal berdirinya, Desa Brakas Dejeh dipimpin oleh tokoh masyarakat
            yang memiliki peran penting dalam mengatur kehidupan sosial,
            pemerintahan, serta adat istiadat desa. Seiring berjalannya waktu,
            desa ini terus mengalami perkembangan, baik dari segi pemerintahan,
            pembangunan infrastruktur, maupun peningkatan kualitas sumber daya
            manusia.
          </p>

          <p>
            Hingga saat ini, Desa Brakas Dejeh terus berkomitmen menjaga nilai-nilai
            budaya lokal sekaligus mendorong pembangunan berkelanjutan demi
            kesejahteraan masyarakat desa.
          </p>
        </div>

      </div>

    </div>
  </section>



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
              <p>Desa Santan Ulu dan Desa Santan Ilir</p>
            </div>
            <div>
              <strong>Timur</strong>
              <p>Selat Makassar</p>
            </div>
            <div>
              <strong>Selatan</strong>
              <p>Selat Makassar dan Desa Semangko</p>
            </div>
            <div>
              <strong>Barat</strong>
              <p>Desa Santan Ulu</p>
            </div>
          </div>

          <hr>

          <div class="peta-stat">
            <p><strong>Luas Desa:</strong> 4.000.000 m²</p>
            <p><strong>Jumlah Penduduk:</strong> 1.161 Jiwa</p>
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
