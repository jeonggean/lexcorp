<?php
// index.php — Halaman Beranda
session_start();
$sudah_login = isset($_SESSION['user_id']);
$nama = $_SESSION['nama_lengkap'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LexCorp Law Firm — Beranda</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a class="navbar-brand" href="index.php">
        <span class="logo-icon">⚖️</span>
        LEXCORP LAW FIRM
    </a>
    <div class="navbar-nav">
        <?php if ($sudah_login): ?>
            <a href="logout.php">🚪 Logout</a>
        <?php else: ?>
            <a href="login.php">🔐 Login</a>
        <?php endif; ?>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-text">
        <h1>LexCorp Law Firm</h1>
        <h2>Keadilan adalah Hak Setiap Insan</h2>
        <p>
            <?php if ($sudah_login): ?>
                [ Halo, <strong><?= htmlspecialchars($nama) ?></strong>. Selamat datang kembali! ]
            <?php else: ?>
                [ Halaman ini hanya dapat diakses oleh Lawyer / Admin ]
            <?php endif; ?>
        </p>
        <div class="hero-buttons">
            <a href="<?= $sudah_login ? 'tambah_kasus.php' : 'login.php?pesan=belum_login' ?>"
               class="btn btn-primary">📋 Input Data Kasus</a>
            <a href="<?= $sudah_login ? 'lihat_kasus.php' : 'login.php?pesan=belum_login' ?>"
               class="btn btn-secondary">📂 Lihat Data Kasus</a>
            <a href="<?= $sudah_login ? 'kelola_kasus.php' : 'login.php?pesan=belum_login' ?>"
               class="btn btn-secondary">✏️ Kelola Data Kasus</a>
        </div>
    </div>
    <div class="hero-img">⚖️</div>
</section>

<!-- FOOTER -->
<footer>
    <p>Copyright &copy; 2025 — <span>LexCorp Law Firm</span>. All rights reserved.</p>
</footer>

</body>
</html>