<?php
session_start();

$host_db        = "localhost";
$user_db        = "root";
$pass_db        = "";
$nama_db        = "crud";
$tabel_pengguna = "tb_login_bc";
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$success_msg    = "";
$err            = "";
$newUsername    = "";
$phone_number   = "";
$email          = "";
$newPassword    = "";
$confirmPassword = "";

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
            $hashedPassword = md5($newPassword);
            $sqlInsertUser = "INSERT INTO $tabel_pengguna (username, phone_number, email, password) VALUES ('$newUsername', '$phone_number', '$email', '$hashedPassword')";
            $resultInsertUser = mysqli_query($koneksi, $sqlInsertUser);

            if ($resultInsertUser) {
                $success_msg = "Akun berhasil dibuat. Silakan login.";
                // Kosongkan field setelah berhasil mendaftar
                $newUsername = $phone_number = $email = $newPassword = $confirmPassword = "";
            } else {
                $err .= "<li>Gagal menyimpan data pengguna.</li>";
            }
        }
    }
}

// Cek apakah pengguna sudah login
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
</head>

<body>

<div class="wrapper">
    <div class="inner">
        <img src="login-register/images/image-1.png" alt="" class="image-1">
        <form action="" method="post">
            <h3>New Account?</h3>

            <?php if ($success_msg) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_msg; ?>
                </div>
            <?php } ?>

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
            <button type="submit" name="register">
                <span>Register</span>
            </button>
			<form method="post" action="login_bc.php">
    <!-- Form fields can be added here if needed -->

    <button type="submit" name="login" formaction="login_bc.php">
        <span>Login</span>
    </button>
</form>

        </form>
        <img src="login-register/images/image-2.png" alt="" class="image-2">
    </div>
</div>

<script src="login-register/js/jquery-3.3.1.min.js"></script>
<script src="login-registerjs/main.js"></script>
</body>
</html>
