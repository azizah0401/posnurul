<?php  //homepage
session_start();
require "./koneksi.php";
if (!isset($_SESSION["pass"])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>homepage</title>
</head>
<body>
    <h1> Welcome To Venizia Store</h1>
    
    <div class="sidebar">
        <a href="profile.php"> profile</a>
        <a href="produk.php"> Produk</a>
        <a href="transaksi.php"> Transaksi</a>
        <a href="laporan.php"> Laporan Penjualan</a>
        <a href="logout.php"> Logout</a>
    </div>
</body>
</html>