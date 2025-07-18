<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$cart_id = intval($_GET['id']);
$conn->query("DELETE FROM cart WHERE id=$cart_id AND user_id=" . $_SESSION['user_id']);

header('Location: cart.php');
exit();
?>
