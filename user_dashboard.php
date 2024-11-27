<?php
session_start();
include 'db.php';

if ($_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

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
    
        a:hover {
            text-decoration: underline;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
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
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Buku tidak ditemukan.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
