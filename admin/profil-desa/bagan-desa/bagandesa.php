<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

/* Ambil data bagan desa */
$query = mysqli_query($conn, "SELECT * FROM bagan_desa ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Bagan Desa</title>
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
                <li><a href="../visi-misi/visimisi.php">Visi & Misi</a></li>
                <li><a href="./bagandesa.php" class="active" style="background:rgba(255,255,255,0.15)">Bagan Desa</a></li>
                <li><a href="../sejarah-desa/sejarah.php">Sejarah Desa</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Infografis ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../../infografis/penduduk/penduduk.php">Penduduk</a></li>
                <li><a href="../../infografis/apbdes/apbdes.php">APBDes</a></li>
                <li><a href="../../infografis/bansos/bansos.php">Bansos</a></li>
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
        <h1>Bagan Desa</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <!-- CONTENT -->
    <?php while ($b = mysqli_fetch_assoc($query)) : ?>
        <div class="card" style="margin-bottom:30px">
            <h3><?= $b['judul']; ?></h3>

            <form action="bagandesa_proses.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $b['id']; ?>">
                <input type="hidden" name="tipe" value="<?= $b['tipe']; ?>">

                <label>Judul</label>
                <input type="text" name="judul" value="<?= $b['judul']; ?>" required>

                <label>Upload Gambar</label>
                <input type="file" name="gambar" accept="image/*">

                <?php if (!empty($b['gambar'])) : ?>
                    <br>
                    <img src="../../../uploads/bagan/<?= $b['gambar']; ?>"
                         width="220"
                         style="margin-top:10px;border-radius:10px;border:1px solid #ddd">
                <?php endif; ?>

                <button type="submit" style="margin-top:15px">Simpan</button>
            </form>
        </div>
    <?php endwhile; ?>

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

function toggleDropdown(el) {
    const menu = el.nextElementSibling;
    menu.classList.toggle('show');
}
</script>

</body>
</html>
