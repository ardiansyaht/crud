<?php

$koneksi = mysqli_connect("localhost", "root", "", "crud");

// Periksa koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query untuk mengambil data
$query = "SELECT bidang, COUNT(*) as jumlah FROM peserta GROUP BY bidang";
$result = mysqli_query($koneksi, $query);

// Inisialisasi array untuk menyimpan data
$bidang = [];
$jumlah = [];

// Isi array dengan data dari database
while ($row = mysqli_fetch_assoc($result)) {
    $bidang[] = $row['bidang'];
    $jumlah[] = $row['jumlah'];
}

// Tutup koneksi ke database
mysqli_close($koneksi);
if ($_SESSION['session_role'] !== 'admin') {
    // Redirect atau lakukan sesuatu jika peran bukan "admin"
    // Contoh: redirect ke halaman tertentu atau tampilkan pesan error
    header("location: ../../../unauthorized.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div style="width: 80%; margin: auto;">
        <canvas id="barChart"></canvas>
    </div>

    <script>
        var bidang = <?php echo json_encode($bidang); ?>;
        var jumlah = <?php echo json_encode($jumlah); ?>;

        var ctx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: bidang,
                datasets: [{
                    label: 'Jumlah',
                    data: jumlah,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>