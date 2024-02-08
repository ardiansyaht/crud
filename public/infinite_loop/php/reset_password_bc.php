<?php
header('X-Frame-Options: DENY');
session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'use_only_cookies' => true,
]);
require '../../crud/php/config_koneksi.php';
$host_db        = DB_HOST;
$user_db        = DB_USER;
$pass_db        = DB_PASS;
$nama_db        = DB_NAME;
$tabel_pengguna = "tb_login_bc";
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

if (isset($_POST['reset_password'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validasi sisi server
    if (empty($token) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error_message = "Silakan lengkapi semua kolom.";
    } elseif (!isValidEmail($email)) {
        $error_message = "Email tidak valid. Pastikan menggunakan simbol '@' dan tidak ada simbol lainnya.";
    } elseif (!isValidPassword($password)) {
        $error_message = "Password harus terdiri dari minimal 8 karakter, 1 huruf kecil, 1 huruf besar, 1 angka, dan 1 simbol.";
    } elseif ($password != $confirmPassword) {
        $error_message = "Konfirmasi password tidak cocok.";
    } else {
        // Periksa token dan waktu kedaluwarsa
        $sqlCheckToken = "SELECT * FROM $tabel_pengguna WHERE reset_token = ? AND reset_token_expires > NOW() AND email = ?";
        $stmtCheckToken = mysqli_prepare($koneksi, $sqlCheckToken);

        if ($stmtCheckToken) {
            mysqli_stmt_bind_param($stmtCheckToken, "ss", $token, $email);
            mysqli_stmt_execute($stmtCheckToken);

            $resultCheckToken = mysqli_stmt_get_result($stmtCheckToken);

            if (mysqli_num_rows($resultCheckToken) > 0) {
                // Reset password menggunakan password_hash
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sqlResetPassword = "UPDATE $tabel_pengguna SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE reset_token = ? AND email = ?";
                $stmtResetPassword = mysqli_prepare($koneksi, $sqlResetPassword);

                if ($stmtResetPassword) {
                    mysqli_stmt_bind_param($stmtResetPassword, "sss", $hashedPassword, $token, $email);
                    mysqli_stmt_execute($stmtResetPassword);

                    // Tampilkan pesan sukses
                    $success_message = "Password berhasil direset. Silakan login dengan password baru Anda.";

                    // Hapus token reset setelah berhasil
                    $sqlClearToken = "UPDATE $tabel_pengguna SET reset_token = NULL, reset_token_expires = NULL WHERE email = ?";
                    $stmtClearToken = mysqli_prepare($koneksi, $sqlClearToken);

                    if ($stmtClearToken) {
                        mysqli_stmt_bind_param($stmtClearToken, "s", $email);
                        mysqli_stmt_execute($stmtClearToken);
                        mysqli_stmt_close($stmtClearToken);
                    }
                } else {
                    $error_message = "Gagal menyiapkan pernyataan reset password. Error: " . mysqli_error($koneksi);
                }

                mysqli_stmt_close($stmtResetPassword);
            } else {
                $error_message = "Token reset password tidak valid atau telah kedaluwarsa.";
            }

            mysqli_stmt_close($stmtCheckToken);
        } else {
            $error_message = "Gagal menyiapkan pernyataan pengecekan token. Error: " . mysqli_error($koneksi);
        }
    }

    mysqli_close($koneksi);
}

// Fungsi untuk memvalidasi email
function isValidEmail($email)
{
    // Pastikan email hanya mengandung satu simbol '@'
    return (substr_count($email, '@') === 1);
}

// Fungsi untuk memvalidasi password
function isValidPassword($password)
{
    // Persyaratan: minimal 8 karakter, 1 huruf kecil, 1 huruf besar, 1 angka, dan 1 simbol.
    return (strlen($password) >= 8 && preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password) && preg_match('/[!@#$%^&*()_+]/', $password));
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
    <footer class="text-center small tm-footer">
        <p class="mb-0">
            Copyright &copy; 2023 TechForge Academy</p>
    </footer>

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