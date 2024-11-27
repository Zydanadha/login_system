<?php
session_start();
include 'db.php';

// Cek apakah user memiliki role admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Ambil data buku berdasarkan ID
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM books WHERE id = $id");

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
} else {
    echo "Data buku tidak ditemukan!";
    exit();
}

if (isset($_POST['submit'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun = (int)$_POST['tahun'];

    if ($_FILES['cover']['name']) {
        $cover = mysqli_real_escape_string($conn, $_FILES['cover']['name']);
        $cover_tmp = $_FILES['cover']['tmp_name'];
        move_uploaded_file($cover_tmp, "uploads/" . $cover);

        $query = "UPDATE books SET judul='$judul', penulis='$penulis', penerbit='$penerbit', tahun='$tahun', cover='$cover' WHERE id=$id";
    } else {
        $query = "UPDATE books SET judul='$judul', penulis='$penulis', penerbit='$penerbit', tahun='$tahun' WHERE id=$id";
    }

    if ($conn->query($query)) {
        header('Location: admin_dashboard.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
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
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            text-align: center;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Buku</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="judul" value="<?php echo htmlspecialchars($book['judul']); ?>" required>
            <input type="text" name="penulis" value="<?php echo htmlspecialchars($book['penulis']); ?>" required>
            <input type="text" name="penerbit" value="<?php echo htmlspecialchars($book['penerbit']); ?>">
            <input type="number" name="tahun" value="<?php echo $book['tahun']; ?>">
            <input type="file" name="cover">
            <button type="submit" name="submit">Simpan Perubahan</button>
        </form>
        <a href="admin_dashboard.php">Kembali</a>
    </div>
</body>
</html>
