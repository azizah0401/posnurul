<?php
session_start();
require "./koneksi.php";

// Edit item di keranjang
if (isset($_POST['simpan_edit'])) {
    $id_edit = $_POST['id_produk'];
    $jumlah_baru = $_POST['jumlah'];
    $harga_baru = $_POST['harga'];

    foreach ($_SESSION['keranjang'] as $key => $item) {
        if ($item['id_produk'] == $id_edit) {
            $_SESSION['keranjang'][$key]['jumlah'] = $jumlah_baru;
            $_SESSION['keranjang'][$key]['harga'] = $harga_baru;
            $_SESSION['keranjang'][$key]['total'] = $harga_baru * $jumlah_baru;
            break;
        }
    }
}

// Hapus item dari keranjang
if (isset($_POST['hapus_item']) && isset($_POST['id_produk'])) {
    $id_hapus = $_POST['id_produk'];
    foreach ($_SESSION['keranjang'] as $key => $item) {
        if ($item['id_produk'] == $id_hapus) {
            unset($_SESSION['keranjang'][$key]);
            $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
            break;
        }
    }
}

// Tambah produk baru ke keranjang dan database
if (isset($_POST['tambah_keranjang'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];

    // Cek apakah produk ada di database dan stok cukup
    $query_check_produk = "SELECT nama_produk, harga, stok FROM produk WHERE id_produk = '$id_produk' AND stok >= '$jumlah'";
    $result = mysqli_query($koneksi, $query_check_produk);

    if (mysqli_num_rows($result) > 0) {
        $produk = mysqli_fetch_assoc($result);

        // Produk ditemukan dan stok cukup, tambahkan ke keranjang
        $_SESSION['keranjang'][] = [
            'id_produk' => $id_produk,
            'nama_produk' => $produk['nama_produk'],
            'harga' => $produk['harga'],
            'jumlah' => $jumlah,
            'total' => $produk['harga'] * $jumlah
        ];
    } else {
        echo "Stok tidak mencukupi atau produk tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="penjualan.css">
    <title>Transaksi Kasir</title>
</head>
<body>
    <h2>Tambah Produk ke Keranjang</h2>

    <form method="POST">
        <label for="produk">Pilih Produk:</label>
        <select name="id_produk" id="produk" required>
            <?php
            // Menampilkan daftar produk yang ada di tabel produk
            $query_produk = "SELECT id_produk, nama_produk, stok FROM produk WHERE stok > 0";
            $result_produk = mysqli_query($koneksi, $query_produk);

            while ($produk = mysqli_fetch_assoc($result_produk)) {
                echo "<option value='{$produk['id_produk']}'>".$produk['nama_produk']." - Stok: ".$produk['stok']."</option>";
            }
            ?>
        </select>

        <label for="jumlah">Jumlah:</label>
        <input type="number" name="jumlah" id="jumlah" min="1" required>

        <button type="submit" name="tambah_keranjang">Tambah ke Keranjang</button>
    </form>

    <h2>Keranjang</h2>
    <table border="1">
        <tr>
            <th>Nama Produk</th><th>Harga</th><th>Jumlah</th><th>Total</th><th>Aksi</th>
        </tr>
        <?php
        $total_harga = 0;
        if (!empty($_SESSION['keranjang'])):
            foreach ($_SESSION['keranjang'] as $item):
                $total_harga += $item['total'];
        ?>
            <tr>
                <td><?= $item['nama_produk']; ?></td>
                <td><?= $item['harga']; ?></td>
                <td><?= $item['jumlah']; ?></td>
                <td><?= $item['total']; ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id_produk" value="<?= $item['id_produk']; ?>">
                        <button type="submit" name="hapus_item">Hapus</button>
                    </form>

                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id_produk" value="<?= $item['id_produk']; ?>">
                        <button type="submit" name="edit_item">Edit</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="5">Keranjang kosong</td></tr>
        <?php endif; ?>
    </table>
    
    <?php
    // Form edit keranjang
    if (isset($_POST['edit_item'])) {
        $id_edit = $_POST['id_produk'];
        foreach ($_SESSION['keranjang'] as $item) {
            if ($item['id_produk'] == $id_edit) {
                ?>
                <h2>Edit Produk di Keranjang</h2>
                <form method="POST">
                    <input type="hidden" name="id_produk" value="<?= $item['id_produk']; ?>">
                    <label>Nama: <?= $item['nama_produk']; ?></label><br>
                    <label>Harga: </label><input type="number" name="harga" value="<?= $item['harga']; ?>" required><br>
                    <label>Jumlah: </label><input type="number" name="jumlah" value="<?= $item['jumlah']; ?>" required><br>
                    <button type="submit" name="simpan_edit">Simpan Perubahan</button>
                </form>
                <?php
                break;
            }
        }
    }
    ?>
    
    <?php if (!empty($_SESSION['keranjang'])): ?>
        <h2>Pembayaran</h2>

        <form method="POST" action="prosestransaksi.php">
            <label>Total: </label>
            <input type="number" id="total" name="total" readonly value="<?= $total_harga; ?>"><br>
            <label>Bayar: </label>
            <input type="number" id="bayar" name="bayar" required min="<?= $total_harga; ?>"><br>
            <label>Kembalian: </label>
            <input type="text" id="kembalian" readonly><br>
            <button type="submit" name="checkout">Bayar 💸</button>
        </form>

        <script>
            document.getElementById('bayar').addEventListener('input', function () {
                var total = parseFloat(document.getElementById('total').value);
                var bayar = parseFloat(this.value);
                var kembalian = bayar - total;

                document.getElementById('kembalian').value = (kembalian >= 0) ? "Rp " + kembalian.toFixed(2) : '';
            });
        </script>
    <?php endif; ?>

    <!-- Tombol Kembali ke Homepage -->
    <a href="homepage.php">
        <button style="background-color: #b3daff; color: #005f99; padding: 8px 14px; border: none; border-radius: 6px; cursor: pointer; margin-top: 10px;">Kembali ke Homepage</button>
    </a>
</body>
</html>
