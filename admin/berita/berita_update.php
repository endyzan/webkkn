<?php
include '../../db.php';

$id    = $_POST['id'];
$judul = $_POST['judul'];
$isi   = $_POST['isi'];

$gambar_lama = $_POST['gambar_lama'];

if (!empty($_FILES['gambar']['name'])) {
    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    move_uploaded_file($tmp, "../../uploads/berita/".$gambar);

    // hapus gambar lama
    if (file_exists("../../uploads/berita/".$gambar_lama)) {
        unlink("../../uploads/berita/".$gambar_lama);
    }
} else {
    $gambar = $gambar_lama;
}

mysqli_query($conn, "UPDATE berita SET 
    judul='$judul',
    isi='$isi',
    gambar='$gambar'
    WHERE id='$id'
");

header("Location: berita.php");
