<?php
include '../../db.php';

$judul = $_POST['judul'];
$isi = $_POST['isi'];
$tanggal = date('Y-m-d');

$gambar = $_FILES['gambar']['name'];
$tmp = $_FILES['gambar']['tmp_name'];

move_uploaded_file($tmp, "../../uploads/berita/".$gambar);

mysqli_query($conn, "INSERT INTO berita 
(judul, isi, gambar, penulis, tanggal) 
VALUES ('$judul','$isi','$gambar','Administrator','$tanggal')");

header("Location: berita.php");
