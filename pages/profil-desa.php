<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil Desa - Desa Brakas Dejeh</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    /* Tambahan styling khusus untuk halaman profil */
    .profil-desa {
      padding: 100px 80px 80px;
      background: #ffffff;
    }
    
    .profil-header {
      text-align: center;
      margin-bottom: 40px;
    }
    
    .profil-header h1 {
      color: #6cc24a;
      font-size: 36px;
      font-weight: 800;
      margin-bottom: 10px;
    }
    
    .profil-header p {
      color: #666;
      max-width: 800px;
      margin: 0 auto;
      line-height: 1.6;
    }
    
    .profil-content {
      max-width: 1000px;
      margin: 0 auto;
    }
    
    /* Visi dan Misi */
    .visi-misi {
      background: #f9f9f9;
      border-radius: 10px;
      padding: 40px;
      margin-bottom: 40px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .visi-container, .misi-container {
      margin-bottom: 30px;
    }
    
    .visi-title, .misi-title {
      color: #8b0000;
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 15px;
      padding-bottom: 8px;
      border-bottom: 2px solid #6cc24a;
      display: flex;
      align-items: center;
    }
    
    .visi-title:before, .misi-title:before {
      content: "🎯";
      margin-right: 10px;
      font-size: 20px;
    }
    
    .misi-title:before {
      content: "📋";
    }
    
    .visi-text {
      font-size: 18px;
      line-height: 1.8;
      color: #333;
      font-style: italic;
      padding-left: 20px;
      border-left: 3px solid #6cc24a;
      margin-left: 10px;
    }
    
    .misi-list {
      list-style: none;
      padding-left: 0;
    }
    
    .misi-list li {
      display: flex;
      align-items: flex-start;
      margin-bottom: 15px;
      padding: 12px 15px;
      background: #fff;
      border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      transition: transform 0.2s ease;
    }
    
    .misi-list li:hover {
      transform: translateX(5px);
    }
    
    .misi-number {
      background: #6cc24a;
      color: white;
      font-weight: bold;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      flex-shrink: 0;
    }
    
    .misi-text {
      font-size: 16px;
      line-height: 1.6;
      color: #333;
    }
    
    /* Sejarah dan Geografi */
    .sejarah-geografi {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
      margin-bottom: 40px;
    }
    
    .sejarah-box, .geografi-box {
      background: #f9f9f9;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .section-title {
      color: #8b0000;
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
    }
    
    .sejarah-box .section-title:before {
      content: "📜";
      margin-right: 10px;
    }
    
    .geografi-box .section-title:before {
      content: "🗺️";
      margin-right: 10px;
    }
    
    .sejarah-text, .geografi-text {
      font-size: 15px;
      line-height: 1.7;
      color: #333;
    }
    
    .sejarah-text p, .geografi-text p {
      margin-bottom: 15px;
    }
    
    /* Demografi */
    .demografi {
      background: #f9f9f9;
      border-radius: 10px;
      padding: 30px;
      margin-bottom: 40px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .demografi .section-title:before {
      content: "👥";
      margin-right: 10px;
    }
    
    .demografi-stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
      margin-top: 20px;
    }
    
    .stat-box {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
    
    .stat-number {
      display: block;
      font-size: 26px;
      font-weight: 800;
      color: #6cc24a;
      margin-bottom: 5px;
    }
    
    .stat-label {
      font-size: 14px;
      color: #555;
    }
    
    /* Potensi Desa */
    .potensi-desa {
      background: #f9f9f9;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .potensi-desa .section-title:before {
      content: "🌟";
      margin-right: 10px;
    }
    
    .potensi-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-top: 20px;
    }
    
    .potensi-card {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
      transition: transform 0.2s ease;
    }
    
    .potensi-card:hover {
      transform: translateY(-5px);
    }
    
    .potensi-icon {
      font-size: 40px;
      margin-bottom: 15px;
      display: block;
    }
    
    .potensi-name {
      font-size: 16px;
      font-weight: 700;
      color: #8b0000;
      margin-bottom: 10px;
    }
    
    .potensi-desc {
      font-size: 14px;
      color: #555;
      line-height: 1.5;
    }
    
    /* Tombol kembali */
    .back-button {
      text-align: center;
      margin-top: 50px;
    }
    
    .back-button a {
      display: inline-block;
      background: #6cc24a;
      color: white;
      padding: 12px 30px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 700;
      transition: background 0.3s ease;
    }
    
    .back-button a:hover {
      background: #5aa83a;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
      .profil-desa {
        padding: 100px 40px 60px;
      }
      
      .sejarah-geografi {
        grid-template-columns: 1fr;
      }
      
      .demografi-stats {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .potensi-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    
    @media (max-width: 768px) {
      .profil-desa {
        padding: 100px 20px 40px;
      }
      
      .visi-misi, .sejarah-box, .geografi-box, .demografi, .potensi-desa {
        padding: 20px;
      }
      
      .demografi-stats {
        grid-template-columns: 1fr;
      }
      
      .potensi-grid {
        grid-template-columns: 1fr;
      }
      
      .profil-header h1 {
        font-size: 28px;
      }
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="nav-left">
      <img src="./assets/img/logonew.png" alt="Logo Desa" />
      <div class="text">
        <strong> Desa Brakas Dejeh</strong><br />
        Kabupaten Bangkalan
      </div>
    </div>
    <div class="nav-right">
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="#" style="text-decoration: underline;">Profil Desa</a></li>
        <li><a href="#">Listing</a></li>
        <li><a href="#">IDM</a></li>
        <li><a href="#">Berita</a></li>
        <li><a href="#">Belanja</a></li>
        <li><a href="#">PPID</a></li>
      </ul>
    </div>
  </nav>

  <!-- PROFIL DESA -->
  <section class="profil-desa">
    <div class="profil-header">
      <h1>Profil Desa Brakas Dejeh</h1>
      <p>Desa Brakas Dejeh adalah sebuah desa di Kecamatan Modung, Kabupaten Bangkalan, Provinsi Jawa Timur. Desa ini memiliki berbagai potensi dan kekayaan budaya yang menjadi ciri khas masyarakatnya.</p>
    </div>
    
    <div class="profil-content">
      <!-- VISI DAN MISI -->
      <div class="visi-misi">
        <div class="visi-container">
          <h2 class="visi-title">Visi Desa</h2>
          <div class="visi-text">
            "Desa Kersik sebagai Desa Wisata yang mampu mengelola potensi Desa dan pembangunan berkelanjutan untuk mewujudkan masyarakat yang sejahtera"
          </div>
        </div>
        
        <div class="misi-container">
          <h2 class="misi-title">Misi Desa</h2>
          <ul class="misi-list">
            <li>
              <div class="misi-number">1</div>
              <div class="misi-text">Mewujudkan tata kelola pemerintahan yang baik</div>
            </li>
            <li>
              <div class="misi-number">2</div>
              <div class="misi-text">Mengembangkan kegiatan keagamaan</div>
            </li>
            <li>
              <div class="misi-number">3</div>
              <div class="misi-text">Meningkatkan kualitas pendidikan dan sumber daya manusia</div>
            </li>
            <li>
              <div class="misi-number">4</div>
              <div class="misi-text">Mengembangkan teknologi informasi</div>
            </li>
            <li>
              <div class="misi-number">5</div>
              <div class="misi-text">Pembangunan infrastruktur, sarana dan prasarana</div>
            </li>
          </ul>
        </div>
      </div>
      
      <!-- SEJARAH DAN GEOGRAFI -->
      <div class="sejarah-geografi">
        <div class="sejarah-box">
          <h2 class="section-title">Sejarah Desa</h2>
          <div class="sejarah-text">
            <p>Desa Brakas Dejeh memiliki sejarah panjang yang bermula dari sekelompok masyarakat yang membuka lahan dan menetap di wilayah ini. Nama "Brakas Dejeh" berasal dari bahasa Madura yang memiliki makna filosofis tentang semangat kebersamaan dan gotong royong.</p>
            <p>Sejak berdiri, Desa Brakas Dejeh telah dipimpin oleh beberapa kepala desa yang memberikan kontribusi dalam pembangunan dan kemajuan desa. Masyarakat desa dikenal dengan semangat kegotongroyongan yang tinggi dalam setiap aspek kehidupan.</p>
          </div>
        </div>
        
        <div class="geografi-box">
          <h2 class="section-title">Geografi Desa</h2>
          <div class="geografi-text">
            <p>Desa Brakas Dejeh terletak di Kecamatan Modung, Kabupaten Bangkalan, Provinsi Jawa Timur. Desa ini memiliki luas wilayah sekitar 250 hektar yang terdiri dari area pemukiman, persawahan, dan lahan perkebunan.</p>
            <p>Desa ini berbatasan dengan:
              <br>- Sebelah Utara: Desa ...
              <br>- Sebelah Selatan: Desa ...
              <br>- Sebelah Timur: Desa ...
              <br>- Sebelah Barat: Desa ...
            </p>
            <p>Topografi desa berupa dataran rendah dengan ketinggian rata-rata 10-50 meter di atas permukaan laut.</p>
          </div>
        </div>
      </div>
      
      <!-- DEMOGRAFI -->
      <div class="demografi">
        <h2 class="section-title">Demografi Penduduk</h2>
        <div class="demografi-stats">
          <div class="stat-box">
            <span class="stat-number">1.161</span>
            <span class="stat-label">Total Penduduk</span>
          </div>
          <div class="stat-box">
            <span class="stat-number">607</span>
            <span class="stat-label">Laki-laki</span>
          </div>
          <div class="stat-box">
            <span class="stat-number">554</span>
            <span class="stat-label">Perempuan</span>
          </div>
          <div class="stat-box">
            <span class="stat-number">309</span>
            <span class="stat-label">Kepala Keluarga</span>
          </div>
        </div>
      </div>
      
      <!-- POTENSI DESA -->
      <div class="potensi-desa">
        <h2 class="section-title">Potensi Desa</h2>
        <div class="potensi-grid">
          <div class="potensi-card">
            <span class="potensi-icon">🌾</span>
            <h3 class="potensi-name">Pertanian</h3>
            <p class="potensi-desc">Desa memiliki lahan pertanian subur dengan komoditas utama padi, jagung, dan kacang-kacangan.</p>
          </div>
          
          <div class="potensi-card">
            <span class="potensi-icon">🐟</span>
            <h3 class="potensi-name">Perikanan</h3>
            <p class="potensi-desc">Budidaya ikan air tawar seperti lele dan nila yang dikelola oleh kelompok tani desa.</p>
          </div>
          
          <div class="potensi-card">
            <span class="potensi-icon">🏺</span>
            <h3 class="potensi-name">Kerajinan</h3>
            <p class="potensi-desc">Pengrajin gerabah dan anyaman bambu yang menjadi produk unggulan desa.</p>
          </div>
          
          <div class="potensi-card">
            <span class="potensi-icon">🌴</span>
            <h3 class="potensi-name">Pariwisata</h3>
            <p class="potensi-desc">Potensi wisata alam dan budaya dengan panorama persawahan yang indah.</p>
          </div>
          
          <div class="potensi-card">
            <span class="potensi-icon">🍯</span>
            <h3 class="potensi-name">Produk Lokal</h3>
            <p class="potensi-desc">Madu hutan dan gula aren sebagai produk lokal yang memiliki nilai ekonomi.</p>
          </div>
          
          <div class="potensi-card">
            <span class="potensi-icon">🏛️</span>
            <h3 class="potensi-name">Budaya</h3>
            <p class="potensi-desc">Kesenian tradisional Madura seperti Ludruk dan Saronen yang masih dilestarikan.</p>
          </div>
        </div>
      </div>
      
      <!-- TOMBOL KEMBALI -->
      <div class="back-button">
        <a href="index.html">Kembali ke Beranda</a>
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
          <li><a href="index.html">Beranda</a></li>
          <li><a href="#">Website Kemendesa</a></li>
          <li><a href="#">Website Kemendagri</a></li>
          <li><a href="#">Website Kabupaten Bangkalan</a></li>
        </ul>
      </div>

    </div>

    <div class="footer-bottom">
      © 2026 Pemerintah Desa Brakas Dejeh. KKN 2025/2026.
    </div>
  </footer>

</body>
</html>