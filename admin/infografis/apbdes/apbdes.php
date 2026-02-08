<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

// Filter tahun
$selected_year = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$current_year = date('Y');

// Query untuk dropdown tahun
$year_query = mysqli_query($conn, "SELECT DISTINCT tahun FROM apbdes ORDER BY tahun DESC");
$years = [];
while($row = mysqli_fetch_assoc($year_query)) {
    $years[] = $row['tahun'];
}

// Query data APBDes berdasarkan tahun
$query = "SELECT * FROM apbdes WHERE tahun = '$selected_year' ORDER BY tahun DESC";
$result = mysqli_query($conn, $query);
$apbdes_data = mysqli_fetch_assoc($result);

// Query detail pendapatan
$pendapatan_data = [];
if ($apbdes_data) {
    $pendapatan_query = mysqli_query($conn, "SELECT * FROM apbdes_pendapatan WHERE apbdes_id = '{$apbdes_data['id']}'");
    while($row = mysqli_fetch_assoc($pendapatan_query)) {
        $pendapatan_data[] = $row;
    }
}

// Query detail belanja
$belanja_data = [];
if ($apbdes_data) {
    $belanja_query = mysqli_query($conn, "SELECT * FROM apbdes_belanja WHERE apbdes_id = '{$apbdes_data['id']}'");
    while($row = mysqli_fetch_assoc($belanja_query)) {
        $belanja_data[] = $row;
    }
}

// Query pembiayaan
$pembiayaan_data = [];
if ($apbdes_data) {
    $pembiayaan_query = mysqli_query($conn, "SELECT * FROM apbdes_pembiayaan WHERE apbdes_id = '{$apbdes_data['id']}'");
    while($row = mysqli_fetch_assoc($pembiayaan_query)) {
        $pembiayaan_data[] = $row;
    }
}

