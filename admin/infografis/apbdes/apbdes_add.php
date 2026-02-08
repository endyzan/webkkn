<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Cek apakah data tahun ini sudah ada
$check = mysqli_query($conn, "SELECT id FROM apbdes WHERE tahun = '$tahun'");
if(mysqli_num_rows($check) > 0) {
    header("Location: apbdes.php?tahun=$tahun");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);
    $pendapatan = str_replace(['.', ','], ['', '.'], $_POST['pendapatan']);
    $belanja = str_replace(['.', ','], ['', '.'], $_POST['belanja']);
    $pembiayaan_penerimaan = str_replace(['.', ','], ['', '.'], $_POST['pembiayaan_penerimaan']);
    $pembiayaan_pengeluaran = str_replace(['.', ','], ['', '.'], $_POST['pembiayaan_pengeluaran']);
    
    $query = "INSERT INTO apbdes (tahun, pendapatan, belanja, pembiayaan_penerimaan, pembiayaan_pengeluaran) 
              VALUES ('$tahun', '$pendapatan', '$belanja', '$pembiayaan_penerimaan', '$pembiayaan_pengeluaran')";
    
    if(mysqli_query($conn, $query)) {
        $apbdes_id = mysqli_insert_id($conn);
        
        // Redirect ke halaman detail
        header("Location: apbdes_detail.php?type=pendapatan&apbdes_id=$apbdes_id");
        exit;
    } else {
        $error = "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah APBDes</title>
    <link rel="stylesheet" href="../../../assets/admin/style.css">
    <style>
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #2e7d32;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
        }
        
        .btn-submit {
            background: #2e7d32;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-cancel {
            background: #757575;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        
        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .info-box {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #2196f3;
        }
    </style>
</head>
<body>
<div class="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR (sama seperti di atas) -->
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
                <li><a href="./apbdes.php" style="background:rgba(255,255,255,0.15)">APBDes</a></li>
                <li><a href="../bansos/bansos.php">Bansos</a></li>
            </ul>
        </li>
        <li><a href="../../berita/berita.php">Berita</a></li>
        <li><a href="../../galeri/galeri.php">Galeri</a></li>
        <li><a href="../../chatbot/manage.php">Chatbot</a></li>

        
        <li><a href="../../logout.php">Logout</a></li>
    </ul>
</div>

<div class="main">
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Tambah Data APBDes</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <div class="form-container">
        <?php if(isset($error)): ?>
            <div class="error-message"><?= $error; ?></div>
        <?php endif; ?>
        
        <div class="info-box">
            <strong>Informasi:</strong> Anda akan menambahkan data APBDes untuk tahun <?= $tahun; ?>. 
            Setelah menambahkan ringkasan, Anda dapat menambahkan detail pendapatan, belanja, dan pembiayaan.
        </div>
        
        <form method="POST" action="">
            <input type="hidden" name="tahun" value="<?= $tahun; ?>">
            
            <div class="form-group">
                <label>Tahun</label>
                <input type="text" value="<?= $tahun; ?>" readonly style="background: #f5f5f5;">
            </div>
            
            <div class="form-group">
                <label>Total Pendapatan (Rp)</label>
                <input type="text" name="pendapatan" required 
                       placeholder="Contoh: 4254715300" 
                       oninput="formatRupiah(this)">
            </div>
            
            <div class="form-group">
                <label>Total Belanja (Rp)</label>
                <input type="text" name="belanja" required 
                       placeholder="Contoh: 4235654389" 
                       oninput="formatRupiah(this)">
            </div>
            
            <div class="form-group">
                <label>Pembiayaan - Penerimaan (Rp)</label>
                <input type="text" name="pembiayaan_penerimaan" 
                       placeholder="Contoh: 125939089" 
                       oninput="formatRupiah(this)">
            </div>
            
            <div class="form-group">
                <label>Pembiayaan - Pengeluaran (Rp)</label>
                <input type="text" name="pembiayaan_pengeluaran" 
                       placeholder="Contoh: 145000000" 
                       oninput="formatRupiah(this)">
            </div>
            
            <div class="form-actions">
                <a href="apbdes.php?tahun=<?= $tahun; ?>" class="btn-cancel">
                    Batal
                </a>
                <button type="submit" class="btn-submit">
                    Simpan & Lanjutkan ke Detail
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function formatRupiah(input) {
    // Hapus karakter selain angka
    let value = input.value.replace(/[^\d]/g, '');
    
    // Format dengan titik sebagai pemisah ribuan
    if(value) {
        value = parseInt(value).toLocaleString('id-ID');
    }
    
    input.value = value;
}

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
</script>

</body>
</html>