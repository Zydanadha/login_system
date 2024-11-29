<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

// Cek apakah user memilih buku untuk dipinjam
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : null;

// Ambil detail buku jika ada book_id
if ($book_id) {
    $stmt = $conn->prepare("SELECT judul FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->bind_result($judul_buku);
    $stmt->fetch();
    $stmt->close();
} else {
    $judul_buku = null;
}

// Proses peminjaman buku
if (isset($_POST['submit_peminjaman'])) {
    $user_id = $_SESSION['user_id'];
    $nama_peminjaman = $_POST['nama_peminjaman'];
    $kelas = $_POST['kelas'];
    $tanggal_peminjaman = date('Y-m-d');
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $status = 'terpinjam';

    if (empty($tanggal_kembali)) {
        echo "<script>alert('Tanggal kembali tidak boleh kosong.');</script>";
    } else {
        // Simpan data peminjaman
        $stmt = $conn->prepare("INSERT INTO peminjaman (user_id, book_id, nama_peminjaman, kelas, tanggal_peminjaman, tanggal_kembali, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $user_id, $book_id, $nama_peminjaman, $kelas, $tanggal_peminjaman, $tanggal_kembali, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Buku berhasil dipinjam!'); window.location.href = 'user_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }
}

// Fungsi pencarian buku
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $searchQueryEscaped = mysqli_real_escape_string($conn, $searchQuery);
    $result = $conn->query("SELECT * FROM books WHERE judul LIKE '$searchQueryEscaped%'");
} else {
    $result = $conn->query("SELECT * FROM books");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
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
        h1, h2 {
            text-align: center;
            color: #333;
        }
        a {
            display: block;
            margin: 10px auto;
            text-align: center;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="date"] {
            width: 70%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-right: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
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
        img {
            max-width: 50px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat datang di perpustakaan zyrexx</h1>
        <a href="logout.php">Logout</a>

        <?php if (!$book_id): ?>
            <!-- Bagian Dashboard User -->
            <h2>Daftar Buku</h2>

            <form method="GET">
                <input type="text" name="search" placeholder="Cari buku berdasarkan judul..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit">Cari</button>
                <a href="user_dashboard.php" class="reset-btn">Reset</a>
            </form>

            <table>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Cover</th>
                    <th>Peminjaman</th>
                </tr>
                <?php
                $no = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['judul']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['penulis']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['penerbit']) . "</td>";
                        echo "<td>" . (int)$row['tahun'] . "</td>";
                        echo "<td><img src='uploads/" . htmlspecialchars($row['cover']) . "' alt='Cover'></td>";
                        echo "<td>";
                        echo "<a href='user_dashboard.php?book_id=" . $row['id'] . "' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Pinjam Buku</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Buku tidak ditemukan.</td></tr>";
                }
                ?>
            </table>
        <?php else: ?>
            <!-- Bagian Form Peminjaman -->
            <h2>Form Peminjaman Buku</h2>
            <p><strong>Judul Buku:</strong> <?php echo htmlspecialchars($judul_buku); ?></p>

            <form method="POST">
                <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                <label for="nama">Nama:</label><br>
                <input type="text" name="nama" id="nama" required><br><br>

                <label for="kelas">Kelas:</label><br>
                <input type="text" name="kelas" id="kelas" required><br><br>

                <label for="tanggal_kembali">Tanggal Kembali:</label><br>
                <input type="date" name="tanggal_kembali" id="tanggal_kembali" required><br><br>

                <button type="submit" name="submit_peminjaman">Submit</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
