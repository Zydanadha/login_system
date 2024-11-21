<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #eef2f7;
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }
        .dashboard {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 380px;
            text-align: center;
        }
        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            color: #333;
            font-size: 18px;
            margin-bottom: 20px;
        }
        button {
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Welcome to Your Dashboard</h2>
        <p>Selamat datang, user <?php echo $_SESSION['username']; ?></p>
        <a href="login.php?logout=true"><button>Logout</button></a>
    </div>
</body>
</html>
