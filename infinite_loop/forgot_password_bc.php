<!-- forgot_password_bc.php -->

<?php
session_start();

$host_db        = "localhost";
$user_db        = "root";
$pass_db        = "";
$nama_db        = "crud";
$tabel_pengguna = "tb_login_bc";
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];

    $sqlCheckUser = "SELECT * FROM $tabel_pengguna WHERE email = '$email'";
    $resultCheckUser = mysqli_query($koneksi, $sqlCheckUser);

    if (mysqli_num_rows($resultCheckUser) > 0) {
        // Generate token untuk reset password
        $resetToken = bin2hex(random_bytes(32));

        // Simpan token dan waktu kedaluwarsa di database
        $sqlUpdateToken = "UPDATE $tabel_pengguna SET reset_token = '$resetToken', reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = '$email'";
        mysqli_query($koneksi, $sqlUpdateToken);

        // Kirim email reset password (sesuaikan dengan kebutuhan)
        $resetLink = "localhost/web 1/project-2/bootstrap/sb-admin/infinite_loop/reset_password_bc.php?token=$resetToken&email=$email";
        // Implementasikan fungsi kirim email di sini
        // ...

        // Tampilkan pesan sukses
        $success_message = "Link reset password telah dikirim ke email Anda.";
    } else {
        // Tampilkan pesan bahwa email tidak terdaftar
        $error_message = "Email tidak terdaftar.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
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
            <h3>Forgot Password</h3>

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
                <span class="lnr lnr-envelope"></span>
                <input type="text" class="form-control" name="email" placeholder="Email">
            </div>
            <button type="submit" name="forgot_password">
                <span>Reset Password</span>
            </button>
        </form>
        <img src="login-register/images/image-2.png" alt="" class="image-2">
    </div>
</div>

<script src="login-register/js/jquery-3.3.1.min.js"></script>
<script src="login-register/js/main.js"></script>
<!-- Tambahkan script JS sesuai kebutuhan -->

</body>
</html>
