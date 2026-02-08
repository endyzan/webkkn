<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

$id = $_GET['id'];

// Get tahun sebelum delete untuk redirect
$query = "SELECT tahun FROM apbdes WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if($data) {
    // Delete detail terlebih dahulu karena foreign key cascade
    $tables = ['apbdes_pendapatan', 'apbdes_belanja', 'apbdes_pembiayaan'];
    foreach($tables as $table) {
        mysqli_query($conn, "DELETE FROM $table WHERE apbdes_id = '$id'");
    }
    
    // Delete apbdes utama
    mysqli_query($conn, "DELETE FROM apbdes WHERE id = '$id'");
}

header("Location: apbdes.php?tahun=" . ($data['tahun'] ?? date('Y')));
exit;