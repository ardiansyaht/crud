<?php
session_start();

$host_db    = "localhost";
$user_db    = "root";
$pass_db    = "";
$nama_db    = "crud";
$koneksi    = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$err        = "";
$username   = "";

// ...
if (isset($_POST['reset'])) {
    $username = $_POST['username'];

    if ($username == '') {
        $err .= "<li>Silakan masukkan username.</li>";
    } else {
        $sqlCheckUser = "SELECT * FROM tb_login WHERE username = '$username'";
        $resultCheckUser = mysqli_query($koneksi, $sqlCheckUser);

        if (mysqli_num_rows($resultCheckUser) > 0) {
            $userData = mysqli_fetch_assoc($resultCheckUser);

            // Cek apakah akun terkunci
            if ($userData['is_locked'] == 1) {
                $err .= "<li>Akun Anda telah terkunci. Hubungi customer service untuk mereset password.</li>";
            } else {
                // Tentukan durasi waktu kedaluwarsa berdasarkan peran
                $expirationTime = ($userData['role'] === 'admin') ? "1 SECOND" : "1 HOUR";

                // Generate token untuk reset password
                $resetToken = bin2hex(random_bytes(32));
                $resetLink = "https://example.com/reset_password.php?token=$resetToken&username=$username";

                // Simpan token dan waktu kedaluwarsa di database
                $sqlUpdateToken = "UPDATE tb_login SET reset_token = '$resetToken', reset_token_expires = DATE_ADD(NOW(), INTERVAL $expirationTime) WHERE username = '$username'";
                mysqli_query($koneksi, $sqlUpdateToken);

                // Redirect ke halaman reset password
                header("Location: reset_password.php?token=$resetToken&username=$username");
                exit();
            }
        } else {
            $err .= "<li>Username <b>$username</b> tidak terdaftar.</li>";
        }
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="login/images/icons/favicon.ico"/>
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
                        Forgot Password
                    </span>

                    <?php if ($err) { ?>
                        <div id="forgot-password-alert" class="alert alert-info col-sm-12">
                            <ul><?php echo $err; ?></ul>
                        </div>
                    <?php } ?>

                    <div class="wrap-input100 validate-input" data-validate="Enter username">
    <input class="input100" type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
    <span class="focus-input100" data-placeholder="&#xf159;"></span>
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
	

	<script src="../login/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="../login/vendor/animsition/js/animsition.min.js"></script>
	<script src="../login/vendor/bootstrap/js/popper.js"></script>
	<script src="../login/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="../login/vendor/select2/select2.min.js"></script>
	<script src="../login/vendor/daterangepicker/moment.min.js"></script>
	<script src="../login/vendor/daterangepicker/daterangepicker.js"></script>
	<script src="../login/vendor/countdowntime/countdowntime.js"></script>
	<script src="../login/js/main.js"></script>
</body>
</html>
