<?php // index
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="indexr.css">
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password">
        </div>
        <button type="submit" class="login-btn">Login</button>
    </form>
</div>

</body>
</html>
