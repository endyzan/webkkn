<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Kersik - Pemerintah Desa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
        }
        
        /* Header dan Navigasi */
        .logo-header {
            background-color: #ffffff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .logo-container {
            display: flex;
            align-items: center;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            background-color: #2c5e1a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 15px;
        }
        
        .logo-text {
            font-size: 22px;
            font-weight: bold;
            color: #2c5e1a;
        }
        
        .logo-subtext {
            font-size: 14px;
            color: #666;
        }
        
        .nav-menu {
            display: flex;
            background-color: #2c5e1a;
            padding: 0 20px;
            justify-content: center; /* Ubah menjadi center */

        }
        
        .nav-item {
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .nav-item:hover {
            background-color: #3a7a23;
        }
        
        .nav-item.active {
            background-color: #1e4212;
        }
        
        /* Hero Section */
        .hero {
            position: relative;
            height: 400px;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1518837695005-2083093ee35b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        
        .hero-content {
            max-width: 800px;
            padding: 20px;
        }
        
        .hero-title {
            font-size: 36px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        .hero-subtitle {
            font-size: 20px;
            margin-bottom: 20px;
        }
        
        /* Konten Utama */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .section-title {
            font-size: 24px;
            color: #2c5e1a;
            margin: 30px 0 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2c5e1a;
        }
        
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: #2c5e1a;
            color: white;
            padding: 15px;
            font-weight: bold;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .info-box {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .info-box h3 {
            color: #2c5e1a;
            margin-bottom: 15px;
        }
        
        /* Profil Kepala Desa */
        .kades-profile {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .kades-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 25px;
            border: 5px solid #2c5e1a;
        }
        
        .kades-info h3 {
            color: #2c5e1a;
            margin-bottom: 5px;
        }
        
        .kades-info p {
            color: #666;
            margin-bottom: 10px;
        }
        
        /* Peta */
        #map {
            height: 400px;
            width: 100%;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Data Anggaran */
        .anggaran-container {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .anggaran-box {
            flex: 1;
            min-width: 250px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .anggaran-title {
            font-size: 18px;
            color: #2c5e1a;
            margin-bottom: 10px;
        }
        
        .anggaran-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        /* Berita */
        .berita-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .berita-item {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .berita-image {
            height: 180px;
            width: 100%;
            object-fit: cover;
        }
        
        .berita-content {
            padding: 20px;
        }
        
        .berita-title {
            font-size: 18px;
            color: #2c5e1a;
            margin-bottom: 10px;
        }
        
        .berita-meta {
            display: flex;
            justify-content: space-between;
            color: #888;
            font-size: 14px;
            margin-top: 15px;
        }
        
        /* Produk UMKM */
        .produk-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .produk-item {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .produk-image {
            height: 150px;
            width: 100%;
            object-fit: cover;
        }
        
        .produk-info {
            padding: 15px;
        }
        
        .produk-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .produk-price {
            color: #2c5e1a;
            font-weight: bold;
        }
        
        /* Footer */
        footer {
            background-color: #2c5e1a;
            color: white;
            padding: 40px 20px;
            margin-top: 50px;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .footer-section h3 {
            font-size: 18px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-bottom: 10px;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section ul li {
            margin-bottom: 10px;
        }
        
        .footer-section ul li a {
            color: #ddd;
            text-decoration: none;
        }
        
        .footer-section ul li a:hover {
            color: white;
            text-decoration: underline;
        }
        
        .btn {
            display: inline-block;
            background-color: #2c5e1a;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background-color: #3a7a23;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .nav-menu {
                flex-wrap: wrap;
            }
            
            .nav-item {
                padding: 10px 15px;
                font-size: 14px;
            }
            
            .hero-title {
                font-size: 28px;
            }
            
            .kades-profile {
                flex-direction: column;
                text-align: center;
            }
            
            .kades-photo {
                margin-right: 0;
                margin-bottom: 15px;
            }
        }



        /* search-navbar */
        .search-section form {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            border-radius: 30px;
            padding: 5px 15px;
            border: 1px solid #ddd;
        }

        .search-section input {
            border: none;
            background: transparent;
            padding: 8px;
            outline: none;
            width: 250px;
            font-size: 0.9rem;
        }

        .search-section button {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <!-- Logo dan Judul -->
    <div class="logo-header">
        <div class="logo-container">
            <div class="logo">DK</div>
            <div>
                <div class="logo-text">PEMERINTAH DESA KERSIK</div>
                <div class="logo-subtext">Kabupaten Kutai Kartanegara, Kalimantan Timur</div>
            </div>
        </div>
        <div class="search-section">
            <form action="#" method="GET">
                <input type="text" placeholder="Cari artikel..." name="search">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
    
    <!-- Navigasi -->
    <div class="nav-menu">
        <a href="#" class="nav-item active">Home</a>
        <a href="#" class="nav-item">Profil Desa</a>
        <a href="#" class="nav-item">Infografis</a>
        <a href="#" class="nav-item">Listing IDM</a>
        <a href="#" class="nav-item">Berita</a>
        <a href="#" class="nav-item">Belanja</a>
    </div>
    
    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <h1 class="hero-title">DESA KERSIK</h1>
            <p class="hero-subtitle">Website ini hadir sebagai wujud transformasi desa Kersik menjadi desa yang mampu memanfaatkan teknologi informasi dan komunikasi</p>
            <a href="#" class="btn">Jelajahi Desa</a>
        </div>
    </div>
    
    <!-- Konten Utama -->
    <div class="container">
        <!-- Profil Desa -->
        <h2 class="section-title">PROFIL DESA</h2>
        <div class="kades-profile">
            <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Kepala Desa Kersik" class="kades-photo">
            <div class="kades-info">
                <h3>JUMADI</h3>
                <p>Kepala Desa Kersik</p>
                <p><i>"Assalamu Alaikum Warohmatullahi Wabarakatu. Website ini hadir sebagai wujud transformasi desa Kersik menjadi desa yang mampu memanfaatkan teknologi informasi dan komunikasi, terintegrasi kedalam sistem online. Keterbukaan informasi publik, pelayanan publik dan kegiatan perekonomian di desa, guna mewujudkan desa Kersik sebagai desa wisata yang berkelanjutan, adaptasi dan mitigasi terhadap perubahan iklim serta menjadi desa yang mandiri."</i></p>
            </div>
        </div>
        
        <!-- Peta Desa -->
        <h2 class="section-title">GEOSPASIAL DESA KERSIK</h2>
        <div id="map"></div>
        
        <!-- Infografis -->
        <h2 class="section-title">INFOGRAFIS</h2>
        <div class="cards-container">
            <div class="card">
                <div class="card-header">Penduduk</div>
                <div class="card-body">
                    <h3>2.543 Jiwa</h3>
                    <p>Jumlah penduduk Desa Kersik</p>
                    <div style="display: flex; justify-content: space-between; margin-top: 15px;">
                        <div>
                            <p><strong>Laki-laki</strong></p>
                            <p>1.320</p>
                        </div>
                        <div>
                            <p><strong>Perempuan</strong></p>
                            <p>1.223</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">Kepala Keluarga</div>
                <div class="card-body">
                    <h3>685 KK</h3>
                    <p>Jumlah kepala keluarga di Desa Kersik</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">APB Desa</div>
                <div class="card-body">
                    <h3>Rp 4,25 Miliar</h3>
                    <p>Anggaran Pendapatan dan Belanja Desa Tahun 2023</p>
                    <div style="margin-top: 15px;">
                        <p><strong>Pendapatan:</strong> Rp 4.254.715.300</p>
                        <p><strong>Belanja:</strong> Rp 4.235.654.388</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Berita Terbaru -->
        <h2 class="section-title">BERITA TERBARU</h2>
        <div class="berita-container">
            <div class="berita-item">
                <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="POKDARWIS" class="berita-image">
                <div class="berita-content">
                    <h3 class="berita-title">POKDARWIS PANTAI BIRU KERSIK TERIMA BANTUAN...</h3>
                    <p>Kersik - Kelompok Sadar Wisata (POKDARWIS) Pantai Biru Kersik menerima bantuan 10 (sepuluh) unit gazebo dari Bank Indonesia...</p>
                    <div class="berita-meta">
                        <span>Administrator</span>
                        <span>Dilihat 59 kali</span>
                    </div>
                </div>
            </div>
            
            <div class="berita-item">
                <img src="https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Gotong Royong" class="berita-image">
                <div class="berita-content">
                    <h3 class="berita-title">KEGIATAN GOTONG ROYONG WARGA RT.002 DESA KERSIK...</h3>
                    <p>Kersik - Warga RT.002 Desa Kersik, Kecamatan Marang Kayu, Kabupaten Kutai Kartanegara, melaksanakan kegiatan gotong royong...</p>
                    <div class="berita-meta">
                        <span>Administrator</span>
                        <span>Dilihat 73 kali</span>
                    </div>
                </div>
            </div>
            
            <div class="berita-item">
                <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Penjagaan" class="berita-image">
                <div class="berita-content">
                    <h3 class="berita-title">RT DI DESA KERSIK TINGKATKAN PENJAGAAN...</h3>
                    <p>Kersik - Dalam upaya menciptakan lingkungan yang aman, tertib, dan kondusif, Ketua RT bersama warga di Desa Kersik, Kecamatan...</p>
                    <div class="berita-meta">
                        <span>Administrator</span>
                        <span>Dilihat 61 kali</span>
                    </div>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin-bottom: 40px;">
            <a href="#" class="btn">LIHAT BERITA LEBIH BANYAK</a>
        </div>
        
        <!-- Potensi Desa -->
        <h2 class="section-title">POTENSI DESA</h2>
        <div class="cards-container">
            <div class="card">
                <div class="card-header">PARIWISATA</div>
                <div class="card-body">
                    <h3>Pantai Biru Kersik</h3>
                    <p>Pantai ini terbuka untuk umum dan siapa saja boleh mengunjunginya, pengunjung atau wisatawan akan disambut baik oleh penduduk lokal daerah wisata...</p>
                    <a href="#" class="btn" style="margin-top: 15px; display: inline-block;">Lihat Wisata</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">POTENSI PERIKANAN</div>
                <div class="card-body">
                    <h3>Hasil Laut Melimpah</h3>
                    <p>Desa Kersik memiliki potensi perikanan yang besar dengan hasil tangkapan ikan beragam yang menjadi mata pencaharian utama masyarakat pesisir.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">PRODUK UMKM</div>
                <div class="card-body">
                    <p>Beragam produk UMKM desa untuk meningkatkan perekonomian masyarakat</p>
                    <div style="margin-top: 15px;">
                        <p><strong>Roti Tawar:</strong> Rp 10.000</p>
                        <p><strong>Konektor Masker:</strong> Rp 10.000</p>
                        <p><strong>Souvenir:</strong> Rp 150.000</p>
                    </div>
                    <a href="#" class="btn" style="margin-top: 15px; display: inline-block;">Lihat Produk</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Pemerintah Desa Kersik</h3>
                <p>Jalan Langaseng Dusun Empang RT.003<br>
                Desa Kersik, Kecamatan Marang Kayu,<br>
                Kabupaten Kutai Kartanegara<br>
                Provinsi Kalimantan Timur, 75385</p>
            </div>
            
            <div class="footer-section">
                <h3>Hubungi Kami</h3>
                <ul>
                    <li><i class="fas fa-phone"></i> 082150208664</li>
                    <li><i class="fas fa-envelope"></i> kersik.marangkayu@kukarkab.go.id</li>
                    <li><i class="fas fa-map-marker-alt"></i> Kode Wilayah: 64.02.17.2005</li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Nomor Telepon Penting</h3>
                <ul>
                    <li>Polisi: 110</li>
                    <li>Pemadam Kebakaran: 113</li>
                    <li>Ambulans: 118</li>
                    <li>Puskesmas Marang Kayu: (0541) 712345</li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Jelajahi</h3>
                <ul>
                    <li><a href="#">Website Kemendesa</a></li>
                    <li><a href="#">Website Kemendagri</a></li>
                    <li><a href="#">Website Kabupaten Kutai Kartanegara</a></li>
                    <li><a href="#">Cek DPT Online</a></li>
                </ul>
            </div>
        </div>
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2);">
            <p>&copy; 2023 Pemerintah Desa Kersik. Hak Cipta Dilindungi.</p>
        </div>
    </footer>
    
    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-0.5, 117.3], 13);
        
        // Tambahkan tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Tambahkan marker untuk lokasi Desa Kersik
        L.marker([-0.5, 117.3]).addTo(map)
            .bindPopup('<b>Desa Kersik</b><br>Kecamatan Marang Kayu<br>Kabupaten Kutai Kartanegara')
            .openPopup();
            
        // Tambahkan beberapa POI (Point of Interest)
        var poiLocations = [
            {lat: -0.505, lng: 117.295, title: 'Pantai Biru Kersik', desc: 'Wisata Pantai'},
            {lat: -0.495, lng: 117.31, title: 'Balai Desa', desc: 'Pusat Pemerintahan'},
            {lat: -0.51, lng: 117.305, title: 'Pasar Desa', desc: 'Pusat Perdagangan'},
            {lat: -0.49, lng: 117.29, title: 'SDN Kersik', desc: 'Sekolah Dasar'},
            {lat: -0.5, lng: 117.32, title: 'Pelabuhan', desc: 'Dermaga Perikanan'}
        ];
        
        poiLocations.forEach(function(poi) {
            L.marker([poi.lat, poi.lng]).addTo(map)
                .bindPopup('<b>' + poi.title + '</b><br>' + poi.desc);
        });
        
        // Fungsi untuk menampilkan data anggaran dengan animasi
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi untuk angka-angka statistik
            const observerOptions = {
                threshold: 0.5
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Animasi sederhana untuk elemen yang terlihat
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            // Terapkan animasi pada kartu
            document.querySelectorAll('.card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s, transform 0.5s';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>