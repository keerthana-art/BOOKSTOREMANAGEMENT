<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $cart_id => $qty) {
        $qty = intval($qty);
        if ($qty < 1) $qty = 1;
        // Update quantity
        $conn->query("UPDATE cart SET quantity = $qty WHERE id = $cart_id AND user_id = " . $_SESSION['user_id']);
    }
}

header('Location: cart.php');
exit();
?>
