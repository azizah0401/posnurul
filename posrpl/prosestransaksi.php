<?php
session_start();
require 'koneksi.php';

if (isset($_POST['checkout']) && !empty($_SESSION['keranjang'])) {
    $total = $_POST['total'];
    $tanggal = date('Y-m-d');
    $id_pelanggan = 1; // Bisa disesuaikan
    $nama_kasir = $_SESSION['nama_kasir'];  // Nama kasir dari session

    // Simpan ke tabel penjualan
    $query = "INSERT INTO penjualan (tanggal_penjualan, total_harga, id_pelanggan)
              VALUES ('$tanggal', $total, $id_pelanggan)";
    mysqli_query($koneksi, $query);
    $id_penjualan = mysqli_insert_id($koneksi);

    foreach ($_SESSION['keranjang'] as $item) {
        $id_produk = $item['id_produk'];
        $jumlah = $item['jumlah'];
        $subtotal = $item['total'];

        // Simpan ke detail penjualan
        $query_detail = "INSERT INTO detail_penjualan (id_produk, jumlah, id_penjualan, subtotal)
                         VALUES ($id_produk, $jumlah, $id_penjualan, $subtotal)";
        mysqli_query($koneksi, $query_detail);

        // Kurangi stok
        $update_stok = "UPDATE produk SET stok = stok - $jumlah WHERE id_produk = $id_produk";
        mysqli_query($koneksi, $update_stok);
    }

    // Simpan ke laporan harian
    $query_laporan_harian = "INSERT INTO laporan_harian (total_penjualan, tanggal_penjualan, nama_kasir, id_user)
                             VALUES ('$total', '$tanggal', '$nama_kasir', '$id_penjualan')";
    mysqli_query($koneksi, $query_laporan_harian);

    // Simpan ke laporan bulanan
    $bulan = date('Y-m-d');
    $query_laporan_bulanan = "INSERT INTO laporan_bulanan (total_penjualan, bulan_penjualan, nama_kasir, id_user)
                              VALUES ('$total', '$bulan', '$nama_kasir', '$id_penjualan')";
    mysqli_query($koneksi, $query_laporan_bulanan);

    // Bersihkan keranjang
    unset($_SESSION['keranjang']);

    // Redirect ke struk
    echo "<script>
        alert('Transaksi berhasil disimpan!');
        window.location.href = 'struk.php?id=$id_penjualan';
    </script>";
} else {
    echo "<script>
        alert('Keranjang kosong!');
        window.location.href='transaksi.php';
    </script>";
}
?>
