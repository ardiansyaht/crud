<?php
require_once('tcpdf/tcpdf.php');
include "koneksi.php"; // Pastikan file koneksi.php ada

$pdf = new TCPDF();

// Set dokumen PDF
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Daftar Peserta Pelatihan');

// Tambahkan halaman
$pdf->AddPage();

// Konten PDF
$content = '<h1>Daftar Peserta Pelatihan</h1>';
$content .= '<table border="1">';
$content .= '<thead><tr><th>No</th><th>Nama</th><th>Sekolah</th><th>Jurusan</th><th>No Hp</th><th>Alamat</th></tr></thead>';
$content .= '<tbody>';

$sql = "SELECT * FROM peserta";
$result = mysqli_query($kon, $sql);
$no = 0;

while ($data = mysqli_fetch_array($result)) {
    $no++;
    $content .= "<tr>";
    $content .= "<td>{$no}</td>";
    $content .= "<td>{$data['nama']}</td>";
    $content .= "<td>{$data['sekolah']}</td>";
    $content .= "<td>{$data['jurusan']}</td>";
    $content .= "<td>{$data['no_hp']}</td>";
    $content .= "<td>{$data['alamat']}</td>";
    $content .= "</tr>";
}

$content .= '</tbody></table>';

// Tambahkan konten ke halaman PDF
$pdf->writeHTML($content, true, false, true, false, '');

// Simpan file PDF ke server
$pdf->Output('daftar_peserta_pelatihan.pdf', 'D');
