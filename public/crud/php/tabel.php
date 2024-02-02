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
        <h4>
            <center>DAFTAR PESERTA PELATIHAN</center>
        </h4>

        <?php
        // Include the PDO connection
        include "koneksi.php";

        if ($_SESSION['session_role'] !== 'admin') {
            // Redirect or do something if the role is not "admin"
            header("location: ../../../unauthorized.php");
            exit();
        }

        // Check if there is a form submission via the GET method
        if (isset($_GET['id_peserta'])) {
            $id_peserta = htmlspecialchars($_GET["id_peserta"]);
            $sql = "DELETE FROM peserta WHERE id_peserta = :id_peserta";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_peserta', $id_peserta, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Processing Search
        if (isset($_GET['search'])) {
            $keyword = '%' . $_GET['search'] . '%';
            $sql = "SELECT * FROM peserta WHERE nama LIKE :keyword ORDER BY id_peserta DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        } else {
            $sql = "SELECT * FROM peserta ORDER BY id_peserta DESC";
            $stmt = $pdo->query($sql);
        }

        $stmt->execute();
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
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $no++;
            ?>
                <tbody>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data["nama"]; ?></td>
                        <td><?php echo $data["sekolah"];   ?></td>
                        <td><?php echo $data["jurusan"];   ?></td>
                        <td><?php echo $data["no_hp"];   ?></td>
                        <td><?php echo $data["alamat"];   ?></td>
                        <td><?php echo $data["email"];   ?></td>
                        <td><?php echo $data["bidang"];   ?></td>
                        <td>
                            <a href="update.php?id_peserta=<?php echo htmlspecialchars($data['id_peserta']); ?>" class="btn btn-warning" role="button">Update</a>
                            <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id_peserta=<?php echo $data['id_peserta']; ?>" class="btn btn-danger" role="button">Delete</a>
                        </td>
                    </tr>
                </tbody>
            <?php
            }
            ?>
        </table>
        <?php include "barchart.php"; ?>

        <a href="generate_pdf.php" class="btn btn-danger" role="button">Download PDF</a>
        <a href="generate_excel.php" class="btn btn-success" role="button">Download Excel</a>
    </div>
</body>

</html>