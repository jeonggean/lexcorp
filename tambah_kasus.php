<?php
// tambah_kasus.php — CREATE: Tambah data kasus baru
require_once 'includes/cek_session.php';
require_once 'includes/koneksi.php';

$nama_lengkap = $_SESSION['nama_lengkap'];
$error        = "";
$success      = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor_kasus   = trim($_POST['nomor_kasus'] ?? '');
    $nama_klien    = trim($_POST['nama_klien'] ?? '');
    $jenis_kasus   = $_POST['jenis_kasus'] ?? '';
    $deskripsi     = trim($_POST['deskripsi'] ?? '');
    $tanggal_masuk = $_POST['tanggal_masuk'] ?? '';
    $status        = $_POST['status'] ?? 'Aktif';

    if (empty($nomor_kasus) || empty($nama_klien) || empty($jenis_kasus) ||
        empty($deskripsi)   || empty($tanggal_masuk)) {
        $error = "Semua field wajib diisi!";
    } else {
        $cek = mysqli_query($koneksi, "SELECT id FROM kasus WHERE nomor_kasus = '" . mysqli_real_escape_string($koneksi, $nomor_kasus) . "'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Nomor kasus <strong>" . htmlspecialchars($nomor_kasus) . "</strong> sudah terdaftar.";
        } else {
            $n = mysqli_real_escape_string($koneksi, $nomor_kasus);
            $k = mysqli_real_escape_string($koneksi, $nama_klien);
            $j = mysqli_real_escape_string($koneksi, $jenis_kasus);
            $d = mysqli_real_escape_string($koneksi, $deskripsi);
            $t = mysqli_real_escape_string($koneksi, $tanggal_masuk);
            $s = mysqli_real_escape_string($koneksi, $status);
            $p = mysqli_real_escape_string($koneksi, $nama_lengkap);

            $query = "INSERT INTO kasus (nomor_kasus, nama_klien, jenis_kasus, deskripsi, tanggal_masuk, status, pengacara)
                      VALUES ('$n','$k','$j','$d','$t','$s','$p')";
            if (mysqli_query($koneksi, $query)) {
                header("Location: dashboard.php?pesan=tambah_sukses");
                exit();
            } else {
                $error = "Gagal menyimpan: " . mysqli_error($koneksi);
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
    <title>Tambah Kasus — LexCorp Law Firm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Source+Serif+4:ital,wght@0,300;0,400;1,300&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f0ece2;
            font-family: 'Source Serif 4', Georgia, serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .lx-navbar {
            background: #13111a;
            padding: 0 32px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #c9a227;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .lx-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            color: #c9a227;
            letter-spacing: 2px;
            text-decoration: none;
        }
        .btn-lx-outline {
            background: transparent;
            color: #c9a227;
            font-family: 'Playfair Display', serif;
            font-size: 0.82rem;
            font-weight: 700;
            border: 1px solid #c9a227;
            border-radius: 2px;
            padding: 7px 16px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-lx-outline:hover { background: #c9a227; color: #13111a; }
        footer {
            background: #13111a;
            color: #555;
            text-align: center;
            padding: 14px;
            font-size: 0.78rem;
            border-top: 3px solid #c9a227;
            margin-top: auto;
        }
        footer em { color: #c9a227; font-style: normal; }
        .main-content { flex: 1; padding: 40px 20px; }
        .form-card {
            background: #fff;
            border-radius: 3px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.09);
            overflow: hidden;
            max-width: 680px;
            margin: 0 auto;
            border: 1px solid #e8e2d4;
        }
        .form-card-header {
            background: #13111a;
            padding: 22px 32px;
            border-bottom: 3px solid #c9a227;
        }
        .form-card-header h3 {
            font-family: 'Playfair Display', serif;
            color: #c9a227;
            font-size: 1.35rem;
            margin: 0 0 2px;
        }
        .form-card-header p { color: #888; font-size: 0.82rem; font-style: italic; margin: 0; }
        .form-card-body { padding: 28px 32px; }
        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #2c2c2c;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .form-label .req { color: #c9a227; margin-left: 2px; }
        .form-control, .form-select {
            border: 1px solid #d5cebd;
            border-radius: 2px;
            font-family: 'Source Serif 4', serif;
            font-size: 0.9rem;
            padding: 9px 13px;
            background: #faf8f3;
            transition: border 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #c9a227;
            box-shadow: 0 0 0 3px rgba(201,162,39,0.15);
            background: #fff;
        }
        .form-control::placeholder { color: #bbb; }
        textarea.form-control { resize: vertical; min-height: 110px; }
        .btn-submit {
            background: #c9a227;
            color: #13111a;
            font-family: 'Playfair Display', serif;
            font-size: 0.92rem;
            font-weight: 700;
            border: none;
            border-radius: 2px;
            padding: 10px 28px;
            transition: background 0.2s;
            letter-spacing: 0.5px;
        }
        .btn-submit:hover { background: #a8871e; }
        .btn-back {
            background: transparent;
            color: #666;
            font-family: 'Source Serif 4', serif;
            font-size: 0.88rem;
            border: 1px solid #ccc;
            border-radius: 2px;
            padding: 10px 20px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-back:hover { border-color: #999; color: #333; }
        .form-divider {
            border: none;
            border-top: 1px solid #ede8de;
            margin: 20px 0;
        }
    </style>
</head>
<body>

<nav class="lx-navbar">
    <a href="dashboard.php" class="lx-brand">⚖️ LEXCORP LAW FIRM</a>
    <a href="dashboard.php" class="btn-lx-outline">← Dashboard</a>
</nav>

<div class="main-content">
    <div class="form-card">
        <div class="form-card-header">
            <h3>📋 Input Data Kasus</h3>
            <p>Silakan isi formulir berikut untuk mendaftarkan kasus baru.</p>
        </div>
        <div class="form-card-body">

            <?php if ($error): ?>
                <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:0.85rem; border-left:4px solid #8b1a1a; border-radius:2px;">
                    ❌ <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="tambah_kasus.php">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Kasus <span class="req">*</span></label>
                        <input type="text" name="nomor_kasus" class="form-control"
                               placeholder="cth: KS-2025-006"
                               value="<?= htmlspecialchars($_POST['nomor_kasus'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Masuk <span class="req">*</span></label>
                        <input type="date" name="tanggal_masuk" class="form-control"
                               value="<?= htmlspecialchars($_POST['tanggal_masuk'] ?? date('Y-m-d')) ?>">
                    </div>
                </div>

                <hr class="form-divider">

                <div class="mb-3">
                    <label class="form-label">Nama Klien <span class="req">*</span></label>
                    <input type="text" name="nama_klien" class="form-control"
                           placeholder="Nama lengkap klien / perusahaan"
                           value="<?= htmlspecialchars($_POST['nama_klien'] ?? '') ?>">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kasus <span class="req">*</span></label>
                        <select name="jenis_kasus" class="form-select">
                            <option value="">— Pilih Jenis —</option>
                            <?php foreach (['Perdata','Pidana','Ketenagakerjaan','Bisnis','Keluarga'] as $j): ?>
                                <option value="<?= $j ?>" <?= (($_POST['jenis_kasus'] ?? '') === $j) ? 'selected' : '' ?>><?= $j ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Kasus <span class="req">*</span></label>
                        <select name="status" class="form-select">
                            <?php foreach (['Aktif','Selesai','Ditangguhkan'] as $s): ?>
                                <option value="<?= $s ?>" <?= (($_POST['status'] ?? 'Aktif') === $s) ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Deskripsi Kasus <span class="req">*</span></label>
                    <textarea name="deskripsi" class="form-control"
                              placeholder="Uraikan kasus secara singkat dan jelas..."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-submit">💾 Simpan Kasus</button>
                    <a href="dashboard.php" class="btn-back">Batal</a>
                </div>

            </form>
        </div>
    </div>
</div>

<footer>
    <p>Copyright &copy; 2025 — <em>LexCorp Law Firm</em>. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
