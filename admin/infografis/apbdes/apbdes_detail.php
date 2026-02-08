<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

$type = $_GET['type']; // pendapatan, belanja, pembiayaan
$apbdes_id = $_GET['apbdes_id'];

// Get tahun
$query_apbdes = mysqli_query($conn, "SELECT * FROM apbdes WHERE id = '$apbdes_id'");
$apbdes = mysqli_fetch_assoc($query_apbdes);
$tahun = $apbdes['tahun'];

// Get existing data
$table = "apbdes_" . $type;
$query = "SELECT * FROM $table WHERE apbdes_id = '$apbdes_id'";
$result = mysqli_query($conn, $query);

$items = [];
while($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}

// Predefined items berdasarkan type
$predefined_items = [];
if($type == 'pendapatan') {
    $predefined_items = [
        'Pendapatan Asli Desa',
        'Pendapatan Transfer',
        'Pendapatan Lain-lain'
    ];
} elseif($type == 'belanja') {
    $predefined_items = [
        'Penyelenggaraan Pemerintahan Desa',
        'Pelaksanaan Pembangunan Desa',
        'Pembinaan Kemasyarakatan Desa',
        'Pemberdayaan Masyarakat Desa',
        'Penanggulangan Bencana & Keadaan Mendesak'
    ];
} elseif($type == 'pembiayaan') {
    $predefined_items = [
        'penerimaan',
        'pengeluaran'
    ];
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['add_item'])) {
        $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
        $jumlah = str_replace(['.', ','], ['', '.'], $_POST['jumlah']);
        $persentase = mysqli_real_escape_string($conn, $_POST['persentase']);
        
        $query = "INSERT INTO $table (apbdes_id, jenis, jumlah, persentase) 
                  VALUES ('$apbdes_id', '$jenis', '$jumlah', '$persentase')";
        mysqli_query($conn, $query);
        
        header("Location: ?type=$type&apbdes_id=$apbdes_id");
        exit;
        
    } elseif(isset($_POST['update_item'])) {
        $id = $_POST['id'];
        $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
        $jumlah = str_replace(['.', ','], ['', '.'], $_POST['jumlah']);
        $persentase = mysqli_real_escape_string($conn, $_POST['persentase']);
        
        $query = "UPDATE $table SET jenis='$jenis', jumlah='$jumlah', persentase='$persentase' 
                  WHERE id='$id'";
        mysqli_query($conn, $query);
        
        header("Location: ?type=$type&apbdes_id=$apbdes_id");
        exit;
        
    } elseif(isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $query = "DELETE FROM $table WHERE id='$id'";
        mysqli_query($conn, $query);
        
        header("Location: ?type=$type&apbdes_id=$apbdes_id");
        exit;
    }
}

$type_labels = [
    'pendapatan' => 'Pendapatan',
    'belanja' => 'Belanja',
    'pembiayaan' => 'Pembiayaan'
];

