<!-- File: app/Views/layouts/main_layout.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title></title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" /> -->
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- ... (sesuaikan bagian navbar sesuai kebutuhan) ... -->
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <!-- ... (sesuaikan bagian sidebar sesuai kebutuhan) ... -->
        </div>
        <div id="layoutSidenav_content">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <script>
        function logout() {
            // Lakukan proses logout di sini (hapus sesi, dll.)

            // Redirect ke halaman login setelah logout
            window.location.href = "login.php";
        }
    </script>
    <script>
        // Tambahkan script JavaScript untuk mengatur username saat toggle diklik
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            // Gantilah 'Username' dengan variabel atau fungsi yang menyimpan username pengguna saat login
            document.getElementById('loggedInUsername').innerText = 'Username';
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="../assets/demo/chart-area-demo.js"></script>
    <script src="../assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</body>
<script>
    function logout() {
        // Lakukan logout melalui AJAX atau langsung mengarahkan ke halaman logout PHP
        // Saya akan menunjukkan contoh menggunakan AJAX
        // Pastikan untuk memasukkan library jQuery jika belum ada

        $.ajax({
            type: "POST",
            url: "logout.php", // Gantilah dengan URL yang sesuai
            success: function(response) {
                // Redirect ke halaman login setelah logout
                window.location.href = "login.php";
            },
            error: function(error) {
                console.error("Error during logout:", error);
                // Handle error jika diperlukan
            }
        });
    }
</script>

</html>