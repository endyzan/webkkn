<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

// Fungsi untuk update statistik
function updateStatistik($conn) {
    // Hitung total penduduk yang masih hidup
    $total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM penduduk WHERE status_penduduk = 'hidup'"))['total'];
    
    // Hitung kepala keluarga
    $kk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT kk) as total FROM penduduk WHERE status_penduduk = 'hidup'"))['total'];
    
    // Hitung perempuan
    $perempuan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM penduduk WHERE jenis_kelamin = 'P' AND status_penduduk = 'hidup'"))['total'];
    
    // Hitung laki-laki
    $laki = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM penduduk WHERE jenis_kelamin = 'L' AND status_penduduk = 'hidup'"))['total'];
    
    // Update statistik
    mysqli_query($conn, "UPDATE statistik_penduduk SET 
        total_penduduk = '$total',
        kepala_keluarga = '$kk',
        perempuan = '$perempuan',
        laki_laki = '$laki'
    WHERE id = 1");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $dusun = mysqli_real_escape_string($conn, $_POST['dusun']);
    $agama = mysqli_real_escape_string($conn, $_POST['agama']);
    $pendidikan = mysqli_real_escape_string($conn, $_POST['pendidikan']);
    $pekerjaan = mysqli_real_escape_string($conn, $_POST['pekerjaan']);
    $status_perkawinan = mysqli_real_escape_string($conn, $_POST['status_perkawinan']);
    $status_keluarga = mysqli_real_escape_string($conn, $_POST['status_keluarga']);
    $kk = mysqli_real_escape_string($conn, $_POST['kk']);
    $status_penduduk = $_POST['status_penduduk'];
    $tanggal_status = $_POST['tanggal_status'] ?: date('Y-m-d');
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    if ($id) {
        // Update data
        $query = "UPDATE penduduk SET 
            nama = '$nama',
            nik = '$nik',
            tempat_lahir = '$tempat_lahir',
            tanggal_lahir = '$tanggal_lahir',
            jenis_kelamin = '$jenis_kelamin',
            alamat = '$alamat',
            dusun = '$dusun',
            agama = '$agama',
            pendidikan = '$pendidikan',
            pekerjaan = '$pekerjaan',
            status_perkawinan = '$status_perkawinan',
            status_keluarga = '$status_keluarga',
            kk = '$kk',
            status_penduduk = '$status_penduduk',
            tanggal_status = '$tanggal_status',
            keterangan = '$keterangan'
        WHERE id = '$id'";
    } else {
        // Tambah data baru
        $query = "INSERT INTO penduduk (
            nama, nik, tempat_lahir, tanggal_lahir, jenis_kelamin,
            alamat, dusun, agama, pendidikan, pekerjaan,
            status_perkawinan, status_keluarga, kk, status_penduduk,
            tanggal_status, keterangan
        ) VALUES (
            '$nama', '$nik', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin',
            '$alamat', '$dusun', '$agama', '$pendidikan', '$pekerjaan',
            '$status_perkawinan', '$status_keluarga', '$kk', '$status_penduduk',
            '$tanggal_status', '$keterangan'
        )";
    }
    
    if (mysqli_query($conn, $query)) {
        // Update statistik
        updateStatistik($conn);
        
        $_SESSION['success'] = "Data penduduk berhasil " . ($id ? "diupdate" : "ditambahkan");
    } else {
        $_SESSION['error'] = "Gagal menyimpan data: " . mysqli_error($conn);
    }
    
    header("Location: penduduk.php");
    exit;
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM penduduk WHERE id = '$id_hapus'");
    updateStatistik($conn);
    $_SESSION['success'] = "Data berhasil dihapus";
    header("Location: penduduk.php");
    exit;
}
?>