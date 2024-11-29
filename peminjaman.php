<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('User ID tidak ditemukan. Silakan login kembali.');</script>";
    header('Location: logout.php');
    exit();
}

// Ambil book_id dari URL
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;

// Ambil informasi buku untuk ditampilkan (opsional)
$judul_buku = '';
if ($book_id > 0) {
    $stmt = $conn->prepare("SELECT judul FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->bind_result($judul_buku);
    $stmt->fetch();
    $stmt->close();
}

// Proses peminjaman buku
if (isset($_POST['submit_peminjaman'])) {
    $user_id = $_SESSION['user_id'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $tanggal_peminjaman = date('Y-m-d');
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $status = 'terpinjam';

    // Validasi form
    if (empty($nama) || empty($kelas) || empty($tanggal_kembali)) {
        echo "<script>alert('Semua field wajib diisi.');</script>";
    } else {
        // Menggunakan prepared statement untuk keamanan
        $stmt = $conn->prepare("INSERT INTO peminjaman (user_id, book_id, nama, kelas, tanggal_peminjaman, tanggal_kembali, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $user_id, $book_id, $nama, $kelas, $tanggal_peminjaman, $tanggal_kembali, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Buku berhasil dipinjam!'); window.location.href = 'user_dashboard.php';</script>";
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
    <title>Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: #f9f9f9;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="date"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Form Peminjaman Buku</h1>

        <!-- Tampilkan informasi buku -->
        <?php if (!empty($judul_buku)): ?>
            <p><strong>Judul Buku:</strong> <?php echo htmlspecialchars($judul_buku); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="nama">Nama:</label><br>
            <input type="text" name="nama" id="nama" required><br>

            <label for="kelas">Kelas:</label><br>
            <input type="text" name="kelas" id="kelas" required><br>

            <label for="tanggal_kembali">Tanggal Kembali:</label><br>
            <input type="date" name="tanggal_kembali" id="tanggal_kembali" required><br>

            <button type="submit" name="submit_peminjaman">Submit</button>
        </form>
    </div>
</body>
</html>
