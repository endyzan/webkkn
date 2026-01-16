<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

$data = mysqli_query($conn, "SELECT * FROM sotk ORDER BY urutan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - SOTK</title>
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
                <li><a href="../banner-hero/banner.php">Banner</a></li>
                <li><a href="../sambutan/sambutan.php">Sambutan</a></li>
                <li><a href=".sotk.php">SOTK</a></li>
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
        <h1>SOTK</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <div class="card">
        <h3>Tambah Aparat Desa</h3>

        <form action="sotk_proses.php" method="POST" enctype="multipart/form-data">

            <label>Nama</label>
            <input type="text" name="nama" required>

            <label>Jabatan</label>
            <input type="text" name="jabatan" required>

            <label>Urutan</label>
            <input type="number" name="urutan" value="0">

            <label>Foto</label>
            <input type="file" name="foto">

            <button type="submit" name="simpan">Simpan</button>
        </form>
    </div>



    <div class="card">
        <h3>Data Aparat Desa</h3>

        <table class="table">
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Aksi</th>
            </tr>

            <?php $no=1; while($s = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>
                    <img src="../../../uploads/sotk/<?= $s['foto']; ?>" width="60">
                </td>
                <td><?= $s['nama']; ?></td>
                <td><?= $s['jabatan']; ?></td>
                <td>
                    <a href="./sotk_hapus.php?id=<?= $s['id']; ?>" onclick="return confirm('Hapus data?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
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
