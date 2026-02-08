<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add_penerima') {
        $id_jenis_bansos = mysqli_real_escape_string($conn, $_POST['id_jenis_bansos']);
        $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);
        $bulan = mysqli_real_escape_string($conn, $_POST['bulan']);
        $status_penerimaan = mysqli_real_escape_string($conn, $_POST['status_penerimaan']);
        $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
        $nik = mysqli_real_escape_string($conn, $_POST['nik']);
        $nama_manual = mysqli_real_escape_string($conn, $_POST['nama_manual']);
        
        // Cari ID penduduk berdasarkan NIK jika ada
        $id_penduduk = null;
        if (!empty($nik)) {
            $query = "SELECT id FROM penduduk WHERE nik = '$nik' LIMIT 1";
            $result = mysqli_query($conn, $query);
            if ($row = mysqli_fetch_assoc($result)) {
                $id_penduduk = $row['id'];
            }
        }
        
        $sql = "INSERT INTO penerima_bansos (id_penduduk, id_jenis_bansos, tahun, bulan, status_penerimaan, keterangan) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isssss", $id_penduduk, $id_jenis_bansos, $tahun, $bulan, $status_penerimaan, $keterangan);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Data penerima berhasil ditambahkan";
        } else {
            $_SESSION['error'] = "Gagal menambahkan data: " . mysqli_error($conn);
        }
        
        header("Location: bansos.php");
        exit;
        
    } elseif ($action == 'add_jenis') {
        $nama_bansos = mysqli_real_escape_string($conn, $_POST['nama_bansos']);
        $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        
        $sql = "INSERT INTO jenis_bansos (nama_bansos, keterangan, status) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $nama_bansos, $keterangan, $status);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Jenis bansos berhasil ditambahkan";
        } else {
            $_SESSION['error'] = "Gagal menambahkan jenis bansos: " . mysqli_error($conn);
        }
        
        header("Location: bansos.php");
        exit;
    }
} elseif (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete_penerima' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "DELETE FROM penerima_bansos WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = "Data penerima berhasil dihapus";
        } else {
            $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($conn);
        }
        
        header("Location: bansos.php");
        exit;
        
    } elseif ($_GET['action'] == 'delete_jenis' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        
        // Cek apakah jenis bansos masih digunakan
        $check = mysqli_query($conn, "SELECT COUNT(*) as total FROM penerima_bansos WHERE id_jenis_bansos = $id");
        $row = mysqli_fetch_assoc($check);
        
        if ($row['total'] > 0) {
            $_SESSION['error'] = "Jenis bansos tidak dapat dihapus karena masih digunakan";
        } else {
            $sql = "DELETE FROM jenis_bansos WHERE id = $id";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['success'] = "Jenis bansos berhasil dihapus";
            } else {
                $_SESSION['error'] = "Gagal menghapus jenis bansos: " . mysqli_error($conn);
            }
        }
        
        header("Location: bansos.php");
        exit;
    }
}
?>