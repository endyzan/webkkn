<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin Desa</title>
    <link rel="stylesheet" href="../assets/admin/login.css">
</head>
<body>

<div class="login-card">
    <h2>Admin Desa</h2>
    <p>Silakan login untuk mengelola website desa</p>

    <form action="login_proses.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>



        <button type="submit">Login</button>
    </form>

    <div class="login-footer">
        © <?= date('Y'); ?> SID | KKN 14 UTM 2025-2026
    </div>
</div>

</body>
</html>
