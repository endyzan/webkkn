<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../db.php';

// Search dan Filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Query dengan filter
$query = "SELECT * FROM galeri WHERE 1=1";
if (!empty($search)) {
    $query .= " AND (judul LIKE '%$search%' OR deskripsi LIKE '%$search%')";
}
if (!empty($kategori) && in_array($kategori, ['foto_random', 'agenda', 'kegiatan'])) {
    $query .= " AND kategori = '$kategori'";
}
$query .= " ORDER BY tanggal DESC, created_at DESC";

$result = mysqli_query($conn, $query);

// Tambah data
if (isset($_POST['tambah'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    
    // Upload gambar
    $gambar = '';
    if ($_FILES['gambar']['error'] == 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../../uploads/galeri/" . $gambar);
    }
    
    mysqli_query($conn, "INSERT INTO galeri (judul, deskripsi, gambar, kategori, tanggal) 
                         VALUES ('$judul', '$deskripsi', '$gambar', '$kategori', '$tanggal')");
    header("Location: galeri.php?success=1");
    exit;
}

// Edit data
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    
    $update = "UPDATE galeri SET judul='$judul', deskripsi='$deskripsi', 
               kategori='$kategori', tanggal='$tanggal' WHERE id=$id";
    
    if ($_FILES['gambar']['error'] == 0) {
        // Hapus gambar lama
        $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM galeri WHERE id=$id"));
        if ($old['gambar'] && file_exists("../../uploads/galeri/" . $old['gambar'])) {
            unlink("../../uploads/galeri/" . $old['gambar']);
        }
        
        // Upload gambar baru
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../../uploads/galeri/" . $gambar);
        $update = "UPDATE galeri SET judul='$judul', deskripsi='$deskripsi', 
                  gambar='$gambar', kategori='$kategori', tanggal='$tanggal' WHERE id=$id";
    }
    
    mysqli_query($conn, $update);
    header("Location: galeri.php?success=2");
    exit;
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM galeri WHERE id=$id"));
    if ($data['gambar'] && file_exists("../../uploads/galeri/" . $data['gambar'])) {
        unlink("../../uploads/galeri/" . $data['gambar']);
    }
    mysqli_query($conn, "DELETE FROM galeri WHERE id=$id");
    header("Location: galeri.php?success=3");
    exit;
}

// Toggle status
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    mysqli_query($conn, "UPDATE galeri SET status = IF(status='aktif','nonaktif','aktif') WHERE id=$id");
    header("Location: galeri.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Galeri - Desa Brakas Dejeh</title>
    <link rel="stylesheet" href="../../assets/admin/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .close {
            float: right;
            font-size: 28px;
            cursor: pointer;
        }
        .galeri-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .galeri-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }
        .galeri-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .galeri-info {
            padding: 15px;
        }
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-edit { background: #4CAF50; color: white; }
        .btn-hapus { background: #f44336; color: white; }
        .btn-toggle { background: #2196F3; color: white; }
        .btn-tambah { background: #009688; color: white; padding: 10px 20px; }
        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .search-box input, .search-box select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .kategori-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .kategori-foto_random { background: #e3f2fd; color: #1976d2; }
        .kategori-agenda { background: #f3e5f5; color: #7b1fa2; }
        .kategori-kegiatan { background: #e8f5e8; color: #388e3c; }
    </style>
</head>
<body>

<div class="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>ADMIN DESA</h2>
    <ul>
        <li><a href="../index.php">Dashboard</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Home ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../home/banner-hero/banner.php">Banner</a></li>
                <li><a href="../home/sambutan/sambutan.php">Sambutan</a></li>
                <li><a href="../home/sotk/sotk.php">SOTK</a></li>
            </ul>
        </li>
        
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Profil Desa ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../profil-desa/visi-misi/visimisi.php">Visi & Misi</a></li>
                <li><a href="../profil-desa/bagan-desa/bagandesa.php">Bagan Desa</a></li>
                <li><a href="../profil-desa/sejarah-desa/sejarah.php">Sejarah Desa</a></li>
            </ul>
        </li>
        
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Infografis ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../infografis/penduduk/penduduk.php">Penduduk</a></li>
                <li><a href="../infografis/apbdes/apbdes.php">APBDes</a></li>
                <li><a href="../infografis/bansos/bansos.php">Bansos</a></li>
            </ul>
        </li>
        <li><a href="../berita/berita.php">Berita</a></li>
        <li><a href="./galeri.php" style="background:rgba(255,255,255,0.15)">Galeri</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)"><i class="fas fa-robot"></i> Chatbot ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../chatbot/manage.php">Chatbot AI</a></li>
                <li><a href="../chatbot/analytics_advanced.php">Analytics AI</a></li>
            </ul>
        </li>

        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">
    <!-- TOPBAR -->
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Manajemen Galeri</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <!-- Notifikasi -->
    <?php if (isset($_GET['success'])): ?>
        <div style="background:#4CAF50;color:white;padding:10px;border-radius:4px;margin:15px 0;">
            <?php 
            if ($_GET['success'] == 1) echo "Galeri berhasil ditambahkan!";
            elseif ($_GET['success'] == 2) echo "Galeri berhasil diupdate!";
            elseif ($_GET['success'] == 3) echo "Galeri berhasil dihapus!";
            ?>
        </div>
    <?php endif; ?>

    <!-- Search dan Filter -->
    <div class="search-box">
        <form method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <input type="text" name="search" placeholder="Cari judul atau deskripsi..." 
                   value="<?= htmlspecialchars($search) ?>" style="min-width: 250px;">
            
            <select name="kategori">
                <option value="">Semua Kategori</option>
                <option value="foto_random" <?= $kategori=='foto_random'?'selected':'' ?>>Foto Random</option>
                <option value="agenda" <?= $kategori=='agenda'?'selected':'' ?>>Agenda</option>
                <option value="kegiatan" <?= $kategori=='kegiatan'?'selected':'' ?>>Kegiatan</option>
            </select>
            
            <button type="submit" style="background:#2196F3;color:white;border:none;padding:8px 16px;border-radius:4px;">
                Cari
            </button>
            <a href="galeri.php" style="padding:8px 16px;background:#9e9e9e;color:white;border-radius:4px;text-decoration:none;">
                Reset
            </a>
        </form>
        
        <button onclick="openModal('tambah')" class="btn-tambah">+ Tambah Galeri</button>
    </div>

    <!-- Daftar Galeri -->
    <div class="galeri-grid">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="galeri-card">
            <img src="../../uploads/galeri/<?= $row['gambar'] ?>" 
                 alt="<?= htmlspecialchars($row['judul']) ?>"
                 onerror="this.src='../../assets/img/placeholder.jpg'">
            
            <div class="galeri-info">
                <span class="kategori-badge kategori-<?= $row['kategori'] ?>">
                    <?= ucfirst(str_replace('_', ' ', $row['kategori'])) ?>
                </span>
                <h3><?= htmlspecialchars($row['judul']) ?></h3>
                <p style="color:#666;font-size:14px;">
                    📅 <?= date('d M Y', strtotime($row['tanggal'])) ?>
                    <br>
                    Status: <strong><?= $row['status'] == 'aktif' ? '✅ Aktif' : '❌ Nonaktif' ?></strong>
                </p>
                <p style="font-size:14px;color:#555;"><?= substr($row['deskripsi'], 0, 100) ?>...</p>
                
                <div class="actions">
                    <button onclick="openModal('edit', <?= $row['id'] ?>)" class="btn btn-edit">Edit</button>
                    <a href="?toggle=<?= $row['id'] ?>" class="btn btn-toggle">
                        <?= $row['status'] == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                    </a>
                    <a href="?hapus=<?= $row['id'] ?>" 
                       onclick="return confirm('Yakin hapus galeri ini?')"
                       class="btn btn-hapus">Hapus</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
        
        <?php if (mysqli_num_rows($result) == 0): ?>
        <div style="grid-column:1/-1;text-align:center;padding:40px;background:#f9f9f9;border-radius:8px;">
            <p>Tidak ada data galeri ditemukan.</p>
        </div>
        <?php endif; ?>
    </div>

</div>

<!-- Modal Tambah/Edit -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle">Tambah Galeri Baru</h2>
        <form id="formGaleri" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="formId">
            <input type="hidden" name="edit" id="formType">
            
            <div style="margin-bottom: 15px;">
                <label>Judul</label>
                <input type="text" name="judul" id="formJudul" required 
                       style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label>Deskripsi</label>
                <textarea name="deskripsi" id="formDeskripsi" rows="4" 
                          style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;"></textarea>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label>Kategori</label>
                <select name="kategori" id="formKategori" 
                        style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
                    <option value="foto_random">Foto Random</option>
                    <option value="agenda">Agenda</option>
                    <option value="kegiatan">Kegiatan</option>
                </select>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label>Tanggal</label>
                <input type="date" name="tanggal" id="formTanggal" required 
                       style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label>Gambar</label>
                <input type="file" name="gambar" id="formGambar" 
                       accept="image/*" 
                       style="width:100%;padding:8px;">
                <small id="currentImage"></small>
            </div>
            
            <div style="text-align:right;">
                <button type="button" onclick="closeModal()" 
                        style="padding:10px 20px;background:#999;color:white;border:none;border-radius:4px;margin-right:10px;">
                    Batal
                </button>
                <button type="submit" name="tambah" 
                        style="padding:10px 20px;background:#009688;color:white;border:none;border-radius:4px;">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const modal = document.getElementById('modal');
let currentData = {};

function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
    document.querySelector('.overlay').classList.toggle('show');
}

function closeSidebar() {
    document.querySelector('.sidebar').classList.remove('active');
    document.querySelector('.overlay').classList.remove('show');
}

function toggleDropdown(element) {
    element.nextElementSibling.classList.toggle('show');
}

function openModal(type, id = null) {
    const form = document.getElementById('formGaleri');
    
    if (type === 'tambah') {
        document.getElementById('modalTitle').textContent = 'Tambah Galeri Baru';
        document.getElementById('formId').value = '';
        document.getElementById('formJudul').value = '';
        document.getElementById('formDeskripsi').value = '';
        document.getElementById('formKategori').value = 'foto_random';
        document.getElementById('formTanggal').value = new Date().toISOString().split('T')[0];
        document.getElementById('formGambar').required = true;
        document.getElementById('formType').value = '';
        document.getElementById('currentImage').innerHTML = '';
        form.querySelector('button[type="submit"]').name = 'tambah';
    } else if (type === 'edit' && id) {
        // AJAX untuk ambil data
        fetch(`get_galeri.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalTitle').textContent = 'Edit Galeri';
                document.getElementById('formId').value = data.id;
                document.getElementById('formJudul').value = data.judul;
                document.getElementById('formDeskripsi').value = data.deskripsi;
                document.getElementById('formKategori').value = data.kategori;
                document.getElementById('formTanggal').value = data.tanggal;
                document.getElementById('formGambar').required = false;
                document.getElementById('formType').value = 'edit';
                
                if (data.gambar) {
                    document.getElementById('currentImage').innerHTML = 
                        `Gambar saat ini: <br><img src="../../uploads/galeri/${data.gambar}" width="100" style="margin-top:5px;">`;
                }
                form.querySelector('button[type="submit"]').name = 'edit';
            })
            .catch(error => console.error('Error:', error));
    }
    
    modal.style.display = 'block';
}

function closeModal() {
    modal.style.display = 'none';
}

// Tutup modal klik di luar
window.onclick = function(event) {
    if (event.target == modal) {
        closeModal();
    }
}
</script>
</body>
</html>