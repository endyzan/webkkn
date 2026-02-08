<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../../db.php';

$id = $_GET['id'];
$query = "SELECT * FROM apbdes WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($query);

if(!$data) {
    header("Location: apbdes.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pendapatan = str_replace(['.', ','], ['', '.'], $_POST['pendapatan']);
    $belanja = str_replace(['.', ','], ['', '.'], $_POST['belanja']);
    $pembiayaan_penerimaan = str_replace(['.', ','], ['', '.'], $_POST['pembiayaan_penerimaan']);
    $pembiayaan_pengeluaran = str_replace(['.', ','], ['', '.'], $_POST['pembiayaan_pengeluaran']);
    
    $query = "UPDATE apbdes SET 
              pendapatan = '$pendapatan',
              belanja = '$belanja',
              pembiayaan_penerimaan = '$pembiayaan_penerimaan',
              pembiayaan_pengeluaran = '$pembiayaan_pengeluaran'
              WHERE id = '$id'";
    
    if(mysqli_query($conn, $query)) {
        header("Location: apbdes.php?tahun=" . $data['tahun']);
        exit;
    } else {
        $error = "Gagal mengupdate data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit APBDes</title>
    <link rel="stylesheet" href="../../../assets/admin/style.css">
    <style>
        /* Style sama seperti apbdes_add.php */
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #2e7d32;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
        }
        
        .btn-submit {
            background: #2e7d32;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-cancel {
            background: #757575;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        
        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR (sama seperti sebelumnya) -->
<div class="sidebar">
    <h2>ADMIN DESA</h2>
    <ul>
        <!-- Menu sama seperti sebelumnya -->
    </ul>
</div>

<div class="main">
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <h1>Edit APBDes Tahun <?= $data['tahun']; ?></h1>
        <span>Halo, <?= $_SESSION['nama_admin']; ?></span>
    </div>

    <div class="form-container">
        <?php if(isset($error)): ?>
            <div class="error-message"><?= $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Tahun</label>
                <input type="text" value="<?= $data['tahun']; ?>" readonly style="background: #f5f5f5;">
            </div>
            
            <div class="form-group">
                <label>Total Pendapatan (Rp)</label>
                <input type="text" name="pendapatan" required 
                       value="<?= number_format($data['pendapatan'], 0, ',', '.'); ?>"
                       oninput="formatRupiah(this)">
            </div>
            
            <div class="form-group">
                <label>Total Belanja (Rp)</label>
                <input type="text" name="belanja" required 
                       value="<?= number_format($data['belanja'], 0, ',', '.'); ?>"
                       oninput="formatRupiah(this)">
            </div>
            
            <div class="form-group">
                <label>Pembiayaan - Penerimaan (Rp)</label>
                <input type="text" name="pembiayaan_penerimaan" 
                       value="<?= number_format($data['pembiayaan_penerimaan'], 0, ',', '.'); ?>"
                       oninput="formatRupiah(this)">
            </div>
            
            <div class="form-group">
                <label>Pembiayaan - Pengeluaran (Rp)</label>
                <input type="text" name="pembiayaan_pengeluaran" 
                       value="<?= number_format($data['pembiayaan_pengeluaran'], 0, ',', '.'); ?>"
                       oninput="formatRupiah(this)">
            </div>
            
            <div class="form-actions">
                <a href="apbdes.php?tahun=<?= $data['tahun']; ?>" class="btn-cancel">
                    Batal
                </a>
                <button type="submit" class="btn-submit">
                    Update Data
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

function toggleDropdown(element) {
    const dropdownMenu = element.nextElementSibling;
    dropdownMenu.classList.toggle('show');
}
</script>

</body>
</html>