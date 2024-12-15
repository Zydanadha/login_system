<?php
session_start();
include 'db.php';

// Cek apakah user sudah login dan merupakan admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Ambil semua user
$result = $conn->query("SELECT * FROM user");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input[type="text"], input[type="number"], input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        button {
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        img {
            max-width: 100px;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard Admin</h1>
        <nav>
    <a href="admin_dashboard.php">Dashboard Admin</a> | 
    <a href="riwayat_peminjaman.php">Riwayat Peminjaman</a> | 
    <a href="tambah_buku.php">Tambah buku</a> | 
    <a href="logout.php">Logout</a> | 
    <a href="data_penguna.php">data penguna</a> 
        </nav>


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
                    echo "<p style='color: green;'>Buku berhasil ditambahkan!</p>";
                } else {
                    echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
                }
            } else {
                echo "<p style='color: red;'>Gagal mengunggah file cover.</p>";
            }
        }
        ?>

        <h2>Daftar Buku</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Cover</th>
                <th>Aksi</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM books");
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . $row['judul'] . "</td>";
                echo "<td>" . $row['penulis'] . "</td>";
                echo "<td>" . $row['penerbit'] . "</td>";
                echo "<td>" . $row['tahun'] . "</td>";
                echo "<td><img src='uploads/" . $row['cover'] . "' alt='Cover'></td>";
                echo "<td><a href='edit.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Yakin ingin menghapus?\")'>Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>