<?php
session_start();
include 'db.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input[type="text"], input[type="number"], input[type="file"], button {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        input[type="text"]:focus, input[type="number"]:focus, input[type="file"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            text-align: center;
            font-size: 16px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Buku</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="judul" placeholder="Judul Buku" required>
            <input type="text" name="penulis" placeholder="Penulis Buku" required>
            <input type="text" name="penerbit" placeholder="Penerbit">
            <input type="number" name="tahun" placeholder="Tahun Terbit">
            <input type="file" name="cover">
            <button type="submit" name="submit">Tambah Buku</button>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $judul = $_POST['judul'];
            $penulis = $_POST['penulis'];
            $penerbit = $_POST['penerbit'];
            $tahun = $_POST['tahun'];
            $cover = $_FILES['cover']['name'];
            $cover_tmp = $_FILES['cover']['tmp_name'];

            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            $upload_path = "uploads/" . $cover;

            if (move_uploaded_file($cover_tmp, $upload_path)) {
                $query = "INSERT INTO books (judul, penulis, penerbit, tahun, cover) VALUES ('$judul', '$penulis', '$penerbit', '$tahun', '$cover')";
                if ($conn->query($query)) {
                    echo "<p class='success'>Buku berhasil ditambahkan!</p>";
                } else {
                    echo "<p class='error'>Error: " . $conn->error . "</p>";
                }
            } else {
                echo "<p class='error'>Gagal mengunggah file cover.</p>";
            }
        }
        ?>

        <a href="admin_dashboard.php" class="back-link">Kembali ke Dashboard</a>
    </div>
</body>
</html>
