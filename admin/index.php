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
    <title>Dashboard Admin Desa</title>
    <link rel="stylesheet" href="../assets/admin/style.css">
</head>
<body>



<div class="sidebar">
    <h2>ADMIN DESA</h2>
    <ul>
        <li><a href="./index.php">Dashboard</a></li>
        <li><a href="./home/sambutan/sambutan.php">Sambutan Kades</a></li>
        <li><a href="#">Berita</a></li>
        <li><a href="#">Galeri</a></li>
        <li><a href="#">Infografis</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>
<div class="main">
    <div class="topbar">
        <h1>Dashboard</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <div class="cards">
        <div class="card">
            <h3>Sambutan</h3>
            <p>Kelola sambutan kepala desa</p>
        </div>

        <div class="card">
            <h3>Berita</h3>
            <p>Kelola berita desa</p>
        </div>

        <div class="card">
            <h3>Galeri</h3>
            <p>Kelola foto kegiatan</p>
        </div>

        <div class="card">
            <h3>Infografis</h3>
            <p>Data penduduk, APBDes, Bansos</p>
        </div>
    </div>
</div>



</body>
</html>
