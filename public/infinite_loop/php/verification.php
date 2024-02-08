<?php
header('X-Frame-Options: DENY');
session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'use_only_cookies' => true,
]);
require __DIR__ . '/../../../nonpublic/vendor/autoload.php';
require 'config_verification.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$host_db = DB_HOST;
$user_db = DB_USER;
$pass_db = DB_PASS;
$nama_db = DB_NAME;
$tabel_pengguna = "tb_login_bc";
$koneksi = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$error_message = "";
$success_message = "";

// Logika untuk mengirim ulang kode
if (isset($_POST['resend_code'])) {
    $email_resend = isset($_POST['email_resend']) ? $_POST['email_resend'] : '';

    if (empty($email_resend)) {
        $error_message = "Email is empty";
    } else {
        // Pengecekan apakah email ada di database
        $sqlCheckEmailExist = "SELECT * FROM $tabel_pengguna WHERE email = ?";
        $stmtCheckEmailExist = mysqli_prepare($koneksi, $sqlCheckEmailExist);
        mysqli_stmt_bind_param($stmtCheckEmailExist, "s", $email_resend);
        mysqli_stmt_execute($stmtCheckEmailExist);
        $resultCheckEmailExist = mysqli_stmt_get_result($stmtCheckEmailExist);

        if (!$resultCheckEmailExist || mysqli_num_rows($resultCheckEmailExist) === 0) {
            $error_message = "Email tidak terdaftar di sistem kami.";
        } else {
            // Pengecekan apakah pengguna sudah terverifikasi sebelumnya
            $sqlCheckUserVerified = "SELECT * FROM $tabel_pengguna WHERE email = ? AND status = 'verified'";
            $stmtCheckUserVerified = mysqli_prepare($koneksi, $sqlCheckUserVerified);
            mysqli_stmt_bind_param($stmtCheckUserVerified, "s", $email_resend);
            mysqli_stmt_execute($stmtCheckUserVerified);
            $resultCheckUserVerified = mysqli_stmt_get_result($stmtCheckUserVerified);

            if ($resultCheckUserVerified && mysqli_num_rows($resultCheckUserVerified) > 0) {
                $error_message = "Email sudah terverifikasi sebelumnya.";
            } else {
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
                    $mail->Host = SMTP_HOST;
                    $mail->SMTPAuth = true;
                    $mail->Username = SMTP_USERNAME;
                    $mail->Password = SMTP_PASSWORD;
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port = SMTP_PORT;

                    // Pengaturan email
                    $mail->setFrom(MAIL_FROM, 'Code'); // Ganti dengan alamat email dan nama Anda
                    $mail->addAddress($email_resend); // Alamat email pengguna
                    $mail->Subject = 'Resend Code Verification';
                    $mail->Body = "Kode verifikasi Anda: $otp_code  Code OTP akan kadaluarsa dalam 2 menit";

                    // Kirim email
                    $mail->send();

                    // Perbarui informasi sesi verifikasi di tabel pengguna
                    $sqlUpdateVerification = "UPDATE $tabel_pengguna SET otp_code = ?, otp_expiration = ? WHERE email = ?";
                    $stmtUpdateVerification = mysqli_prepare($koneksi, $sqlUpdateVerification);
                    mysqli_stmt_bind_param($stmtUpdateVerification, "sss", $otp_code, $expiration_time, $email_resend);
                    mysqli_stmt_execute($stmtUpdateVerification);

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
    }
}

// Logika untuk memproses verifikasi
if (isset($_POST['verify'])) {
    $otp_code = $_POST['otp_code'];
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    if (empty($email)) {
        $error_message = "Email is empty";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Validasi format email
        $error_message = "Format email tidak valid.";
    } elseif (!preg_match('/^\d{6}$/', $otp_code)) {
        // Validasi panjang OTP harus 6 angka
        $error_message = "Kode OTP harus terdiri dari 6 angka.";
    } else {
        // Pengecekan apakah email ada di database
        $sqlCheckEmailExist = "SELECT * FROM $tabel_pengguna WHERE email = ?";
        $stmtCheckEmailExist = mysqli_prepare($koneksi, $sqlCheckEmailExist);
        mysqli_stmt_bind_param($stmtCheckEmailExist, "s", $email);
        mysqli_stmt_execute($stmtCheckEmailExist);
        $resultCheckEmailExist = mysqli_stmt_get_result($stmtCheckEmailExist);

        if (!$resultCheckEmailExist || mysqli_num_rows($resultCheckEmailExist) === 0) {
            $error_message = "Email tidak terdaftar di sistem kami.";
        } else {
            // Pengecekan apakah pengguna sudah terverifikasi sebelumnya
            $sqlCheckUserVerified = "SELECT * FROM $tabel_pengguna WHERE email = ? AND status = 'verified'";
            $stmtCheckUserVerified = mysqli_prepare($koneksi, $sqlCheckUserVerified);
            mysqli_stmt_bind_param($stmtCheckUserVerified, "s", $email);
            mysqli_stmt_execute($stmtCheckUserVerified);
            $resultCheckUserVerified = mysqli_stmt_get_result($stmtCheckUserVerified);

            if ($resultCheckUserVerified && mysqli_num_rows($resultCheckUserVerified) > 0) {
                // Pengguna sudah diverifikasi sebelumnya
                $error_message = "Email sudah diverifikasi sebelumnya.";
            } else {
                $sqlCheckOTP = "SELECT * FROM $tabel_pengguna WHERE email = ? AND otp_code = ? AND otp_expiration > NOW()";
                $stmtCheckOTP = mysqli_prepare($koneksi, $sqlCheckOTP);
                mysqli_stmt_bind_param($stmtCheckOTP, "ss", $email, $otp_code);
                mysqli_stmt_execute($stmtCheckOTP);
                $resultCheckOTP = mysqli_stmt_get_result($stmtCheckOTP);

                if ($resultCheckOTP && mysqli_num_rows($resultCheckOTP) > 0) {
                    $sqlUpdateVerificationStatus = "UPDATE $tabel_pengguna SET status = 'verified' WHERE email = ?";
                    $stmtUpdateVerificationStatus = mysqli_prepare($koneksi, $sqlUpdateVerificationStatus);
                    mysqli_stmt_bind_param($stmtUpdateVerificationStatus, "s", $email);
                    mysqli_stmt_execute($stmtUpdateVerificationStatus);

                    if ($stmtUpdateVerificationStatus) {
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
    }
    $otp_code = $email = "";
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../login-register/fonts/linearicons/style.css">
    <link rel="stylesheet" href="../login-register/css/style.css">
    <link rel="icon" type="image/png" href="../img/favicon.ico" />
    <style>
        /* Tambahkan gaya untuk pesan kesalahan dan sukses */
        .error-message,
        .success-message {
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
            <img src="../login-register/images/image-1.png" alt="" class="image-1">

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

            <img src="../login-register/images/image-2.png" alt="" class="image-2">
        </div>
    </div>
    <footer class="text-center small tm-footer">
        <p class="mb-0">
            Copyright &copy; 2023 TechForge Academy</p>
    </footer>

    <script src="../login-register/js/jquery-3.3.1.min.js"></script>
    <script src="../login-register/js/main.js"></script>

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