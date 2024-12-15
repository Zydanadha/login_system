<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if ($_SESSION['role'] !== 'user') {
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['book_id'])) {
        $user_id = $_SESSION['user_id'];
        $book_id = $_POST['book_id'];
        $nama_peminjaman = $_POST['nama_peminjaman'];
        $kelas = $_POST['kelas'];
        $tanggal_peminjaman = date('Y-m-d');
        $tanggal_kembali = $_POST['tanggal_kembali'];
        $status = 'terpinjam';

        if (empty($tanggal_kembali)) {
            echo "<script>alert('Tanggal kembali tidak boleh kosong.');</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO peminjaman (user_id, book_id, nama_peminjam, kelas, tanggal_peminjaman, tanggal_kembali, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisssss", $user_id, $book_id, $nama_peminjaman, $kelas, $tanggal_peminjaman, $tanggal_kembali, $status);

            if ($stmt->execute()) {
                echo "<script>alert('Buku berhasil dipinjam!'); window.location.href = 'user_dashboard.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
    } else {
        echo "<script>alert('ID Buku tidak ditemukan.');</script>";
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
        .book-grid {
           display: grid;
           grid-template-columns: repeat(4, 1fr); /* 4 kolom */
           gap: 50px; /* Jarak antar kolom dan baris */
           padding: 20px; /* Jarak dari tepi container */
           justify-items: center; /* Pusatkan isi grid */
}
        .book-card {
          border: 1px solid #ddd;
          border-radius: 10px;
          padding: 20px;
          width: 100%;
          max-width: 200px; /* Lebar maksimal setiap buku */
          text-align: center;
          background-color: #fff;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
          margin: 10px; /* Tambahkan jarak antar elemen */
}

        .book-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .book-card h3 {
            margin: 10px 0;
            font-size: 1.2em;
            color: #333;
        }
        .book-card p {
            margin: 5px 0;
            font-size: 0.9em;
            color: #555;
        }
        .book-card a {
            display: block;
            margin-top: 15px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .book-card a:hover {
            background-color: #0056b3;
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

        <div class="book-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="book-card">
                        <img src="uploads/<?php echo htmlspecialchars($row['cover']); ?>" alt="Cover">
                        <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                        <p>Penulis: <?php echo htmlspecialchars($row['penulis']); ?></p>
                        <p>Penerbit: <?php echo htmlspecialchars($row['penerbit']); ?></p>
                        <p>Tahun: <?php echo (int)$row['tahun']; ?></p>
                        <a href="user_dashboard.php?book_id=<?php echo $row['id']; ?>">Pinjam Buku</a>
                    </div>
                    <?php
                }
            } else {
                echo "<p>Buku tidak ditemukan.</p>";
            }
            ?>
        </div>
      
    <?php else: ?>
        <!-- Bagian Form Peminjaman -->
        <h2>Form Peminjaman Buku</h2>
        <p><strong>Judul Buku:</strong> <?php echo htmlspecialchars($judul_buku); ?></p>

        <form action="user_dashboard.php" method="POST">
    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>"> <!-- Sembunyikan book_id -->
    <label for="nama_peminjaman">Nama Peminjam:</label>
    <input type="text" name="nama_peminjaman" required><br>
    <label for="kelas">Kelas:</label>
    <input type="text" name="kelas" required><br>
    <label for="tanggal_kembali">Tanggal Kembali:</label>
    <input type="date" name="tanggal_kembali" required><br>
    <button type="submit" name="submit_peminjaman">Pinjam Buku</button>
</form>

    <?php endif; ?>
</div>
</body>
</html>
