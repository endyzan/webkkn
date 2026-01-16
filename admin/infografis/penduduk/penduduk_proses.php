<?php
session_start();
include '../../../db.php';

$total = $_POST['total_penduduk'];
$kk = $_POST['kepala_keluarga'];
$perempuan = $_POST['perempuan'];
$laki = $_POST['laki_laki'];

$cek = mysqli_query($conn, "SELECT id FROM penduduk");
if (mysqli_num_rows($cek) > 0) {
    mysqli_query($conn, "
        UPDATE penduduk SET
        total_penduduk='$total',
        kepala_keluarga='$kk',
        perempuan='$perempuan',
        laki_laki='$laki'
    ");
} else {
    mysqli_query($conn, "
        INSERT INTO penduduk 
        (total_penduduk, kepala_keluarga, perempuan, laki_laki)
        VALUES ('$total','$kk','$perempuan','$laki')
    ");
}

header("Location: penduduk.php");
exit;
