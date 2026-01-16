<?php
include '../../../db.php';

$nama    = $_POST['nama'];
$jabatan = $_POST['jabatan'];
$urutan  = $_POST['urutan'];

$foto = '';
if (!empty($_FILES['foto']['name'])) {
    $foto = time() . '_' . $_FILES['foto']['name'];
    move_uploaded_file(
        $_FILES['foto']['tmp_name'],
        '../../../uploads/sotk/' . $foto
    );
}

mysqli_query($conn, "
    INSERT INTO sotk (nama, jabatan, foto, urutan)
    VALUES ('$nama', '$jabatan', '$foto', '$urutan')
");

header("Location: sotk.php");
