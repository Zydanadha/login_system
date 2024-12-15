<?php
session_start();
include 'db.php';

// Cek apakah admin sudah login
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Menampilkan riwayat peminjaman dari semua user
$query = "SELECT p.*, b.judul, u.username FROM peminjaman p 
          JOIN books b ON p.book_id = b.id
          JOIN user u ON p.user_id = u.id";
$result = $conn->query($query);

// Cek apakah query berhasil dijalankan
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman & Form Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_dashboard.php" style="text-decoration: none; color: #007bff; font-weight: bold;">&larr; Kembali ke Dashboard Admin</a>

        <h2>Riwayat Peminjaman Buku</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
            <?php
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . (isset($row['username']) ? htmlspecialchars($row['username']) : 'Data tidak ada') . "</td>";
                echo "<td>" . (isset($row['judul']) ? htmlspecialchars($row['judul']) : 'Data tidak ada') . "</td>";
                echo "<td>" . (isset($row['tanggal_peminjaman']) ? htmlspecialchars($row['tanggal_peminjaman']) : 'Data tidak ada') . "</td>";
                echo "<td>" . (isset($row['tanggal_kembali']) ? htmlspecialchars($row['tanggal_kembali']) : 'Data tidak ada') . "</td>";
                echo "<td>" . (isset($row['status']) ? htmlspecialchars($row['status']) : 'Data tidak ada') . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
