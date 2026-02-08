<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include '../../db.php';

$id = $_GET['id'] ?? '';
$data = mysqli_query($conn, "SELECT * FROM berita WHERE id='$id'");
$b = mysqli_fetch_assoc($data);

if (!$b) {
    echo "Data tidak ditemukan";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita</title>
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
        <li><a href="../chatbot/manage.php">Chatbot</a></li>

        
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>



<!-- MAIN -->
<div class="main berita-page">

    <!-- TOPBAR -->
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Data Berita</h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <div class="breadcrumb">
        <a href="../index.php">
            <i class="bi bi-house-door-fill"></i>
        </a>
        <span>/</span>
        <a href="./berita.php">
            Berita
        </a>
        <span>/</span>
        <a href="berita_edit.php?id=<?= $_GET['id']; ?>">
            Edit Berita
        </a>
    </div>

    <!-- CONTENT -->
    <div class="card">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px">
            <h3>Edit Berita</h3>
        </div>

        <form action="berita_update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $b['id'] ?>">
            <input type="hidden" name="gambar_lama" value="<?= $b['gambar'] ?>">

            <label>Judul Berita</label>
            <input type="text" name="judul" value="<?= $b['judul'] ?>" required>

            <label>Isi Berita</label>
            <textarea name="isi" rows="6" required><?= $b['isi'] ?></textarea>

            <label>Gambar</label>
            <input type="file" name="gambar">
            <br>
            <img src="../../uploads/berita/<?= $b['gambar'] ?>" width="150" style="margin-top:10px;border-radius:8px">

            <br><br>
            <button type="submit">Update</button>
            <a href="berita.php">Batal</a>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/1bs7zobfm5rqmd45xvcbj36oedbxw6ke3eyzhpp3mn7dmrju/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: 'textarea[name="isi"]',
    height: 300,
    menubar: false,
    plugins: 'lists link image preview code',
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link | preview',
    branding: false,
    setup: function (editor) {
        editor.on('change', function () {
            tinymce.triggerSave(); // PENTING
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

