<?php
// register.php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

require_once 'includes/koneksi.php';

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $username     = trim($_POST['username'] ?? '');
    $password     = trim($_POST['password'] ?? '');

    if (empty($nama_lengkap) || empty($username) || empty($password)) {
        $error = "Semua field wajib diisi!";
    } else {
        $username_safe = mysqli_real_escape_string($koneksi, $username);
        $cek = mysqli_query($koneksi, "SELECT id FROM users WHERE username = '$username_safe'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Username <strong>" . htmlspecialchars($username) . "</strong> sudah terdaftar. Pilih username lain.";
        } else {
            $nama_safe     = mysqli_real_escape_string($koneksi, $nama_lengkap);
            $password_safe = mysqli_real_escape_string($koneksi, $password);
            $query = "INSERT INTO users (nama_lengkap, username, password) VALUES ('$nama_safe','$username_safe','$password_safe')";
            if (mysqli_query($koneksi, $query)) {
                $success = "Registrasi berhasil! Silakan login.";
                $_POST = [];
            } else {
                $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi — LexCorp Law Firm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Source+Serif+4:ital,wght@0,300;0,400;1,300&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0ece2;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c8b06a' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Source Serif 4', Georgia, serif;
        }
        .lx-navbar {
            background: #13111a;
            padding: 14px 32px;
            display: flex;
            align-items: center;
            border-bottom: 3px solid #c9a227;
        }
        .lx-brand {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.25rem;
            color: #c9a227;
            letter-spacing: 2px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        footer {
            background: #13111a;
            color: #666;
            text-align: center;
            padding: 14px;
            font-size: 0.8rem;
            margin-top: auto;
            border-top: 3px solid #c9a227;
        }
        footer em { color: #c9a227; font-style: normal; }
        .reg-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
        }
        .reg-card {
            background: #fff;
            border-radius: 2px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.12);
            overflow: hidden;
        }
        .reg-card-header {
            background: #13111a;
            padding: 28px 36px 24px;
            text-align: center;
            border-bottom: 3px solid #c9a227;
        }
        .reg-card-header h2 {
            font-family: 'Playfair Display', serif;
            color: #c9a227;
            font-size: 1.55rem;
            margin: 0 0 4px;
        }
        .reg-card-header p { color: #888; font-size: 0.82rem; font-style: italic; margin: 0; }
        .reg-card-body { padding: 32px 36px; }
        .form-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #2c2c2c;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .form-control {
            border: 1px solid #d5cebd;
            border-radius: 2px;
            font-family: 'Source Serif 4', serif;
            font-size: 0.92rem;
            padding: 10px 13px;
            background: #faf8f3;
            transition: border 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: #c9a227;
            box-shadow: 0 0 0 3px rgba(201,162,39,0.15);
            background: #fff;
        }
        .btn-register {
            background: #c9a227;
            color: #13111a;
            font-family: 'Playfair Display', serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 1px;
            border: none;
            border-radius: 2px;
            padding: 11px;
            width: 100%;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-register:hover { background: #a8871e; transform: translateY(-1px); }
        .login-link { text-align: center; font-size: 0.83rem; color: #888; margin-top: 16px; }
        .login-link a { color: #c9a227; font-weight: 600; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }
        .divider { border-top: 1px solid #ede8de; margin: 20px 0; }
    </style>
</head>
<body>

<nav class="lx-navbar">
    <a href="login.php" class="lx-brand">⚖️ LEXCORP LAW FIRM</a>
</nav>

<div class="reg-wrap">
    <div class="reg-card">
        <div class="reg-card-header">
            <h2>📝 Daftar Akun</h2>
            <p>Buat akun untuk mengakses sistem manajemen kasus.</p>
        </div>
        <div class="reg-card-body">

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 px-3" style="font-size:0.85rem; border-left:4px solid #8b1a1a; border-radius:2px;">
                    ❌ <?= $error ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success py-2 px-3" style="font-size:0.85rem; border-left:4px solid #2e7d32; border-radius:2px;">
                    ✅ <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div class="mb-3">
                    <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                           placeholder="Nama lengkap Anda"
                           value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control"
                           placeholder="Pilih username unik"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Buat password" required>
                </div>
                <button type="submit" class="btn-register">Register</button>
            </form>

            <div class="divider"></div>
            <p class="login-link">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </p>
        </div>
    </div>
</div>

<footer>
    <p>Copyright &copy; 2025 — <em>LexCorp Law Firm</em>. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>