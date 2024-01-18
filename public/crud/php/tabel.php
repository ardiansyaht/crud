<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <title>TechForge Academy</title>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <span class="navbar-brand mb-0 h1">TechForge Academy</span>
    </nav>
    <div class="container">
        <br>
        <h4><center>DAFTAR PESERTA PELATIHAN</center></h4>
        
        <?php
        
        include "koneksi.php";
        if (!isset($_SESSION['session_username'])) {
            header("location: login.php");
            exit();
        }
        if ($_SESSION['session_role'] !== 'admin') {
            // Redirect atau lakukan sesuatu jika peran bukan "admin"
            // Contoh: redirect ke halaman tertentu atau tampilkan pesan error
            header("location: unauthorized.php");
            exit();
        }

        //Cek apakah ada kiriman form dari method post
        if (isset($_GET['id_peserta'])) {
            $id_peserta = htmlspecialchars($_GET["id_peserta"]);
            $sql = "DELETE FROM peserta WHERE id_peserta='$id_peserta'";
            $hasil = mysqli_query($kon, $sql);
        }

        // Pemrosesan Pencarian
        if (isset($_GET['search'])) {
            $keyword = mysqli_real_escape_string($kon, $_GET['search']);
            $sql = "SELECT * FROM peserta WHERE nama LIKE '%$keyword%' ORDER BY id_peserta DESC";
        } else {
            $sql = "SELECT * FROM peserta ORDER BY id_peserta DESC";
        }

        $hasil = mysqli_query($kon, $sql);
        $no = 0;
        ?>

        <table class="my-3 table table-bordered">
            <thead>
                <tr class="table-primary">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Sekolah</th>
                    <th>Jurusan</th>
                    <th>No Hp</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>Bidang</th>
                    <th colspan='2'>Aksi</th>
                </tr>
            </thead>
            <?php
            while ($data = mysqli_fetch_array($hasil)) {
                $no++;
                ?>
                <tbody>
                    <tr>
                        <td><?php echo $no;?></td>
                        <td><?php echo $data["nama"]; ?></td>
                        <td><?php echo $data["sekolah"];   ?></td>
                        <td><?php echo $data["jurusan"];   ?></td>
                        <td><?php echo $data["no_hp"];   ?></td>
                        <td><?php echo $data["alamat"];   ?></td>
                        <td><?php echo $data["email"];   ?></td>
                        <td><?php echo $data["bidang"];   ?></td>
                        <td>
                            <a href="update.php?id_peserta=<?php echo htmlspecialchars($data['id_peserta']); ?>" class="btn btn-warning" role="button">Update</a>
                            <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?id_peserta=<?php echo $data['id_peserta']; ?>" class="btn btn-danger" role="button">Delete</a>
                        </td>
                    </tr>
                </tbody>
                <?php
            }
            ?>
        </table>
        <!-- <a href="create.php" class="btn btn-primary" role="button">Tambah Data</a> -->
        <a href="generate_pdf.php" class="btn btn-success" role="button">Download PDF</a>
    </div>
</body>
</html>
