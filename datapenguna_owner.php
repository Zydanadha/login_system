<?php
session_start();
include 'db.php';

// Redirect jika bukan role 'owner'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header('Location: login.php');
    exit();
}

// Query data pengguna (kecuali admin)
$users = $conn->query("SELECT id, username, role, password FROM user WHERE role != 'admin'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
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
    <h2>Data Pengguna</h2>
    <a href="owner_dashboard.php">Kembali ke Dashboard</a>

    <table>
        <tr>
            <th>ID</th>
              <th>Username</th>
            <th>Role</th>
                    
        </tr>
        <?php while ($user = $users->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']); ?></td>
            <td><?= htmlspecialchars($user['username']); ?></td>
            <td><?= htmlspecialchars($user['role']); ?></td>
         
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
