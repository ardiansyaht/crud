<?php
header('X-Frame-Options: DENY');
session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'use_only_cookies' => true,
]);

require  '../../crud/php/config_koneksi.php';
$host_db        = DB_HOST;
$user_db        = DB_USER;
$pass_db        = DB_PASS;
$nama_db        = DB_NAME;
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$err            = "";
$email          = "";

// Fungsi untuk menghitung percobaan login yang gagal
function getFailedLoginAttempts($email, $koneksi)
{
    $sql = "SELECT failed_login_attempts FROM tb_login_bc WHERE email = '$email'";
    $result = mysqli_query($koneksi, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['failed_login_attempts'];
}

// Fungsi untuk mengupdate percobaan login yang gagal
function updateFailedLoginAttempts($email, $koneksi, $attempts)
{
    $sql = "UPDATE tb_login_bc SET failed_login_attempts = $attempts WHERE email = '$email'";
    mysqli_query($koneksi, $sql);
}

// Fungsi untuk menghapus akun yang belum diverifikasi dalam waktu 1 jam
function deleteUnverifiedAccounts($koneksi)
{
    $sqlDeleteUnverified = "DELETE FROM tb_login_bc WHERE status = 'notverified' AND registration_time < NOW() - INTERVAL 1 HOUR";
    mysqli_query($koneksi, $sqlDeleteUnverified);
}

// Panggil fungsi penghapusan otomatis
deleteUnverifiedAccounts($koneksi);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Logout user
    session_unset();
    session_destroy();
    header("location: login_bc.php");
    exit();
}

if (isset($_POST['login'])) {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Validasi reCAPTCHA
    $recaptchaSecretKey = '6LceCFspAAAAAOiZ7XgAOMgIboFKgD0vsXwQb7Dn';
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $recaptchaVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecretKey&response=$recaptchaResponse";

    $recaptchaVerify = json_decode(file_get_contents($recaptchaVerifyUrl));

    if (!$recaptchaVerify->success) {
        $err .= "<li>Verifikasi reCAPTCHA gagal.</li>";
    }


    $sql1 = "SELECT * FROM tb_login_bc WHERE email = '$email'";
    $q1   = mysqli_query($koneksi, $sql1);
    $r1   = mysqli_fetch_array($q1);

    // Mengecek apakah akun di-lock
    if ($r1 && $r1['is_locked'] == 1) {
        $err .= "<li>Akun Anda telah terkunci. Hubungi customer service.</li>";
    } elseif (!$r1) {
        error_reporting(0);
        $err .= "<li>Email <b>$email</b> tidak tersedia.</li>";
    } elseif (!password_verify($password, $r1['password'])) {
        $failedAttempts = getFailedLoginAttempts($email, $koneksi);

        // Menambah percobaan login yang gagal
        $failedAttempts++;

        // Update percobaan login yang gagal
        updateFailedLoginAttempts($email, $koneksi, $failedAttempts);

        $err .= "<li>Password yang dimasukkan tidak sesuai. Percobaan ke-$failedAttempts.</li>";

        // Mengecek apakah telah mencapai batas percobaan
        if ($failedAttempts >= 5) {
            $err .= "<li>Akun Anda akan terkunci setelah 10 percobaan gagal.</li>";
        }

        // Mengecek apakah harus mengunci akun
        if ($failedAttempts >= 10) {
            $err .= "<li>Akun Anda terkunci. Hubungi customer service.</li>";

            // Mengunci akun
            $sqlLockAccount = "UPDATE tb_login_bc SET is_locked = 1 WHERE email = '$email'";
            mysqli_query($koneksi, $sqlLockAccount);
        }
    }

    if (empty($err)) {
        // Cek status verifikasi
        if ($r1['status'] != 'verified') {
            // Akun belum diverifikasi
            $_SESSION['email_for_verification'] = $email;
            $err .= "<li>Akun Anda belum diverifikasi. Silakan <a href='verification.php'>verifikasi</a> terlebih dahulu.</li>";
        } else {
            // Reset percobaan login yang gagal
            updateFailedLoginAttempts($email, $koneksi, 0);

            $_SESSION['session_email'] = $email;
            $_SESSION['session_role'] = $r1['role'];
            $_SESSION['username'] = $r1['username'];

            header("location:homepage.php");
            exit();
        }
    }
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
    <style>

    </style>
</head>

<body>
    <div class="wrapper">
        <div class="inner">

            <img src="../login-register/images/image-1.png" alt="" class="image-1">
            <a href="homepage.php" class="back-link" style="color: #808080;"><span class="lnr lnr-arrow-left"></span> Back to Home</a>
            <form action="" method="post">
                <h3>LOGIN</h3>

                <?php if (!empty($err)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <?php echo $err; ?>
                        </ul>
                    </div>
                <?php } ?>

                <div class="form-holder">
                    <span class="lnr lnr-envelope"></span>
                    <input type="text" class="form-control" name="email" placeholder="Mail">
                </div>
                <div class="form-holder">
                    <span class="lnr lnr-lock"></span>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                    <span class="toggle-icon" onclick="togglePassword()" id="toggleIcon"></span>
                </div>
                <button type="submit" name="login">
                    <span>Login</span>
                </button>
                <div class="g-recaptcha" data-sitekey="6LceCFspAAAAAE2ZLBwHhGBXA1lxMVOyMP_qG2BQ"></div>

                <!-- Footer Section -->
                <div class="footer">
                    <div class="left-footer">
                        <p style="color: #808080;">
                            Don't have an account? <a href="register_bc.php" class="sign-up-link" style="color: #808080;">Sign Up</a>
                        </p>
                    </div>
                    <div class="right-footer">
                        <p style="color: #808080;">
                            <a href="forgot_password_bc.php" class="forgot-password-link" style="color: #808080;">Forgot password?</a>
                        </p>
                    </div>

                </div>
                <!-- End Footer Section -->
            </form>
            <img src="../login-register/images/image-2.png" alt="" class="image-2">
            <footer class="text-center small tm-footer text-dark">
                <p class="mb-0">
                    Copyright &copy; 21552011105_KELOMPOK 1_TIFRP221PA_UASWEB1.</p>
            </footer>

        </div>
    </div>

    <script src="../login-register/js/jquery-3.3.1.min.js"></script>
    <script src="../login-register/js/main.js"></script>
</body>

</html>