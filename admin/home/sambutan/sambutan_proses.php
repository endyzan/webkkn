<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ./admin/login.php");
    exit;
}

include '../../../db.php';


$id = $_POST['id'];
$nama = $_POST['nama_kades'];
$jabatan = $_POST['jabatan'];
$isi = $_POST['isi'];

$foto = $_FILES['foto']['name'];
$tmp  = $_FILES['foto']['tmp_name'];

if ($foto) {
    $nama_foto = time() . '_' . $foto;
    move_uploaded_file($tmp, "../../../uploads/sambutan/" . $nama_foto);
}

// nonaktifkan sambutan lama
mysqli_query($conn, "UPDATE sambutan SET status='nonaktif'");

if ($id) {
    // UPDATE
    if ($foto) {
        mysqli_query($conn, "UPDATE sambutan SET 
            nama_kades='$nama',
            jabatan='$jabatan',
            foto='$nama_foto',
            isi='$isi',
            status='aktif'
            WHERE id='$id'");
    } else {
        mysqli_query($conn, "UPDATE sambutan SET 
            nama_kades='$nama',
            jabatan='$jabatan',
            isi='$isi',
            status='aktif'
            WHERE id='$id'");
    }
} else {
    // INSERT
    mysqli_query($conn, "INSERT INTO sambutan 
        (nama_kades, jabatan, foto, isi, status)
        VALUES ('$nama','$jabatan','$nama_foto','$isi','aktif')");
}

header("Location: ./sambutan.php");
exit;
