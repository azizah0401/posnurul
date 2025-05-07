<?php
session_start();
require "./koneksi.php";

$nama_kasir = $_SESSION['nama_kasir'];

// Inisialisasi variabel pencarian
$tanggal_cari = "";
$bulan_cari = "";

// ======== LAPORAN HARIAN ========
$where_harian = "WHERE nama_kasir = ?";
$param_harian = "s";
$values_harian = [$nama_kasir];

if (isset($_POST['cari']) && !empty($_POST['tanggal_cari'])) {
    $tanggal_cari = $_POST['tanggal_cari'];
    $where_harian .= " AND tanggal_penjualan = ?";
    $param_harian .= "s";
    $values_harian[] = $tanggal_cari;
}

$query_harian = "SELECT * FROM laporan_harian $where_harian";
$stmt_harian = $koneksi->prepare($query_harian);
$stmt_harian->bind_param($param_harian, ...$values_harian);
$stmt_harian->execute();
$result_harian = $stmt_harian->get_result();

// ======== LAPORAN BULANAN ========
$where_bulanan = "WHERE nama_kasir = ?";
$param_bulanan = "s";
$values_bulanan = [$nama_kasir];

if (isset($_POST['cari']) && !empty($_POST['bulan_cari'])) {
    $bulan_cari = $_POST['bulan_cari'];
    $where_bulanan .= " AND bulan_penjualan = ?";
    $param_bulanan .= "s";
    $values_bulanan[] = $bulan_cari;
}

$query_bulanan = "SELECT * FROM laporan_bulanan $where_bulanan";
$stmt_bulanan = $koneksi->prepare($query_bulanan);
$stmt_bulanan->bind_param($param_bulanan, ...$values_bulanan);
$stmt_bulanan->execute();
$result_bulanan = $stmt_bulanan->get_result();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="laporanhb.css">
    <title>Laporan Penjualan</title>
</head>
<body>
    <h1>Laporan Penjualan</h1>
    
    <!-- Form Pencarian -->
    <form method="POST">
        <label>Tanggal: </label>
        <input type="date" name="tanggal_cari" value="<?= $tanggal_cari; ?>">
        <label>Bulan: </label>
        <input type="month" name="bulan_cari" value="<?= $bulan_cari; ?>">
        <button type="submit" name="cari">Cari Laporan</button>
    </form>

    <!-- Tabel Laporan Harian -->
    <h2>Laporan Harian</h2>
    <table border="1">
        <tr>
            <th>Tanggal</th>
            <th>Total Penjualan</th>
            <th>Kasir</th>
        </tr>
        <?php while ($row = $result_harian->fetch_assoc()): ?>
            <tr>
                <td><?= $row['tanggal_penjualan']; ?></td>
                <td>Rp <?= number_format($row['total_penjualan'], 2); ?></td>
                <td><?= $row['nama_kasir']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Tabel Laporan Bulanan -->
    <h2>Laporan Bulanan</h2>
    <table border="1">
        <tr>
            <th>Bulan</th>
            <th>Total Penjualan</th>
            <th>Kasir</th>
        </tr>
        <?php while ($row = $result_bulanan->fetch_assoc()): ?>
            <tr>
                <td><?= $row['bulan_penjualan']; ?></td>
                <td>Rp <?= number_format($row['total_penjualan'], 2); ?></td>
                <td><?= $row['nama_kasir']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
 <!-- Tombol Kembali ke Homepage -->
 <a href="homepage.php">
        <button style="background-color: #b3daff; color: #005f99; padding: 8px 14px; border: none; border-radius: 6px; cursor: pointer; margin-top: 10px;">Kembali ke Homepage</button>
    </a>
</body>
</html>
