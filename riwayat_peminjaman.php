<?php
session_start();
include 'db.php';

// Cek jika user adalah admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Proses peminjaman buku (jika form dipost)
if (isset($_POST['submit_peminjaman'])) {
    $user_id = $_POST['user_id'];
    $book_id = $_POST['book_id'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $kelas = $_POST['kelas'];
    $tanggal_peminjaman = date('Y-m-d');
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $status = 'terpinjam';

    if (empty($tanggal_kembali)) {
        echo "<script>alert('Tanggal kembali tidak boleh kosong.');</script>";
    } else {
        // Menggunakan prepared statement untuk keamanan
        $stmt = $conn->prepare("INSERT INTO peminjaman (user_id, book_id, nama_peminjam, kelas, tanggal_peminjaman, tanggal_kembali, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $user_id, $book_id, $nama_peminjam, $kelas, $tanggal_peminjaman, $tanggal_kembali, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Buku berhasil dipinjam!');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }
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

        <!-- Tabel Riwayat Peminjaman -->
        <h2>Riwayat Peminjaman</h2>
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
            // Menampilkan riwayat peminjaman
            $result = $conn->query("SELECT p.*, b.judul FROM peminjaman p JOIN books b ON p.book_id = b.id");
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . htmlspecialchars($row['nama_peminjam']) . "</td>";
                echo "<td>" . htmlspecialchars($row['judul']) . "</td>";
                echo "<td>" . $row['tanggal_peminjaman'] . "</td>";
                echo "<td>" . $row['tanggal_kembali'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>