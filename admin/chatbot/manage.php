<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include '../../db.php';

// Inisialisasi variabel
$success = '';
$error = '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

// Handle actions
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Delete action
if ($action == 'delete' && $id > 0) {
    $check = mysqli_query($conn, "SELECT * FROM chatbot_questions WHERE id='$id'");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "DELETE FROM chatbot_questions WHERE id='$id'");
        $success = "Pertanyaan berhasil dihapus!";
    } else {
        $error = "Data tidak ditemukan!";
    }
}

// Toggle status action
if ($action == 'toggle_status' && $id > 0) {
    $check = mysqli_query($conn, "SELECT * FROM chatbot_questions WHERE id='$id'");
    if (mysqli_num_rows($check) > 0) {
        $current = mysqli_fetch_assoc($check);
        $new_status = $current['status'] == 'aktif' ? 'nonaktif' : 'aktif';
        mysqli_query($conn, "UPDATE chatbot_questions SET status='$new_status' WHERE id='$id'");
        $success = "Status berhasil diubah menjadi " . $new_status . "!";
    } else {
        $error = "Data tidak ditemukan!";
    }
}

// Bulk delete action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bulk_action'])) {
    if (isset($_POST['selected_ids']) && !empty($_POST['selected_ids'])) {
        $ids = implode(',', array_map('intval', $_POST['selected_ids']));
        
        if ($_POST['bulk_action'] == 'delete') {
            mysqli_query($conn, "DELETE FROM chatbot_questions WHERE id IN ($ids)");
            $success = count($_POST['selected_ids']) . " data berhasil dihapus!";
        } elseif ($_POST['bulk_action'] == 'activate') {
            mysqli_query($conn, "UPDATE chatbot_questions SET status='aktif' WHERE id IN ($ids)");
            $success = count($_POST['selected_ids']) . " data berhasil diaktifkan!";
        } elseif ($_POST['bulk_action'] == 'deactivate') {
            mysqli_query($conn, "UPDATE chatbot_questions SET status='nonaktif' WHERE id IN ($ids)");
            $success = count($_POST['selected_ids']) . " data berhasil dinonaktifkan!";
        }
    } else {
        $error = "Pilih data terlebih dahulu!";
    }
}

// Handle form submission (add/edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_form'])) {
    $question = mysqli_real_escape_string($conn, trim($_POST['question']));
    $keywords = mysqli_real_escape_string($conn, trim($_POST['keywords']));
    $answer = mysqli_real_escape_string($conn, trim($_POST['answer']));
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $priority = intval($_POST['priority']);
    
    // Validasi
    if (empty($question) || empty($answer)) {
        $error = "Pertanyaan dan jawaban harus diisi!";
    } else {
        if (isset($_POST['edit_id'])) {
            // Update data
            $edit_id = intval($_POST['edit_id']);
            $query = "UPDATE chatbot_questions SET 
                      question='$question',
                      keywords='$keywords',
                      answer='$answer',
                      category='$category',
                      status='$status',
                      priority='$priority',
                      created_at=created_at
                      WHERE id='$edit_id'";
            mysqli_query($conn, $query);
            $success = "Data berhasil diperbarui!";
        } else {
            // Insert data baru
            $query = "INSERT INTO chatbot_questions (question, keywords, answer, category, status, priority) 
                      VALUES ('$question', '$keywords', '$answer', '$category', '$status', '$priority')";
            mysqli_query($conn, $query);
            $success = "Data berhasil ditambahkan!";
        }
    }
}

// Get data for edit
$edit_data = null;
if ($action == 'edit' && $id > 0) {
    $edit_result = mysqli_query($conn, "SELECT * FROM chatbot_questions WHERE id='$id'");
    if (mysqli_num_rows($edit_result) > 0) {
        $edit_data = mysqli_fetch_assoc($edit_result);
    } else {
        $error = "Data tidak ditemukan!";
    }
}

// Get all questions with filters
$query = "SELECT * FROM chatbot_questions WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (question LIKE '%$search%' OR keywords LIKE '%$search%' OR answer LIKE '%$search%')";
}

if (!empty($category)) {
    $query .= " AND category='$category'";
}

if (!empty($status_filter)) {
    $query .= " AND status='$status_filter'";
}

$query .= " ORDER BY priority DESC, id DESC";

$result = mysqli_query($conn, $query);
$total_rows = mysqli_num_rows($result);

