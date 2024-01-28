<!-- reset_password_bc.php -->

<?php
session_start();

$host_db        = "localhost";
$user_db        = "root";
$pass_db        = "";
$nama_db        = "crud";
$tabel_pengguna = "tb_login_bc";
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

if (isset($_POST['reset_password'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Periksa token dan waktu kedaluwarsa
    $sqlCheckToken = "SELECT * FROM $tabel_pengguna WHERE reset_token = ? AND reset_token_expires > NOW() AND email = ?";
    $stmt = mysqli_prepare($koneksi, $sqlCheckToken);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $token, $email);
        mysqli_stmt_execute($stmt);

        $resultCheckToken = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultCheckToken) > 0) {
            if (strlen($password) < 5) {
                $error_message = "Password harus terdiri dari minimal 5 karakter.";
            } elseif ($password != $confirmPassword) {
                $error_message = "Konfirmasi password tidak cocok.";
            } else {
                // Reset password menggunakan password_hash
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sqlResetPassword = "UPDATE $tabel_pengguna SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE reset_token = ? AND email = ?";
                $stmtReset = mysqli_prepare($koneksi, $sqlResetPassword);

                if ($stmtReset) {
                    mysqli_stmt_bind_param($stmtReset, "sss", $hashedPassword, $token, $email);
                    mysqli_stmt_execute($stmtReset);

                    // Tampilkan pesan sukses
                    $success_message = "Password berhasil direset. Silakan login.";
                } else {
                    $error_message = "Gagal menyiapkan pernyataan reset password.";
                }
            }
        } else {
            $error_message = "Token reset password tidak valid atau telah kedaluwarsa.";
        }

        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmtReset);
    } else {
        $error_message = "Gagal menyiapkan pernyataan pengecekan token.";
    }
    mysqli_close($koneksi);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../login-register/fonts/linearicons/style.css">
    <link rel="icon" type="image/png" href="../img/favicon.ico" />
    <link rel="stylesheet" href="../login-register/css/style.css">
    <!-- Tambahkan CSS sesuai kebutuhan -->
</head>

<body>

    <div class="wrapper">
        <div class="inner">
            <img src="../login-register/images/image-1.png" alt="" class="image-1">
            <a href="homepage.php" class="back-link" style="color: #808080;"><span class="lnr lnr-arrow-left"></span> Back to Home</a>
            <form action="" method="post">
                <h3>Reset Password</h3>

                <?php if (isset($error_message)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php } elseif (isset($success_message)) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php } ?>

                <div class="form-holder">
                    <span class="lnr lnr-lock"></span>
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <div class="form-holder">
                    <span class="lnr lnr-lock"></span>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                </div>
                <button type="submit" name="reset_password">
                    <span>Reset Password</span>
                </button>

            </form>
            <img src="../login-register/images/image-2.png" alt="" class="image-2">
        </div>
    </div>

    <script src="../login-register/js/jquery-3.3.1.min.js"></script>
    <script src="../login-register/js/main.js"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($success_message)) { ?>
                setTimeout(function() {
                    window.close();
                }, 3000);
            <?php } ?>
        });
    </script>
</body>

</html>