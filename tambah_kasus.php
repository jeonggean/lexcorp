<?php
// tambah_kasus.php — CREATE: Tambah data kasus baru
require_once 'includes/cek_session.php';
require_once 'includes/koneksi.php';

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor_kasus   = trim($_POST['nomor_kasus'] ?? '');
    $nama_klien    = trim($_POST['nama_klien'] ?? '');
    $jenis_kasus   = $_POST['jenis_kasus'] ?? '';
    $deskripsi     = trim($_POST['deskripsi'] ?? '');
    $tanggal_masuk = $_POST['tanggal_masuk'] ?? '';
    $status        = $_POST['status'] ?? 'Aktif';
    $pengacara     = trim($_POST['pengacara'] ?? '');

    // Validasi
    if (empty($nomor_kasus) || empty($nama_klien) || empty($jenis_kasus) ||
        empty($deskripsi)   || empty($tanggal_masuk) || empty($pengacara)) {
        $error = "Semua field wajib diisi!";
    } else {
        // Cek duplikat nomor kasus
        $cek = mysqli_query($koneksi, "SELECT id FROM kasus WHERE nomor_kasus = '$nomor_kasus'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Nomor kasus <strong>$nomor_kasus</strong> sudah terdaftar. Gunakan nomor yang berbeda.";
        } else {
            $nomor_kasus   = mysqli_real_escape_string($koneksi, $nomor_kasus);
            $nama_klien    = mysqli_real_escape_string($koneksi, $nama_klien);
            $jenis_kasus   = mysqli_real_escape_string($koneksi, $jenis_kasus);
            $deskripsi     = mysqli_real_escape_string($koneksi, $deskripsi);
            $tanggal_masuk = mysqli_real_escape_string($koneksi, $tanggal_masuk);
            $status        = mysqli_real_escape_string($koneksi, $status);
            $pengacara     = mysqli_real_escape_string($koneksi, $pengacara);

            $query = "INSERT INTO kasus (nomor_kasus, nama_klien, jenis_kasus, deskripsi, tanggal_masuk, status, pengacara)
                      VALUES ('$nomor_kasus','$nama_klien','$jenis_kasus','$deskripsi','$tanggal_masuk','$status','$pengacara')";

            if (mysqli_query($koneksi, $query)) {
                $success = "✅ Data kasus <strong>$nomor_kasus</strong> berhasil ditambahkan!";
                // Reset form setelah berhasil
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
    <title>Input Data Kasus — LexCorp Law Firm</title>
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

<!-- CONTENT -->
<div class="main-content">

    <div class="form-card">
        <h3> Input Data Kasus</h3>
        <p class="subtitle">Silakan isi formulir berikut untuk mendaftarkan kasus baru.</p>

        <?php if ($error): ?>
            <div class="alert alert-danger">❌ <?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="tambah_kasus.php">

            <div class="form-row">
                <div class="form-group">
                    <label for="nomor_kasus">Nomor Kasus *</label>
                    <input type="text" id="nomor_kasus" name="nomor_kasus"
                           placeholder="cth: KS-2025-006"
                           value="<?= htmlspecialchars($_POST['nomor_kasus'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="tanggal_masuk">Tanggal Masuk *</label>
                    <input type="date" id="tanggal_masuk" name="tanggal_masuk"
                           value="<?= htmlspecialchars($_POST['tanggal_masuk'] ?? date('Y-m-d')) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="nama_klien">Nama Klien *</label>
                <input type="text" id="nama_klien" name="nama_klien"
                       placeholder="Nama lengkap klien / perusahaan"
                       value="<?= htmlspecialchars($_POST['nama_klien'] ?? '') ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="jenis_kasus">Jenis Kasus *</label>
                    <select id="jenis_kasus" name="jenis_kasus">
                        <option value="">-- Pilih Jenis --</option>
                        <?php
                        $jenis_list = ['Perdata','Pidana','Ketenagakerjaan','Bisnis','Keluarga'];
                        foreach ($jenis_list as $j):
                            $sel = (($_POST['jenis_kasus'] ?? '') === $j) ? 'selected' : '';
                        ?>
                            <option value="<?= $j ?>" <?= $sel ?>><?= $j ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status Kasus *</label>
                    <select id="status" name="status">
                        <?php
                        $status_list = ['Aktif','Selesai','Ditangguhkan'];
                        foreach ($status_list as $s):
                            $sel = (($_POST['status'] ?? 'Aktif') === $s) ? 'selected' : '';
                        ?>
                            <option value="<?= $s ?>" <?= $sel ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            

            <div class="form-group">
                <label for="deskripsi">Deskripsi Kasus *</label>
                <textarea id="deskripsi" name="deskripsi"
                          placeholder="Uraikan kasus secara singkat dan jelas..."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
            </div>

            <div style="display:flex; gap:12px; margin-top:8px;">
                <button type="submit" class="btn btn-primary">Simpan Kasus</button>
            </div>

        </form>
    </div>

</div>

<!-- FOOTER -->
<footer>
    <p>Copyright &copy; 2025 — <span>LexCorp Law Firm</span>. All rights reserved.</p>
</footer>

</body>
</html>