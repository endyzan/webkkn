<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

// Ambil data jenis bansos
// $jenis_bansos = mysqli_query($conn, "SELECT * FROM jenis_bansos WHERE status='aktif' ORDER BY nama_bansos");
$jenis_bansos = mysqli_query($conn, "SELECT * FROM jenis_bansos ORDER BY nama_bansos");


// Ambil data penerima bansos dengan join
$query = "SELECT pb.*, jb.nama_bansos, p.nama, p.nik 
          FROM penerima_bansos pb
          LEFT JOIN jenis_bansos jb ON pb.id_jenis_bansos = jb.id
          LEFT JOIN penduduk p ON pb.id_penduduk = p.id
          ORDER BY pb.tahun DESC, pb.id DESC";
$penerima = mysqli_query($conn, $query);

// Hitung total per jenis bansos
$stats_query = "SELECT jb.nama_bansos, COUNT(pb.id) as jumlah
                FROM jenis_bansos jb
                LEFT JOIN penerima_bansos pb ON jb.id = pb.id_jenis_bansos 
                AND pb.status_penerimaan = 'diterima'
                WHERE jb.status = 'aktif'
                GROUP BY jb.id";
$stats = mysqli_query($conn, $stats_query);
$stat_data = [];
while($row = mysqli_fetch_assoc($stats)) {
    $stat_data[$row['nama_bansos']] = $row['jumlah'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infografis - Bansos</title>
    <link rel="stylesheet" href="../../../assets/admin/style.css">
    <style>
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .action-buttons button {
            padding: 5px 10px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-edit {
            background: #4CAF50;
            color: white;
        }
        .btn-delete {
            background: #f44336;
            color: white;
        }
        .btn-view {
            background: #2196F3;
            color: white;
        }
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
            width: 80%;
            max-width: 500px;
        }
        .close {
            float: right;
            cursor: pointer;
            font-size: 24px;
        }
        .tab-container {
            margin-bottom: 20px;
        }
        .tab {
            display: flex;
            border-bottom: 1px solid #ccc;
        }
        .tab button {
            padding: 10px 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        .tab button.active {
            border-bottom: 3px solid #4CAF50;
            color: #4CAF50;
        }
        .tab-content {
            display: none;
            padding: 20px 0;
        }
        .tab-content.active {
            display: block;
        }

/* Tambahkan di akhir file */
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
    width: 80%;
    max-width: 500px;
    position: relative;
}

.close {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #f00;
}

.tab-container {
    margin-bottom: 20px;
}

.tab {
    display: flex;
    border-bottom: 1px solid #ccc;
    background: #f5f5f5;
    border-radius: 8px 8px 0 0;
}

.tab button {
    padding: 12px 24px;
    background: none;
    border: none;
    cursor: pointer;
    font-weight: bold;
    color: #666;
    transition: all 0.3s;
}

.tab button:hover {
    background: #e9e9e9;
}

.tab button.active {
    color: #4CAF50;
    border-bottom: 3px solid #4CAF50;
}

.tab-content {
    display: none;
    padding: 20px 0;
}

.tab-content.active {
    display: block;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.action-buttons button {
    padding: 5px 10px;
    font-size: 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: opacity 0.3s;
}

.action-buttons button:hover {
    opacity: 0.8;
}

.btn-edit {
    background: #4CAF50;
    color: white;
}

.btn-delete {
    background: #f44336;
    color: white;
}

.btn-view {
    background: #2196F3;
    color: white;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table th {
    background: #f2f2f2;
    padding: 12px;
    text-align: left;
    font-weight: bold;
    border-bottom: 2px solid #ddd;
}

table td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

table tr:hover {
    background: #f9f9f9;
}

.stat-card {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #4CAF50;
    margin-bottom: 20px;
}

.stat-card h3 {
    margin: 0 0 10px 0;
    color: #333;
}

.stat-card p {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin: 0;
}
    </style>
</head>
<body>
<div class="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR (tetap sama seperti sebelumnya) -->
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
                <li><a href="./bansos.php" style="background:rgba(255,255,255,0.15)">Bansos</a></li>
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
        <h1>Manajemen Bansos</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <!-- TAB NAVIGASI -->
    <div class="tab-container">
        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'tab-penerima')">Data Penerima</button>
            <button class="tablinks" onclick="openTab(event, 'tab-statistik')">Statistik</button>
            <button class="tablinks" onclick="openTab(event, 'tab-jenis')">Jenis Bansos</button>
        </div>
    </div>

    <!-- TAB 1: Data Penerima -->
    <div id="tab-penerima" class="tab-content active">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Data Penerima Bansos</h2>
                <button onclick="openModal('addModal')" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    + Tambah Penerima
                </button>
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f2f2f2;">
                        <th style="padding: 10px; text-align: left;">No</th>
                        <th style="padding: 10px; text-align: left;">Nama Penerima</th>
                        <th style="padding: 10px; text-align: left;">NIK</th>
                        <th style="padding: 10px; text-align: left;">Jenis Bansos</th>
                        <th style="padding: 10px; text-align: left;">Tahun</th>
                        <th style="padding: 10px; text-align: left;">Status</th>
                        <th style="padding: 10px; text-align: left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($row = mysqli_fetch_assoc($penerima)): ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= $no++ ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= $row['nama'] ?? '-' ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= $row['nik'] ?? '-' ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= $row['nama_bansos'] ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= $row['tahun'] ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <?php 
                            $status_color = [
                                'diterima' => 'green',
                                'ditolak' => 'red',
                                'proses' => 'orange'
                            ];
                            ?>
                            <span style="color: <?= $status_color[$row['status_penerimaan']] ?? 'black' ?>; font-weight: bold;">
                                <?= ucfirst($row['status_penerimaan']) ?>
                            </span>
                        </td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <div class="action-buttons">
                                <button class="btn-edit" onclick="editPenerima(<?= $row['id'] ?>)">Edit</button>
                                <button class="btn-delete" onclick="deletePenerima(<?= $row['id'] ?>)">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 2: Statistik -->
    <div id="tab-statistik" class="tab-content">
        <div class="card">
            <h2>Statistik Penerima Bansos</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                <?php 
                // Reset pointer untuk query stats
                mysqli_data_seek($stats, 0);
                while($stat = mysqli_fetch_assoc($stats)): 
                ?>
                <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; border-left: 4px solid #4CAF50;">
                    <h3 style="margin: 0 0 10px 0;"><?= $stat['nama_bansos'] ?></h3>
                    <p style="font-size: 24px; font-weight: bold; color: #333; margin: 0;">
                        <?= $stat['jumlah'] ?> Penerima
                    </p>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- TAB 3: Jenis Bansos -->
    <div id="tab-jenis" class="tab-content">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Jenis Bansos</h2>
                <button onclick="openModal('jenisModal')" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    + Tambah Jenis
                </button>
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f2f2f2;">
                        <th style="padding: 10px; text-align: left;">No</th>
                        <th style="padding: 10px; text-align: left;">Nama Bansos</th>
                        <th style="padding: 10px; text-align: left;">Keterangan</th>
                        <th style="padding: 10px; text-align: left;">Status</th>
                        <th style="padding: 10px; text-align: left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    mysqli_data_seek($jenis_bansos, 0);
                    $no_jenis = 1; 
                    while($jenis = mysqli_fetch_assoc($jenis_bansos)): 
                    ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= $no_jenis++ ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= $jenis['nama_bansos'] ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= $jenis['keterangan'] ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <?php if($jenis['status'] == 'aktif'): ?>
                                <span style="color: green; font-weight: bold;">Aktif</span>
                            <?php else: ?>
                                <span style="color: red; font-weight: bold;">Nonaktif</span>
                                <small style="color: #666; display: block;">(Tidak tersedia untuk penerima baru)</small>
                            <?php endif; ?>
                        </td>

                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <div class="action-buttons">
                                <button class="btn-edit" onclick="editJenis(<?= $jenis['id'] ?>)">Edit</button>
                                <button class="btn-delete" onclick="deleteJenis(<?= $jenis['id'] ?>)">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH PENERIMA -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addModal')">&times;</span>
        <h2>Tambah Penerima Bansos</h2>
        <form action="bansos_proses.php" method="POST">
            <input type="hidden" name="action" value="add_penerima">
            
            <label>Jenis Bansos</label>
            <select name="id_jenis_bansos" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
                <option value="">-- Pilih Jenis Bansos --</option>
                <?php 
                mysqli_data_seek($jenis_bansos, 0);
                while($jb = mysqli_fetch_assoc($jenis_bansos)): 
                ?>
                <option value="<?= $jb['id'] ?>"><?= $jb['nama_bansos'] ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>NIK Penerima (Opsional)</label>
            <input type="text" name="nik" placeholder="Masukkan NIK jika ada" style="width: 100%; padding: 10px; margin-bottom: 15px;">
            
            <label>Nama Penerima (Jika tidak ada di database)</label>
            <input type="text" name="nama_manual" placeholder="Nama lengkap penerima" style="width: 100%; padding: 10px; margin-bottom: 15px;">
            
            <label>Tahun</label>
            <input type="text" name="tahun" value="<?= date('Y') ?>" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
            
            <label>Bulan (Opsional)</label>
            <input type="text" name="bulan" placeholder="01, 02, ... atau kosongkan" style="width: 100%; padding: 10px; margin-bottom: 15px;">
            
            <label>Status</label>
            <select name="status_penerimaan" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
                <option value="proses">Proses</option>
                <option value="diterima">Diterima</option>
                <option value="ditolak">Ditolak</option>
            </select>
            
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3" style="width: 100%; padding: 10px; margin-bottom: 15px;"></textarea>
            
            <button type="submit" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Simpan
            </button>
        </form>
    </div>
</div>

<!-- MODAL TAMBAH JENIS BANSOS -->
<div id="jenisModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('jenisModal')">&times;</span>
        <h2>Tambah Jenis Bansos</h2>
        <form action="bansos_proses.php" method="POST">
            <input type="hidden" name="action" value="add_jenis">
            
            <label>Nama Bansos</label>
            <input type="text" name="nama_bansos" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
            
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3" style="width: 100%; padding: 10px; margin-bottom: 15px;"></textarea>
            
            <label>Status</label>
            <select name="status" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
            
            <button type="submit" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Simpan
            </button>
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

// Fungsi untuk tab
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
        tabcontent[i].classList.remove("active");
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.className += " active";
}

// Fungsi untuk modal
function openModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

// Tutup modal ketika klik di luar
window.onclick = function(event) {
    if (event.target.className === 'modal') {
        event.target.style.display = "none";
    }
}

// Fungsi edit dan delete (sederhana)
function editPenerima(id) {
    alert('Edit penerima ID: ' + id + '\nIni akan mengarah ke form edit');
    window.location.href = 'bansos_edit_penerima.php?id=' + id;
}

function deletePenerima(id) {
    if(confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        window.location.href = 'bansos_proses.php?action=delete_penerima&id=' + id;
    }
}

function editJenis(id) {
    alert('Edit jenis ID: ' + id + '\nIni akan mengarah ke form edit');
    window.location.href = 'bansos_edit_jenis.php?id=' + id;
}

function deleteJenis(id) {
    if(confirm('Apakah Anda yakin ingin menghapus jenis bansos ini?')) {
        window.location.href = 'bansos_proses.php?action=delete_jenis&id=' + id;
    }
}
</script>

</body>
</html>