// Get statistics
$stats = [
    'total' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chatbot_questions"))['total'],
    'active' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chatbot_questions WHERE status='aktif'"))['total'],
    'inactive' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chatbot_questions WHERE status='nonaktif'"))['total'],
];

// Get category statistics
$category_stats = [];
$cat_result = mysqli_query($conn, 
    "SELECT category, COUNT(*) as count, 
     SUM(CASE WHEN status='aktif' THEN 1 ELSE 0 END) as active_count
     FROM chatbot_questions 
     GROUP BY category"
);
while($row = mysqli_fetch_assoc($cat_result)) {
    $category_stats[$row['category']] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Chatbot AI - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/admin/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom Styles */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            margin: 8px 0;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .stat-icon {
            font-size: 24px;
            margin-bottom: 10px;
            color: #667eea;
        }
        
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .badge-category {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .badge-umum { background: rgba(102, 126, 234, 0.1); color: #667eea; border: 1px solid rgba(102, 126, 234, 0.3); }
        .badge-penduduk { background: rgba(118, 75, 162, 0.1); color: #764ba2; border: 1px solid rgba(118, 75, 162, 0.3); }
        .badge-administrasi { background: rgba(17, 153, 142, 0.1); color: #11998e; border: 1px solid rgba(17, 153, 142, 0.3); }
        .badge-apbdes { background: rgba(247, 151, 30, 0.1); color: #f7971e; border: 1px solid rgba(247, 151, 30, 0.3); }
        .badge-berita { background: rgba(255, 65, 108, 0.1); color: #ff416c; border: 1px solid rgba(255, 65, 108, 0.3); }
        .badge-bansos { background: rgba(56, 239, 125, 0.1); color: #38ef7d; border: 1px solid rgba(56, 239, 125, 0.3); }
        .badge-layanan { background: rgba(245, 87, 108, 0.1); color: #f5576c; border: 1px solid rgba(245, 87, 108, 0.3); }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-aktif { background: rgba(46, 204, 113, 0.1); color: #27ae60; border: 1px solid rgba(46, 204, 113, 0.3); }
        .status-nonaktif { background: rgba(231, 76, 60, 0.1); color: #c0392b; border: 1px solid rgba(231, 76, 60, 0.3); }
        
        .priority-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            font-weight: bold;
            font-size: 12px;
        }
        
        .priority-high { background: #ff4757; color: white; }
        .priority-medium { background: #ffa502; color: white; }
        .priority-low { background: #2ed573; color: white; }
        
        .table-container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #e9ecef;
        }
        
        .table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .table tbody tr.selected {
            background: rgba(102, 126, 234, 0.05);
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-edit {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }
        
        .btn-edit:hover {
            background: #3498db;
            color: white;
        }
        
        .btn-delete {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }
        
        .btn-delete:hover {
            background: #e74c3c;
            color: white;
        }
        
        .btn-toggle {
            background: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }
        
        .btn-toggle:hover {
            background: #2ecc71;
            color: white;
        }
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filter-select {
            padding: 8px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            min-width: 150px;
        }
        
        .search-input {
            padding: 8px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            min-width: 250px;
        }
        
        .btn-filter {
            background: #667eea;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-filter:hover {
            background: #5a6fd8;
        }
        
        .btn-reset {
            background: #95a5a6;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-reset:hover {
            background: #7f8c8d;
        }
        
        .bulk-actions {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .bulk-select-all {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .bulk-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-bulk {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-bulk-delete {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }
        
        .btn-bulk-delete:hover {
            background: #e74c3c;
            color: white;
        }
        
        .btn-bulk-activate {
            background: rgba(46, 204, 113, 0.1);
            color: #27ae60;
        }
        
        .btn-bulk-activate:hover {
            background: #27ae60;
            color: white;
        }
        
        .btn-bulk-deactivate {
            background: rgba(241, 196, 15, 0.1);
            color: #f39c12;
        }
        
        .btn-bulk-deactivate:hover {
            background: #f39c12;
            color: white;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 25px;
        }
        
        .page-link {
            padding: 8px 15px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .page-link:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .page-link.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #95a5a6;
        }
        
        .no-data i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #bdc3c7;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert-success {
            background: rgba(46, 204, 113, 0.1);
            color: #27ae60;
            border: 1px solid rgba(46, 204, 113, 0.3);
        }
        
        .alert-error {
            background: rgba(231, 76, 60, 0.1);
            color: #c0392b;
            border: 1px solid rgba(231, 76, 60, 0.3);
        }
        
        .alert i {
            font-size: 20px;
        }
        
        .info-box {
            background: rgba(52, 152, 219, 0.1);
            border: 1px solid rgba(52, 152, 219, 0.3);
            border-left: 4px solid #3498db;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .info-box p {
            margin: 0;
            color: #2c3e50;
            font-size: 14px;
        }
        
        .info-box i {
            color: #3498db;
            margin-right: 10px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-input, .filter-select {
                min-width: 100%;
            }
            
            .table th, .table td {
                padding: 10px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
    <script>
        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
                const row = checkbox.closest('tr');
                if (source.checked) {
                    row.classList.add('selected');
                } else {
                    row.classList.remove('selected');
                }
            });
            updateSelectedCount();
        }
        
        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]:checked');
            const countElement = document.getElementById('selectedCount');
            if (countElement) {
                countElement.textContent = checkboxes.length;
            }
        }
        
        function validateBulkAction() {
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]:checked');
            if (checkboxes.length === 0) {
                alert('Pilih data terlebih dahulu!');
                return false;
            }
            return true;
        }
        
        function confirmDelete(action = 'delete', id = null) {
            const message = id ? 
                'Yakin menghapus pertanyaan ini?' : 
                'Yakin menghapus data yang dipilih?';
            
            if (confirm(message)) {
                if (id) {
                    window.location.href = `?action=delete&id=${id}`;
                } else {
                    document.getElementById('bulkForm').submit();
                }
            }
            return false;
        }
        
        function toggleStatus(id) {
            if (confirm('Yakin mengubah status pertanyaan ini?')) {
                window.location.href = `?action=toggle_status&id=${id}`;
            }
        }
        
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Teks berhasil disalin!');
            }).catch(err => {
                console.error('Gagal menyalin teks: ', err);
            });
        }
    </script>
</head>
<body>

<div class="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>ADMIN DESA</h2>
    <ul>
        <li><a href="../index.php">Dashboard</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Home ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../home/banner-hero/banner.php">Banner</a></li>
                <li><a href="../home/sambutan/sambutan.php">Sambutan</a></li>
                <li><a href="../home/sotk/sotk.php">SOTK</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Profil Desa ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../profil-desa/visi-misi/visimisi.php">Visi & Misi</a></li>
                <li><a href="../profil-desa/bagan-desa/bagandesa.php">Bagan Desa</a></li>
                <li><a href="../profil-desa/sejarah-desa/sejarah.php">Sejarah Desa</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Infografis ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../infografis/penduduk/penduduk.php">Penduduk</a></li>
                <li><a href="../infografis/apbdes/apbdes.php">APBDes</a></li>
                <li><a href="../infografis/bansos/bansos.php">Bansos</a></li>
            </ul>
        </li>
        <li><a href="../berita/berita.php">Berita</a></li>
        <li><a href="../galeri/galeri.php">Galeri</a></li>

        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)"><i class="fas fa-robot"></i> Chatbot ▾</a>
            <ul class="dropdown-menu">
                <li><a href="./manage.php" style="background:rgba(255,255,255,0.15); font-weight:bold;">Chatbot AI</a></li>
                <li><a href="./analytics_advanced.php">Analytics AI</a></li>
            </ul>
        </li>

        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1><i class="fas fa-robot"></i> Manajemen Chatbot AI</h1>
        <span>Halo, <?= htmlspecialchars($_SESSION['nama_admin']); ?></span>
    </div>

    <div class="breadcrumb">
        <a href="../index.php"><i class="bi bi-house-door-fill"></i></a>
        <span>/</span>
        <a href="./manage.php">
            Chatbot AI
        </a>
    </div>

    <!-- ALERT MESSAGES -->
    <?php if ($success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?= htmlspecialchars($success); ?>
    </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <?= htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <!-- STATISTIK -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-database"></i></div>
            <div class="stat-number"><?= number_format($stats['total']); ?></div>
            <div class="stat-label">Total Pertanyaan</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle" style="color:#2ecc71;"></i></div>
            <div class="stat-number"><?= number_format($stats['active']); ?></div>
            <div class="stat-label">Aktif</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-times-circle" style="color:#e74c3c;"></i></div>
            <div class="stat-number"><?= number_format($stats['inactive']); ?></div>
            <div class="stat-label">Nonaktif</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chart-bar" style="color:#9b59b6;"></i></div>
            <div class="stat-number"><?= number_format($total_rows); ?></div>
            <div class="stat-label">Ditemukan</div>
        </div>
    </div>

    <!-- INFO BOX -->
    <div class="info-box">
        <p><i class="fas fa-info-circle"></i> 
        <strong>Tips:</strong> Gunakan keyword yang relevan untuk meningkatkan akurasi pencarian chatbot. 
        Prioritas tinggi akan diutamakan dalam pencarian jawaban.
        </p>
    </div>

    <!-- FORM TAMBAH/EDIT -->
    <div class="form-container">
        <h3 style="margin-bottom:25px; color:#2c3e50;">
            <i class="fas fa-<?= $edit_data ? 'edit' : 'plus'; ?>"></i>
            <?= $edit_data ? 'Edit Pertanyaan' : 'Tambah Pertanyaan Baru'; ?>
        </h3>
        
        <form method="POST" action="">
            <?php if ($edit_data): ?>
            <input type="hidden" name="edit_id" value="<?= $edit_data['id']; ?>">
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Pertanyaan *</label>
                    <input type="text" name="question" class="form-control" 
                           value="<?= $edit_data ? htmlspecialchars($edit_data['question']) : ''; ?>"
                           placeholder="Contoh: Bagaimana cara mengurus KTP?"
                           required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Keywords (pisahkan dengan koma)</label>
                    <input type="text" name="keywords" class="form-control" 
                           value="<?= $edit_data ? htmlspecialchars($edit_data['keywords']) : ''; ?>"
                           placeholder="Contoh: ktp, kartu tanda penduduk, urus ktp, buat ktp">
                    <small style="color:#7f8c8d;">Keyword membantu chatbot memahami variasi pertanyaan</small>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Jawaban *</label>
                <textarea name="answer" class="form-control" 
                          placeholder="Jawaban lengkap untuk pertanyaan di atas"
                          required><?= $edit_data ? htmlspecialchars($edit_data['answer']) : ''; ?></textarea>
                <small style="color:#7f8c8d;">
                    <i class="fas fa-lightbulb"></i> Gunakan [nama_variabel] untuk data dinamis. Contoh: [total_penduduk]
                </small>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="category" class="form-control" required>
                        <option value="umum" <?= ($edit_data && $edit_data['category']=='umum')?'selected':''; ?>>Umum</option>
                        <option value="penduduk" <?= ($edit_data && $edit_data['category']=='penduduk')?'selected':''; ?>>Penduduk</option>
                        <option value="administrasi" <?= ($edit_data && $edit_data['category']=='administrasi')?'selected':''; ?>>Administrasi</option>
                        <option value="apbdes" <?= ($edit_data && $edit_data['category']=='apbdes')?'selected':''; ?>>APBDes</option>
                        <option value="berita" <?= ($edit_data && $edit_data['category']=='berita')?'selected':''; ?>>Berita</option>
                        <option value="bansos" <?= ($edit_data && $edit_data['category']=='bansos')?'selected':''; ?>>Bansos</option>
                        <option value="layanan" <?= ($edit_data && $edit_data['category']=='layanan')?'selected':''; ?>>Layanan</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Prioritas</label>
                    <select name="priority" class="form-control">
                        <option value="3" <?= ($edit_data && $edit_data['priority']==3)?'selected':''; ?>>Tinggi</option>
                        <option value="2" <?= ($edit_data && (!$edit_data || $edit_data['priority']==2))?'selected':''; ?>>Sedang</option>
                        <option value="1" <?= ($edit_data && $edit_data['priority']==1)?'selected':''; ?>>Rendah</option>
                        <option value="0" <?= ($edit_data && $edit_data['priority']==0)?'selected':''; ?>>Default</option>
                    </select>
                    <small style="color:#7f8c8d;">Prioritas tinggi akan diutamakan dalam pencarian</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="aktif" <?= ($edit_data && (!$edit_data || $edit_data['status']=='aktif'))?'selected':''; ?>>Aktif</option>
                        <option value="nonaktif" <?= ($edit_data && $edit_data['status']=='nonaktif')?'selected':''; ?>>Nonaktif</option>
                    </select>
                </div>
            </div>
            
            <div style="display:flex; gap:15px; margin-top:10px;">
                <button type="submit" name="submit_form" class="btn-primary">
                    <i class="fas fa-<?= $edit_data ? 'save' : 'plus'; ?>"></i>
                    <?= $edit_data ? 'Simpan Perubahan' : 'Tambah Pertanyaan'; ?>
                </button>
                
                <?php if ($edit_data): ?>
                <a href="manage.php" class="btn-secondary">
                    <i class="fas fa-times"></i> Batal Edit
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-section">
        <form method="GET" action="" style="display:contents;">
            <div class="filter-group">
                <input type="text" name="search" class="search-input" 
                       value="<?= htmlspecialchars($search); ?>" 
                       placeholder="Cari pertanyaan, keyword, atau jawaban...">
            </div>
            
            <div class="filter-group">
                <select name="category" class="filter-select">
                    <option value="">Semua Kategori</option>
                    <option value="umum" <?= $category=='umum'?'selected':''; ?>>Umum</option>
                    <option value="penduduk" <?= $category=='penduduk'?'selected':''; ?>>Penduduk</option>
                    <option value="administrasi" <?= $category=='administrasi'?'selected':''; ?>>Administrasi</option>
                    <option value="apbdes" <?= $category=='apbdes'?'selected':''; ?>>APBDes</option>
                    <option value="berita" <?= $category=='berita'?'selected':''; ?>>Berita</option>
                    <option value="bansos" <?= $category=='bansos'?'selected':''; ?>>Bansos</option>
                    <option value="layanan" <?= $category=='layanan'?'selected':''; ?>>Layanan</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" <?= $status_filter=='aktif'?'selected':''; ?>>Aktif</option>
                    <option value="nonaktif" <?= $status_filter=='nonaktif'?'selected':''; ?>>Nonaktif</option>
                </select>
            </div>
            
            <button type="submit" class="btn-filter">
                <i class="fas fa-filter"></i> Filter
            </button>
            
            <a href="manage.php" class="btn-reset">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>
    </div>

    <!-- BULK ACTIONS -->
    <form id="bulkForm" method="POST" action="" onsubmit="return validateBulkAction()">
        <div class="bulk-actions">
            <div class="bulk-select-all">
                <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                <label for="selectAll">Pilih Semua</label>
                <span style="color:#667eea; font-weight:500;">
                    (<span id="selectedCount">0</span> dipilih)
                </span>
            </div>
            
            <div class="bulk-buttons">
                <select name="bulk_action" class="filter-select" style="min-width:150px;">
                    <option value="">Aksi Massal</option>
                    <option value="delete">Hapus</option>
                    <option value="activate">Aktifkan</option>
                    <option value="deactivate">Nonaktifkan</option>
                </select>
                
                <button type="submit" class="btn-bulk btn-bulk-delete" onclick="return confirm('Yakin melakukan aksi massal?')">
                    <i class="fas fa-play"></i> Jalankan
                </button>
            </div>
            
            <div style="margin-left:auto; color:#7f8c8d; font-size:14px;">
                <i class="fas fa-list"></i> Menampilkan <?= number_format($total_rows); ?> data
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-container">
            <?php if ($total_rows == 0): ?>
            <div class="no-data">
                <i class="fas fa-inbox"></i>
                <h3 style="color:#95a5a6; margin-bottom:10px;">Tidak ada data</h3>
                <p style="color:#bdc3c7;">
                    <?= ($search || $category || $status_filter) ? 
                        'Coba ubah filter pencarian Anda' : 
                        'Mulai tambahkan pertanyaan untuk chatbot' ?>
                </p>
            </div>
            <?php else: ?>
            
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="selectAllTable" onchange="toggleSelectAll(this)">
                        </th>
                        <th>Pertanyaan</th>
                        <th width="100">Kategori</th>
                        <th width="80">Prioritas</th>
                        <th width="80">Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="selected_ids[]" 
                                   value="<?= $row['id']; ?>"
                                   onchange="updateSelectedCount()">
                        </td>
                        <td>
                            <div style="margin-bottom:5px; font-weight:500;">
                                <?= htmlspecialchars($row['question']); ?>
                            </div>
                            <?php if (!empty($row['keywords'])): ?>
                            <div style="font-size:12px; color:#7f8c8d;">
                                <i class="fas fa-key"></i> 
                                <?= htmlspecialchars(substr($row['keywords'], 0, 50)); ?>
                                <?= strlen($row['keywords']) > 50 ? '...' : ''; ?>
                            </div>
                            <?php endif; ?>
                            <div style="font-size:12px; color:#95a5a6; margin-top:5px;">
                                <i class="far fa-clock"></i> 
                                <?= date('d M Y', strtotime($row['created_at'])); ?>
                            </div>
                        </td>
                        <td>
                            <?php 
                            $category_class = 'badge-' . $row['category'];
                            $category_name = ucfirst($row['category']);
                            ?>
                            <span class="badge-category <?= $category_class; ?>">
                                <?= $category_name; ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            $priority_class = '';
                            if ($row['priority'] >= 3) $priority_class = 'priority-high';
                            elseif ($row['priority'] == 2) $priority_class = 'priority-medium';
                            elseif ($row['priority'] <= 1) $priority_class = 'priority-low';
                            ?>
                            <span class="priority-badge <?= $priority_class; ?>">
                                <?= $row['priority']; ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                            $status_class = 'status-' . $row['status'];
                            $status_name = ucfirst($row['status']);
                            ?>
                            <span class="status-badge <?= $status_class; ?>">
                                <?= $status_name; ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="?action=edit&id=<?= $row['id']; ?>" 
                                   class="btn-action btn-edit" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <button type="button" 
                                        onclick="toggleStatus(<?= $row['id']; ?>)" 
                                        class="btn-action btn-toggle" 
                                        title="Ubah Status">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                
                                <button type="button" 
                                        onclick="return confirmDelete('delete', <?= $row['id']; ?>)" 
                                        class="btn-action btn-delete" 
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                
                                <button type="button" 
                                        onclick="copyToClipboard('<?= addslashes($row['question']); ?>')" 
                                        class="btn-action" 
                                        style="background:rgba(155, 89, 182, 0.1); color:#9b59b6;"
                                        title="Salin Pertanyaan">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <?php endif; ?>
        </div>
    </form>

    <!-- CATEGORY STATISTICS -->
    <div class="form-container" style="margin-top:30px;">
        <h3 style="margin-bottom:25px; color:#2c3e50;">
            <i class="fas fa-chart-pie"></i> Statistik per Kategori
        </h3>
        
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px;">
            <?php foreach($category_stats as $cat_name => $cat_data): ?>
            <?php 
            $category_class = 'badge-' . $cat_name;
            $category_name = ucfirst($cat_name);
            $percentage = $cat_data['count'] > 0 ? round(($cat_data['count'] / $stats['total']) * 100, 1) : 0;
            $active_percentage = $cat_data['count'] > 0 ? round(($cat_data['active_count'] / $cat_data['count']) * 100, 1) : 0;
            ?>
            <div style="background:white; padding:15px; border-radius:8px; border:1px solid #e0e0e0;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <span class="badge-category <?= $category_class; ?>" style="font-size:11px;">
                        <?= $category_name; ?>
                    </span>
                    <span style="font-weight:bold; color:#2c3e50;"><?= $cat_data['count']; ?></span>
                </div>
                <div style="height:8px; background:#f0f0f0; border-radius:4px; overflow:hidden; margin-bottom:5px;">
                    <div style="height:100%; width:<?= $percentage; ?>%; background:#667eea;"></div>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:11px; color:#7f8c8d;">
                    <span><?= $percentage; ?>% dari total</span>
                    <span><?= $active_percentage; ?>% aktif</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script>
// Sidebar functions
const sidebar = document.querySelector('.sidebar');
const overlay = document.querySelector('.overlay');

function toggleSidebar() {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('show');
}

function closeSidebar() {
    sidebar.classList.remove('active');
    overlay.classList.remove('show');
}

function toggleDropdown(el) {
    el.nextElementSibling.classList.toggle('show');
}

// Auto-update selected count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSelectedCount();
    
    // Add row selection effect
    const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const row = this.closest('tr');
            if (this.checked) {
                row.classList.add('selected');
            } else {
                row.classList.remove('selected');
            }
        });
    });
    
    // Auto-focus on search input if there's search term
    const searchInput = document.querySelector('.search-input');
    if (searchInput && searchInput.value) {
        searchInput.focus();
        searchInput.select();
    }
    
    // Auto-focus on question input in form
    const questionInput = document.querySelector('input[name="question"]');
    if (questionInput) {
        questionInput.focus();
    }
});
</script>

</body>
</html>