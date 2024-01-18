<?php
session_start();

$host_db    = "localhost";
$user_db    = "root";
$pass_db    = "";
$nama_db    = "crud";
$koneksi    = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$err              = "";
$username         = "";
$password         = "";
$confirmPassword  = "";

if (isset($_POST['reset'])) {
    $token = $_GET['token'];
    $username = $_GET['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (strlen($password) < 5) {
        $err .= "<li>Password harus terdiri dari minimal 5 karakter.</li>";
    } elseif ($password != $confirmPassword) {
        $err .= "<li>Konfirmasi password tidak cocok.</li>";
    } else {
        // Periksa token dan waktu kedaluwarsa
        $sqlCheckToken = "SELECT * FROM tb_login WHERE reset_token = '$token' AND reset_token_expires > NOW() AND username = '$username'";
        $resultCheckToken = mysqli_query($koneksi, $sqlCheckToken);

        if (mysqli_num_rows($resultCheckToken) > 0) {
            $userData = mysqli_fetch_assoc($resultCheckToken);

            // Cek apakah pengguna memiliki peran "admin"
            if ($userData['role'] === 'admin') {
                $err .= "<li>Pengguna dengan peran admin tidak dapat mereset password.</li>";
            } elseif ($userData['is_locked'] == 1) {
                $err .= "<li>Akun Anda telah terkunci. Hubungi customer service untuk mereset password.</li>";
            } else {
                // Reset password dengan SHA-1
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $sqlResetPassword = "UPDATE tb_login SET password = '$hashedPassword', reset_token = NULL, reset_token_expires = NULL WHERE reset_token = '$token' AND username = '$username'";
                mysqli_query($koneksi, $sqlResetPassword);

                // Tampilkan pesan sukses
                $err = "<li>Password berhasil direset. Silakan login.</li>";
            }
        } else {
            $err .= "<li>Token reset password tidak valid atau telah kedaluwarsa.</li>";
        }
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="../login/images/icons/favicon.ico"/>
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
        <div class="container-login100" style="background-image: url('login/images/bg-01.jpg');">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="post" action="">
                    <span class="login100-form-logo">
                        <i class="zmdi zmdi-landscape"></i>
                    </span>

                    <span class="login100-form-title p-b-34 p-t-27">
                        Reset Password
                    </span>

                    <?php if ($err) { ?>
                        <div id="reset-password-alert" class="alert alert-info col-sm-12">
                            <ul><?php echo $err; ?></ul>
                        </div>
                    <?php } ?>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Confirm password">
                        <input class="input100" type="password" name="confirm_password" placeholder="Confirm Password">
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit" name="reset">
                            Reset Password
                        </button>
                    </div>

                    <div class="text-center p-t-5">
                        <a class="txt1" href="login.php">
                            Back to Login
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
