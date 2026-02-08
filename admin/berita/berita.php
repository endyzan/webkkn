<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include '../../db.php';

$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

$query = "SELECT * FROM berita";
if ($search != '') {
    $query .= " WHERE judul LIKE '%$search%'";
}
$query .= " ORDER BY id DESC";

$data = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Berita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/admin/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Infografis ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../infografis/penduduk/penduduk.php">Penduduk</a></li>
                <li><a href="../infografis/apbdes/apbdes.php">APBDes</a></li>
                <li><a href="../infografis/bansos/bansos.php">Bansos</a></li>
            </ul>
        </li>

        <li><a href="./berita.php" style="background:rgba(255,255,255,0.15)">Berita</a></li>
        <li><a href="../galeri/galeri.php">Galeri</a></li>
        <li><a href="./chatbot/manage.php">Chatbot</a></li>

        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main berita-page">

    <!-- TOPBAR -->
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Manajemen Berita</h1>
        <span>Halo, <?= htmlspecialchars($_SESSION['nama_admin']); ?></span>
    </div>


    <div class="breadcrumb">
        <a href="../index.php">
            <i class="bi bi-house-door-fill"></i>
        </a>
        <span>/</span>
        <a href="./berita.php">
            Berita
        </a>
    </div>

    <!-- CONTENT -->
    <div class="card berita-card">

        <!-- HEADER -->
        <div class="berita-header">
            <h3>Daftar Berita</h3>
            <a href="berita_tambah.php" class="btn-berita">+ Tambah Berita</a>
        </div>

        <!-- SEARCH -->
        <form method="GET" class="berita-search">
            <input type="text" name="q" value="<?= htmlspecialchars($search); ?>" placeholder="Cari judul berita...">
        </form>

        <!-- TABLE -->
        <div style="overflow-x:auto">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Dilihat</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>

            <?php if(mysqli_num_rows($data) == 0): ?>
                <tr>
                    <td colspan="5" style="text-align:center;color:#64748b">
                        Tidak ada berita
                    </td>
                </tr>
            <?php endif; ?>

            <?php $no = 1; while($b = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <strong><?= htmlspecialchars($b['judul']); ?></strong>
                    </td>
                    <td><?= date('d M Y', strtotime($b['tanggal'])); ?></td>
                    <td>
                        <span class="badge"><?= (int)$b['dilihat']; ?>x</span>
                    </td>
                    <td>
                        <a href="berita_edit.php?id=<?= $b['id']; ?>" class="btn-edit"><i class="bi bi-pen-fill"></i></a>
                        <a href="berita_hapus.php?id=<?= $b['id']; ?>" 
                           class="btn-hapus"
                           onclick="return confirm('Yakin hapus berita ini?')">
                           <i class="bi bi-trash-fill"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>

            </tbody>
        </table>
        </div>

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

function toggleDropdown(el) {
    el.nextElementSibling.classList.toggle('show');
}
</script>

</body>
</html>
