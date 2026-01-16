<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include '../../db.php';
$data = mysqli_query($conn, "SELECT * FROM berita ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita</title>
    <link rel="stylesheet" href="../../assets/admin/style.css">
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
        
        <!-- DROPDOWN INFOGRAFIS -->
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Infografis ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../infografis/penduduk/penduduk.php">Penduduk</a></li>
                <li><a href="../infografis/apbdes/apbdes.php">APBDes</a></li>
                <li><a href="../infografis/bansos/bansos.php">Bansos</a></li>
            </ul>
        </li>
        <li><a href="./berita.php">Berita</a></li>
        <li><a href="../galeri/galeri.php">Galeri</a></li>
        
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Data Berita</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <!-- CONTENT -->
    <div class="card">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px">
            <h3>Daftar Berita</h3>
            <a href="berita_tambah.php" class="btn">+ Tambah Berita</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Dilihat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($data) == 0): ?>
                <tr>
                    <td colspan="5" style="text-align:center">Belum ada berita</td>
                </tr>
                <?php endif; ?>

                <?php $no=1; while($b = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $b['judul'] ?></td>
                    <td><?= date('d M Y', strtotime($b['tanggal'])) ?></td>
                    <td><?= $b['dilihat'] ?></td>
                    <td>
                        <a href="berita_edit.php?id=<?= $b['id'] ?>" class="btn-edit">Edit</a>
                        <a href="berita_hapus.php?id=<?= $b['id'] ?>" 
                           class="btn-hapus"
                           onclick="return confirm('Yakin hapus berita ini?')">
                           Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
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
