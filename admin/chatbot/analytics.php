<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include '../../db.php';

// Hitung statistik
$total_questions = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM chatbot_questions")
)['total'];

$active_questions = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM chatbot_questions WHERE status='aktif'")
)['total'];

$category_stats = [];
$result = mysqli_query($conn, 
    "SELECT category, COUNT(*) as count 
     FROM chatbot_questions 
     GROUP BY category"
);
while($row = mysqli_fetch_assoc($result)) {
    $category_stats[$row['category']] = $row['count'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Analytics Chatbot - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/admin/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .category-chart {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .chart-bar {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .chart-label {
            width: 150px;
            font-weight: 500;
        }
        
        .chart-value {
            flex: 1;
            height: 25px;
            background: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .chart-fill {
            height: 100%;
            background: #3498db;
            text-align: right;
            padding-right: 10px;
            line-height: 25px;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR (sama seperti manage.php) -->
<div class="sidebar">
    <h2>ADMIN DESA</h2>
    <ul>
        <li><a href="../index.php">Dashboard</a></li>
        <!-- ... menu lainnya ... -->
        <li><a href="./manage.php">Chatbot</a></li>
        <li><a href="./analytics.php" style="background:rgba(255,255,255,0.15)">Analytics Chatbot</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Analytics Chatbot</h1>
        <span>Halo, <?= htmlspecialchars($_SESSION['nama_admin']); ?></span>
    </div>

    <div class="breadcrumb">
        <a href="../index.php"><i class="bi bi-house-door-fill"></i></a>
        <span>/</span>
        <a href="manage.php">Chatbot</a>
        <span>/</span>
        <span>Analytics</span>
    </div>

    <!-- STATISTIK -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?= $total_questions; ?></div>
            <div class="stat-label">Total Pertanyaan</div>
            <i class="bi bi-chat-dots" style="font-size: 24px; color:#3498db;"></i>
        </div>
        
        <div class="stat-card">
            <div class="stat-number"><?= $active_questions; ?></div>
            <div class="stat-label">Pertanyaan Aktif</div>
            <i class="bi bi-check-circle" style="font-size: 24px; color:#2ecc71;"></i>
        </div>
        
        <div class="stat-card">
            <div class="stat-number"><?= $total_questions - $active_questions; ?></div>
            <div class="stat-label">Pertanyaan Nonaktif</div>
            <i class="bi bi-x-circle" style="font-size: 24px; color:#e74c3c;"></i>
        </div>
        
        <div class="stat-card">
            <div class="stat-number">5</div>
            <div class="stat-label">Kategori</div>
            <i class="bi bi-tags" style="font-size: 24px; color:#9b59b6;"></i>
        </div>
    </div>

    <!-- DISTRIBUSI KATEGORI -->
    <div class="category-chart">
        <h3>Distribusi Pertanyaan per Kategori</h3>
        
        <?php
        $max_count = max($category_stats ?: [1]);
        foreach($category_stats as $category => $count):
            $percentage = ($count / $max_count) * 100;
        ?>
        <div class="chart-bar">
            <div class="chart-label"><?= strtoupper($category); ?></div>
            <div class="chart-value">
                <div class="chart-fill" style="width: <?= $percentage; ?>%">
                    <?= $count; ?> pertanyaan
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
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
</script>

</body>
</html>