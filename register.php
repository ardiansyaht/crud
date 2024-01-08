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
            $hashedPassword = md5($newPassword);
            $sqlInsertUser = "INSERT INTO tb_login (username, password, role) VALUES ('$newUsername', '$hashedPassword', '$role')";
            $resultInsertUser = mysqli_query($koneksi, $sqlInsertUser);

            if ($resultInsertUser) {
                $_SESSION['session_username'] = $newUsername;
                $_SESSION['session_password'] = $hashedPassword;
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
    <style>
        /* Tambahkan CSS sesuai kebutuhan */
        .show-password-btn {
            cursor: pointer;
        }
    </style>
    <!-- Tambahkan script validasi di sisi klien -->
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
    <!-- Bagian Register -->
    <div class="container my-4">    
        <div id="registerbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                <div class="panel-heading">
                    <div class="panel-title">Registrasi Akun</div>
                </div>      
                <div style="padding-top:30px" class="panel-body">
                    <?php if($err){ ?>
                        <div id="register-alert" class="alert alert-danger col-sm-12">
                            <ul><?php echo $err ?></ul>
                        </div>
                    <?php } ?>                
                    <form id="registerform" class="form-horizontal" action="" method="post" role="form" onsubmit="return validateForm()">       
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="new-username" type="text" class="form-control" name="new_username" value="<?php echo $newUsername ?>" placeholder="Username">                                        
                        </div>
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="new-password" type="password" class="form-control" name="new_password" placeholder="Password">
                            <!-- Menambahkan tombol "Show Password" -->
                            <span class="input-group-addon show-password-btn" onclick="togglePassword('new-password')">
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </span>
                        </div>
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="confirm-password" type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                            <!-- Menambahkan tombol "Show Password" -->
                            <span class="input-group-addon show-password-btn" onclick="togglePassword('confirm-password')">
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </span>
                        </div>
                        <div style="margin-top:10px" class="form-group">
                            <div class="col-sm-12 controls">
                                <input type="submit" name="register" class="btn btn-success" value="Register"/>
                                <a href="login.php" class="btn btn-primary">Login</a>
                            </div>
                        </div>
                    </form>    
                </div>                     
            </div>  
        </div>
    </div>
    <!-- Script untuk toggle password (sama seperti yang ada di form login) -->
    <script>
        function togglePassword(inputId) {
            var passwordInput = document.getElementById(inputId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>
</html>
