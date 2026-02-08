<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Galeri - Desa Brakas Dajah</title>
  <link rel="stylesheet" href="../assets/css/style.css">
<style>
.galeri-filter {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin: 20px 0;
    flex-wrap: wrap;
}
.filter-btn {
    padding: 8px 20px;
    border: 2px solid #ddd;
    background: white;
    border-radius: 30px;
    cursor: pointer;
    transition: all 0.3s;
}
.filter-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}
.galeri-card {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.galeri-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.3s;
}
.galeri-card img:hover {
    transform: scale(1.05);
}
.galeri-info {
    padding: 15px;
    background: white;
}
.kategori-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    margin-bottom: 10px;
}
.kategori-foto_random { background: #e3f2fd; color: #1976d2; }
.kategori-agenda { background: #f3e5f5; color: #7b1fa2; }
.kategori-kegiatan { background: #e8f5e8; color: #388e3c; }
.galeri-date {
    color: #666;
    font-size: 14px;
    margin-top: 10px;
}

/* Image Modal */
.image-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    padding-top: 50px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.9);
}
.modal-content {
    margin: auto;
    display: block;
    max-width: 80%;
    max-height: 80%;
}
#modalCaption {
    text-align: center;
    color: white;
    padding: 20px;
}
.close-modal {
    position: absolute;
    top: 20px;
    right: 35px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}
</style>
</head>
<body>

<!-- navbar -->
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



<!-- GALERI DESA -->
<section class="galeri">
  <div class="galeri-container">

    <div class="galeri-header">
      <h2>GALERI DESA</h2>
      <p>Menampilkan kegiatan-kegiatan yang berlangsung di desa</p>
      
      <!-- Filter Kategori -->
      <div class="galeri-filter">
        <button class="filter-btn active" data-kategori="">Semua</button>
        <button class="filter-btn" data-kategori="foto_random">Foto Random</button>
        <button class="filter-btn" data-kategori="agenda">Agenda</button>
        <button class="filter-btn" data-kategori="kegiatan">Kegiatan</button>
      </div>
    </div>

    <div class="galeri-grid" id="galeriGrid">
      <!-- Data akan dimuat via AJAX -->
    </div>

    <!-- Loading -->
    <div id="loading" style="text-align:center;padding:20px;display:none;">
      <p>Memuat data...</p>
    </div>

    <!-- PAGINATION -->
    <div class="galeri-pagination" id="pagination">
      <!-- Pagination akan diisi via JavaScript -->
    </div>

  </div>
</section>

<!-- Modal Preview Gambar -->
<div id="imageModal" class="image-modal">
  <span class="close-modal">&times;</span>
  <img class="modal-content" id="modalImage">
  <div id="modalCaption"></div>
</div>


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

  <script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let currentKategori = '';
    const itemsPerPage = 12;

    // Load galeri pertama kali
    loadGaleri();

    // Filter button event
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentKategori = this.dataset.kategori;
            currentPage = 1;
            loadGaleri();
        });
    });

    function loadGaleri() {
        const loading = document.getElementById('loading');
        const grid = document.getElementById('galeriGrid');
        const pagination = document.getElementById('pagination');
        
        loading.style.display = 'block';
        grid.innerHTML = '';
        pagination.innerHTML = '';
        
        // AJAX request
        fetch(`../api/galeri.php?page=${currentPage}&limit=${itemsPerPage}&kategori=${currentKategori}`)
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                
                // Render images
                if (data.data.length > 0) {
                    data.data.forEach(item => {
                        const card = document.createElement('div');
                        card.className = 'galeri-card';
                        card.innerHTML = `
                            <img src="../uploads/galeri/${item.gambar}" 
                                 alt="${item.judul}" 
                                 onclick="openImageModal('${item.gambar}', '${item.judul}')"
                                 onerror="this.src='../assets/img/placeholder.jpg'">
                            <div class="galeri-info">
                                <span class="kategori-badge kategori-${item.kategori}">
                                    ${item.kategori.replace('_', ' ')}
                                </span>
                                <h3>${item.judul}</h3>
                                <p>${item.deskripsi.substring(0, 100)}...</p>
                                <div class="galeri-date">
                                    📅 ${item.tanggal_formatted}
                                </div>
                            </div>
                        `;
                        grid.appendChild(card);
                    });
                    
                    // Render pagination
                    renderPagination(data.totalPages);
                } else {
                    grid.innerHTML = '<p style="grid-column:1/-1;text-align:center;padding:40px;">Tidak ada data galeri.</p>';
                }
            });
    }

    function renderPagination(totalPages) {
        const pagination = document.getElementById('pagination');
        let html = '';
        
        if (currentPage > 1) {
            html += `<button onclick="changePage(${currentPage - 1})">&lsaquo;</button>`;
        }
        
        for (let i = 1; i <= totalPages; i++) {
            html += `<button class="${i == currentPage ? 'active' : ''}" 
                      onclick="changePage(${i})">${i}</button>`;
        }
        
        if (currentPage < totalPages) {
            html += `<button onclick="changePage(${currentPage + 1})">&rsaquo;</button>`;
        }
        
        pagination.innerHTML = html;
    }

    window.changePage = function(page) {
        currentPage = page;
        loadGaleri();
    };
});

// Modal image preview
function openImageModal(imageSrc, title) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const caption = document.getElementById('modalCaption');
    
    modal.style.display = "block";
    modalImg.src = "../uploads/galeri/" + imageSrc;
    caption.innerHTML = `<strong>${title}</strong>`;
    
    // Close modal
    document.querySelector('.close-modal').onclick = function() {
        modal.style.display = "none";
    };
    
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
}
</script>

  <script src="../assets/js/scripts.js"></script>

  </body>
</html>
