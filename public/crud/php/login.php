<?php
session_start();

// Atur koneksi ke database
$host_db    = "localhost";
$user_db    = "root";
$pass_db    = "";
$nama_db    = "crud";
$koneksi    = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

// Atur variabel
$err        = "";
$username   = "";

// Fungsi untuk menghitung percobaan login yang gagal
function getFailedLoginAttempts($username, $koneksi) {
    $sql = "SELECT failed_login_attempts FROM tb_login WHERE username = '$username'";
    $result = mysqli_query($koneksi, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['failed_login_attempts'];
}

// Fungsi untuk mengupdate percobaan login yang gagal
function updateFailedLoginAttempts($username, $koneksi, $attempts) {
    $sql = "UPDATE tb_login SET failed_login_attempts = $attempts WHERE username = '$username'";
    mysqli_query($koneksi, $sql);
}

if (isset($_POST['login'])) {
    $username   = $_POST['username'];
    $password   = $_POST['password'];

    if ($username == '' or $password == '') {
        $err .= "<li>Silakan masukkan username dan juga password.</li>";
    } else {
        $sql1 = "SELECT * FROM tb_login WHERE username = '$username'";
        $q1   = mysqli_query($koneksi, $sql1);
        $r1   = mysqli_fetch_array($q1);

        // Mengecek apakah akun di-lock
        if ($r1 && $r1['is_locked'] == 1) {
            $err .= "<li>Akun Anda telah terkunci. Hubungi customer service.</li>";
        } elseif (!$r1) {
            error_reporting(0);
            $err .= "<li>Username <b>$username</b> tidak tersedia.</li>";
        } elseif (!password_verify($password, $r1['password'])) {
            $failedAttempts = getFailedLoginAttempts($username, $koneksi);
            
            // Menambah percobaan login yang gagal
            $failedAttempts++;

            // Update percobaan login yang gagal
            updateFailedLoginAttempts($username, $koneksi, $failedAttempts);

            $err .= "<li>Password yang dimasukkan tidak sesuai. Percobaan ke-$failedAttempts.</li>";

            // Mengecek apakah telah mencapai batas percobaan
            if ($failedAttempts >= 5) {
                $err .= "<li>Akun Anda akan terkunci setelah 10 percobaan gagal.</li>";
            }

            // Mengecek apakah harus mengunci akun
            if ($failedAttempts >= 10) {
                $err .= "<li>Akun Anda terkunci. Hubungi customer service.</li>";

                // Mengunci akun
                $sqlLockAccount = "UPDATE tb_login SET is_locked = 1 WHERE username = '$username'";
                mysqli_query($koneksi, $sqlLockAccount);
            }
        }

        if (empty($err)) {
            // Reset percobaan login yang gagal
            updateFailedLoginAttempts($username, $koneksi, 0);

            $_SESSION['session_username'] = $username;
            $_SESSION['session_role'] = $r1['role'];

            header("location:../../infinite_loop/php/index.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="../loginv1/images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="../loginv1/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../loginv1/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../loginv1/fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="../loginv1/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="../loginv1/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="../loginv1/vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="../loginv1/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="../loginv1/vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="../loginv1/css/util.css">
	<link rel="stylesheet" type="text/css" href="../loginv1/css/main.css">
</head>
<body>
<div class="limiter">
    <div class="container-login100" style="background-image: url('../loginv1/images/bg-01.jpg');">
        <div class="wrap-login100">
            <form class="login100-form validate-form" method="post" action="">
            <?php
           if (!empty($err)) {
           echo '<div class="alert alert-danger" role="alert">' . $err . '</div>';
           }
           ?>
                <span class="login100-form-logo">
                    <i class="zmdi zmdi-landscape"></i>
                </span>

                <span class="login100-form-title p-b-34 p-t-27">
                    Log in
                </span>

                <div class="wrap-input100 validate-input" data-validate="Enter username">
                    <input class="input100" type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
                    <span class="focus-input100" data-placeholder="&#xf207;"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Enter password">
                    <input class="input100" type="password" name="password" placeholder="Password">
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>

                <!-- <div class="contact100-form-checkbox">
                    <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
                    <label class="label-checkbox100" for="ckb1">
                        Remember me
                    </label>
                </div> -->

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit" name="login">
                            Login
                        </button>
                    </div>

                <div class="text-center p-t-30">
                    <a class="txt1" href="register.php">
                        Sign Up?

                <div class="text-center p-t-5">
                    <a class="txt1" href="forgot_password.php">
                        Forgot Password?
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
	<div id="dropDownSelect1"></div>
	<script src="../loginv1/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="../loginv1/vendor/animsition/js/animsition.min.js"></script>
	<script src="../loginv1/vendor/bootstrap/js/popper.js"></script>
	<script src="../loginv1/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="../loginv1/vendor/select2/select2.min.js"></script>
	<script src="../loginv1/vendor/daterangepicker/moment.min.js"></script>
	<script src="../loginv1/vendor/daterangepicker/daterangepicker.js"></script>
	<script src="../loginv1/vendor/countdowntime/countdowntime.js"></script>
	<script src="../loginv1/js/main.js"></script>

</body>
</html>