<?php
session_start();

$host_db        = "localhost";
$user_db        = "root";
$pass_db        = "";
$nama_db        = "crud";
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$err            = "";
$email          = "";

// Fungsi untuk menghitung percobaan login yang gagal
function getFailedLoginAttempts($email, $koneksi) {
    $sql = "SELECT failed_login_attempts FROM tb_login_bc WHERE email = '$email'";
    $result = mysqli_query($koneksi, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['failed_login_attempts'];
}

// Fungsi untuk mengupdate percobaan login yang gagal
function updateFailedLoginAttempts($email, $koneksi, $attempts) {
    $sql = "UPDATE tb_login_bc SET failed_login_attempts = $attempts WHERE email = '$email'";
    mysqli_query($koneksi, $sql);
}

if (isset($_POST['login'])) {
    $email    = $_POST['email'];
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    } else {
        // Handle jika 'password' tidak ada dalam $_POST
        // Misalnya, set nilai default atau tampilkan pesan kesalahan
        $password = ""; // Atau sesuaikan dengan kebutuhan
        echo "Password tidak ditemukan dalam data POST.";
    }
    

    $hashedPassword = sha1($password);

    $sql1 = "SELECT * FROM tb_login_bc WHERE email = '$email'";
    $q1   = mysqli_query($koneksi, $sql1);
    $r1   = mysqli_fetch_array($q1);

    // Mengecek apakah akun di-lock
    if ($r1 && $r1['is_locked'] == 1) {
        $err .= "<li>Akun Anda telah terkunci. Hubungi customer service.</li>";
    } elseif (!$r1) {
        error_reporting(0);
        $err .= "<li>Email <b>$email</b> tidak tersedia.</li>";
    } elseif ($r1['password'] != $hashedPassword) {
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
        // Reset percobaan login yang gagal
        updateFailedLoginAttempts($email, $koneksi, 0);

        $_SESSION['session_email'] = $email;
        $_SESSION['session_password'] = $hashedPassword;
        $_SESSION['session_role'] = $r1['role'];

        header("location:index.php");
        exit();
    }
}

if (!isset($_SESSION['session_username'])) {
    header("location: ../login.php");
    exit();
}
$userRole = isset($_SESSION['session_role']) ? $_SESSION['session_role'] : '';
?>



<!DOCTYPE html>
<html>
<head>
<script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleIcon = document.getElementById("toggleIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.className = "lnr lnr-eye";
            } else {
                passwordField.type = "password";
                toggleIcon.className = "lnr lnr-eye-off";
            }
        }
    </script>
    <meta charset="utf-8">
    <title>Bootcamp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login-register/fonts/linearicons/style.css">
    <link rel="stylesheet" href="login-register/css/style.css">
    <style>
        
    </style>
</head>

<body>

<div class="wrapper">
    <div class="inner">
    
        <img src="login-register/images/image-1.png" alt="" class="image-1">
        <a href="index.php" class="back-link" style="color: #808080;"><span class="lnr lnr-arrow-left"></span> Back to Home</a>
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
        <img src="login-register/images/image-2.png" alt="" class="image-2">
    </div>
</div>

<script src="login-register/js/jquery-3.3.1.min.js"></script>
<script src="login-register/js/main.js"></script>
</body>
</html>
