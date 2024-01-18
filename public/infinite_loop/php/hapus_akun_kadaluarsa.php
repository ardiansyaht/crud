<?php
require 'vendor/autoload.php'; // Sesuaikan dengan lokasi dan nama folder Anda

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$host_db        = "localhost";
$user_db        = "root";
$pass_db        = "";
$nama_db        = "crud";
$tabel_pengguna = "tb_login_bc";
$koneksi        = mysqli_connect($host_db, $user_db, $pass_db, $nama_db);

// Hapus akun yang tidak terverifikasi atau OTP-nya sudah kadaluarsa
$sqlDeleteExpiredAccounts = "DELETE FROM $tabel_pengguna WHERE otp_code IS NULL OR otp_expiration <= NOW()";
mysqli_query($koneksi, $sqlDeleteExpiredAccounts);
?>
