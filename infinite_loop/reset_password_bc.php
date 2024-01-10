<!-- reset_password_bc.php -->

<?php
session_start();

$host_db        = "localhost";
$user_db        = "root";
$pass_db        = "";
$nama_db        = "crud";
$tabel_pengguna = "tb_login_bc";
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

if (isset($_POST['reset_password'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Periksa token dan waktu kedaluwarsa
    $sqlCheckToken = "SELECT * FROM $tabel_pengguna WHERE reset_token = '$token' AND reset_token_expires > NOW() AND email = '$email'";
    $resultCheckToken = mysqli_query($koneksi, $sqlCheckToken);

    if (mysqli_num_rows($resultCheckToken) > 0) {
        if (strlen($password) < 5) {
            $error_message = "Password harus terdiri dari minimal 5 karakter.";
        } elseif ($password != $confirmPassword) {
            $error_message = "Konfirmasi password tidak cocok.";
        } else {
            // Reset password
            $hashedPassword = sha1($password);
            $sqlResetPassword = "UPDATE $tabel_pengguna SET password = '$hashedPassword', reset_token = NULL, reset_token_expires = NULL WHERE reset_token = '$token' AND email = '$email'";
            mysqli_query($koneksi, $sqlResetPassword);

            // Tampilkan pesan sukses
            $success_message = "Password berhasil direset. Silakan login.";
        }
    } else {
        $error_message = "Token reset password tidak valid atau telah kedaluwarsa.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="login-register/fonts/linearicons/style.css">
    <link rel="stylesheet" href="login-register/css/style.css">
    <!-- Tambahkan CSS sesuai kebutuhan -->
</head>
<body>

<div class="wrapper">
    <div class="inner">
        <img src="login-register/images/image-1.png" alt="" class="image-1">
        <a href="index.php" class="back-link" style="color: #808080;"><span class="lnr lnr-arrow-left"></span> Back to Home</a>
        <form action="" method="post">
            <h3>Reset Password</h3>

            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php } elseif (isset($success_message)) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php } ?>
             
            <div class="form-holder">
                <span class="lnr lnr-lock"></span>
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-holder">
                <span class="lnr lnr-lock"></span>
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
            </div>
            <button type="submit" name="reset_password">
                <span>Reset Password</span>
            </button>
            <p><a href="login_bc.php"><span class="lnr lnr-arrow-left"></span> Back to Login</a></p> 
        </form>
        <img src="login-register/images/image-2.png" alt="" class="image-2">
    </div>
</div>

<script src="login-register/js/jquery-3.3.1.min.js"></script>
<script src="login-register/js/main.js"></script>
<!-- Tambahkan script JS sesuai kebutuhan -->

</body>
</html>

