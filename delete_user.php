<?php
session_start();
include 'db.php';

// Cek apakah user sudah login dan merupakan admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Hapus data peminjaman yang terkait dengan user
    $stmt = $conn->prepare("DELETE FROM peminjaman WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Hapus user dari tabel user
    $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        echo "<script>alert('User berhasil dihapus!'); window.location.href = 'admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('ID user tidak ditemukan!'); window.location.href = 'admin_dashboard.php';</script>";
}
?>