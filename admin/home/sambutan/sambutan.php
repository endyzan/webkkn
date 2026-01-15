<?php
include '../../../db.php';

$data = mysqli_query($conn, "SELECT * FROM sambutan WHERE status='aktif' LIMIT 1");
$s = mysqli_fetch_assoc($data);
?>



<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin Desa</title>
    <link rel="stylesheet" href="../../../assets/admin/style.css">
</head>
<body>



<div class="sidebar">
    <h2>ADMIN DESA</h2>
    <ul>
        <li><a href="./index.php">Dashboard</a></li>
        <li><a href="./home/sambutan/sambutan.php">Sambutan Kades</a></li>
        <li><a href="#">Berita</a></li>
        <li><a href="#">Galeri</a></li>

        <!-- INF0GRAFIS DROPDOWN -->
        <li class="dropdown">
            <a href="#">Infografis</a>
            <ul class="dropdown-menu">
                <li><a href="#">Penduduk</a></li>
                <li><a href="#">Bansos</a></li>
                <li><a href="#">APBDes</a></li>
            </ul>
        </li>

        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>



<div class="main">
    <h1>Sambutan Kepala Desa</h1>

    <form action="./sambutan_proses.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $s['id'] ?? '' ?>">

        <label>Nama Kepala Desa</label><br>
        <input type="text" name="nama_kades" value="<?= $s['nama_kades'] ?? '' ?>" required><br><br>

        <label>Jabatan</label><br>
        <input type="text" name="jabatan" value="<?= $s['jabatan'] ?? 'Kepala Desa' ?>"><br><br>

        <label>Foto</label><br>
        <input type="file" name="foto"><br>
        <?php if (!empty($s['foto'])): ?>
            <img src="../../../uploads/sambutan/?= $s['foto']; ?>" width="120"><br>
        <?php endif; ?>
        <br>

        <label>Isi Sambutan</label><br>
        <textarea name="isi" rows="6" required><?= $s['isi'] ?? '' ?></textarea><br><br>

        <button type="submit">Simpan</button>
    </form>
</div>

</body>
</html>
