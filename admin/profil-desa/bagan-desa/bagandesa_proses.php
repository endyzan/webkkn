<?php
session_start();
include '../../../db.php';

$id     = $_POST['id'];
$judul  = $_POST['judul'];
$tipe   = $_POST['tipe'];

$gambarBaru = $_FILES['gambar']['name'];

if ($gambarBaru) {
    $tmp = $_FILES['gambar']['tmp_name'];
    $namaFile = time().'_'.$gambarBaru;
    $path = "../../../uploads/bagan/".$namaFile;
    move_uploaded_file($tmp, $path);

    mysqli_query($conn, "
        UPDATE bagan_desa SET
        judul='$judul',
        gambar='$namaFile'
        WHERE id='$id'
    ");
} else {
    mysqli_query($conn, "
        UPDATE bagan_desa SET
        judul='$judul'
        WHERE id='$id'
    ");
}

header("Location: bagandesa.php");
exit;
