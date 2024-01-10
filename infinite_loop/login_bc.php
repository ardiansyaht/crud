<?php
session_start();

$host_db        = "localhost";
$user_db        = "root";
$pass_db        = "";
$nama_db        = "crud";
$tabel_pengguna = "tb_login_bc";
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

if (isset($_POST['login'])) {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $hashedPassword = md5($password);

    $sqlCheckUser = "SELECT * FROM $tabel_pengguna WHERE email = '$email' AND password = '$hashedPassword'";
    $resultCheckUser = mysqli_query($koneksi, $sqlCheckUser);

    if (mysqli_num_rows($resultCheckUser) > 0) {
        // Login berhasil
        $_SESSION['user_email'] = $email;
        header("location: index.php"); 
        exit();
    } else {
        // Login gagal
        $error_message = "Invalid email or password. Please try again.";
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

            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php } ?>
             
            <div class="form-holder">
                <span class="lnr lnr-envelope"></span>
                <input type="text" class="form-control" name="email" placeholder="Mail">
            </div>
            <div class="form-holder">
                <span class="lnr lnr-lock"></span>
                <input type="password" class="form-control" name="password" placeholder="Password">
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
