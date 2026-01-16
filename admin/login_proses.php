<?php
session_start();

session_start();

if (
    !isset($_POST['csrf_token']) ||
    !isset($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    die('Akses tidak valid (CSRF detected)');
}

include '../db.php';

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
$data  = mysqli_fetch_assoc($query);

if ($data && password_verify($password, $data['password'])) {
    $_SESSION['admin'] = $data['id'];
    $_SESSION['nama_admin'] = $data['nama'];
    header("Location: index.php");
    exit;
} else {
    echo "<script>
            alert('Username atau Password salah!');
            window.location='login.php';
          </script>";
}
