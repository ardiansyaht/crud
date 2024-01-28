<?php
session_start();
require __DIR__ . '/../../../nonpublic/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        $sqlUpdateToken = "UPDATE $tabel_pengguna SET reset_token = '$resetToken', reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 MINUTE) WHERE email = '$email'";
        mysqli_query($koneksi, $sqlUpdateToken);

        // Kirim email reset password menggunakan PHPMailer
        $resetLink = "http://localhost/web-1/public/infinite_loop/php/reset_password_bc.php?token=$resetToken&email=$email";

        $mail = new PHPMailer(true);

        try {
            // Pengaturan server SMTP Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ardiansyah3151@gmail.com'; // Ganti dengan alamat email Gmail Anda
            $mail->Password = 'piatkorcdqlkieds'; // Ganti dengan kata sandi Gmail Anda
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // Pengaturan email
            $mail->setFrom('ardiansyah3151@gmail.com', 'bang al'); // Ganti dengan alamat email dan nama Anda
            $mail->addAddress($email); // Alamat email pengguna
            $mail->Subject = 'Reset Your Password';
            $mail->Body = "Click the following link to reset your password: $resetLink";

            // Aktifkan output debugging
            $mail->SMTPDebug = 0;

            // Kirim email
            $mail->send();

            // Tampilkan pesan sukses
            $success_message = "Link reset password telah dikirim ke email Anda.";
        } catch (Exception $e) {
            // Tampilkan pesan kesalahan jika email tidak dapat dikirim
            $error_message = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
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
    <link rel="stylesheet" href="../login-register/fonts/linearicons/style.css">
    <link rel="icon" type="image/png" href="../img/favicon.ico" />
    <link rel="stylesheet" href="../login-register/css/style.css">
</head>

<body>
    <div class="wrapper">
        <div class="inner">
            <img src="../login-register/images/image-1.png" alt="" class="image-1">
            <a href="homepage.php" class="back-link" style="color: #808080;"><span class="lnr lnr-arrow-left"></span> Back to Home</a>
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
                <p><a href="login_bc.php"><span class="lnr lnr-arrow-left"></span> Back to Login</a></p>
            </form>
            <img src="../login-register/images/image-2.png" alt="" class="image-2">
        </div>
    </div>

    <script src="../login-register/js/jquery-3.3.1.min.js"></script>
    <script src="../login-register/js/main.js"></script>
    <!-- Tambahkan script JS sesuai kebutuhan -->

</body>

</html>