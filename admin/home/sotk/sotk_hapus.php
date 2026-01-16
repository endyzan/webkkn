<?php
include '../../../db.php';

$id = $_GET['id'];

$data = mysqli_query($conn, "SELECT foto FROM sotk WHERE id=$id");
$row = mysqli_fetch_assoc($data);

if ($row['foto']) {
    unlink('../../../uploads/sotk/' . $row['foto']);
}

mysqli_query($conn, "DELETE FROM sotk WHERE id=$id");

header("Location: sotk.php");
