<?php
session_start();
require __DIR__ . '/../../../bootstrap/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$host_db        = "localhost";
$user_db        = "root";
$pass_db        = "";
$nama_db        = "crud";
$tabel_pengguna = "tb_login_bc";
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$success_msg      = "";
$err              = "";
$newUsername      = "";
$phone_number     = "";
$email            = "";
$newPassword      = "";
$confirmPassword  = "";


function deleteUnverifiedAccounts($koneksi)
{
    $current_time = date('Y-m-d H:i:s');
    $two_minutes_ago = date('Y-m-d H:i:s', strtotime('-1 hour'));
    $sql = "DELETE FROM tb_login_bc WHERE status = 'notverified' AND registration_time < '$two_minutes_ago'";
    mysqli_query($koneksi, $sql);
}

if (isset($_POST['register'])) {
    $newUsername       = $_POST['new_username'];
    $phone_number      = $_POST['phone_number'];
    $email             = $_POST['email'];
    $newPassword       = $_POST['new_password'];
    $confirmPassword   = $_POST['confirm_password'];

    // Validasi form di sisi klien
    if (empty($newUsername) || empty($phone_number) || empty($email) || empty($newPassword) || empty($confirmPassword)) {
        $err .= "<li>Silakan lengkapi semua kolom.</li>";
    } elseif (strlen($newPassword) < 5) {
        $err .= "<li>Kata sandi harus terdiri dari minimal 5 karakter.</li>";
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
            $sqlCheckUser = "SELECT * FROM $tabel_pengguna WHERE username = '$newUsername' OR email = '$email'";
            $resultCheckUser = mysqli_query($koneksi, $sqlCheckUser);

            if (mysqli_num_rows($resultCheckUser) > 0) {
                $userRow = mysqli_fetch_assoc($resultCheckUser);

                if ($userRow['username'] == $newUsername) {
                    $err .= "<li>Username <b>$newUsername</b> sudah digunakan.</li>";
                }

                if ($userRow['email'] == $email) {
                    $err .= "<li>Email <b>$email</b> sudah digunakan.</li>";
                }
            } else {
                // Simpan data pengguna baru ke database
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $otp_code = rand(100000, 999999);
                date_default_timezone_set('Asia/Jakarta');
                $expiration_time = date('Y-m-d H:i:s', strtotime('+2 minutes')); // Waktu kedaluwarsa dalam contoh adalah 10 menit
                $registration_time = date('Y-m-d H:i:s'); // Tidak ada penambahan waktu di sini

                $sqlInsertUser = "INSERT INTO $tabel_pengguna (username, phone_number, email, password, otp_code, otp_expiration, status, registration_time) VALUES ('$newUsername', '$phone_number', '$email', '$hashedPassword', '$otp_code', '$expiration_time', 'notverified', '$registration_time')";
                $resultInsertUser = mysqli_query($koneksi, $sqlInsertUser);

                if ($resultInsertUser) {
                    // Kode OTP
                    $otp_code = rand(100000, 999999);
                    $expiration_time = date('Y-m-d H:i:s', strtotime('+2 minutes'));

                    $mail = new PHPMailer(true);

                    try {
                        // Pengaturan server SMTP Gmail
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'ardiansyah3151@gmail.com'; // Ganti dengan alamat email Gmail Anda
                        $mail->Password = 'japojvauiitefutx'; // Ganti dengan kata sandi Gmail Anda
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;

                        // Pengaturan email
                        $mail->setFrom('ardiansyah3151@gmail.com', 'TechForge Academy'); // Ganti dengan alamat email dan nama Anda
                        $mail->addAddress($email); // Alamat email pengguna
                        $mail->Subject = 'Code Verification';
                        $mail->Body = "Kode verifikasi Anda: $otp_code  Code OTP akan kadaluarsa dalam 2 menit";

                        // Kirim email
                        $mail->send();

                        // Perbarui informasi sesi verifikasi di tabel pengguna
                        $sqlUpdateVerification = "UPDATE $tabel_pengguna SET otp_code = '$otp_code', otp_expiration = '$expiration_time' WHERE email = '$email'";
                        mysqli_query($koneksi, $sqlUpdateVerification);

                        // Set email ke dalam sesi
                        $_SESSION['email'] = $email;

                        $success_msg = "Akun berhasil dibuat. Silakan cek email Anda untuk kode verifikasi.";

                        // Kosongkan field setelah berhasil mendaftar
                        header("location: verification.php");
                        $newUsername = $phone_number = $email = $newPassword = $confirmPassword = "";

                        // Panggil fungsi penghapusan otomatis

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


// Cek apakah pengguna sudah login
if (!isset($_SESSION['session_username'])) {
    header("location: ../../crud/php/login.php");
    exit();
}
$userRole = isset($_SESSION['session_role']) ? $_SESSION['session_role'] : '';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bootcamp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <input type="text" class="form-control" name="new_username" placeholder="Username" value="<?php echo $newUsername; ?>">
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
            </form>



            <img src="../login-register/images/image-2.png" alt="" class="image-2">
        </div>
    </div>

    <script src="../login-register/js/jquery-3.3.1.min.js"></script>
    <script src="../login-registerjs/main.js"></script>
</body>

</html>