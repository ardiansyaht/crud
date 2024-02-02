<?php
include "koneksi.php";
require __DIR__ . '/../../../nonpublic/vendorexcel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

// Cek apakah pengguna sudah login

// Cek apakah peran pengguna adalah "admin"
if ($_SESSION['session_role'] !== 'admin') {
    // Redirect atau lakukan sesuatu jika peran bukan "admin"
    // Contoh: redirect ke halaman tertentu atau tampilkan pesan error
    header("location: unauthorized.php");
    exit();
}

// Inisialisasi objek Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Judul kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Nama');
$sheet->setCellValue('C1', 'Sekolah');
$sheet->setCellValue('D1', 'Jurusan');
$sheet->setCellValue('E1', 'No Hp');
$sheet->setCellValue('F1', 'Alamat');
$sheet->setCellValue('G1', 'Email');
$sheet->setCellValue('H1', 'Bidang');

// Data peserta
$sql = "SELECT * FROM peserta";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$no = 0;
$row = 2;

while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $no++;
    $sheet->setCellValue('A' . $row, $no);
    $sheet->setCellValue('B' . $row, $data['nama']);
    $sheet->setCellValue('C' . $row, $data['sekolah']);
    $sheet->setCellValue('D' . $row, $data['jurusan']);
    $sheet->setCellValue('E' . $row, $data['no_hp']);
    $sheet->setCellValue('F' . $row, $data['alamat']);
    $sheet->setCellValue('G' . $row, $data['email']);
    $sheet->setCellValue('H' . $row, $data['bidang']);

    $row++;
}

// Set lebar kolom otomatis
foreach (range('A', 'H') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// Set nama file Excel
$excelFileName = 'daftar_peserta_pelatihan.xlsx';

// Mengatur header agar browser mengenali sebagai file Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $excelFileName . '"');
header('Cache-Control: max-age=0');

// Simpan ke format Excel 2007 (xlsx)
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Hentikan eksekusi script
exit();
