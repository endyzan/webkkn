<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../db.php';
$data = mysqli_query($conn, "SELECT * FROM sambutan WHERE status='aktif' LIMIT 1");
$s = mysqli_fetch_assoc($data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri</title>
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
        <li><a href="../berita/berita.php">Berita</a></li>
        <li><a href="./galeri.php">Galeri</a></li>
        
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Sambutan Kepala Desa</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <!-- CARD FORM -->
    <div class="card">
        <form action="./sambutan_proses.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?= $s['id'] ?? '' ?>">

            <label>Nama Kepala Desa</label>
            <input type="text" name="nama_kades" value="<?= $s['nama_kades'] ?? '' ?>" required>

            <label>Jabatan</label>
            <input type="text" name="jabatan" value="<?= $s['jabatan'] ?? 'Kepala Desa' ?>">

            <label>Foto</label>
            <input type="file" name="foto">
            <?php if (!empty($s['foto'])): ?>
                <br>
                <img src="../../../uploads/sambutan/<?= $s['foto']; ?>" width="120" style="margin-top:10px;border-radius:8px;">
            <?php endif; ?>

            <label>Isi Sambutan</label>
            <textarea name="isi" rows="6" required><?= $s['isi'] ?? '' ?></textarea>

            <button type="submit" style="margin-top:15px">Simpan</button>
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
