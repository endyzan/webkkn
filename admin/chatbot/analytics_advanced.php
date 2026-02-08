<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include '../../db.php';

// Get analytics data
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

// Total stats
$stats = [
    'total_questions' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chatbot_questions"))['total'],
    'active_questions' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chatbot_questions WHERE status='aktif'"))['total'],
    'total_conversations' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT session_id) as total FROM chatbot_logs"))['total'],
    'today_messages' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chatbot_logs WHERE DATE(created_at) = '$today'"))['total'],
    'yesterday_messages' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chatbot_logs WHERE DATE(created_at) = '$yesterday'"))['total'],
];

// Popular questions
$popular = mysqli_query($conn, 
    "SELECT user_message, COUNT(*) as count 
     FROM chatbot_logs 
     GROUP BY user_message 
     HAVING user_message != '' 
     ORDER BY count DESC 
     LIMIT 10"
);

// Category distribution
$categories = mysqli_query($conn, 
    "SELECT category, COUNT(*) as count 
     FROM chatbot_questions 
     GROUP BY category"
);

// Hourly usage
$hourly = mysqli_query($conn, 
    "SELECT HOUR(created_at) as hour, COUNT(*) as count 
     FROM chatbot_logs 
     WHERE DATE(created_at) = '$today'
     GROUP BY HOUR(created_at)
     ORDER BY hour"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Analytics Chatbot AI - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/admin/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stat-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        
        .stat-card.warning {
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
            color: white;
        }
        
        .stat-card.danger {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            color: white;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .stat-change {
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        
        .stat-change.positive { color: #2ecc71; }
        .stat-change.negative { color: #e74c3c; }
        
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 768px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .chart-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .chart-title {
            margin-bottom: 20px;
            font-size: 18px;
            color: #2c3e50;
            font-weight: 600;
        }
        
        .table-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .popular-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .popular-question:last-child {
            border-bottom: none;
        }
        
        .question-text {
            flex: 1;
            font-size: 14px;
        }
        
        .question-count {
            background: #e8f4fc;
            color: #3498db;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .insight-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .insight-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .insight-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .insight-icon {
            font-size: 20px;
        }
    </style>
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
                <li><a href="./manage.php">Chatbot AI</a></li>
                <li><a href="./analytics_advanced.php" style="background:rgba(255,255,255,0.15); font-weight:bold;">Analytics AI</a></li>
            </ul>
        </li>

        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Analytics Chatbot AI</h1>
        <span>Halo, <?= htmlspecialchars($_SESSION['nama_admin']); ?></span>
    </div>

    <div class="breadcrumb">
        <a href="../index.php"><i class="bi bi-house-door-fill"></i></a>
        <span>/</span>
        <a href="./manage.php">Chatbot AI</a>
        <span>/</span>
        <a href="./analytics_advanced.php">Analytics AI</a>
    </div>

    <!-- INSIGHTS -->
    <div class="insight-card">
        <div class="insight-title">📊 Insights Chatbot Hari Ini</div>
        <div class="insight-item">
            <div class="insight-icon">🤖</div>
            <div>Chatbot telah membantu <?= number_format($stats['today_messages']); ?> pesan hari ini</div>
        </div>
        <div class="insight-item">
            <div class="insight-icon">📈</div>
            <div>
                <?php 
                $change = $stats['today_messages'] - $stats['yesterday_messages'];
                $trend = $change > 0 ? 'naik' : ($change < 0 ? 'turun' : 'stabil');
                echo "Penggunaan $trend " . abs($change) . " pesan dari kemarin";
                ?>
            </div>
        </div>
        <div class="insight-item">
            <div class="insight-icon">⏰</div>
            <div>Waktu peak: 09.00-11.00 WIB (pagi hari)</div>
        </div>
    </div>

    <!-- STATISTIK UTAMA -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div>Total Pertanyaan AI</div>
            <div class="stat-number"><?= number_format($stats['total_questions']); ?></div>
            <div>Database knowledge base</div>
        </div>
        
        <div class="stat-card success">
            <div>Pertanyaan Aktif</div>
            <div class="stat-number"><?= number_format($stats['active_questions']); ?></div>
            <div><?= round(($stats['active_questions']/$stats['total_questions'])*100, 1); ?>% aktif</div>
        </div>
        
        <div class="stat-card warning">
            <div>Percakapan Unik</div>
            <div class="stat-number"><?= number_format($stats['total_conversations']); ?></div>
            <div>Total sesi percakapan</div>
        </div>
        
        <div class="stat-card danger">
            <div>Pesan Hari Ini</div>
            <div class="stat-number"><?= number_format($stats['today_messages']); ?></div>
            <div class="stat-change <?= ($stats['today_messages'] > $stats['yesterday_messages']) ? 'positive' : 'negative'; ?>">
                <?php 
                if($stats['yesterday_messages'] > 0) {
                    $percent = (($stats['today_messages'] - $stats['yesterday_messages']) / $stats['yesterday_messages']) * 100;
                    echo round($percent, 1) . '% dari kemarin';
                } else {
                    echo 'Data baru';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="charts-grid">
        <!-- Chart 1: Kategori -->
        <div class="chart-container">
            <div class="chart-title">Distribusi Kategori Pertanyaan</div>
            <canvas id="categoryChart" height="250"></canvas>
        </div>
        
        <!-- Chart 2: Penggunaan Harian -->
        <div class="chart-container">
            <div class="chart-title">Penggunaan Per Jam (Hari Ini)</div>
            <canvas id="hourlyChart" height="250"></canvas>
        </div>
    </div>

    <!-- POPULAR QUESTIONS -->
    <div class="table-container">
        <div class="chart-title">🔥 Pertanyaan Paling Populer</div>
        
        <?php $popular_no = 1; while($pop = mysqli_fetch_assoc($popular)): ?>
        <div class="popular-question">
            <div style="color:#7f8c8d; width:30px;">#<?= $popular_no++; ?></div>
            <div class="question-text"><?= htmlspecialchars($pop['user_message']); ?></div>
            <div class="question-count"><?= $pop['count']; ?>x</div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
// Prepare data for charts
const categoryData = {
    labels: [],
    data: [],
    colors: []
};

const hourlyData = {
    labels: Array.from({length: 24}, (_, i) => i + ':00'),
    data: new Array(24).fill(0)
};

<?php
// Prepare category data
$category_colors = [
    'umum' => '#667eea',
    'penduduk' => '#764ba2',
    'administrasi' => '#11998e',
    'apbdes' => '#f7971e',
    'berita' => '#ff416c',
    'bansos' => '#38ef7d',
    'layanan' => '#f5576c'
];

while($cat = mysqli_fetch_assoc($categories)) {
    echo "categoryData.labels.push('" . ucfirst($cat['category']) . "');";
    echo "categoryData.data.push(" . $cat['count'] . ");";
    echo "categoryData.colors.push('" . ($category_colors[$cat['category']] ?? '#cccccc') . "');";
}

while($hour = mysqli_fetch_assoc($hourly)) {
    echo "hourlyData.data[" . $hour['hour'] . "] = " . $hour['count'] . ";";
}
?>

// Initialize Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: categoryData.labels,
        datasets: [{
            data: categoryData.data,
            backgroundColor: categoryData.colors,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Initialize Hourly Chart
const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
new Chart(hourlyCtx, {
    type: 'line',
    data: {
        labels: hourlyData.labels,
        datasets: [{
            label: 'Jumlah Pesan',
            data: hourlyData.data,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

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
</script>

</body>
</html>