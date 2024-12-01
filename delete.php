<?php
session_start();
include 'db.php';

// Cek apakah user memiliki role admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];

// Hapus data buku
$query = "DELETE FROM books WHERE id = $id";
if ($conn->query($query)) {
    header('Location: admin_dashboard.php');
} else {
    echo "Error: " . $conn->error;
}
?>