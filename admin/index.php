<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Desa</title>
    <link rel="stylesheet" href="../assets/admin/style.css">
</head>
<body>
<div class="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>ADMIN DESA</h2>
    <ul>
        <li><a href="./index.php" style="background:rgba(255,255,255,0.15)">Dashboard</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Home ▾</a>
            <ul class="dropdown-menu">
                <li><a href="./home/banner-hero/banner.php">Banner</a></li>
                <li><a href="./home/sambutan/sambutan.php">Sambutan</a></li>
                <li><a href="./home/sotk/sotk.php">SOTK</a></li>
            </ul>
        </li>
        
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Profil Desa ▾</a>
            <ul class="dropdown-menu">
                <li><a href="./profil-desa/visi-misi/visimisi.php">Visi & Misi</a></li>
                <li><a href="./profil-desa/bagan-desa/bagandesa.php">Bagan Desa</a></li>
                <li><a href="./profil-desa/sejarah-desa/sejarah.php">Sejarah Desa</a></li>
            </ul>
        </li>
        
        <!-- DROPDOWN INFOGRAFIS -->
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Infografis ▾</a>
            <ul class="dropdown-menu">
                <li><a href="./infografis/penduduk/penduduk.php">Penduduk</a></li>
                <li><a href="./infografis/apbdes/apbdes.php">APBDes</a></li>
                <li><a href="./infografis/bansos/bansos.php">Bansos</a></li>
            </ul>
        </li>
        <li><a href="./berita/berita.php">Berita</a></li>
        <li><a href="./galeri/galeri.php">Galeri</a></li>
        <li><a href="./chatbot/manage.php">Chatbot</a></li>

        
        <li><a href="./logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Dashboard</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <div class="cards">
        <a href="./infografis/penduduk/penduduk.php" class="card-link">
            <div class="card">
                <h3>Data Penduduk</h3>
                <p>Kelola data penduduk desa</p>
            </div>
        </a>

        <a href="./berita/berita.php" class="card-link">
            <div class="card">
                <h3>Berita</h3>
                <p>Kelola berita desa</p>
            </div>
        </a>

        <a href="./galeri/galeri.php" class="card-link">
            <div class="card">
                <h3>Galeri</h3>
                <p>Kelola foto kegiatan</p>
            </div>
        </a>
    </div>

</div>

<!-- JAVASCRIPT -->
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