// Hitung total untuk validasi
$total_pendapatan = 0;
$total_belanja = 0;
$total_pembiayaan_penerimaan = 0;
$total_pembiayaan_pengeluaran = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - APBDes</title>
    <link rel="stylesheet" href="../../../assets/admin/style.css">
    <style>
        /* Tambahan CSS khusus untuk APBDes */
        .filter-container {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .filter-container select {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .filter-container button {
            padding: 8px 20px;
            background: #2e7d32;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .filter-container a {
            padding: 8px 20px;
            background: #1976d2;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        
        .data-empty {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .apbdes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .apbdes-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .apbdes-card h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #2e7d32;
            padding-bottom: 10px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn-edit {
            background: #ff9800;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-delete {
            background: #f44336;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-add {
            background: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .total-box {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border-left: 4px solid #2e7d32;
        }
        
        .total-box strong {
            color: #2e7d32;
            font-size: 18px;
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

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Manajemen APBDes</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <!-- FILTER TAHUN -->
    <div class="filter-container">
        <form method="GET" action="" style="display: flex; align-items: center; gap: 10px;">
            <label for="tahun">Pilih Tahun:</label>
            <select name="tahun" id="tahun" onchange="this.form.submit()">
                <?php for($i = $current_year; $i >= 2020; $i--): ?>
                    <option value="<?= $i; ?>" <?= $i == $selected_year ? 'selected' : ''; ?>>
                        <?= $i; ?>
                    </option>
                <?php endfor; ?>
                
                <?php foreach($years as $year): ?>
                    <?php if($year < 2020): ?>
                        <option value="<?= $year; ?>" <?= $year == $selected_year ? 'selected' : ''; ?>>
                            <?= $year; ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </form>
        
        <a href="apbdes_add.php?tahun=<?= $selected_year; ?>" class="btn-add">
            + Tambah Data <?= $selected_year; ?>
        </a>
    </div>

    <?php if(!$apbdes_data): ?>
        <div class="data-empty">
            <h3>Data APBDes Tahun <?= $selected_year; ?> Belum Tersedia</h3>
            <p>Silakan tambahkan data APBDes untuk tahun ini.</p>
            <a href="apbdes_add.php?tahun=<?= $selected_year; ?>" class="btn-add">
                + Tambah Data APBDes <?= $selected_year; ?>
            </a>
        </div>
    <?php else: ?>
        <!-- DATA APBDes -->
        <div class="apbdes-grid">
            <!-- Card 1: Ringkasan -->
            <div class="apbdes-card">
                <h3>Ringkasan APBDes <?= $selected_year; ?></h3>
                <div class="detail-item">
                    <span>Pendapatan:</span>
                    <strong>Rp<?= number_format($apbdes_data['pendapatan'], 0, ',', '.'); ?></strong>
                </div>
                <div class="detail-item">
                    <span>Belanja:</span>
                    <strong>Rp<?= number_format($apbdes_data['belanja'], 0, ',', '.'); ?></strong>
                </div>
                <div class="detail-item">
                    <span>Surplus/Defisit:</span>
                    <strong style="color: #2e7d32;">
                        Rp<?= number_format($apbdes_data['pendapatan'] - $apbdes_data['belanja'], 0, ',', '.'); ?>
                    </strong>
                </div>
                
                <div class="action-buttons">
                    <a href="apbdes_edit.php?id=<?= $apbdes_data['id']; ?>" class="btn-edit">
                        Edit Ringkasan
                    </a>
                    <a href="apbdes_delete.php?id=<?= $apbdes_data['id']; ?>" 
                       class="btn-delete"
                       onclick="return confirm('Hapus data APBDes tahun <?= $selected_year; ?>?')">
                        Hapus
                    </a>
                </div>
            </div>
            
            <!-- Card 2: Pembiayaan -->
            <div class="apbdes-card">
                <h3>Pembiayaan</h3>
                <div class="detail-item">
                    <span>Penerimaan:</span>
                    <strong>Rp<?= number_format($apbdes_data['pembiayaan_penerimaan'], 0, ',', '.'); ?></strong>
                </div>
                <div class="detail-item">
                    <span>Pengeluaran:</span>
                    <strong>Rp<?= number_format($apbdes_data['pembiayaan_pengeluaran'], 0, ',', '.'); ?></strong>
                </div>
            </div>
        </div>
        
        <!-- DETAIL PENDAPATAN -->
        <div class="apbdes-card">
            <h3>Detail Pendapatan</h3>
            <?php if(empty($pendapatan_data)): ?>
                <p style="color: #666; padding: 10px 0;">Belum ada data detail pendapatan.</p>
            <?php else: ?>
                <?php foreach($pendapatan_data as $item): ?>
                    <?php $total_pendapatan += $item['jumlah']; ?>
                    <div class="detail-item">
                        <span><?= htmlspecialchars($item['jenis']); ?> (<?= $item['persentase']; ?>%)</span>
                        <strong>Rp<?= number_format($item['jumlah'], 0, ',', '.'); ?></strong>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <div class="total-box">
                Total Pendapatan: <strong>Rp<?= number_format($total_pendapatan, 0, ',', '.'); ?></strong>
                <?php if($apbdes_data['pendapatan'] != $total_pendapatan): ?>
                    <br><small style="color: #f44336;">Warning: Total detail tidak sama dengan ringkasan!</small>
                <?php endif; ?>
            </div>
            
            <div class="action-buttons">
                <a href="apbdes_detail.php?type=pendapatan&apbdes_id=<?= $apbdes_data['id']; ?>" class="btn-edit">
                    Kelola Detail Pendapatan
                </a>
            </div>
        </div>
        
        <!-- DETAIL BELANJA -->
        <div class="apbdes-card">
            <h3>Detail Belanja</h3>
            <?php if(empty($belanja_data)): ?>
                <p style="color: #666; padding: 10px 0;">Belum ada data detail belanja.</p>
            <?php else: ?>
                <?php foreach($belanja_data as $item): ?>
                    <?php $total_belanja += $item['jumlah']; ?>
                    <div class="detail-item">
                        <span><?= htmlspecialchars($item['jenis']); ?> (<?= $item['persentase']; ?>%)</span>
                        <strong>Rp<?= number_format($item['jumlah'], 0, ',', '.'); ?></strong>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <div class="total-box">
                Total Belanja: <strong>Rp<?= number_format($total_belanja, 0, ',', '.'); ?></strong>
                <?php if($apbdes_data['belanja'] != $total_belanja): ?>
                    <br><small style="color: #f44336;">Warning: Total detail tidak sama dengan ringkasan!</small>
                <?php endif; ?>
            </div>
            
            <div class="action-buttons">
                <a href="apbdes_detail.php?type=belanja&apbdes_id=<?= $apbdes_data['id']; ?>" class="btn-edit">
                    Kelola Detail Belanja
                </a>
            </div>
        </div>
        
        <!-- DETAIL PEMBIAYAAN -->
        <div class="apbdes-card">
            <h3>Detail Pembiayaan</h3>
            <?php if(empty($pembiayaan_data)): ?>
                <p style="color: #666; padding: 10px 0;">Belum ada data detail pembiayaan.</p>
            <?php else: ?>
                <?php foreach($pembiayaan_data as $item): ?>
                    <?php if($item['jenis'] == 'penerimaan') $total_pembiayaan_penerimaan += $item['jumlah']; ?>
                    <?php if($item['jenis'] == 'pengeluaran') $total_pembiayaan_pengeluaran += $item['jumlah']; ?>
                    <div class="detail-item">
                        <span><?= ucfirst($item['jenis']); ?> (<?= $item['persentase']; ?>%)</span>
                        <strong>Rp<?= number_format($item['jumlah'], 0, ',', '.'); ?></strong>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <div class="total-box">
                Total Penerimaan: <strong>Rp<?= number_format($total_pembiayaan_penerimaan, 0, ',', '.'); ?></strong><br>
                Total Pengeluaran: <strong>Rp<?= number_format($total_pembiayaan_pengeluaran, 0, ',', '.'); ?></strong>
                <?php if($apbdes_data['pembiayaan_penerimaan'] != $total_pembiayaan_penerimaan || 
                         $apbdes_data['pembiayaan_pengeluaran'] != $total_pembiayaan_pengeluaran): ?>
                    <br><small style="color: #f44336;">Warning: Total detail tidak sama dengan ringkasan!</small>
                <?php endif; ?>
            </div>
            
            <div class="action-buttons">
                <a href="apbdes_detail.php?type=pembiayaan&apbdes_id=<?= $apbdes_data['id']; ?>" class="btn-edit">
                    Kelola Detail Pembiayaan
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- JS -->
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
</script>

</body>
</html>