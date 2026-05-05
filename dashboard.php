<?php
// dashboard.php — Dashboard utama pengacara
require_once 'includes/cek_session.php';
require_once 'includes/koneksi.php';

$user_id      = $_SESSION['user_id'];
$nama_lengkap = $_SESSION['nama_lengkap'];
$nama_safe    = mysqli_real_escape_string($koneksi, $nama_lengkap);

// Hapus kasus
if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM kasus WHERE id = $id_hapus AND pengacara = '$nama_safe'");
    header("Location: dashboard.php?pesan=hapus_sukses");
    exit();
}

$pesan = $_GET['pesan'] ?? '';

// Ambil kasus milik pengacara yang login (relasi via nama)
$result = mysqli_query($koneksi,
    "SELECT * FROM kasus WHERE pengacara = '$nama_safe' ORDER BY tanggal_masuk DESC"
);

// Helper badge
function badge_jenis($j) {
    $map = [
        'Perdata'         => 'bg-purple-badge',
        'Pidana'          => 'bg-red-badge',
        'Ketenagakerjaan' => 'bg-yellow-badge',
        'Bisnis'          => 'bg-teal-badge',
        'Keluarga'        => 'bg-pink-badge',
    ];
    return $map[$j] ?? 'bg-secondary';
}
function badge_status($s) {
    $map = [
        'Aktif'        => 'badge-aktif-lx',
        'Selesai'      => 'badge-selesai-lx',
        'Ditangguhkan' => 'badge-tangguh-lx',
    ];
    return $map[$s] ?? 'bg-secondary';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — LexCorp Law Firm</title>
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
        /* NAVBAR */
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
        .lx-nav-actions { display: flex; gap: 10px; align-items: center; }
        .btn-lx-gold {
            background: #c9a227;
            color: #13111a;
            font-family: 'Playfair Display', serif;
            font-size: 0.82rem;
            font-weight: 700;
            border: none;
            border-radius: 2px;
            padding: 7px 16px;
            text-decoration: none;
            transition: background 0.2s;
            letter-spacing: 0.5px;
        }
        .btn-lx-gold:hover { background: #a8871e; color: #13111a; }
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
            letter-spacing: 0.5px;
        }
        .btn-lx-outline:hover { background: #c9a227; color: #13111a; }
        /* WELCOME BANNER */
        .welcome-banner {
            background: linear-gradient(110deg, #13111a 0%, #1e1a2e 50%, #0d1f3c 100%);
            padding: 36px 40px;
            color: #f5f0e8;
            border-bottom: 3px solid #c9a227;
        }
        .welcome-banner .greeting {
            font-family: 'Playfair Display', serif;
            font-size: 1.55rem;
            color: #c9a227;
            margin-bottom: 4px;
        }
        .welcome-banner .subtitle { font-size: 0.9rem; color: #9d9080; font-style: italic; }
        /* CONTENT */
        .main-content { flex: 1; padding: 36px 40px; max-width: 1200px; margin: 0 auto; width: 100%; }
        /* TABLE CARD */
        .table-card {
            background: #fff;
            border-radius: 3px;
            box-shadow: 0 3px 18px rgba(0,0,0,0.08);
            overflow: hidden;
            border: 1px solid #e8e2d4;
        }
        .table-card-header {
            background: #faf8f3;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e8e2d4;
        }
        .table-card-header h5 {
            font-family: 'Playfair Display', serif;
            color: #13111a;
            font-size: 1.1rem;
            margin: 0;
        }
        .table-card-header span { font-size: 0.82rem; color: #999; }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        table thead th {
            background: #13111a;
            color: #c9a227;
            padding: 12px 16px;
            text-align: left;
            font-size: 0.78rem;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            font-family: 'Source Serif 4', serif;
            font-weight: 700;
        }
        table tbody td { padding: 12px 16px; border-bottom: 1px solid #f0ece2; vertical-align: middle; }
        table tbody tr:last-child td { border-bottom: none; }
        table tbody tr:hover td { background: #fdf9ef; }
        /* BADGES */
        .lx-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            font-family: 'Source Serif 4', serif;
        }
        .badge-aktif-lx   { background: #d4edda; color: #155724; }
        .badge-selesai-lx { background: #cce5ff; color: #003d7a; }
        .badge-tangguh-lx { background: #f8d7da; color: #721c24; }
        .bg-purple-badge  { background: #ede0f8; color: #5a1f8a; }
        .bg-red-badge     { background: #fde8e8; color: #8a1f1f; }
        .bg-yellow-badge  { background: #fff3cd; color: #856404; }
        .bg-teal-badge    { background: #d1ecf1; color: #0c5460; }
        .bg-pink-badge    { background: #fce4ec; color: #880e4f; }
        /* ACTION BUTTONS */
        .btn-tbl-edit {
            background: #1a4a8b;
            color: #fff;
            border: none;
            border-radius: 2px;
            padding: 4px 11px;
            font-size: 0.78rem;
            text-decoration: none;
            font-family: 'Source Serif 4', serif;
            transition: background 0.15s;
        }
        .btn-tbl-edit:hover { background: #0f3160; color: #fff; }
        .btn-tbl-del {
            background: #7b1c1c;
            color: #fff;
            border: none;
            border-radius: 2px;
            padding: 4px 11px;
            font-size: 0.78rem;
            text-decoration: none;
            font-family: 'Source Serif 4', serif;
            transition: background 0.15s;
            cursor: pointer;
        }
        .btn-tbl-del:hover { background: #5a1212; }
        /* EMPTY STATE */
        .empty-state { text-align: center; padding: 60px 20px; color: #b0a890; }
        .empty-state .icon { font-size: 3.5rem; margin-bottom: 14px; }
        .empty-state p { font-size: 0.95rem; font-style: italic; }
        /* PAGE HEADER */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #c9a227;
        }
        .section-header h4 {
            font-family: 'Playfair Display', serif;
            color: #13111a;
            font-size: 1.3rem;
            margin: 0;
        }
        .section-header p { font-size: 0.83rem; color: #888; margin: 4px 0 0; }
        /* FOOTER */
        footer {
            background: #13111a;
            color: #555;
            text-align: center;
            padding: 14px;
            font-size: 0.78rem;
            border-top: 3px solid #c9a227;
        }
        footer em { color: #c9a227; font-style: normal; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="lx-navbar">
    <a href="dashboard.php" class="lx-brand">⚖️ LEXCORP LAW FIRM</a>
    <div class="lx-nav-actions">
        <span style="color:#888; font-size:0.82rem; font-style:italic;">Halo, <?= htmlspecialchars($nama_lengkap) ?></span>
        <a href="logout.php" class="btn-lx-outline">🚪 Logout</a>
    </div>
</nav>

<!-- WELCOME BANNER -->
<div class="welcome-banner">
    <p class="greeting">Selamat Datang, <?= htmlspecialchars($nama_lengkap) ?> 👋</p>
    <p class="subtitle">Berikut adalah daftar kasus yang sedang Anda tangani. Kelola dan pantau status setiap kasus dengan mudah.</p>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">

    <?php if ($pesan === 'hapus_sukses'): ?>
        <div class="alert alert-success py-2 px-3 mb-3" style="font-size:0.85rem; border-left:4px solid #2e7d32; border-radius:2px;">
            ✅ Kasus berhasil dihapus.
        </div>
    <?php endif; ?>
    <?php if ($pesan === 'tambah_sukses'): ?>
        <div class="alert alert-success py-2 px-3 mb-3" style="font-size:0.85rem; border-left:4px solid #2e7d32; border-radius:2px;">
            ✅ Kasus baru berhasil ditambahkan.
        </div>
    <?php endif; ?>
    <?php if ($pesan === 'edit_sukses'): ?>
        <div class="alert alert-success py-2 px-3 mb-3" style="font-size:0.85rem; border-left:4px solid #2e7d32; border-radius:2px;">
            ✅ Data kasus berhasil diperbarui.
        </div>
    <?php endif; ?>

    <div class="section-header">
        <div>
            <h4>📂 Data Kasus Saya</h4>
            <p>Kasus yang terdaftar atas nama Anda</p>
        </div>
        <a href="tambah_kasus.php" class="btn-lx-gold">➕ Tambah Kasus</a>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <h5>Tabel Kasus</h5>
            <span><?= mysqli_num_rows($result) ?> kasus ditemukan</span>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Klien</th>
                    <th>Judul / No. Kasus</th>
                    <th>Tanggal Masuk</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td style="color:#aaa;"><?= $no++ ?></td>
                    <td><strong><?= htmlspecialchars($row['nama_klien']) ?></strong></td>
                    <td style="color:#555;"><?= htmlspecialchars($row['nomor_kasus']) ?></td>
                    <td><?= date('d/m/Y', strtotime($row['tanggal_masuk'])) ?></td>
                    <td><span class="lx-badge <?= badge_jenis($row['jenis_kasus']) ?>"><?= htmlspecialchars($row['jenis_kasus']) ?></span></td>
                    <td><span class="lx-badge <?= badge_status($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                    <td style="white-space:nowrap;">
                        <a href="edit_kasus.php?id=<?= $row['id'] ?>" class="btn-tbl-edit">✏️ Edit</a>
                        &nbsp;
                        <a href="dashboard.php?hapus=<?= $row['id'] ?>"
                           class="btn-tbl-del"
                           onclick="return confirm('Hapus kasus ini? Tindakan tidak dapat dibatalkan.')">🗑️ Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <div class="icon">📁</div>
            <p>Belum ada kasus yang terdaftar atas nama Anda.</p>
            <a href="tambah_kasus.php" class="btn-lx-gold" style="margin-top:14px; display:inline-block;">➕ Tambah Kasus Baru</a>
        </div>
        <?php endif; ?>
    </div>

</div>

<footer>
    <p>Copyright &copy; 2025 — <em>LexCorp Law Firm</em>. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
