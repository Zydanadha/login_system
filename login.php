<?php
session_start();
include 'db_connection.php'; // File koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Pastikan untuk mengganti ini dengan hash yang lebih aman seperti password_hash

    // Cek di tabel admin
    $queryAdmin = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $resultAdmin = mysqli_query($conn, $queryAdmin);

    if (mysqli_num_rows($resultAdmin) > 0) {
        $_SESSION['role'] = 'admin';
        $_SESSION['username'] = $username;
        header('Location: admin_dashboard.php');
        exit();
    }

    // Cek di tabel user
    $queryUser = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $resultUser = mysqli_query($conn, $queryUser);

    if (mysqli_num_rows($resultUser) > 0) {
        $_SESSION['role'] = 'user';
        $_SESSION['username'] = $username;
        header('Location: user_dashboard.php');
        exit();
    }

    // Jika login gagal
    echo "<p>Username atau password salah!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
   
   <style>
    body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f3f4f6; /* Lighter background for a fresher look */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* More modern font */
    margin: 0;
}

form {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px; /* Smoother corners */
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); /* Softer shadow for depth */
    width: 100%;
    max-width: 380px;
    text-align: center; /* Center content */
}

h2 {
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
    font-weight: 600;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #555; /* Slightly lighter for contrast */
}

input[type="text"], input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    color: #333;
    background-color: #fafafa; /* Light background for inputs */
    transition: border 0.3s ease, box-shadow 0.3s ease;
}

input[type="text"]:focus, input[type="password"]:focus {
    border-color: #007bff; /* Highlighted border on focus */
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.3); /* Soft glow effect */
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    background-color: #0056b3;
    transform: scale(1.05); /* Slight grow effect on hover */
}

button:active {
    transform: scale(1); /* Return to normal size on click */
}

p {
    color: red;
    font-size: 14px;
    margin-top: 15px;
    font-weight: 500;
    display: inline-block;
    text-align: center;
}
</style>
</head>
<body>
    <form action="" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
