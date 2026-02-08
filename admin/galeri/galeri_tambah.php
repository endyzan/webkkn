<?php
session_start();
include '../../db.php';

if (!isset($_SESSION['admin'])) exit;

if ($_POST) {

    $judul    = $_POST['judul'];
    $kategori = $_POST['kategori'];

    $tanggal_agenda = !empty($_POST['tanggal'])
        ? $_POST['tanggal']
        : NULL;

    $foto = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];

    $nama = time().'_'.$foto;
    move_uploaded_file($tmp, "../../uploads/galeri/".$nama);

    $stmt = $conn->prepare(
        "INSERT INTO galeri (judul, kategori, tanggal_agenda, foto)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssss",
        $judul,
        $kategori,
        $tanggal_agenda,
        $nama
    );

    $stmt->execute();

    header("Location: galeri.php");
}
?>



<form method="POST" enctype="multipart/form-data">

Judul<br>
<input name="judul" required><br><br>

Kategori<br>
<select name="kategori">
<option value="random">Random</option>
<option value="agenda">Agenda</option>
<option value="kegiatan">Kegiatan</option>
</select><br><br>

Tanggal Agenda (opsional)<br>
<input type="date" name="tanggal"><br><br>

Foto<br>
<input type="file" name="foto" required><br><br>

<button>Simpan</button>

</form>
