<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new PDO("mysql:host=127.0.0.1;port=3307;dbname=cake_shop", 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $sql = "UPDATE orders SET status = :status WHERE id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['status' => $status, 'order_id' => $order_id]);

    header("Location: admin_dashboard.php");
    exit();
}
