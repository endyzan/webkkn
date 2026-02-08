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

// Ambil data penerima
$query = "SELECT pb.*, jb.nama_bansos, p.nama, p.nik 
          FROM penerima_bansos pb
          LEFT JOIN jenis_bansos jb ON pb.id_jenis_bansos = jb.id
          LEFT JOIN penduduk p ON pb.id_penduduk = p.id
          WHERE pb.id = $id";
$result = mysqli_query($conn, $query);
$penerima = mysqli_fetch_assoc($result);

if (!$penerima) {
    $_SESSION['error'] = "Data tidak ditemukan";
    header("Location: bansos.php");
    exit;
}

// Ambil data jenis bansos
$jenis_bansos = mysqli_query($conn, "SELECT * FROM jenis_bansos WHERE status='aktif' ORDER BY nama_bansos");

// Proses update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit_penerima') {
    $id_jenis_bansos = mysqli_real_escape_string($conn, $_POST['id_jenis_bansos']);
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);
    $bulan = mysqli_real_escape_string($conn, $_POST['bulan']);
    $status_penerimaan = mysqli_real_escape_string($conn, $_POST['status_penerimaan']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $nama_manual = mysqli_real_escape_string($conn, $_POST['nama_manual']);
    
    // Cari ID penduduk berdasarkan NIK jika ada
    $id_penduduk = null;
    if (!empty($nik)) {
        $query = "SELECT id FROM penduduk WHERE nik = '$nik' LIMIT 1";
        $result = mysqli_query($conn, $query);
        if ($row = mysqli_fetch_assoc($result)) {
            $id_penduduk = $row['id'];
        }
    }
    
    $sql = "UPDATE penerima_bansos SET 
            id_penduduk = ?, 
            id_jenis_bansos = ?, 
            tahun = ?, 
            bulan = ?, 
            status_penerimaan = ?, 
            keterangan = ?,
            updated_at = NOW()
            WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isssssi", $id_penduduk, $id_jenis_bansos, $tahun, $bulan, $status_penerimaan, $keterangan, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Data penerima berhasil diperbarui";
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
    <title>Edit Penerima Bansos</title>
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
        <h1>Edit Penerima Bansos</h1>
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
            <p><strong>Nama:</strong> <?= $penerima['nama'] ?? 'Belum diisi' ?></p>
            <p><strong>NIK:</strong> <?= $penerima['nik'] ?? 'Belum diisi' ?></p>
            <p><strong>Jenis Bansos:</strong> <?= $penerima['nama_bansos'] ?></p>
            <p><strong>Tahun:</strong> <?= $penerima['tahun'] ?></p>
        </div>

        <form method="POST" action="">
            <input type="hidden" name="action" value="edit_penerima">
            
            <div class="form-group">
                <label for="id_jenis_bansos">Jenis Bansos *</label>
                <select name="id_jenis_bansos" id="id_jenis_bansos" required>
                    <option value="">-- Pilih Jenis Bansos --</option>
                    <?php while($jb = mysqli_fetch_assoc($jenis_bansos)): ?>
                        <option value="<?= $jb['id'] ?>" <?= $jb['id'] == $penerima['id_jenis_bansos'] ? 'selected' : '' ?>>
                            <?= $jb['nama_bansos'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="nik">NIK Penerima (Opsional)</label>
                <input type="text" name="nik" id="nik" 
                       value="<?= htmlspecialchars($penerima['nik'] ?? '') ?>" 
                       placeholder="Masukkan NIK jika ada">
                <small>Kosongkan jika tidak ada data penduduk</small>
            </div>

            <div class="form-group">
                <label for="nama_manual">Nama Penerima (Otomatis terisi jika NIK ditemukan)</label>
                <input type="text" name="nama_manual" id="nama_manual" 
                       value="<?= htmlspecialchars($penerima['nama'] ?? '') ?>" 
                       placeholder="Nama akan otomatis terisi jika NIK ditemukan" readonly>
            </div>

            <div class="form-group">
                <label for="tahun">Tahun *</label>
                <input type="text" name="tahun" id="tahun" 
                       value="<?= htmlspecialchars($penerima['tahun']) ?>" required>
            </div>

            <div class="form-group">
                <label for="bulan">Bulan (Opsional)</label>
                <input type="text" name="bulan" id="bulan" 
                       value="<?= htmlspecialchars($penerima['bulan'] ?? '') ?>" 
                       placeholder="01, 02, ... atau kosongkan">
            </div>

            <div class="form-group">
                <label for="status_penerimaan">Status *</label>
                <select name="status_penerimaan" id="status_penerimaan" required>
                    <option value="proses" <?= $penerima['status_penerimaan'] == 'proses' ? 'selected' : '' ?>>Proses</option>
                    <option value="diterima" <?= $penerima['status_penerimaan'] == 'diterima' ? 'selected' : '' ?>>Diterima</option>
                    <option value="ditolak" <?= $penerima['status_penerimaan'] == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"><?= htmlspecialchars($penerima['keterangan'] ?? '') ?></textarea>
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

// Fungsi untuk autofill nama berdasarkan NIK
document.getElementById('nik').addEventListener('blur', function() {
    const nik = this.value;
    if (nik.length > 0) {
        fetch('../../../api/get_penduduk.php?nik=' + nik)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.nama) {
                    document.getElementById('nama_manual').value = data.nama;
                } else {
                    document.getElementById('nama_manual').value = '';
                    alert('NIK tidak ditemukan dalam database penduduk');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
});
</script>
</body>
</html>