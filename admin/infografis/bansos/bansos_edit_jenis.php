<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    $_SESSION['error'] = "ID tidak valid";
    header("Location: bansos.php");
    exit;
}

// Ambil data jenis bansos
$query = "SELECT * FROM jenis_bansos WHERE id = $id";
$result = mysqli_query($conn, $query);
$jenis = mysqli_fetch_assoc($result);

if (!$jenis) {
    $_SESSION['error'] = "Data tidak ditemukan";
    header("Location: bansos.php");
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit_jenis') {
    $nama_bansos = mysqli_real_escape_string($conn, $_POST['nama_bansos']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $sql = "UPDATE jenis_bansos SET 
            nama_bansos = ?, 
            keterangan = ?, 
            status = ?,
            updated_at = NOW()
            WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $nama_bansos, $keterangan, $status, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Jenis bansos berhasil diperbarui";
        header("Location: bansos.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal memperbarui data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jenis Bansos</title>
    <link rel="stylesheet" href="../../../assets/admin/style.css">
    <style>
        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 20px auto;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: #4CAF50;
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-back {
            background: #007bff;
            color: white;
            margin-bottom: 20px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
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
        
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-aktif {
            background: #d4edda;
            color: #155724;
        }
        
        .status-nonaktif {
            background: #f8d7da;
            color: #721c24;
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
                <li><a href="../../home/banner-hero/banner.php">Banner</a></li>
                <li><a href="../../home/sambutan/sambutan.php">Sambutan</a></li>
                <li><a href="../../home/sotk/sotk.php">SOTK</a></li>
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
        
        <!-- DROPDOWN INFOGRAFIS -->
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Infografis ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../penduduk/penduduk.php">Penduduk</a></li>
                <li><a href="../apbdes/apbdes.php">APBDes</a></li>
                <li><a href="../bansos/bansos.php" style="background:rgba(255,255,255,0.15)">Bansos</a></li>
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
        <h1>Edit Jenis Bansos</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <a href="bansos.php" class="btn btn-back" style="margin: 20px;">← Kembali ke Data Bansos</a>

    <div class="card">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="info-box">
            <h4>Informasi Data Saat Ini</h4>
            <p><strong>Nama Bansos:</strong> <?= htmlspecialchars($jenis['nama_bansos']) ?></p>
            <p><strong>Keterangan:</strong> <?= htmlspecialchars($jenis['keterangan'] ?? 'Tidak ada keterangan') ?></p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-<?= $jenis['status'] ?>">
                    <?= ucfirst($jenis['status']) ?>
                </span>
            </p>
        </div>

        <form method="POST" action="">
            <input type="hidden" name="action" value="edit_jenis">
            
            <div class="form-group">
                <label for="nama_bansos">Nama Bansos *</label>
                <input type="text" name="nama_bansos" id="nama_bansos" 
                       value="<?= htmlspecialchars($jenis['nama_bansos']) ?>" required>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"><?= htmlspecialchars($jenis['keterangan'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select name="status" id="status" required>
                    <option value="aktif" <?= $jenis['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= $jenis['status'] == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
                <small>Status "Nonaktif" akan menyembunyikan jenis bansos ini dari pilihan baru</small>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="bansos.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
// Fungsi untuk sidebar
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

// Konfirmasi jika mengubah status ke nonaktif
document.getElementById('status').addEventListener('change', function() {
    if (this.value === 'nonaktif') {
        if (!confirm('Status Nonaktif akan menyembunyikan jenis bansos ini dari pilihan baru. Apakah Anda yakin?')) {
            this.value = 'aktif';
        }
    }
});
</script>
</body>
</html>