<?php
include '../../../db.php';

$id    = $_POST['id'] ?? '';
$judul = mysqli_real_escape_string($conn, $_POST['judul']);
$isi   = mysqli_real_escape_string($conn, $_POST['isi']);

$fotoQuery = "";
if (!empty($_FILES['foto']['name'])) {
    $namaFile = time() . '_' . $_FILES['foto']['name'];
    move_uploaded_file($_FILES['foto']['tmp_name'], "../../../uploads/sejarah/$namaFile");
    $fotoQuery = ", foto='$namaFile'";
}

/* JIKA DATA SUDAH ADA → UPDATE */
if (!empty($id)) {

    mysqli_query($conn, "
        UPDATE sejarah_desa 
        SET judul='$judul', isi='$isi' $fotoQuery
        WHERE id='$id'
    ");

} else {

    /* JIKA DATA BELUM ADA → INSERT SEKALI */
    mysqli_query($conn, "
        INSERT INTO sejarah_desa (judul, isi, foto, status)
        VALUES ('$judul', '$isi', '$namaFile', 'aktif')
    ");
}

header("Location: sejarah.php");
exit;
