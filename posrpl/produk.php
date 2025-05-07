<?php
session_start();
require "./koneksi.php";

// Fungsi untuk menambahkan produk
if (isset($_POST['tambah_produk'])) {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $query = "INSERT INTO produk (nama_produk, harga, stok) VALUES ('$nama_produk', $harga, $stok)";
    if ($koneksi->query($query) === TRUE) {
        echo "<script>alert('Produk berhasil ditambahkan!');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan produk: " . $koneksi->error . "');</script>";
    }
}

// Fungsi untuk mengedit produk
if (isset($_POST['edit_produk'])) {
    $id_produk = $_POST['id_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $query = "UPDATE produk SET nama_produk='$nama_produk', harga=$harga, stok=$stok WHERE id_produk=$id_produk";
    if ($koneksi->query($query)) {
        echo "<script>alert('Produk berhasil diupdate!');</script>";
    } else {
        echo "<script>alert('Gagal mengupdate produk: " . $koneksi->error . "');</script>";
    }
}

// Fungsi untuk menghapus produk
if (isset($_GET['hapus'])) {
    $id_produk = $_GET['hapus'];
    $query = "DELETE FROM produk WHERE id_produk=$id_produk";
    if ($koneksi->query($query)) {
        echo "<script>alert('Produk berhasil dihapus!');</script>";
    } else {
        echo "<script>alert('Gagal menghapus produk: " . $koneksi->error . "');</script>";
    }
}

// Ambil data produk dari database
$produk = $koneksi->query("SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="produkr.css">
    <title>produk</title>
</head>
<body>
    <h1>Produk</h1>
    <form method="POST">
        <input type="text" name="nama_produk" placeholder="Nama Produk" required>
        <input type="number" name="harga" placeholder="Harga" step="0.01" required>
        <input type="number" name="stok" placeholder="Stok" required>
        <button type="submit" name="tambah_produk">Tambah Produk</button>
    </form>

    <!-- Tabel Daftar Produk -->
    <h2>Daftar Produk</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $produk->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id_produk']; ?></td>
                <td><?php echo $row['nama_produk']; ?></td>
                <td>Rp <?php echo number_format($row['harga'], 2); ?></td>
                <td><?php echo $row['stok']; ?></td>
                <td>
                    <a href="?edit=<?php echo $row['id_produk']; ?>">Edit</a>
                    <a href="?hapus=<?php echo $row['id_produk']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <!-- Form Edit Produk -->
    <?php if (isset($_GET['edit'])): ?>
        <?php
        $id_produk = $_GET['edit'];
        $query = "SELECT * FROM produk WHERE id_produk=$id_produk";
        $result = $koneksi->query($query);
        $row = $result->fetch_assoc();
        ?>
        <h2>Edit Produk</h2>
        <form method="POST">
            <input type="hidden" name="id_produk" value="<?php echo $row['id_produk']; ?>">
            <input type="text" name="nama_produk" value="<?php echo $row['nama_produk']; ?>" required>
            <input type="number" name="harga" value="<?php echo $row['harga']; ?>" step="0.01" required>
            <input type="number" name="stok" value="<?php echo $row['stok']; ?>" required>
            <button type="submit" name="edit_produk">Update Produk</button>
        </form>
    <?php endif; ?>

    <!-- Tombol Kembali ke Homepage -->
    <a href="homepage.php">
        <button style="background-color: #b3daff; color: #005f99; padding: 8px 14px; border: none; border-radius: 6px; cursor: pointer; margin-top: 10px;">Kembali ke Homepage</button>
    </a>
</body>
</html>