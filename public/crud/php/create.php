<?php
header('X-Frame-Options: DENY');
session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'use_only_cookies' => true,
]);

require  'config_koneksi.php';

$host_db        = DB_HOST;
$user_db        = DB_USER;
$pass_db        = DB_PASS;
$nama_db        = DB_NAME;
$koneksi = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$err = "";
$email = "";


if (isset($_SESSION['session_email']) && isset($_SESSION['session_role'])) {
    $allowedRole = $_SESSION['session_role'];

    $allowedEmail = $_SESSION['session_email'];

    // Query untuk mendapatkan data pengguna berdasarkan email
    $query = "SELECT * FROM tb_login_bc WHERE email = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $allowedEmail);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        // Handle kesalahan query jika perlu
        die("Query failed");
    }

    $user = mysqli_fetch_assoc($result);

    if (!$user || $user['status'] !== 'verified') {
        // Email tidak ditemukan di database atau status tidak terverifikasi
        // Lakukan sesuatu (redirect atau lainnya)
        header("location: ../../infinite_loop/php/homepage.php");
        exit();
    }

    // Cek apakah email sudah terdaftar di tb_peserta
    $query_cek_peserta = "SELECT * FROM peserta WHERE email = ?";
    $stmt_cek_peserta = mysqli_prepare($koneksi, $query_cek_peserta);
    mysqli_stmt_bind_param($stmt_cek_peserta, "s", $allowedEmail);
    mysqli_stmt_execute($stmt_cek_peserta);

    $result_cek_peserta = mysqli_stmt_get_result($stmt_cek_peserta);

    if (mysqli_num_rows($result_cek_peserta) > 0) {
        // Email sudah terdaftar di tb_peserta, beri respons atau tindakan yang sesuai
        $notification = "Anda sudah mengisi formulir pendaftaran.";

        // Replace the following line with the SweetAlert code
        echo json_encode(array('success' => false, 'error' => $notification));
        header("location: ../../infinite_loop/php/homepage.php");
        exit();
    }
} else {
    // Jika 'session_email' atau 'session_role' tidak di-set, redirect atau tindakan lainnya
    header("location: ../../infinite_loop/php/homepage.php");
    exit();
}
// Fungsi untuk mencegah inputan karakter yang tidak sesuai
function input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$bidang = '';
// Cek apakah ada kiriman form dari method post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = input($_POST["nama"]);
    $sekolah = input($_POST["sekolah"]);
    $jurusan = input($_POST["jurusan"]);
    $no_hp = input($_POST["no_hp"]);
    $alamat = input($_POST["alamat"]);
    $bidang = input($_POST["bidang"]);

    // Validasi karakter maksimum
    function validateMaxLength($value, $max_length, $field_name)
    {
        if (strlen($value) > $max_length) {
            return "$field_name terlalu panjang. Maksimum $max_length karakter diperbolehkan.";
        }
        return null;
    }
    $err = [
        validateMaxLength($nama, 30, 'Nama'),
        validateMinLength($nama, 4, 'Nama'),
        validateMaxLength($sekolah, 30, 'Sekolah'),
        validateMinLength($sekolah, 4, 'Sekolah'),
        validateMaxLength($jurusan, 20, 'Jurusan'),
        validateMinLength($jurusan, 4, 'Jurusan'),
        validateMaxLength($no_hp, 15, 'Nomor Telepon'),
        validateMinLength($no_hp, 4, 'Nomor Telepon'),
        validateMaxLength($alamat, 50, 'Alamat'),
        validateMinLength($alamat, 4, 'Alamat'),
        isValidAlphabetic($nama, 'Nama'),
        isValidAlphabetic($sekolah, 'Sekolah'),
        isValidAlphabetic($jurusan, 'Jurusan'),
        isValidAlphabetic($alamat, 'Alamat'),
    ];


    // Hapus elemen array yang bernilai null (valid)
    $err = array_filter($err);

    if (empty($err)) {
        // Lanjutkan dengan penyimpanan data jika tidak ada kesalahan
        $opsi_bidang = ['web-development', 'data-science', 'full-stack-development', 'mobile-app-development', 'cyber-security', 'devops', 'ui-ux-design', 'game-development'];
        if (in_array($bidang, $opsi_bidang)) {
            // Bidang yang dipilih valid, lanjutkan dengan penyimpanan data
            $sql = "INSERT INTO peserta (nama, sekolah, jurusan, no_hp, alamat, email, bidang) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "sssssss", $nama, $sekolah, $jurusan, $no_hp, $alamat, $allowedEmail, $bidang);
            $hasil = mysqli_stmt_execute($stmt);

            if ($hasil) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false, 'error' => mysqli_error($koneksi)));
            }

            // Hentikan eksekusi lebih lanjut setelah memberikan respons JSON
            exit();
        } else {
            // Jika bidang yang dipilih tidak valid, Anda bisa memberikan respons atau melakukan tindakan lain
            echo json_encode(array('success' => false, 'error' => 'Bidang yang dipilih tidak valid.'));
            exit();
        }
    } else {
        // Tampilkan pesan kesalahan
        echo json_encode(array('success' => false, 'error' => implode('<br>', $err)));
        exit();
    }
}
function isValidAlphabetic($value, $field_name)
{
    if (!preg_match('/^[a-zA-Z\s]+$/', $value)) {
        return "$field_name hanya boleh mengandung huruf.";
    }
    return null;
}
// Fungsi validasi untuk memeriksa panjang minimal 4 karakter
function validateMinLength($value, $min_length, $field_name)
{
    if (strlen($value) < $min_length) {
        return "$field_name harus memiliki panjang minimal $min_length karakter.";
    }
    return null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran Peserta</title>
    <link rel="icon" type="image/png" href="crud/assets/img/favicon.ico" />
    <link rel="stylesheet" href="..//css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <h2>Formulir Pendaftaran Peserta</h2>
        <form action="create.php" method="post" id="myForm">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Nama:</span>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama" required />
                </div>
                <div class="input-box">
                    <span class="details">Sekolah:</span>
                    <input type="text" name="sekolah" class="form-control" placeholder="Masukkan Nama Sekolah" required />
                </div>
                <div class="input-box">
                    <span class="details">Jurusan:</span>
                    <input type="text" name="jurusan" class="form-control" placeholder="Masukkan Jurusan" required />
                </div>
                <div class="input-box">
                    <span class="details">No HP:</span>
                    <input type="number" name="no_hp" class="form-control" placeholder="Masukkan No HP" required />
                </div>
                <div class="input-box">
                    <span class="details">Alamat:</span>
                    <textarea name="alamat" class="form-control" rows="5" placeholder="Masukkan Alamat" required></textarea>
                </div>
            </div>
            <div class="category">
                <span class="details">Bidang Pilihan:</span>
                <select name="bidang" class="form-control" required>
                    <option value="" selected disabled hidden>Pilih Bidang</option>
                    <option value="web-development" <?php echo ($bidang === 'web-development') ? 'selected' : ''; ?>>Web Development</option>
                    <option value="data-science" <?php echo ($bidang === 'data-science') ? 'selected' : ''; ?>>Data Science</option>
                    <option value="full-stack-development" <?php echo ($bidang === 'full-stack-development') ? 'selected' : ''; ?>>Full Stack Development</option>
                    <option value="mobile-app-development" <?php echo ($bidang === 'mobile-app-development') ? 'selected' : ''; ?>>Mobile App Development</option>
                    <option value="cyber-security" <?php echo ($bidang === 'cyber-security') ? 'selected' : ''; ?>>Cyber Security</option>
                    <option value="devops" <?php echo ($bidang === 'devops') ? 'selected' : ''; ?>>DevOps</option>
                    <option value="ui-ux-design" <?php echo ($bidang === 'ui-ux-design') ? 'selected' : ''; ?>>UI/UX Design</option>
                    <option value="game-development" <?php echo ($bidang === 'game-development') ? 'selected' : ''; ?>>Game Development</option>
                </select>
            </div>

            <div class="button">
                <input type="submit" value="Register">
            </div>
            <script>
                document.getElementById("myForm").addEventListener("submit", function(event) {
                    event.preventDefault(); // Mencegah submit langsung

                    // Menampilkan konfirmasi menggunakan SweetAlert2
                    Swal.fire({
                        title: 'Apakah Anda yakin telah mengisi dengan benar?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika user memilih "Yes", lanjutkan dengan mengirim formulir
                            submitForm();
                        } else {
                            // Jika user memilih "No", tidak melakukan apa-apa
                        }
                    });
                });

                function submitForm() {
                    // Menggunakan Fetch API untuk mengirim data form ke server
                    fetch("create.php", {
                            method: "POST",
                            body: new FormData(document.getElementById("myForm")),
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Handle response dari server (response.json() mengembalikan Promise)
                            if (data.success) {
                                // Menampilkan notifikasi sukses menggunakan SweetAlert2
                                Swal.fire({
                                    title: 'Pendaftaran Berhasil',
                                    icon: 'success',
                                    text: 'Terima kasih, pendaftaran Anda berhasil.',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    // Lakukan tindakan lain jika perlu, seperti membersihkan formulir
                                    document.getElementById("myForm").reset();
                                    window.location.href = '../../infinite_loop/php/homepage.php';
                                });
                            } else {
                                // Menampilkan notifikasi gagal menggunakan SweetAlert2
                                if (data.error.includes('Anda sudah mengisi formulir pendaftaran')) {
                                    Swal.fire({
                                        title: 'Error',
                                        icon: 'error',
                                        text: 'Anda sudah mengisi formulir pendaftaran.',
                                    });
                                } else if (data.error.includes('Email sudah terdaftar di tb_peserta')) {
                                    Swal.fire({
                                        title: 'Error',
                                        icon: 'error',
                                        text: 'Email sudah terdaftar sebagai peserta.',
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        icon: 'error',
                                        text: 'Pendaftaran gagal. Silakan coba lagi.',
                                    });
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            </script>
    </div>
</body>

</html>