<?php
include '../db.php';

// Konfigurasi pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 12;
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$offset = ($page - 1) * $limit;

// Query data
$where = "WHERE status = 'aktif'";
if (!empty($kategori) && in_array($kategori, ['foto_random', 'agenda', 'kegiatan'])) {
    $where .= " AND kategori = '" . mysqli_real_escape_string($conn, $kategori) . "'";
}

// Hitung total data
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM galeri $where");
$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ceil($totalData / $limit);

// Ambil data
$query = "SELECT * FROM galeri $where ORDER BY tanggal DESC, created_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['tanggal_formatted'] = date('d M Y', strtotime($row['tanggal']));
    $data[] = $row;
}

// Response JSON
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'data' => $data,
    'currentPage' => $page,
    'totalPages' => $totalPages,
    'totalData' => $totalData
]);
?>