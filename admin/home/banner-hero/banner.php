<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

// Sesuaikan path dengan struktur Anda
include '../../../db.php';

// Proses form jika ada submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $subjudul = mysqli_real_escape_string($conn, $_POST['subjudul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // Upload gambar
    $gambar = $_POST['gambar_lama'];
    
    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "../../../uploads/hero/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $nama_file = time() . "_" . uniqid() . "." . $file_ext;
        $target_file = $target_dir . $nama_file;
        
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($file_ext, $allowed)) {
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                // Hapus gambar lama jika ada
                if (!empty($_POST['gambar_lama']) && file_exists($target_dir . $_POST['gambar_lama'])) {
                    unlink($target_dir . $_POST['gambar_lama']);
                }
                $gambar = $nama_file;
            }
        }
    }
    
    // Update atau insert
    $cek = mysqli_query($conn, "SELECT id FROM hero WHERE status='aktif' LIMIT 1");
    if (mysqli_num_rows($cek) > 0) {
        $query = "UPDATE hero SET 
                  judul='$judul', 
                  subjudul='$subjudul', 
                  deskripsi='$deskripsi', 
                  gambar='$gambar' 
                  WHERE status='aktif'";
    } else {
        $query = "INSERT INTO hero (judul, subjudul, deskripsi, gambar, status) 
                  VALUES ('$judul', '$subjudul', '$deskripsi', '$gambar', 'aktif')";
    }
    
    if (mysqli_query($conn, $query)) {
        $pesan = "success|Hero berhasil diperbarui!";
    } else {
        $pesan = "error|Terjadi kesalahan: " . mysqli_error($conn);
    }
    
    // Redirect ke halaman yang sama
    header("Location: " . $_SERVER['PHP_SELF'] . "?pesan=" . urlencode($pesan));
    exit;
}

// Ambil data hero aktif
$data = mysqli_query($conn, "SELECT * FROM hero WHERE status='aktif' LIMIT 1");
$hero = mysqli_fetch_assoc($data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Hero Banner</title>
    <link rel="stylesheet" href="../../../assets/admin/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .preview-image {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
            border: 2px solid #ddd;
        }
        .current-image {
            margin: 15px 0;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 8px;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin: 15px 0;
            font-size: 14px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
<div class="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>ADMIN DESA</h2>
    <ul>
        <li><a href="../../index.php">Dashboard</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Home ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../hero/hero.php" style="background:rgba(255,255,255,0.15)">Hero Banner</a></li>
                <li><a href="../sambutan/sambutan.php">Sambutan</a></li>
                <li><a href="../sotk/sotk.php">SOTK</a></li>
            </ul>
        </li>
        
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Profil Desa ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../../profil-desa/visi-misi/visimisi.php">Visi & Misi</a></li>
                <li><a href="../../profil-desa/bagan-desa/bagandesa.php">Bagan Desa</a></li>
                <li><a href="../../profil-desa/sejarah-desa/sejarah.php">Sejarah Desa</a></li>
            </ul>
        </li>
        
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Infografis ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../../infografis/penduduk/penduduk.php">Penduduk</a></li>
                <li><a href="../../infografis/apbdes/apbdes.php">APBDes</a></li>
                <li><a href="../../infografis/bansos/bansos.php">Bansos</a></li>
            </ul>
        </li>
        <li><a href="../../berita/berita.php">Berita</a></li>
        <li><a href="../../galeri/galeri.php">Galeri</a></li>
        <li><a href="../../chatbot/manage.php">Chatbot</a></li>

        
        <li><a href="../../logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Hero Banner</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <div class="breadcrumb">
        <a href="../../index.php">
            <i class="bi bi-house-door-fill"></i>
        </a>
        <span>/</span>
        <a href="../banner-hero/banner.php">
            Hero Banner
        </a>
    </div>

    <!-- Notifikasi -->
    <?php if (isset($_GET['pesan'])): 
        $pesan = urldecode($_GET['pesan']);
        list($tipe, $teks) = explode('|', $pesan, 2);
    ?>
    <div class="alert <?= $tipe == 'success' ? 'alert-success' : 'alert-error' ?>">
        <?= htmlspecialchars($teks) ?>
    </div>
    <?php endif; ?>

    <!-- CARD FORM -->
    <div class="card">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="gambar_lama" value="<?= $hero['gambar'] ?? '' ?>">
            
            <label>Judul Utama</label>
            <input type="text" name="judul" value="<?= htmlspecialchars($hero['judul'] ?? 'Selamat Datang') ?>" required>
            
            <label>Sub Judul</label>
            <input type="text" name="subjudul" value="<?= htmlspecialchars($hero['subjudul'] ?? 'Website Resmi Desa Brakas Dejeh') ?>" required>
            
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3" required><?= htmlspecialchars($hero['deskripsi'] ?? 'Sumber informasi terbaru tentang pemerintahan dan kegiatan masyarakat di Desa Brakas Dejeh.') ?></textarea>
            
            <label>Gambar Hero</label>
            <input type="file" name="gambar" id="gambarInput" accept="image/*">
            
            <!-- Preview Gambar Baru -->
            <div id="previewContainer" style="display:none; margin-top:10px;">
                <p>Preview Gambar Baru:</p>
                <img id="previewImage" class="preview-image" src="#" alt="Preview">
            </div>
            
            <!-- Gambar Saat Ini -->
            <?php if (!empty($hero['gambar'])): ?>
            <div class="current-image">
                <p>Gambar Saat Ini:</p>
                <img src="../../../uploads/hero/<?= htmlspecialchars($hero['gambar']) ?>" 
                     alt="Current Hero" class="preview-image"
                     onerror="this.src='../../../assets/img/placeholder.jpg'">
                <p style="margin-top:5px; font-size:14px; color:#666;">
                    <?= htmlspecialchars($hero['gambar']) ?>
                </p>
            </div>
            <?php endif; ?>
            
            <button type="submit" style="margin-top:15px">
                <i class="bi bi-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<script>
const sidebar = document.querySelector('.sidebar');
const overlay = document.querySelector('.overlay');

function toggleSidebar() {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('show');
}

function closeSidebar() {
    sidebar.classList.remove('active');
    overlay.classList.remove('show');
}

function toggleDropdown(element) {
    const dropdownMenu = element.nextElementSibling;
    dropdownMenu.classList.toggle('show');
}

// Preview gambar sebelum upload
document.getElementById('gambarInput').addEventListener('change', function(e) {
    const previewContainer = document.getElementById('previewContainer');
    const previewImage = document.getElementById('previewImage');
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(this.files[0]);
    } else {
        previewContainer.style.display = 'none';
    }
});

// Auto hide alert setelah 5 detik
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>

</body>
</html>