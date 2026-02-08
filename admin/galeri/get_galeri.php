<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

include '../../db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM galeri WHERE id = $id");
    
    if ($row = mysqli_fetch_assoc($query)) {
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        header("HTTP/1.1 404 Not Found");
    }
}
?>