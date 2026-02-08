<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';
$data = mysqli_query($conn, "SELECT * FROM sambutan WHERE status='aktif' LIMIT 1");
$s = mysqli_fetch_assoc($data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil desa - Visi & Misi</title>
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
                <li><a href="./visimisi.php" style="background:rgba(255,255,255,0.15)">Visi & Misi</a></li>
                <li><a href="../bagan-desa/bagandesa.php">Bagan Desa</a></li>
                <li><a href="../sejarah-desa/sejarah.php">Sejarah Desa</a></li>
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
        <li><a href="../../chatbot/manage.php">Chatbot</a></li>

        
        <li><a href="../../logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

  <div class="topbar">
    <button class="hamburger" onclick="toggleSidebar()">☰</button>
    <h1>Visi & Misi</h1>
    <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
  </div>

  <div class="card">
    <form action="./visimisi_proses.php" method="POST" onsubmit="tinymce.triggerSave();">

      <input type="hidden" name="id" value="<?= $vm['id'] ?? '' ?>">

      <label>Visi</label>
      <textarea name="visi" required><?= $vm['visi'] ?? '' ?></textarea>

      <label>Misi</label>
      <textarea name="misi" required><?= $vm['misi'] ?? '' ?></textarea>

      <button type="submit" style="margin-top:15px">Simpan</button>
    </form>
  </div>

</div>


<script src="https://cdn.tiny.cloud/1/1bs7zobfm5rqmd45xvcbj36oedbxw6ke3eyzhpp3mn7dmrju/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
tinymce.init({
    selector: 'textarea[name="visi"], textarea[name="misi"]',
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
