<?php
// lihat_kasus.php — READ: Tampilkan semua data kasus (tanpa tombol kelola)
require_once 'includes/cek_session.php';
require_once 'includes/koneksi.php';

// Filter opsional
$filter_jenis  = $_GET['jenis']  ?? '';
$filter_status = $_GET['status'] ?? '';

$where = "WHERE 1=1";
if ($filter_jenis)  $where .= " AND jenis_kasus = '" . mysqli_real_escape_string($koneksi, $filter_jenis) . "'";
if ($filter_status) $where .= " AND status = '"      . mysqli_real_escape_string($koneksi, $filter_status) . "'";

$result = mysqli_query($koneksi, "SELECT * FROM kasus $where ORDER BY tanggal_masuk DESC");

// Helper badge
function badge_jenis($j) {
    $map = [
        'Perdata'         => 'badge-perdata',
        'Pidana'          => 'badge-pidana',
        'Ketenagakerjaan' => 'badge-tk',
        'Bisnis'          => 'badge-bisnis',
        'Keluarga'        => 'badge-keluarga',
    ];
    return $map[$j] ?? '';
}
function badge_status($s) {
    $map = [
        'Aktif'          => 'badge-aktif',
        'Selesai'        => 'badge-selesai',
        'Ditangguhkan'   => 'badge-tangguh',
    ];
    return $map[$s] ?? '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data Kasus — LexCorp Law Firm</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .filter-bar {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            gap: 14px;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .filter-bar label { font-size: 0.82rem; font-weight: bold; display:block; margin-bottom:4px; }
        .filter-bar select { padding: 7px 10px; border: 1px solid #ccc; border-radius:4px; font-size:0.88rem; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a class="navbar-brand" href="index.php">
        <span class="logo-icon">⚖️</span>
        LEXCORP LAW FIRM
    </a>
    <div class="navbar-nav">
        <a href="logout.php">logout</a>
    </div>
</nav>

<!-- CONTENT -->
<div class="main-content">

    <div class="page-header">
        <h2>Lihat Data Kasus</h2>
        <p>Daftar seluruh kasus yang terdaftar di sistem.</p>
    </div>

    <!-- TABEL -->
    <div class="table-card">
    <a class="btn btn-primary btn-sm" href="tambah_kasus.php">Tambah Data</a>    
    <div class="table-toolbar">
            <h3>Tabel Data Kasus</h3>
            <span style="font-size:0.85rem; color:#888;"><?= mysqli_num_rows($result) ?> kasus ditemukan</span>
        </div>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. Kasus</th>
                    <th>Nama Klien</th>
                    <th>Jenis</th>
                    <th>Tanggal Masuk</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><strong><?= htmlspecialchars($row['nomor_kasus']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_klien']) ?></td>
                    <td><span class="badge <?= badge_jenis($row['jenis_kasus']) ?>"><?= $row['jenis_kasus'] ?></span></td>
                    <td><?= date('d/m/Y', strtotime($row['tanggal_masuk'])) ?></td>
                    <td><span class="badge <?= badge_status($row['status']) ?>"><?= $row['status'] ?></span></td>
                    <td style="max-width:220px; color:#555; font-size:0.82rem;"><?= htmlspecialchars(substr($row['deskripsi'], 0, 80)) ?>...</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <div class="icon"></div>
            <p>Tidak ada data kasus yang ditemukan.</p>
            <a href="tambah_kasus.php" class="btn btn-primary" style="margin-top:12px;">+ Tambah Kasus Baru</a>
        </div>
        <?php endif; ?>
    </div>

</div>

<!-- FOOTER -->
<footer>
    <p>Copyright &copy; 2025 — <span>LexCorp Law Firm</span>. All rights reserved.</p>
</footer>

</body>
</html>