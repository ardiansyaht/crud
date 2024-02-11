<?php
header('X-Frame-Options: DENY');
session_start();
require __DIR__ . '/../../../nonpublic/vendor/autoload.php';
require 'config_register.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$host_db        = DB_HOST;
$user_db        = DB_USER;
$pass_db        = DB_PASS;
$nama_db        = DB_NAME;
$tabel_pengguna = "tb_login_bc";
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$success_msg      = "";
$err              = "";
$username         = "";
$phone_number     = "";
$email            = "";
$newPassword      = "";
$confirmPassword  = "";

function deleteUnverifiedAccounts($koneksi)
{
    $current_time = date('Y-m-d H:i:s');
    date_default_timezone_set('Asia/Jakarta');
    $two_minutes_ago = date('Y-m-d H:i:s', strtotime('-1 hour'));
    $sql = "DELETE FROM tb_login_bc WHERE status = 'notverified' AND registration_time < ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "s", $two_minutes_ago);
    mysqli_stmt_execute($stmt);
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validasi form di sisi klien
    if (empty($username) || empty($phone_number) || empty($email) || empty($newPassword) || empty($confirmPassword)) {
        $err .= "<li>Silakan lengkapi semua kolom.</li>";
    } elseif (strlen($username) < 4 || !isValidInput($username)) {
        $err .= "<li>Username harus terdiri dari minimal 4 karakter dan hanya boleh mengandung karakter alfanumerik dan simbol (@, ., _, -).</li>";
    } elseif (!isValidInput($phone_number)) {
        $err .= "<li>Nomor telepon hanya boleh mengandung karakter angka.</li>";
    } elseif (strlen($newPassword) < 8 || !preg_match('/[a-z]/', $newPassword) || !preg_match('/[A-Z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword) || !preg_match('/[!@#$%^&*()_+]/', $newPassword)) {
        $err .= "<li>Kata sandi harus memenuhi persyaratan: minimal 8 karakter, 1 huruf kecil, 1 huruf besar, 1 angka, dan 1 simbol.</li>";
    } elseif ($newPassword !== $confirmPassword) {
        $err .= "<li>Konfirmasi kata sandi tidak cocok.</li>";
    } else {

        $recaptchaSecretKey = "6LceCFspAAAAAOiZ7XgAOMgIboFKgD0vsXwQb7Dn"; // Ganti dengan secret key reCAPTCHA Anda
        $recaptchaResponse = $_POST['g-recaptcha-response'];

        $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecretKey&response=$recaptchaResponse";
        $recaptchaData = json_decode(file_get_contents($recaptchaUrl));

        if (!$recaptchaData->success) {
            $err .= "<li>Validasi reCAPTCHA gagal. Silakan coba lagi.</li>";
        } else {
            // Validasi form di sisi server
            $sqlCheckUser = "SELECT * FROM $tabel_pengguna WHERE username = ? OR email = ?";
            $stmtCheckUser = mysqli_prepare($koneksi, $sqlCheckUser);
            mysqli_stmt_bind_param($stmtCheckUser, "ss", $username, $email);
            mysqli_stmt_execute($stmtCheckUser);
            $resultCheckUser = mysqli_stmt_get_result($stmtCheckUser);

            if (mysqli_num_rows($resultCheckUser) > 0) {
                $err .= "<li>Username atau email sudah digunakan.</li>";
            } else {
                // Simpan data pengguna baru ke database
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $otp_code = rand(100000, 999999);
                date_default_timezone_set('Asia/Jakarta');
                $expiration_time = date('Y-m-d H:i:s', strtotime('+2 minutes')); // Waktu kedaluwarsa dalam contoh adalah 2 menit
                $registration_time = date('Y-m-d H:i:s'); // Tidak ada penambahan waktu di sini

                $sqlInsertUser = "INSERT INTO $tabel_pengguna (username, phone_number, email, password, otp_code, otp_expiration, status, registration_time) VALUES (?, ?, ?, ?, ?, ?, 'notverified', ?)";
                $stmtInsertUser = mysqli_prepare($koneksi, $sqlInsertUser);
                mysqli_stmt_bind_param($stmtInsertUser, "sssssss", $username, $phone_number, $email, $hashedPassword, $otp_code, $expiration_time, $registration_time);
                $resultInsertUser = mysqli_stmt_execute($stmtInsertUser);

                if ($resultInsertUser) {
                    // Kode OTP
                    $otp_code = rand(100000, 999999);
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
                        $mail->setFrom(MAIL_FROM, 'TechForge Academy');
                        $mail->addAddress($email);
                        $mail->Subject = 'Code Verification';
                        $mail->Body = "Kode verifikasi Anda: $otp_code  Code OTP akan kadaluarsa dalam 2 menit";

                        // Kirim email
                        $mail->send();

                        // Perbarui informasi sesi verifikasi di tabel pengguna
                        $sqlUpdateVerification = "UPDATE $tabel_pengguna SET otp_code = ?, otp_expiration = ? WHERE email = ?";
                        $stmtUpdateVerification = mysqli_prepare($koneksi, $sqlUpdateVerification);
                        mysqli_stmt_bind_param($stmtUpdateVerification, "sss", $otp_code, $expiration_time, $email);
                        mysqli_stmt_execute($stmtUpdateVerification);

                        // Set email ke dalam sesi
                        $_SESSION['email'] = $email;

                        $success_msg = "Akun berhasil dibuat. Silakan cek email Anda untuk kode verifikasi.";
                        // Kosongkan field setelah berhasil mendaftar
                        header("location: verification.php");
                        exit();
                    } catch (Exception $e) {
                        $err .= "<li>Gagal mengirim email verifikasi. {$mail->ErrorInfo}</li>";
                    }
                } else {
                    $err .= "<li>Gagal menyimpan data pengguna.</li>";
                }
            }
        }
    }
}
function isValidInput($input)
{
    $pola = '/^[a-zA-Z0-9@._-]+$/';
    return preg_match($pola, $input);
}

$userRole = isset($_SESSION['session_role']) ? $_SESSION['session_role'] : '';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bootcamp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/favicon.ico" />
    <link rel="stylesheet" href="../login-register/fonts/linearicons/style.css">
    <link rel="stylesheet" href="../login-register/css/style.css">
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>

<body>

    <div class="wrapper">
        <div class="inner">
            <img src="../login-register/images/image-1.png" alt="" class="image-1">


            <form action="" method="post">
                <h3>New Account?</h3>

                <?php if ($err) { ?>
                    <div id="register-alert" class="alert alert-danger col-sm-12">
                        <ul><?php echo $err; ?></ul>
                    </div>
                <?php } ?>

                <div class="form-holder">
                    <span class="lnr lnr-user"></span>
                    <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>">
                </div>
                <div class="form-holder">
                    <span class="lnr lnr-phone-handset"></span>
                    <input type="number" class="form-control" name="phone_number" placeholder="Phone Number" value="<?php echo $phone_number; ?>">
                </div>
                <div class="form-holder">
                    <span class="lnr lnr-envelope"></span>
                    <input type="email" class="form-control" name="email" placeholder="Mail" value="<?php echo $email; ?>">
                </div>
                <div class="form-holder">
                    <span class="lnr lnr-lock"></span>
                    <input type="password" class="form-control" name="new_password" placeholder="Password">
                </div>
                <div class="form-holder">
                    <span class="lnr lnr-lock"></span>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                </div>
                <div class="g-recaptcha" data-sitekey="6LceCFspAAAAAE2ZLBwHhGBXA1lxMVOyMP_qG2BQ"></div>
                <button type="submit" name="register">
                    <span>Register</span>
                </button>
                <footer class="text-center small tm-footer">
                    <p class="mb-0">
                        Copyright &copy; 21552011105_KELOMPOK 1_TIFRP221PA_UASWEB1.</p>
                </footer>

            </form>



            <img src="../login-register/images/image-2.png" alt="" class="image-2">
        </div>
    </div>

    <script src="../login-register/js/jquery-3.3.1.min.js"></script>
    <script src="../login-registerjs/main.js"></script>
</body>

</html>