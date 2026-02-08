<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data_penduduk_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, [
    'NIK', 'Nama', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin',
    'Agama', 'Alamat', 'Dusun', 'Nomor KK', 'Status Keluarga',
    'Status Perkawinan', 'Pendidikan', 'Pekerjaan', 'Status Penduduk',
    'Tanggal Status', 'Keterangan'
]);

$query = "SELECT * FROM penduduk ORDER BY nama";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['nik'],
        $row['nama'],
        $row['tempat_lahir'],
        $row['tanggal_lahir'],
        $row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan',
        $row['agama'],
        $row['alamat'],
        $row['dusun'],
        $row['kk'],
        $row['status_keluarga'],
        $row['status_perkawinan'],
        $row['pendidikan'],
        $row['pekerjaan'],
        $row['status_penduduk'],
        $row['tanggal_status'],
        $row['keterangan']
    ]);
}

fclose($output);
exit;