<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

// Ambil data statistik
$stat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM statistik_penduduk LIMIT 1"));

// Ambil data penduduk untuk tabel
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$dusun_filter = isset($_GET['dusun']) ? $_GET['dusun'] : '';

$where = "WHERE 1=1";
if ($search) {
    $where .= " AND (nama LIKE '%$search%' OR nik LIKE '%$search%' OR alamat LIKE '%$search%')";
}
if ($status_filter) {
    $where .= " AND status_penduduk = '$status_filter'";
}
if ($dusun_filter) {
    $where .= " AND dusun = '$dusun_filter'";
}

$query = "SELECT * FROM penduduk $where ORDER BY nama LIMIT 100";
$result = mysqli_query($conn, $query);

// Ambil daftar dusun unik
$dusun_result = mysqli_query($conn, "SELECT DISTINCT dusun FROM penduduk WHERE dusun IS NOT NULL ORDER BY dusun");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Penduduk</title>
    <link rel="stylesheet" href="../../../assets/admin/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .search-box {
            display: flex;
            gap: 10px;
            flex: 1;
            max-width: 500px;
        }
        
        .search-box input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .filters select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }
        
        .btn-add {
            background: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-add:hover {
            background: #45a049;
        }
        
        .table-responsive {
            overflow-x: auto;
            margin-top: 20px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .data-table th {
            background: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        
        .data-table tr:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-hidup { background: #d4edda; color: #155724; }
        .status-meninggal { background: #f8d7da; color: #721c24; }
        .status-pindah { background: #fff3cd; color: #856404; }
        .status-penduduk_sementara { background: #d1ecf1; color: #0c5460; }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-edit, .btn-delete {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
        
        .btn-edit {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-edit:hover {
            background: #e0a800;
        }
        
        .btn-delete:hover {
            background: #c82333;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 0;
            width: 90%;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-body {
            padding: 20px;
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .form-group {
            flex: 1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }
        
        .close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }
        
        .alert {
            padding: 12px 20px;
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
        
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            margin: 0;
            font-size: 28px;
            color: #2c3e50;
        }
        
        .stat-card p {
            margin: 5px 0 0;
            color: #7f8c8d;
            font-size: 14px;
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
        
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Infografis ▾</a>
            <ul class="dropdown-menu">
                <li><a href="./penduduk.php" style="background:rgba(255,255,255,0.15)">Penduduk</a></li>
                <li><a href="../apbdes/apbdes.php">APBDes</a></li>
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
        <h1>Manajemen Penduduk</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <!-- Statistik -->
    <div class="stat-cards">
        <div class="stat-card">
            <h3><?= number_format($stat['total_penduduk'] ?? 0) ?></h3>
            <p>Total Penduduk</p>
        </div>
        <div class="stat-card">
            <h3><?= number_format($stat['kepala_keluarga'] ?? 0) ?></h3>
            <p>Kepala Keluarga</p>
        </div>
        <div class="stat-card">
            <h3><?= number_format($stat['perempuan'] ?? 0) ?></h3>
            <p>Perempuan</p>
        </div>
        <div class="stat-card">
            <h3><?= number_format($stat['laki_laki'] ?? 0) ?></h3>
            <p>Laki-laki</p>
        </div>
    </div>

    <div class="card">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card-header">
            <div class="search-box">
                <form method="GET" style="display: flex; width: 100%;">
                    <input type="text" name="search" placeholder="Cari nama/NIK/alamat..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn-add"><i class="fas fa-search"></i> Cari</button>
                </form>
            </div>
            
            <div class="filters">
                <select onchange="this.form.submit()" name="status">
                    <option value="">Semua Status</option>
                    <option value="hidup" <?= $status_filter == 'hidup' ? 'selected' : '' ?>>Hidup</option>
                    <option value="meninggal" <?= $status_filter == 'meninggal' ? 'selected' : '' ?>>Meninggal</option>
                    <option value="pindah" <?= $status_filter == 'pindah' ? 'selected' : '' ?>>Pindah</option>
                    <option value="penduduk_sementara" <?= $status_filter == 'penduduk_sementara' ? 'selected' : '' ?>>Sementara</option>
                </select>
                
                <select onchange="this.form.submit()" name="dusun">
                    <option value="">Semua Dusun</option>
                    <?php while ($d = mysqli_fetch_assoc($dusun_result)): ?>
                        <option value="<?= $d['dusun'] ?>" <?= $dusun_filter == $d['dusun'] ? 'selected' : '' ?>>
                            <?= $d['dusun'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <button class="btn-add" onclick="openModal()">
                <i class="fas fa-plus"></i> Tambah Penduduk
            </button>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Dusun</th>
                        <th>Status Penduduk</th>
                        <th>KK</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $row['nik'] ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= $row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                                <td><?= $row['dusun'] ?></td>
                                <td>
                                    <?php 
                                    $status_text = [
                                        'hidup' => 'Hidup',
                                        'meninggal' => 'Meninggal',
                                        'pindah' => 'Mutasi/Pindah',
                                        'penduduk_sementara' => 'Penduduk Sementara'
                                    ];
                                    $status_class = 'status-' . $row['status_penduduk'];
                                    ?>
                                    <span class="status-badge <?= $status_class ?>">
                                        <?= $status_text[$row['status_penduduk']] ?>
                                    </span>
                                </td>
                                <td><?= $row['kk'] ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-edit" onclick="editData(<?= htmlspecialchars(json_encode($row)) ?>)">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="?hapus=<?= $row['id'] ?>" class="btn-delete" 
                                           onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px;">
                                Tidak ada data penduduk
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Tambah/Edit -->
<div id="modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Tambah Data Penduduk</h2>
            <button class="close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="formPenduduk" action="penduduk_proses.php" method="POST">
                <input type="hidden" id="id" name="id" value="">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>NIK *</label>
                        <input type="text" name="nik" id="nik" required maxlength="16">
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="nama" id="nama" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Jenis Kelamin *</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required>
                            <option value="">Pilih</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Agama</label>
                        <select name="agama" id="agama">
                            <option value="">Pilih</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" id="alamat" rows="2"></textarea>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Dusun</label>
                        <input type="text" name="dusun" id="dusun" list="dusun-list">
                        <datalist id="dusun-list">
                            <option value="Takabuh Tengah">
                            <option value="Takabuh Timur">
                            <option value="Takabuh Barat">
                            <option value="Takabuh Selatan">
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label>Nomor KK</label>
                        <input type="text" name="kk" id="kk">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Status Keluarga</label>
                        <select name="status_keluarga" id="status_keluarga">
                            <option value="">Pilih</option>
                            <option value="Kepala Keluarga">Kepala Keluarga</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                            <option value="Menantu">Menantu</option>
                            <option value="Cucu">Cucu</option>
                            <option value="Orang Tua">Orang Tua</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status Perkawinan</label>
                        <select name="status_perkawinan" id="status_perkawinan">
                            <option value="">Pilih</option>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Cerai Hidup">Cerai Hidup</option>
                            <option value="Cerai Mati">Cerai Mati</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Pendidikan Terakhir</label>
                        <select name="pendidikan" id="pendidikan">
                            <option value="">Pilih</option>
                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA/SMK">SMA/SMK</option>
                            <option value="D1/D2/D3">D1/D2/D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pekerjaan</label>
                        <input type="text" name="pekerjaan" id="pekerjaan" list="pekerjaan-list">
                        <datalist id="pekerjaan-list">
                            <option value="Petani">
                            <option value="Nelayan">
                            <option value="PNS">
                            <option value="TNI/Polri">
                            <option value="Wiraswasta">
                            <option value="Buruh">
                            <option value="Pelajar/Mahasiswa">
                            <option value="Ibu Rumah Tangga">
                            <option value="Tidak Bekerja">
                        </datalist>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Status Penduduk *</label>
                        <select name="status_penduduk" id="status_penduduk" required>
                            <option value="hidup">Hidup</option>
                            <option value="meninggal">Meninggal</option>
                            <option value="pindah">Mutasi/Pindah</option>
                            <option value="penduduk_sementara">Penduduk Sementara</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Status</label>
                        <input type="date" name="tanggal_status" id="tanggal_status">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="2" 
                                  placeholder="Keterangan tambahan..."></textarea>
                    </div>
                </div>
                
                <div style="text-align: right; margin-top: 20px;">
                    <button type="button" class="btn-delete" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-add">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const sidebar = document.querySelector('.sidebar');
const overlay = document.querySelector('.overlay');
const modal = document.getElementById('modal');

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

// Modal functions
function openModal(data = null) {
    if (data) {
        document.getElementById('modalTitle').textContent = 'Edit Data Penduduk';
        document.getElementById('id').value = data.id;
        document.getElementById('nik').value = data.nik;
        document.getElementById('nama').value = data.nama;
        document.getElementById('tempat_lahir').value = data.tempat_lahir;
        document.getElementById('tanggal_lahir').value = data.tanggal_lahir;
        document.getElementById('jenis_kelamin').value = data.jenis_kelamin;
        document.getElementById('agama').value = data.agama;
        document.getElementById('alamat').value = data.alamat;
        document.getElementById('dusun').value = data.dusun;
        document.getElementById('kk').value = data.kk;
        document.getElementById('status_keluarga').value = data.status_keluarga;
        document.getElementById('status_perkawinan').value = data.status_perkawinan;
        document.getElementById('pendidikan').value = data.pendidikan;
        document.getElementById('pekerjaan').value = data.pekerjaan;
        document.getElementById('status_penduduk').value = data.status_penduduk;
        document.getElementById('tanggal_status').value = data.tanggal_status;
        document.getElementById('keterangan').value = data.keterangan;
    } else {
        document.getElementById('modalTitle').textContent = 'Tambah Data Penduduk';
        document.getElementById('formPenduduk').reset();
        document.getElementById('id').value = '';
        document.getElementById('tanggal_status').value = new Date().toISOString().split('T')[0];
    }
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function editData(data) {
    openModal(data);
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == modal) {
        closeModal();
    }
}

// Set tanggal status default
window.onload = function() {
    if (!document.getElementById('tanggal_status').value) {
        document.getElementById('tanggal_status').value = new Date().toISOString().split('T')[0];
    }
}
</script>

</body>
</html>