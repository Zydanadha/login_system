<?php
session_start();
include 'db.php';

// Redirect jika bukan role 'owner'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <style>
        /* Reset margin dan padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Style dasar */
        body {
            font-family: Arial, sans-serif;
            height: 100vh; /* Tinggi halaman 100% viewport */
            display: flex; /* Menggunakan Flexbox */
            justify-content: center; /* Pusatkan horizontal */
            align-items: center; /* Pusatkan vertikal */
            background-color: #f8f9fa;
        }

        .container {
            text-align: center; /* Tengahkan teks */
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Tambahkan bayangan */
            max-width: 400px;
            width: 100%;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 20px;
            color: #666;
        }

        a {
            display: block;
            margin: 10px auto;
            padding: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #007BFF;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }

        a.logout {
            background-color: #dc3545;
        }

        a:hover {
            background-color: #0056b3;
        }

        a.logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Owner Dashboard</h2>
        <p>Silakan pilih data yang ingin dilihat:</p>
        <a href="databuku_owner.php">Lihat Data Buku</a>
        <a href="datapenguna_owner.php">Lihat Data Pengguna</a>
        <a href="logout.php" class="logout">kembali</a> <!-- Tombol Logout -->
    </div>
</body>
</html>
