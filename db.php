<?php
$conn = mysqli_connect("localhost", "root", "", "desa_brakas");

if (!$conn) {
    die("Koneksi database gagal!");
}
