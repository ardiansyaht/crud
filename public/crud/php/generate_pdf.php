<?php
require __DIR__ . ('/../../../nonpublic/tcpdf/tcpdf.php');
include "koneksi.php"; // Pastikan file koneksi.php ada
session_start();

// Cek apakah pengguna sudah login

// Cek apakah peran pengguna adalah "admin"
if ($_SESSION['session_role'] !== 'admin') {
    // Redirect atau lakukan sesuatu jika peran bukan "admin"
    // Contoh: redirect ke halaman tertentu atau tampilkan pesan error
    header("location: unauthorized.php");
    exit();
}

$pdf = new TCPDF();

// Set dokumen PDF
$pdf->SetAuthor('TechForge Academy');
$pdf->SetTitle('Daftar Peserta Pelatihan');

// Tambahkan halaman
$pdf->AddPage();

// Konten PDF
$content = '<h1>Daftar Peserta Pelatihan</h1>';
$content .= '<table border="1">';
$content .= '<thead><tr><th>No</th><th>Nama</th><th>Sekolah</th><th>Jurusan</th><th>No Hp</th><th>Email</th><th>Bidang</th><th>Alamat</th></tr></thead>';
$content .= '<tbody>';

$sql = "SELECT * FROM peserta";
$stmt = $pdo->query($sql);
$no = 0;

while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $no++;
    $content .= "<tr>";
    $content .= "<td>{$no}</td>";
    $content .= "<td>{$data['nama']}</td>";
    $content .= "<td>{$data['sekolah']}</td>";
    $content .= "<td>{$data['jurusan']}</td>";
    $content .= "<td>{$data['no_hp']}</td>";
    $content .= "<td>{$data['email']}</td>"; // Tambahkan kolom Email
    $content .= "<td>{$data['bidang']}</td>"; // Tambahkan kolom Bidang
    $content .= "<td>{$data['alamat']}</td>";
    $content .= "</tr>";
}

$content .= '</tbody></table>';

// Tambahkan konten ke halaman PDF
$pdf->writeHTML($content, true, false, true, false, '');

// Simpan file PDF ke server
$pdf->Output('daftar_peserta_pelatihan.pdf', 'D');
