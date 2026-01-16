<?php
include '../../../db.php';

$id   = 1; // PAKSA SATU ID
$visi = $_POST['visi'];
$misi = $_POST['misi'];

mysqli_query($conn, "
  UPDATE visi_misi 
  SET visi='$visi', misi='$misi'
  WHERE id='$id'
");

header("Location: visimisi.php");
