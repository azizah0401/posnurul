<?php //koneksi
$host = "localhost"; // Sesuaikan dengan konfigurasi server Anda
$user = "root"; // Sesuaikan dengan username MySQL Anda
$password = ""; // Jika ada password MySQL, isi di sini
$database = "pos"; // Ganti dengan nama database yang sesuai

$koneksi = mysqli_connect($host, $user, $password, $database);
?>
