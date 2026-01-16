<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';
$q = mysqli_query($conn, "SELECT * FROM penduduk ORDER BY id DESC LIMIT 1");
$p = mysqli_fetch_assoc($q);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sambutan Kepala Desa</title>
    <link rel="stylesheet" href="../../../assets/admin/style.css">
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
                <li><a href="./penduduk.php">Penduduk</a></li>
                <li><a href="../apbdes/apbdes.php">APBDes</a></li>
                <li><a href="../bansos/bansos.php">Bansos</a></li>
            </ul>
        </li>
        <li><a href="../../berita/berita.php">Berita</a></li>
        <li><a href="../../galeri/galeri.php">Galeri</a></li>
        
        <li><a href="../../logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Penduduk</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <div class="card">
        <form action="penduduk_proses.php" method="POST">

            <input type="hidden" name="id" value="<?= $p['id'] ?? '' ?>">

            <label>Total Penduduk</label>
            <input type="number" name="total_penduduk" required value="<?= $p['total_penduduk'] ?? '' ?>">

            <label>Kepala Keluarga</label>
            <input type="number" name="kepala_keluarga" required value="<?= $p['kepala_keluarga'] ?? '' ?>">

            <label>Perempuan</label>
            <input type="number" name="perempuan" required value="<?= $p['perempuan'] ?? '' ?>">

            <label>Laki-laki</label>
            <input type="number" name="laki_laki" required value="<?= $p['laki_laki'] ?? '' ?>">

            <button type="submit">Simpan Data</button>
        </form>
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