$next_type = '';
$prev_type = '';
if($type == 'pendapatan') {
    $prev_type = '';
    $next_type = 'belanja';
} elseif($type == 'belanja') {
    $prev_type = 'pendapatan';
    $next_type = 'pembiayaan';
} elseif($type == 'pembiayaan') {
    $prev_type = 'belanja';
    $next_type = '';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail <?= ucfirst($type); ?></title>
    <link rel="stylesheet" href="../../../assets/admin/style.css">
    <style>
        /* Tambahan CSS untuk halaman detail */
        .detail-header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navigation-buttons {
            display: flex;
            gap: 10px;
        }
        
        .nav-btn {
            padding: 8px 15px;
            background: #2196f3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .nav-btn.prev {
            background: #ff9800;
        }
        
        .nav-btn.next {
            background: #4caf50;
        }
        
        .nav-btn.back {
            background: #757575;
        }
        
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .form-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .form-row input, .form-row select {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn-add {
            background: #2e7d32;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .data-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .data-table th {
            background: #f5f5f5;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .data-table tr:hover {
            background: #f9f9f9;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-edit-small {
            background: #2196f3;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-delete-small {
            background: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .total-row {
            background: #e8f5e9 !important;
            font-weight: bold;
        }
        
        .progress-container {
            width: 100%;
            height: 20px;
            background: #eee;
            border-radius: 10px;
            overflow: hidden;
            margin: 5px 0;
        }
        
        .progress-bar {
            height: 100%;
            background: #4caf50;
            text-align: center;
            color: white;
            font-size: 12px;
            line-height: 20px;
        }
    </style>
</head>
<body>
<div class="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR (sama seperti sebelumnya) -->
<div class="sidebar">
    <h2>ADMIN DESA</h2>
    <ul>
        <li><a href="../../index.php">Dashboard</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Home ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../../home/banner-hero/banner.php">Banner</a></li>
                <li><a href="../../home/sambutan/sambutan.php">Sambutan</a></li>
                <li><a href="../../home/sotk/sotk.php">SOTK</a></li>
            </ul>
        </li>
        
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Profil Desa ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../../profil-desa/visi-misi/visimisi.php">Visi & Misi</a></li>
                <li><a href="../../profil-desa/bagan-desa/bagandesa.php">Bagan Desa</a></li>
                <li><a href="../../profil-desa/sejarah-desa/sejarah.php">Sejarah Desa</a></li>
            </ul>
        </li>
        
        <!-- DROPDOWN INFOGRAFIS -->
        <li class="dropdown">
            <a href="javascript:void(0)" onclick="toggleDropdown(this)">Infografis ▾</a>
            <ul class="dropdown-menu">
                <li><a href="../penduduk/penduduk.php">Penduduk</a></li>
                <li><a href="./apbdes.php" style="background:rgba(255,255,255,0.15)">APBDes</a></li>
                <li><a href="../bansos/bansos.php">Bansos</a></li>
            </ul>
        </li>
        <li><a href="../../berita/berita.php">Berita</a></li>
        <li><a href="../../galeri/galeri.php">Galeri</a></li>
        <li><a href="../../chatbot/manage.php">Chatbot</a></li>

        
        <li><a href="../../logout.php">Logout</a></li>
    </ul>
</div>

<div class="main">
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Detail <?= $type_labels[$type]; ?> - Tahun <?= $tahun; ?></h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <div class="detail-header">
        <div>
            <h2><?= $type_labels[$type]; ?> Desa <?= $tahun; ?></h2>
            <p>Kelola detail <?= strtolower($type_labels[$type]); ?> untuk APBDes tahun <?= $tahun; ?></p>
        </div>
        <div class="navigation-buttons">
            <a href="apbdes.php?tahun=<?= $tahun; ?>" class="nav-btn back">
                ← Kembali
            </a>
            <?php if($prev_type): ?>
                <a href="?type=<?= $prev_type; ?>&apbdes_id=<?= $apbdes_id; ?>" class="nav-btn prev">
                    ← <?= $type_labels[$prev_type]; ?>
                </a>
            <?php endif; ?>
            
            <?php if($next_type): ?>
                <a href="?type=<?= $next_type; ?>&apbdes_id=<?= $apbdes_id; ?>" class="nav-btn next">
                    <?= $type_labels[$next_type]; ?> →
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- FORM TAMBAH ITEM -->
    <div class="form-container">
        <h3>Tambah Item <?= $type_labels[$type]; ?></h3>
        <form method="POST" action="">
            <div class="form-row">
                <select name="jenis" required>
                    <option value="">Pilih Jenis</option>
                    <?php foreach($predefined_items as $item): ?>
                        <option value="<?= $item; ?>"><?= $item; ?></option>
                    <?php endforeach; ?>
                    <option value="custom">-- Custom --</option>
                </select>
                <input type="text" id="custom_jenis" name="custom_jenis" 
                       placeholder="Nama custom..." style="display: none;">
            </div>
            
            <div class="form-row">
                <input type="text" name="jumlah" required 
                       placeholder="Jumlah (Rp)" 
                       oninput="formatRupiah(this)">
                <input type="number" name="persentase" 
                       placeholder="Persentase (%)" step="0.01" min="0" max="100">
            </div>
            
            <button type="submit" name="add_item" class="btn-add">
                + Tambah Item
            </button>
        </form>
    </div>

    <!-- TABEL DATA -->
    <div class="data-table">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Persentase</th>
                    <th>Progress</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($items)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #666;">
                            Belum ada data <?= $type_labels[$type]; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $total = 0;
                    $no = 1;
                    foreach($items as $item): 
                        $total += $item['jumlah'];
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($item['jenis']); ?></td>
                            <td>Rp<?= number_format($item['jumlah'], 0, ',', '.'); ?></td>
                            <td><?= $item['persentase']; ?>%</td>
                            <td>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: <?= $item['persentase']; ?>%;">
                                        <?= $item['persentase']; ?>%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="btn-edit-small" 
                                            onclick="editItem(<?= $item['id']; ?>, '<?= $item['jenis']; ?>', '<?= $item['jumlah']; ?>', '<?= $item['persentase']; ?>')">
                                        Edit
                                    </button>
                                    <a href="?type=<?= $type; ?>&apbdes_id=<?= $apbdes_id; ?>&delete=<?= $item['id']; ?>" 
                                       class="btn-delete-small"
                                       onclick="return confirm('Hapus item ini?')">
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="2"><strong>TOTAL</strong></td>
                        <td><strong>Rp<?= number_format($total, 0, ',', '.'); ?></strong></td>
                        <td><strong>100%</strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL EDIT -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 8px; width: 400px;">
        <h3>Edit Item</h3>
        <form method="POST" action="" id="editForm">
            <input type="hidden" name="id" id="edit_id">
            
            <div style="margin-bottom: 15px;">
                <label>Jenis</label>
                <input type="text" name="jenis" id="edit_jenis" 
                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label>Jumlah (Rp)</label>
                <input type="text" name="jumlah" id="edit_jumlah" 
                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"
                       oninput="formatRupiah(this)">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label>Persentase (%)</label>
                <input type="number" name="persentase" id="edit_persentase" step="0.01" min="0" max="100"
                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeModal()" 
                        style="padding: 8px 20px; background: #757575; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" name="update_item" 
                        style="padding: 8px 20px; background: #2e7d32; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function formatRupiah(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if(value) {
        value = parseInt(value).toLocaleString('id-ID');
    }
    input.value = value;
}

// Toggle custom input
document.querySelector('[name="jenis"]').addEventListener('change', function() {
    const customInput = document.getElementById('custom_jenis');
    if(this.value === 'custom') {
        customInput.style.display = 'block';
        customInput.name = 'jenis';
        this.name = 'jenis_temp';
    } else {
        customInput.style.display = 'none';
        customInput.name = 'custom_jenis';
        this.name = 'jenis';
    }
});

// Modal functions
function editItem(id, jenis, jumlah, persentase) {
    // Format jumlah tanpa . (tapi nanti di server akan di-parse)
    const formattedJumlah = jumlah.replace(/\./g, '');
    
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_jenis').value = jenis;
    document.getElementById('edit_jumlah').value = formattedJumlah.toLocaleString('id-ID');
    document.getElementById('edit_persentase').value = persentase;
    
    document.getElementById('editModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if(e.target === this) {
        closeModal();
    }
});

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
</script>

</body>
</html>