<?php
header('X-Frame-Options: DENY');
session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'use_only_cookies' => true,
]);

// Cek apakah peran pengguna adalah "admin"
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
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="homepage.php">
            <?php if (isset($_SESSION['session_role'])) : ?>
                <i class="fas fa-user"></i> <?php echo $_SESSION['session_role']; ?>
            <?php endif; ?>
        </a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!--navbar search-->
        <form class="form-inline mb-2 ml-auto" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <div class="input-group">
                <input class="form-control" type="text" name="search" placeholder="Cari berdasarkan nama" />
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                    <span id="loggedInUsername">
                        <?php
                        if (isset($_SESSION['session_role'])) {
                            echo $role = $_SESSION['session_role'];
                        }
                        ?>
                    </span>
                    <!-- Ikon Online -->
                    <i class="fas fa-circle text-success" id="onlineIndicator"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <!-- <li><a class="dropdown-item" href="#!">Kategori</a></li>
           
            <li><hr class="dropdown-divider" /></li> -->
                    <li><a class="dropdown-item" href="#" onclick="logout()">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Addons</div>
                        <a class="nav-link" href="../../infinite_loop/php/homepage.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Halaman Utama
                        </a>
                        <!-- <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a> -->
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php
                    if (isset($_SESSION['session_role'])) {
                        $role = $_SESSION['session_role'];
                    }
                    ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <!-- <h1 class="mt-4">Dashboard</h1> -->
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>

                    <!-- Bagian Daftar Peserta Pelatihan -->
                    <?php include "tabel.php"; ?>
                    <?php include "barchart.php"; ?>


                </div>
            </main>

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
            url: "../../infinite_loop/php/logout.php", // Gantilah dengan URL yang sesuai
            success: function(response) {
                // Redirect ke halaman login setelah logout
                window.location.href = "../../infinite_loop/php/login_bc.php";
            },
            error: function(error) {
                console.error("Error during logout:", error);
                // Handle error jika diperlukan
            }
        });
    }
</script>


</html>