<?php
header('X-Frame-Options: DENY');
session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'use_only_cookies' => true,
]);
require __DIR__ . '/../../../nonpublic/vendor/autoload.php';
require 'config_forgot.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$host_db        = DB_HOST;
$user_db        = DB_USER;
$pass_db        = DB_PASS;
$nama_db        = DB_NAME;
$tabel_pengguna = "tb_login_bc";
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];

    // Validasi email (tambahkan validasi sesuai kebutuhan)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid.";
    } else {
        $sqlCheckUser = "SELECT * FROM $tabel_pengguna WHERE email = ?";
        $stmtCheckUser = mysqli_prepare($koneksi, $sqlCheckUser);
        mysqli_stmt_bind_param($stmtCheckUser, "s", $email);
        mysqli_stmt_execute($stmtCheckUser);
        $resultCheckUser = mysqli_stmt_get_result($stmtCheckUser);

        if (mysqli_num_rows($resultCheckUser) > 0) {
            // Generate token untuk reset password
            $resetToken = bin2hex(random_bytes(32));

            date_default_timezone_set('Asia/Jakarta');
            $resetTokenExpires = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            $sqlUpdateToken = "UPDATE $tabel_pengguna SET reset_token = ?, reset_token_expires = ? WHERE email = ?";
            $stmtUpdateToken = mysqli_prepare($koneksi, $sqlUpdateToken);
            mysqli_stmt_bind_param($stmtUpdateToken, "sss", $resetToken, $resetTokenExpires, $email);
            mysqli_stmt_execute($stmtUpdateToken);

            // Kirim email reset password menggunakan PHPMailer
            $resetLink = "http://localhost/web-1/project-2/public/infinite_loop/php/reset_password_bc.php?token=$resetToken&email=$email";

            $mail = new PHPMailer(true);

            try {
                // Pengaturan server SMTP Gmail
                $mail->isSMTP();
                $mail->Host = SMTP_HOST;
                $mail->SMTPAuth = true;
                $mail->Username = SMTP_USERNAME;
                $mail->Password = SMTP_PASSWORD;
                $mail->SMTPSecure = 'ssl';
                $mail->Port = SMTP_PORT;

                // Pengaturan email
                $mail->setFrom(MAIL_FROM, 'bang al');
                $mail->addAddress($email);
                $mail->Subject = 'Reset Your Password';
                $mail->Body = "Click the following link to reset your password: $resetLink";

                // Aktifkan output debugging
                $mail->SMTPDebug = 0;

                // Kirim email
                $mail->send();

                // Tampilkan pesan sukses
                $success_message = "Link reset password telah dikirim ke email Anda. Bila tidak ada di kotak pesan silahkan cek di spam";
            } catch (Exception $e) {
                // Tampilkan pesan kesalahan jika email tidak dapat dikirim
                $error_message = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
            }
        } else {
            // Tampilkan pesan bahwa email tidak terdaftar
            $error_message = "Email tidak terdaftar.";
        }

        mysqli_stmt_close($stmtCheckUser);
        mysqli_stmt_close($stmtUpdateToken);
    }
}

// Menutup koneksi
mysqli_close($koneksi);
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
    <footer class="text-center small tm-footer">
        <p class="mb-0">
            Copyright &copy; 2023 TechForge Academy</p>
    </footer>

    <script src="../login-register/js/jquery-3.3.1.min.js"></script>
    <script src="../login-register/js/main.js"></script>
    <!-- Tambahkan script JS sesuai kebutuhan -->

</body>

</html>