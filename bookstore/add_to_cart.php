<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

if (!isset($_POST['book_id']) || !isset($_POST['quantity'])) {
    die("Invalid request.");
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['book_id']);
$quantity = intval($_POST['quantity']);

// Check if book exists and in stock
$stockResult = $conn->query("SELECT stock FROM books WHERE id = $book_id");
if ($stockResult->num_rows === 0) {
    die("❌ Book not found.");
}

$stock = $stockResult->fetch_assoc()['stock'];
if ($stock < $quantity) {
    die("❌ Not enough stock available.");
}

// Check if already in cart
$check = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND book_id = $book_id");
if ($check->num_rows > 0) {
    // Update quantity
    $conn->query("UPDATE cart SET quantity = quantity + $quantity WHERE user_id = $user_id AND book_id = $book_id");
} else {
    // Insert new cart item
    $conn->query("INSERT INTO cart (user_id, book_id, quantity) VALUES ($user_id, $book_id, $quantity)");
}

header('Location: cart.php');
exit();
