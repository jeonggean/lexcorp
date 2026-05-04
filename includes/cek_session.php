<?php
// includes/cek_session.php
// Sertakan file ini di semua halaman yang butuh login

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?pesan=belum_login");
    exit();
}
?>