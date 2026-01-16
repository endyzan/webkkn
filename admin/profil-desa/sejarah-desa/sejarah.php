<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';
/* Ambil sejarah aktif */
$q = mysqli_query($conn, "SELECT * FROM sejarah_desa WHERE status='aktif' LIMIT 1");
$sejarah = mysqli_fetch_assoc($q);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil desa - Sejarah</title>
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
                <li><a href="../bagan-desa/bagandesa.php">Bagan Desa</a></li>
                <li><a href="./sejarah.php">Sejarah Desa</a></li>
            </ul>
        </li>
        
        <!-- DROPDOWN INFOGRAFIS -->
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
        
        <li><a href="../../logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Sejarah</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <!-- CARD FORM -->
    <div class="card">
        <form action="./sejarah_proses.php" method="POST" enctype="multipart/form-data" onsubmit="tinymce.triggerSave();">

            <input type="hidden" name="id" value="<?= $sejarah['id'] ?? '' ?>">

            <label>Judul</label>
            <input type="text" name="judul" value="<?= $sejarah['judul'] ?? 'Sejarah Desa' ?>" required>

            <label>Foto Sejarah</label>
            <input type="file" name="foto">

            <?php if (!empty($sejarah['foto'])) : ?>
                <br>
                <img src="../../../uploads/sejarah/<?= $sejarah['foto']; ?>" width="150" style="margin-top:10px;border-radius:8px;">
            <?php endif; ?>

            <label>Isi Sejarah</label>
            <textarea name="isi" required><?= $sejarah['isi'] ?? '' ?></textarea>

            <button type="submit" style="margin-top:15px">Simpan</button>
        </form>
    </div>

</div>


    <script src="https://cdn.tiny.cloud/1/1bs7zobfm5rqmd45xvcbj36oedbxw6ke3eyzhpp3mn7dmrju/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
    tinymce.init({
        selector: 'textarea[name="isi"]',
        height: 350,
        menubar: false,
        plugins: 'lists link image preview code',
        toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | link | preview',
        branding: false,
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
    </script>

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
