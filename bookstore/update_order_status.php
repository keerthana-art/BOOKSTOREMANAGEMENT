<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

if (isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $conn->real_escape_string($_POST['status']);

    $conn->query("UPDATE orders SET status = '$status' WHERE id = $order_id");

    header('Location: admin_view_orders.php');
    exit();
} else {
    echo "Invalid request.";
}
