<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Array untuk menyimpan tabel yang akan diperiksa
    $tables = ['admin', 'owner', 'user'];
    $userFound = false;

    foreach ($tables as $table) {
        $stmt = $conn->prepare("SELECT username, password, role FROM $table WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect berdasarkan role
                if ($user['role'] === 'admin') {
                    header('Location: admin_dashboard.php');
                } elseif ($user['role'] === 'owner') {
                    header('Location: owner_dashboard.php');
                } else {
                    header('Location: user_dashboard.php');
                }
                $userFound = true;
                exit();
            } else {
                echo "<p>Password salah!</p>";
                $userFound = true;
                break;
            }
        }
    }

    if (!$userFound) {
        echo "<p>Username tidak ditemukan!</p>";
    }
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
