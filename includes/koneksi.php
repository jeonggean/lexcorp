<?php
// includes/koneksi.php
// Konfigurasi koneksi database

$host     = "localhost";
$user     = "root";
$password = "";
$database = "db_lawfirm";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("<div style='color:red; padding:20px; font-family:Arial;'>
        <h3>❌ Koneksi Database Gagal!</h3>
        <p>" . mysqli_connect_error() . "</p>
        <p>Pastikan XAMPP/MySQL sudah berjalan dan database sudah diimport.</p>
    </div>");
}

mysqli_set_charset($koneksi, "utf8");
?>