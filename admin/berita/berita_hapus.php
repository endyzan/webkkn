<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include '../../db.php';

$id = $_GET['id'];

$data = mysqli_query($conn, "SELECT gambar FROM berita WHERE id='$id'");
$b = mysqli_fetch_assoc($data);

if ($b) {
    if (file_exists("../../uploads/berita/".$b['gambar'])) {
        unlink("../../uploads/berita/".$b['gambar']);
    }

    mysqli_query($conn, "DELETE FROM berita WHERE id='$id'");
}

header("Location: berita.php");
