<?php
session_start();
include 'db.php';

// Redirect jika bukan role 'owner'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header('Location: login.php');
    exit();
}

// Query data buku
$books = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .book-card {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        a {
            text-decoration: none;
            color: #007BFF;
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Data Buku</h2>
    <a href="owner_dashboard.php">Kembali ke Dashboard</a>

    <div class="book-grid">
        <?php if ($books->num_rows > 0): ?>
            <?php while ($row = $books->fetch_assoc()): ?>
                <div class="book-card">
                    <img src="uploads/<?php echo htmlspecialchars($row['cover']); ?>" alt="Cover">
                    <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                    <p>Penulis: <?php echo htmlspecialchars($row['penulis']); ?></p>
                    <p>Penerbit: <?php echo htmlspecialchars($row['penerbit']); ?></p>
                    <p>Tahun: <?php echo (int)$row['tahun']; ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Buku tidak ditemukan.</p>
        <?php endif; ?>
    </div>
</body>
</html>
