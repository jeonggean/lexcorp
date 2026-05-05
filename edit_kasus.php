<?php
// edit_kasus.php — EDIT: Ubah data kasus
require_once 'includes/cek_session.php';
require_once 'includes/koneksi.php';

$user_id = $_SESSION['user_id'];
$error   = "";

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header("Location: dashboard.php");
    exit();
}

// Ambil data kasus
$result = mysqli_query($koneksi, "SELECT * FROM kasus WHERE id = $id AND pengacara_id = $user_id LIMIT 1");
if (mysqli_num_rows($result) === 0) {
    header("Location: dashboard.php");
    exit();
}
$kasus = mysqli_fetch_assoc($result);

// Proses simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_klien    = trim($_POST['nama_klien'] ?? '');
    $nomor_kasus   = trim($_POST['nomor_kasus'] ?? '');
    $tanggal_masuk = $_POST['tanggal_masuk'] ?? '';
    $status        = $_POST['status'] ?? '';
    $deskripsi     = trim($_POST['deskripsi'] ?? '');

    if (empty($nama_klien) || empty($nomor_kasus) || empty($tanggal_masuk) || empty($status)) {
        $error = "Semua field wajib diisi!";
    } else {
        $k = mysqli_real_escape_string($koneksi, $nama_klien);
        $n = mysqli_real_escape_string($koneksi, $nomor_kasus);
        $t = mysqli_real_escape_string($koneksi, $tanggal_masuk);
        $s = mysqli_real_escape_string($koneksi, $status);
        $d = mysqli_real_escape_string($koneksi, $deskripsi);

        $query = "UPDATE kasus SET
            nama_klien    = '$k',
            nomor_kasus   = '$n',
            tanggal_masuk = '$t',
            status        = '$s',
            deskripsi     = '$d'
            WHERE id = $id AND pengacara_id = $user_id";

        if (mysqli_query($koneksi, $query)) {
            header("Location: dashboard.php?pesan=edit_sukses");
            exit();
        } else {
            $error = "Gagal menyimpan: " . mysqli_error($koneksi);
        }
    }
    // Reload kasus jika ada error, gunakan POST values
    $kasus['nama_klien']    = $nama_klien;
    $kasus['nomor_kasus']   = $nomor_kasus;
    $kasus['tanggal_masuk'] = $tanggal_masuk;
    $kasus['status']        = $status;
    $kasus['deskripsi']     = $deskripsi;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kasus — LexCorp Law Firm</title>
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
        .case-id-pill {
            display: inline-block;
            background: rgba(201,162,39,0.15);
            color: #c9a227;
            border: 1px solid #c9a227;
            border-radius: 12px;
            font-size: 0.75rem;
            padding: 2px 10px;
            margin-top: 8px;
            font-family: monospace;
        }
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
        .form-control[readonly] {
            background: #f0ece2;
            color: #888;
            cursor: not-allowed;
        }
        textarea.form-control { resize: vertical; min-height: 110px; }
        .btn-submit {
            background: #1a4a8b;
            color: #fff;
            font-family: 'Playfair Display', serif;
            font-size: 0.92rem;
            font-weight: 700;
            border: none;
            border-radius: 2px;
            padding: 10px 28px;
            transition: background 0.2s;
            letter-spacing: 0.5px;
        }
        .btn-submit:hover { background: #0f3160; }
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
        .form-divider { border: none; border-top: 1px solid #ede8de; margin: 20px 0; }
        .info-readonly {
            font-size: 0.78rem;
            color: #aaa;
            font-style: italic;
            margin-top: 4px;
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
            <h3>✏️ Edit Data Kasus</h3>
            <p>Perbarui informasi kasus yang telah terdaftar.</p>
            <span class="case-id-pill"><?= htmlspecialchars($kasus['nomor_kasus']) ?></span>
        </div>
        <div class="form-card-body">

            <?php if ($error): ?>
                <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:0.85rem; border-left:4px solid #8b1a1a; border-radius:2px;">
                    ❌ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="edit_kasus.php?id=<?= $id ?>">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Kasus <span class="req">*</span></label>
                        <input type="text" name="nomor_kasus" class="form-control"
                               value="<?= htmlspecialchars($kasus['nomor_kasus']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Masuk <span class="req">*</span></label>
                        <input type="date" name="tanggal_masuk" class="form-control"
                               value="<?= htmlspecialchars($kasus['tanggal_masuk']) ?>">
                    </div>
                </div>

                <hr class="form-divider">

                <div class="mb-3">
                    <label class="form-label">Nama Klien <span class="req">*</span></label>
                    <input type="text" name="nama_klien" class="form-control"
                           value="<?= htmlspecialchars($kasus['nama_klien']) ?>">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kasus</label>
                        <input type="text" class="form-control" readonly
                               value="<?= htmlspecialchars($kasus['jenis_kasus']) ?>">
                        <p class="info-readonly">Jenis kasus tidak dapat diubah setelah disimpan.</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Kasus <span class="req">*</span></label>
                        <select name="status" class="form-select">
                            <?php foreach (['Aktif','Selesai','Ditangguhkan'] as $s): ?>
                                <option value="<?= $s ?>" <?= ($kasus['status'] === $s) ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Deskripsi Kasus</label>
                    <textarea name="deskripsi" class="form-control"><?= htmlspecialchars($kasus['deskripsi']) ?></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-submit">💾 Simpan Perubahan</button>
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