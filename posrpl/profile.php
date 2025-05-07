<?php
session_start();
require "./koneksi.php";

// Mengecek apakah kasir sudah login
if (!isset($_SESSION['nama_kasir'])) {
    header('Location: index.php'); // jika belum login, redirect ke login
    exit;
}

$nama_kasir = $_SESSION['nama_kasir'];
$query = "SELECT * FROM akun WHERE nama_kasir = '$nama_kasir' LIMIT 1";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);

// Jika tombol simpan ditekan
if (isset($_POST['submit'])) {
    $nama_kasir_baru = $_POST['nama_kasir'];
    $password_baru = $_POST['pass'];
    $no_hp = $_POST['no_hp'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $alamat = $_POST['alamat'];

    // Update database
    $update_query = "UPDATE akun SET 
                     nama_kasir = '$nama_kasir_baru', 
                     pass = '$password_baru', 
                     no_hp = '$no_hp', 
                     tgl_lahir = '$tgl_lahir', 
                     alamat = '$alamat' 
                     WHERE id_user = ".$row['id_user'];

    if (mysqli_query($koneksi, $update_query)) {
        $_SESSION['nama_kasir'] = $nama_kasir_baru; // Update session nama kasir
        echo "Profile berhasil diperbarui!";
    } else {
        echo "Gagal memperbarui profile!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile.css">
    <title>Edit Profile Kasir</title>
</head>
<body>
    <h2>Edit Profile Kasir</h2>
    <form method="POST" action="">
        <label for="nama_kasir">Nama Kasir:</label>
        <input type="text" name="nama_kasir" value="<?= $row['nama_kasir']; ?>" required><br><br>
        
        <label for="pass">Password:</label>
        <input type="password" name="pass" value="<?= $row['pass']; ?>" required><br><br>
        
        <label for="no_hp">Nomor HP:</label>
        <input type="text" name="no_hp" value="<?= $row['no_hp']; ?>"><br><br>

        <label for="tgl_lahir">Tanggal Lahir:</label>
        <input type="date" name="tgl_lahir" value="<?= $row['tgl_lahir']; ?>"><br><br>

        <label for="alamat">Alamat:</label>
        <textarea name="alamat"><?= $row['alamat']; ?></textarea><br><br>

        <button type="submit" name="submit">Simpan Perubahan</button>
    </form>
    <!-- Tombol Kembali ke Homepage -->
    <a href="homepage.php">
        <button style="background-color: #b3daff; color: #005f99; padding: 8px 14px; border: none; border-radius: 6px; cursor: pointer; margin-top: 10px;">Kembali ke Homepage</button>
    </a>
</body>
</html>
