<?php
session_start();

$host_db = "localhost";
$user_db = "root";
$pass_db = "";
$nama_db = "crud";
$koneksi = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

$err = "";
$email = "";

// Cek apakah pengguna sudah login
if (!isset($_SESSION['session_email'])) {
    header("location: login.php");
    exit();
}
if ($_SESSION['session_role'] !== 'admin') {
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
        header("location: ../infinite_loop/index.php");
        exit();
    }
}

// Fungsi untuk mencegah inputan karakter yang tidak sesuai
function input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Cek apakah ada kiriman form dari method post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = input($_POST["nama"]);
    $sekolah = input($_POST["sekolah"]);
    $jurusan = input($_POST["jurusan"]);
    $no_hp = input($_POST["no_hp"]);
    $alamat = input($_POST["alamat"]);

    // Query input menginput data kedalam tabel anggota
    $sql = "INSERT INTO peserta (nama, sekolah, jurusan, no_hp, alamat) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $nama, $sekolah, $jurusan, $no_hp, $alamat);
    $hasil = mysqli_stmt_execute($stmt);

    if ($hasil) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'error' => mysqli_error($koneksi)));
    }

    // Hentikan eksekusi lebih lanjut setelah memberikan respons JSON
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran Peserta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">
    <h2>Input Data</h2>
    <form action="create.php" method="post" id="myForm">
        <div class="form-group">
            <label>Nama:</label>
            <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama" required />
        </div>
        <div class="form-group">
            <label>Sekolah:</label>
            <input type="text" name="sekolah" class="form-control" placeholder="Masukkan Nama Sekolah" required/>
        </div>
        <div class="form-group">
            <label>Jurusan:</label>
            <input type="text" name="jurusan" class="form-control" placeholder="Masukkan Jurusan" required/>
        </div>
        <div class="form-group">
            <label>No HP:</label>
            <input type="number" name="no_hp" class="form-control" placeholder="Masukkan No HP" required/>
        </div>
        <div class="form-group">
            <label>Alamat:</label>
            <textarea name="alamat" class="form-control" rows="5" placeholder="Masukkan Alamat" required></textarea>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
    <script>
    document.getElementById("myForm").addEventListener("submit", function (event) {
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
                });
            } else {
                // Menampilkan notifikasi gagal menggunakan SweetAlert2
                Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: 'Pendaftaran gagal. Silakan coba lagi.',
                });
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







