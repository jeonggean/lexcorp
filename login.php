<?php
// login.php
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

require_once 'includes/koneksi.php';

$error = "";

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi!";
    } else {
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' LIMIT 1";
        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id']      = $user['id'];
            $_SESSION['username']     = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            header("Location: lihat_kasus.php");
            exit();
        } else {
            $error = "Username atau password salah. Silakan coba lagi.";
        }
    }
}

// Pesan dari redirect halaman lain
$pesan = $_GET['pesan'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — LexCorp Law Firm</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a class="navbar-brand" href="index.php">
        <span class="logo-icon">⚖️</span>
        LEXCORP LAW FIRM
    </a>
</nav>

<!-- LOGIN FORM -->
<div class="login-wrapper">
    <div class="login-box">
        <h2>⚖️ Form Login</h2>
        <p class="subtitle">Silakan login untuk mengakses sistem.</p>

        <?php if (!empty($pesan) && $pesan === 'belum_login'): ?>
            <div class="alert alert-warning">⚠️ Anda harus login terlebih dahulu untuk mengakses halaman tersebut.</div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       placeholder="Masukkan username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; padding:11px;">
                🔐 Login
            </button>
        </form>

        <p style="text-align:center; margin-top:16px; font-size:0.82rem; color:#888;">
            <em>Belum Memiliki akun? <a href="register.php">Registrasi</a></em>
        </p>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <p>Copyright &copy; 2025 — <span>LexCorp Law Firm</span>. All rights reserved.</p>
</footer>

</body>
</html>