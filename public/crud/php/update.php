<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Data Peserta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>

    <div class="container mt-5">
        <?php
        session_start();

        // Include the PDO connection
        include "koneksi.php";

        if ($_SESSION['session_role'] !== 'admin') {
            // Redirect or do something if the role is not "admin"
            header("location: ../../../unauthorized.php");
            exit();
        }

        // Function to prevent invalid input characters
        function input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // Check if there is a value sent using the GET method with the name id_peserta
        if (isset($_GET['id_peserta'])) {
            $id_peserta = input($_GET["id_peserta"]);

            $sql = "SELECT * FROM peserta WHERE id_peserta = :id_peserta";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_peserta', $id_peserta, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Check if there is a form submission via the POST method
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_peserta = htmlspecialchars($_POST["id_peserta"]);
            $nama = input($_POST["nama"]);
            $sekolah = input($_POST["sekolah"]);
            $jurusan = input($_POST["jurusan"]);
            $no_hp = input($_POST["no_hp"]);
            $alamat = input($_POST["alamat"]);
            $bidang = input($_POST["bidang"]);

            // Query to update data in the peserta table
            $sql = "UPDATE peserta SET
            nama = :nama,
            sekolah = :sekolah,
            jurusan = :jurusan,
            no_hp = :no_hp,
            alamat = :alamat,
            bidang = :bidang
            WHERE id_peserta = :id_peserta";

            // Execute the query
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nama', $nama, PDO::PARAM_STR);
            $stmt->bindParam(':sekolah', $sekolah, PDO::PARAM_STR);
            $stmt->bindParam(':jurusan', $jurusan, PDO::PARAM_STR);
            $stmt->bindParam(':no_hp', $no_hp, PDO::PARAM_STR);
            $stmt->bindParam(':alamat', $alamat, PDO::PARAM_STR);
            $stmt->bindParam(':bidang', $bidang, PDO::PARAM_STR);
            $stmt->bindParam(':id_peserta', $id_peserta, PDO::PARAM_INT);

            // Execute the statement
            $stmt->execute();

            // Check if the query was successful
            if ($stmt->rowCount() > 0) {
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";
            }
        }
        ?>

        <h2>Update Data Peserta</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama" required value="<?php echo $data['nama']; ?>" />
            </div>
            <div class="form-group">
                <label for="sekolah">Sekolah:</label>
                <input type="text" name="sekolah" class="form-control" placeholder="Masukkan Nama Sekolah" required value="<?php echo $data['sekolah']; ?>" />
            </div>
            <div class="form-group">
                <label for="jurusan">Jurusan:</label>
                <input type="text" name="jurusan" class="form-control" placeholder="Masukkan Jurusan" required value="<?php echo $data['jurusan']; ?>" />
            </div>
            <div class="form-group">
                <label for="no_hp">No HP:</label>
                <input type="number" name="no_hp" class="form-control" placeholder="Masukkan No HP" required value="<?php echo $data['no_hp']; ?>" />
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <textarea name="alamat" class="form-control" rows="5" placeholder="Masukkan Alamat" required><?php echo $data['alamat']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="bidang">Bidang Pilihan:</label>
                <select name="bidang" class="form-control" required>
                    <option value="web-development" <?php echo ($data['bidang'] === 'web-development') ? 'selected' : ''; ?>>Web Development</option>
                    <option value="data-science" <?php echo ($data['bidang'] === 'data-science') ? 'selected' : ''; ?>>Data Science</option>
                    <option value="full-stack-development" <?php echo ($data['bidang'] === 'full-stack-development') ? 'selected' : ''; ?>>Full Stack Development</option>
                    <option value="mobile-app-development" <?php echo ($data['bidang'] === 'mobile-app-development') ? 'selected' : ''; ?>>Mobile App Development</option>
                    <option value="cyber-security" <?php echo ($data['bidang'] === 'cyber-security') ? 'selected' : ''; ?>>Cyber Security</option>
                    <option value="devops" <?php echo ($data['bidang'] === 'devops') ? 'selected' : ''; ?>>DevOps</option>
                    <option value="ui-ux-design" <?php echo ($data['bidang'] === 'ui-ux-design') ? 'selected' : ''; ?>>UI/UX Design</option>
                    <option value="game-development" <?php echo ($data['bidang'] === 'game-development') ? 'selected' : ''; ?>>Game Development</option>
                </select>
            </div>

            <input type="hidden" name="id_peserta" value="<?php echo $data['id_peserta']; ?>" />

            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

</body>

</html>