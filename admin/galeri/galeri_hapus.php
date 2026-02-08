<?php
include '../../db.php';

$id=$_GET['id'];

$d=mysqli_fetch_assoc(mysqli_query($conn,"SELECT foto FROM galeri WHERE id=$id"));

unlink("../../../uploads/galeri/".$d['foto']);

mysqli_query($conn,"DELETE FROM galeri WHERE id=$id");

header("Location: galeri.php");
