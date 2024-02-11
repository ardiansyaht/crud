<?php
header('X-Frame-Options: DENY');
session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'use_only_cookies' => true,
]);
require  '../../crud/php/config_koneksi.php';
$host_db        = DB_HOST;
$user_db        = DB_USER;
$pass_db        = DB_PASS;
$nama_db        = DB_NAME;
$koneksi = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$err = "";
$email = "";
$userData = [];
$name = "";
$company = "";
$username = "";
$photo_path = "";

if (!isset($_SESSION['session_email'])) {
    header("location: login_bc.php");
    exit();
}

$email = $_SESSION['session_email'];

$sqlGetUser = "SELECT email, username, name, company, photo_path, password, status FROM tb_login_bc WHERE email = ?";
$stmtGetUser = mysqli_prepare($koneksi, $sqlGetUser);
mysqli_stmt_bind_param($stmtGetUser, "s", $email);
mysqli_stmt_execute($stmtGetUser);
$resultGetUser = mysqli_stmt_get_result($stmtGetUser);


if ($resultGetUser) {
    $userData = mysqli_fetch_assoc($resultGetUser);
    $name = isset($userData['name']) ? $userData['name'] : '';
    $company = isset($userData['company']) ? $userData['company'] : '';
    $username = isset($userData['username']) ? $userData['username'] : '';
    $photo_path = isset($userData['photo_path']) ? $userData['photo_path'] : '';
} else {
    // Handle query error
    die(mysqli_error($koneksi));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    // Hapus semua data di tb_login_bc
    $deleteAccountQuery = "DELETE FROM tb_login_bc WHERE email = ?";
    $stmtDeleteAccount = mysqli_prepare($koneksi, $deleteAccountQuery);
    mysqli_stmt_bind_param($stmtDeleteAccount, "s", $email);
    $resultDeleteAccount = mysqli_stmt_execute($stmtDeleteAccount);

    if ($resultDeleteAccount) {
        session_unset();
        session_destroy();
        echo json_encode(array('success' => true));
        exit();
    } else {
        // Mengirim respons JSON ke klien
        echo json_encode(array('success' => false, 'message' => 'Failed to delete account. Please try again.'));
        exit();
    }

    mysqli_stmt_close($stmtDeleteAccount);
}
// Check if there is a notification message
if (isset($_SESSION['notification'])) {
    echo '<div class="alert alert-success mt-3">' . htmlspecialchars($_SESSION['notification']) . '</div>';
    unset($_SESSION['notification']); // Clear the notification after displaying it
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["change_password"])) {
        $currentPassword = mysqli_real_escape_string($koneksi, $_POST["current_password"]);
        $newPassword = mysqli_real_escape_string($koneksi, $_POST["new_password"]);
        $confirmNewPassword = mysqli_real_escape_string($koneksi, $_POST["repeat_password"]);

        // Validasi password baru
        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $newPassword)) {
            $_SESSION['notification'] = "Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 digit, and 1 special character. Minimum length is 8 characters.";
        } else {
            // Verifikasi apakah kata sandi saat ini cocok dengan hash yang ada di database
            if (password_verify($currentPassword, $userData['password'])) {
                // Check if the new password and confirm new password match
                if ($newPassword == $confirmNewPassword) {
                    // Hash the new password using Bcrypt
                    $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                    // Update the password in the database
                    $updatePasswordQuery = "UPDATE tb_login_bc SET password=? WHERE email=?";
                    $stmtUpdatePassword = mysqli_prepare($koneksi, $updatePasswordQuery);
                    mysqli_stmt_bind_param($stmtUpdatePassword, "ss", $hashedNewPassword, $email);
                    $resultUpdatePassword = mysqli_stmt_execute($stmtUpdatePassword);

                    if ($resultUpdatePassword) {
                        $_SESSION['notification'] = "Password changed successfully.";
                    } else {
                        $_SESSION['notification'] = "Failed to update password. Please try again.";
                    }

                    mysqli_stmt_close($stmtUpdatePassword);
                } else {
                    $_SESSION['notification'] = "New password and confirm new password do not match.";
                }
            } else {
                $_SESSION['notification'] = "Current password is incorrect.";
            }
        }

        // Redirect back to the same page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["save_changes"])) {
        $name = mysqli_real_escape_string($koneksi, $_POST["name"]);
        $company = mysqli_real_escape_string($koneksi, $_POST["company"]);
        $username = mysqli_real_escape_string($koneksi, $_POST["username"]);

        // Validasi panjang minimum hanya untuk field yang diisi
        if ((!empty($name) && !isValidLength($name, 4)) || (!empty($company) && !isValidLength($company, 4)) || (!empty($username) && !isValidLength($username, 4))) {
            $_SESSION['notification'] = "Panjang minimum untuk field yang diisi adalah 4 karakter.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Validasi nama hanya boleh huruf
        if (!empty($name) && !isValidName($name)) {
            $_SESSION['notification'] = "Harap pastikan hanya berisi huruf.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Validasi perusahaan boleh mengandung huruf, angka, dan simbol (.) hanya jika diisi
        if (!empty($company) && !isValidCompany($company)) {
            $_SESSION['notification'] = "Invalid company name. Harap pastikan hanya berisi huruf, angka, dan titik.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Validasi username hanya boleh mengandung simbol @, ., _, - hanya jika diisi
        if (!empty($username) && !isValidUsername($username)) {
            $_SESSION['notification'] = "Invalid username. Harap pastikan hanya berisi @, ., _, - symbols.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        $updateQuery = "UPDATE tb_login_bc SET name=?, company=?, username=? WHERE email=?";
        $stmt = mysqli_prepare($koneksi, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ssss", $name, $company, $username, $email);

        $resultUpdate = mysqli_stmt_execute($stmt);

        if ($resultUpdate) {
            // Profile updated successfully.
            if (!empty($_FILES['profile_photo']['name'])) {
                $targetDirectory = "user_image/";
                $originalFileName = basename($_FILES['profile_photo']['name']);
                $targetFile = $targetDirectory . generateUniqueFileName($originalFileName);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Allow only specific file types (PNG, JPEG, JPG)
                $allowedFileTypes = array('png', 'jpeg', 'jpg');
                if (!in_array($imageFileType, $allowedFileTypes)) {
                    $_SESSION['notification'] = "Sorry, only PNG, JPEG, JPG files are allowed.";
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES['profile_photo']['size'] > 2000000) {
                    $_SESSION['notification'] = "Sorry, the maximum file size allowed is 2MB.";
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $_SESSION['notification'] = "Sorry, your file was not uploaded.";
                } else {
                    // if everything is ok, try to upload file
                    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetFile)) {
                        // File berhasil diunggah
                        // $_SESSION['notification'] = "Profile photo uploaded successfully.";

                        // Update database with file path   
                        $photoPath = $targetFile;
                        $photoUpdateQuery = "UPDATE tb_login_bc SET photo_path=? WHERE email=?";
                        $photoStmt = mysqli_prepare($koneksi, $photoUpdateQuery);
                        mysqli_stmt_bind_param($photoStmt, "ss", $photoPath, $email);
                        mysqli_stmt_execute($photoStmt);
                        mysqli_stmt_close($photoStmt);
                    } else {
                        $_SESSION['notification'] = "Sorry, there was an error uploading your file.";
                    }
                }
            }
            $_SESSION['notification'] = "Profile updated successfully.";
        } else {
            $_SESSION['notification'] = "Failed to update profile. Please try again.";
        }

        mysqli_stmt_close($stmt);

        // Redirect back to the same page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } elseif (isset($_POST["reset_photo"])) {
        // Handle reset button click
        $resetPhotoQuery = "UPDATE tb_login_bc SET photo_path=NULL WHERE email=?";
        $resetPhotoStmt = mysqli_prepare($koneksi, $resetPhotoQuery);
        mysqli_stmt_bind_param($resetPhotoStmt, "s", $email);
        mysqli_stmt_execute($resetPhotoStmt);
        mysqli_stmt_close($resetPhotoStmt);
        // $_SESSION['notification'] = "Profile photo has been reset.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
function generateUniqueFileName($originalFileName)
{
    $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $uniqueName = hash('sha256', uniqid(rand(), true)) . '.' . $extension;
    return $uniqueName;
}
function isValidName($input)
{
    // Validasi hanya huruf
    return preg_match('/^[a-zA-Z\s\']+$/u', $input);
}

function isValidCompany($input)
{
    // Validasi boleh mengandung huruf, angka, spasi, dan simbol (.)
    return preg_match('/^[a-zA-Z0-9. ]+$/u', $input);
}

function isValidUsername($input)
{
    // Validasi hanya boleh mengandung simbol @, ., _, -
    return preg_match('/^[a-zA-Z0-9@._-]+$/u', $input);
}
function isValidLength($input, $minLength)
{
    // Validasi panjang minimum
    return strlen($input) >= $minLength;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="icon" type="image/png" href="../img/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-ojQVnY3Wp1dgJzUn1/GWBt5l1QQC4aZ1TO5SN0C7vYHj4lK/xVxm1aIY+KDAK5S" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Konten Profil -->
    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">
            Account settings
        </h4>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links d-none d-lg-block">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">Change password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-actions">Account</a>

                    </div>
                </div>
                <!-- Daftar navigasi mobile -->
                <div class="container d-lg-none mt-2">
                    <div class="list-group">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">Change password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-actions">Account</a>

                    </div>
                </div>

                <div class="col-md-9">
                    <div class="tab-content">
                        <!-- Content for General Tab -->
                        <div class="tab-pane fade active show" id="account-general">
                            <a href="homepage.php" class="back-link" style="color: #808080;"><span class="lnr lnr-home"></span> Back to Home</a>

                            <div class="card-body media align-items-center">
                                <!-- General Tab Content -->
                                <img src="<?php echo !empty($userData['photo_path']) ? $userData['photo_path'] : 'https://cdn.pixabay.com/photo/2020/07/01/12/58/icon-5359553_1280.png'; ?>" alt class="d-block ui-w-80">
                                <div class="media-body ml-4">
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <label class="btn btn-outline-primary">
                                            Upload new photo
                                            <input type="file" class="account-settings-fileinput" name="profile_photo">
                                        </label> &nbsp;
                                        <button type="submit" class="btn btn-default md-btn-flat" name="reset_photo">Reset</button>
                                        <div class="text-black small mt-1">Allowed JPG, JPEG or PNG. Max size of 2MB</div>
                                </div>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <!-- General Tab Content -->
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control mb-1" id="usernameField" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">E-mail</label>
                                        <input type="text" class="form-control mb-1" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" disabled>

                                        <?php if (isset($userData['status'])) : ?>
                                            <?php if ($userData['status'] != 'verified') : ?>
                                                <div class="alert alert-warning mt-3">
                                                    Your email is not confirmed. Please check your inbox.
                                                    <br>
                                                    <a href="verification.php">Resend confirmation</a>
                                                </div>
                                            <?php else : ?>
                                                <div class="alert alert-success mt-3">
                                                    Your email is verified. Thank you!
                                                </div>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <div class="alert alert-danger mt-3">
                                                Unable to retrieve verification status.
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Company/School</label>
                                        <input type="text" class="form-control" name="company" value="<?php echo $company; ?>">
                                    </div>
                                    <div class="text-right mt-3" id="saveChangesContainer">
                                        <button type="submit" class="btn btn-primary" name="save_changes" id="saveChangesBtn">Save changes</button>&nbsp;
                                        <!-- <button type="button" class="btn btn-default" id="cancelBtn">Cancel</button> -->
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Content for Change Password Tab -->
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                                <!-- Change Password Tab Content -->
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label class="form-label">Current password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="current_password" id="currentPassword">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="showCurrentPassword" onclick="togglePassword('currentPassword', 'showCurrentPassword')">
                                                    <i class="far fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">New password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="new_password" id="newPassword">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="showNewPassword" onclick="togglePassword('newPassword', 'showNewPassword')">
                                                    <i class="far fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Repeat new password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="repeat_password" id="repeatPassword">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="showRepeatPassword" onclick="togglePassword('repeatPassword', 'showRepeatPassword')">
                                                    <i class="far fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="change_password">Change Password</button>
                                </form>
                            </div>
                        </div>
                        <!-- Content for Account Actions Tab -->
                        <div class="tab-pane fade" id="account-actions">
                            <div class="card-body pb-2">
                                <form method="POST" action="login_bc.php" id="accountActionsForm">
                                    <div class="text-left mt-3">
                                        <button type="submit" class="btn btn-warning" name="logout" id="logoutBtn">Logout</button>&nbsp;
                                        <button type="button" class="btn btn-danger" id="deleteAccountBtn">Delete Account</button>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <footer class="text-center small tm-footer">
                            <p class="mb-0">
                                Copyright &copy; 21552011105_KELOMPOK 1_TIFRP221PA_UASWEB1.</p>
                        </footer>
                        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Tangkap klik tombol "Delete Account"
                                document.getElementById('deleteAccountBtn').addEventListener('click', function() {
                                    // Tampilkan SweetAlert untuk konfirmasi penghapusan
                                    Swal.fire({
                                        title: 'Are you sure?',
                                        text: 'This action will permanently delete your account.',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Yes, delete it!',
                                        cancelButtonText: 'No, cancel'
                                    }).then((result) => {
                                        // Jika pengguna mengonfirmasi dengan "Yes"
                                        if (result.isConfirmed) {
                                            // Kirim permintaan Ajax untuk menghapus akun
                                            var xhr = new XMLHttpRequest();
                                            xhr.open('POST', 'profile_page.php', true);
                                            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                            xhr.onload = function() {
                                                // Tanggapan dari server
                                                var response = JSON.parse(xhr.responseText);

                                                // Periksa apakah penghapusan berhasil
                                                if (response.success) {
                                                    Swal.fire('Deleted!', 'Your account has been deleted.', 'success').then(() => {
                                                        // Redirect ke halaman login
                                                        window.location.href = 'login_bc.php';
                                                    });
                                                } else {
                                                    Swal.fire('Error!', response.message, 'error');
                                                }
                                            };
                                            // Kirim data formulir
                                            xhr.send('delete_account=true');
                                        }
                                    });
                                });

                                // Tangkap klik tombol "Logout"
                                document.getElementById('logoutBtn').addEventListener('click', function() {
                                    // Kirim form logout saat tombol "Logout" ditekan
                                    document.getElementById('accountActionsForm').submit();
                                });
                            });
                        </script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var changePasswordTab = document.getElementById('account-change-password');
                                var saveChangesContainer = document.getElementById('saveChangesContainer');

                                if (changePasswordTab && saveChangesContainer) {
                                    changePasswordTab.addEventListener('shown.bs.tab', function() {
                                        saveChangesContainer.style.display = 'none';
                                    });

                                    changePasswordTab.addEventListener('hidden.bs.tab', function() {
                                        saveChangesContainer.style.display = 'block';
                                    });
                                } else {
                                    console.error("Elemen dengan ID 'account-change-password' atau 'saveChangesContainer' tidak ditemukan.");
                                }
                            });

                            function togglePassword(inputId, iconId) {
                                var passwordInput = document.getElementById(inputId);
                                var eyeIcon = document.getElementById(iconId);

                                if (passwordInput.type === "password") {
                                    passwordInput.type = "text";
                                    eyeIcon.innerHTML = '<i class="far fa-eye-slash"></i>';
                                } else {
                                    passwordInput.type = "password";
                                    eyeIcon.innerHTML = '<i class="far fa-eye"></i>';
                                }
                            }
                        </script>
</body>

</html>