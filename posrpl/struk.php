<?php
// index
require "./koneksi.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];  
    $password = $_POST['password'];  

    $query = "SELECT * FROM akun WHERE nama_kasir = '$username' AND pass = '$password'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $_SESSION['pass'] = $row['pass'];
        $_SESSION['nama_kasir'] = $row['nama_kasir'];

        header ("Location: homepage.php");
        exit();
    } else {
        echo "<script>alert('Login gagal! Username atau password salah.'); window.location.href='index.php';</script>";
    }
}

?>
<?php
require 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID Penjualan tidak ditemukan.";
    exit;
}

$id_penjualan = $_GET['id'];

// Ambil data penjualan
$query_penjualan = "SELECT * FROM penjualan WHERE id_penjualan = $id_penjualan";
$result_penjualan = mysqli_query($koneksi, $query_penjualan);
$penjualan = mysqli_fetch_assoc($result_penjualan);

// Cek jika penjualan tidak ditemukan
if (!$penjualan) {
    echo "Data penjualan tidak ditemukan.";
    exit;
}

// Ambil detail produk yang dibeli
$query_detail = "SELECT dp.*, p.nama_produk, p.harga
                 FROM detail_penjualan dp
                 JOIN produk p ON dp.id_produk = p.id_produk
                 WHERE dp.id_penjualan = $id_penjualan";
$result_detail = mysqli_query($koneksi, $query_detail);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 300px;
            margin: auto;
        }
        .center {
            text-align: center;
        }
        hr {
            border: 1px solid #000;
        }
        table {
            width: 100%;
            font-size: 14px;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 4px 0;
        }
        .total {
            font-weight: bold;
            text-align: right;
            font-size: 16px;
        }
        .qr {
            text-align: center;
            margin-top: 10px;
        }
        .kasir {
            text-align: center;
            margin-top: 5px;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>

    <h2 class="center">🛒 venezia store</h2>
    <p class="center">Jl. Mangga dua No. 123<br>Telp: 0812-3456-7890</p>
    <hr>

    <p><strong>Tanggal:</strong> <?= $penjualan['tanggal_penjualan'] ?></p>
    <p><strong>ID Penjualan:</strong> <?= $penjualan['id_penjualan'] ?></p>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Sub</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = mysqli_fetch_assoc($result_detail)): ?>
                <tr>
                    <td><?= $item['nama_produk'] ?></td>
                    <td><?= $item['jumlah'] ?></td>
                    <td><?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td><?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <hr>
    <p class="total">Total: Rp <?= number_format($penjualan['total_harga'], 0, ',', '.') ?></p>
    <hr>
    <p class="center">Terima kasih telah berbelanja!</p>

    <div class="qr">
        <img src="qr-code.png" alt="QRIS" width="150"><br>
        <strong>QRIS</strong>
    </div>

    <p class="kasir">Kasir: <?= $_SESSION['nama_kasir'] ?></p>

    <div class="print-button center">
        <button onclick="window.print()">Cetak PDF / Print</button><br><br>
        <a href="transaksi.php"><button>Kembali ke Transaksi</button></a>
    </div>

</body>
</html>
