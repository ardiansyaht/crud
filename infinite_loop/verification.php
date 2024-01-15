<?php
session_start();
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$host_db        = "localhost";
$user_db        = "root";
$pass_db        = "";
$nama_db        = "crud";
$tabel_pengguna = "tb_login_bc";
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$error_message = "";
$success_message = "";

// Logika untuk mengirim ulang kode
if (isset($_POST['resend_code'])) {
    $email_resend = isset($_POST['email_resend']) ? $_POST['email_resend'] : '';

    if (empty($email_resend)) {
        $error_message = "Email is empty";
    } else {
        // Logika pengiriman ulang kode verifikasi ke email tertentu
        // ...

        // Contoh pesan sukses
        $success_message = "Kode verifikasi telah dikirim ulang ke email Anda.";

        // Tambahkan logika pengiriman ulang kode verifikasi ke email
        $otp_code = rand(100000, 999999);
        date_default_timezone_set('Asia/Jakarta');
        $expiration_time = date('Y-m-d H:i:s', strtotime('+2 minutes'));

        $mail = new PHPMailer(true);

        try {
            // Pengaturan server SMTP Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ardiansyah3151@gmail.com'; // Ganti dengan alamat email Gmail Anda
            $mail->Password = 'ecesgskgnryehfim'; // Ganti dengan kata sandi Gmail Anda
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // Pengaturan email
            $mail->setFrom('ardiansyah3151@gmail.com', 'Code'); // Ganti dengan alamat email dan nama Anda
            $mail->addAddress($email_resend); // Alamat email pengguna
            $mail->Subject = 'Resend Code Verification';
            $mail->Body = "Kode verifikasi Anda: $otp_code  Code OTP akan kadaluarsa dalam 2 menit";

            // Kirim email
            $mail->send();

            // Perbarui informasi sesi verifikasi di tabel pengguna
            $sqlUpdateVerification = "UPDATE $tabel_pengguna SET otp_code = '$otp_code', otp_expiration = '$expiration_time' WHERE email = '$email_resend'";
            mysqli_query($koneksi, $sqlUpdateVerification);

            // Set email ke dalam sesi
            $_SESSION['email'] = $email_resend;

            $success_message = "Kode verifikasi telah dikirim ulang ke email Anda.";

            // Kosongkan field setelah berhasil mendaftar
            header("location: verification.php");
            $newUsername = $phone_number = $email = $newPassword = $confirmPassword = "";
        } catch (Exception $e) {
            $error_message = "Gagal mengirim email verifikasi. {$mail->ErrorInfo}";
        }
    }
}

// Logika untuk memproses verifikasi
if (isset($_POST['verify'])) {
    $otp_code = $_POST['otp_code'];
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    if (empty($email)) {
        $error_message = "Email is empty";
    } else {
        $sqlCheckOTP = "SELECT * FROM $tabel_pengguna WHERE email = '$email' AND otp_code = '$otp_code' AND otp_expiration > NOW()";

        $resultCheckOTP = mysqli_query($koneksi, $sqlCheckOTP);

        if ($resultCheckOTP && mysqli_num_rows($resultCheckOTP) > 0) {
            $sqlUpdateVerificationStatus = "UPDATE $tabel_pengguna SET status = 'verified' WHERE email = '$email'";
            $resultUpdateVerificationStatus = mysqli_query($koneksi, $sqlUpdateVerificationStatus);

            if ($resultUpdateVerificationStatus) {
                // Redirect ke halaman login jika verifikasi berhasil
                header("location: login_bc.php");
                exit();
            } else {
                // Gagal memperbarui status verifikasi
                $error_message = "Gagal memperbarui status verifikasi pengguna. Silakan coba lagi.";
            }
        } else {
            // OTP tidak valid atau sudah kedaluwarsa
            $error_message = "Kode OTP tidak valid atau sudah kedaluwarsa. Silakan coba lagi.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login-register/fonts/linearicons/style.css">
    <link rel="stylesheet" href="login-register/css/style.css">
    <style>
        /* Tambahkan gaya untuk pesan kesalahan dan sukses */
        .error-message, .success-message {
            color: #FF0000;
            font-size: 14px;
            border: 1px solid #FF0000;
            padding: 5px;
            margin-top: 5px;
            border-radius: 5px;
        }

        .success-message {
            color: #008000;
            border: 1px solid #008000;
        }

        /* Tambahkan gaya untuk menyembunyikan formulir pengiriman ulang kode secara default */
        #resendForm {
            display: none;
        }
    </style>
    <title>Bootcamp</title>
</head>
<body>

<div class="wrapper">
    <div class="inner">
        <img src="login-register/images/image-1.png" alt="" class="image-1">

        <form action="" method="post" id="verificationForm">
            <h3>Verification Code</h3>
            <!-- Menambahkan input field untuk email -->
            <div class="form-holder">
                <span class="lnr lnr-envelope"></span>
                <input type="text" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-holder">
                <span class="lnr lnr-lock"></span>
                <input type="number" class="form-control" name="otp_code" placeholder="Verification Code" required>
            </div>
            
            <!-- Tampilkan pesan kesalahan di sekitar kotak input -->
            <?php if (!empty($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Tampilkan pesan sukses jika ada -->
            <?php if (!empty($success_message)) : ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <button type="submit" name="verify">
                <span>Verify</span>
            </button>
            
            <!-- Footer Section -->
            <div class="footer">
                <div class="left-footer">
                    <p style="color: #808080;">
                        <a href="#" id="resendLink" class="sign-up-link" style="color: #808080;">Resend Code</a>
                    </p>
                </div>
            </div>
        </form>

        <!-- Form untuk pengiriman ulang kode -->
        <form action="" method="post" id="resendForm">
            <h3>Resend Verification Code</h3>
            <div class="form-holder">
                <span class="lnr lnr-envelope"></span>
                <input type="text" class="form-control" name="email_resend" placeholder="Email" required>
            </div>
            
            <!-- Tampilkan pesan kesalahan di sekitar kotak input -->
            <?php if (!empty($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Tampilkan pesan sukses jika ada -->
            <?php if (!empty($success_message)) : ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <button type="submit" name="resend_code">
                <span>Resend Code</span>
            </button>
        </form>

        <img src="login-register/images/image-2.png" alt="" class="image-2">
    </div>
</div>

<script src="login-register/js/jquery-3.3.1.min.js"></script>
<script src="login-register/js/main.js"></script>

<script>
    document.getElementById('resendLink').addEventListener('click', function() {
        // Sembunyikan formulir verifikasi kode
        document.getElementById('verificationForm').style.display = 'none';
        
        // Tampilkan formulir pengiriman ulang kode
        document.getElementById('resendForm').style.display = 'block';
    });
</script>
</body>
</html>



