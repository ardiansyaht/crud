<?php
session_start();

$host_db    = "localhost";
$user_db    = "root";
$pass_db    = "";
$nama_db    = "crud";
$koneksi    = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$err          = "";
$newUsername  = "";
$newPassword  = "";
$confirmPassword = "";

if (isset($_POST['register'])) {
    $newUsername       = $_POST['new_username'];
    $newPassword       = $_POST['new_password'];
    $confirmPassword   = $_POST['confirm_password'];

    // Validasi form di sisi klien
    if (empty($newUsername) || empty($newPassword) || empty($confirmPassword)) {
        $err .= "<li>Silakan lengkapi semua kolom.</li>";
    } elseif (strlen($newPassword) < 5) {
        $err .= "<li>Kata sandi harus terdiri dari minimal 5 karakter.</li>";
    } elseif ($newPassword !== $confirmPassword) {
        $err .= "<li>Konfirmasi kata sandi tidak cocok.</li>";
    } else {
        // Validasi form di sisi server
        $sqlCheckUser = "SELECT * FROM tb_login WHERE username = '$newUsername'";
        $resultCheckUser = mysqli_query($koneksi, $sqlCheckUser);

        if (mysqli_num_rows($resultCheckUser) > 0) {
            $err .= "<li>Username <b>$newUsername</b> sudah digunakan.</li>";
        } else {
            // Set peran pengguna (role) secara otomatis
            $role = "users";

            // Simpan data pengguna baru ke database dengan peran
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sqlInsertUser = "INSERT INTO tb_login (username, password, role) VALUES ('$newUsername', '$hashedPassword', '$role')";
            $resultInsertUser = mysqli_query($koneksi, $sqlInsertUser);

            if ($resultInsertUser) {
                $_SESSION['session_username'] = $newUsername;
                $_SESSION['session_role'] = $role;

                header("location:login.php");
                exit();
            } else {
                $err .= "<li>Gagal menyimpan data pengguna.</li>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Tambahan CSS atau link ke file eksternal jika diperlukan -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
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
    <style>
     
        
    </style>
    <script>
        function validateForm() {
            var newPassword = document.getElementById("new-password").value;
            var confirmPassword = document.getElementById("confirm-password").value;
            if (newPassword.length < 5) {
                alert("Kata sandi harus terdiri dari minimal 5 karakter.");
                return false;
            }
            if (newPassword !== confirmPassword) {
                alert("Konfirmasi kata sandi tidak cocok.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <!-- Formulir Register -->
    <div class="limiter">
        <div class="container-login100" style="background-image: url('login/images/bg-01.jpg');">
            <div class="wrap-login100">
                <form id="registerform" class="login100-form validate-form" action="" method="post" onsubmit="return validateRegisterForm()">
                    <span class="login100-form-logo">
                        <i class="zmdi zmdi-landscape"></i>
                    </span>

                    <span class="login100-form-title p-b-34 p-t-27">
                        Register
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Enter username">
                        <input class="input100" type="text" name="new_username" placeholder="Username">
                        <span class="focus-input100" data-placeholder="&#xf207;"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input class="input100" type="password" id="new-password" name="new_password" placeholder="Password">
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                        
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Confirm password">
                        <input class="input100" type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password">
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                        
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                    <button class="login100-form-btn" type="submit" name="register">
                        Register
                    </button>
                </div>
                <div class="text-center p-t-10">
    <?php
    if (!empty($err)) {
        echo '<div class="alert alert-danger">' . $err . '</div>';
    }
    ?>
    <?php
    if (!empty($confirmPasswordError)) {
        echo '<div class="alert alert-danger">' . $confirmPasswordError . '</div>';
    }
    ?>
</div>
                <div class="text-center p-t-30">
                    <a class="txt1" href="login.php">
                    Already have an account?
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <script src="login/vendor/jquery/jquery-3.2.1.min.js"></script> -->
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
