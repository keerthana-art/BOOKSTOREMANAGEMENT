<?php
// DEBUGGING: Show all PHP errors (REMOVE this in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

// 1. Fetch cart items with book details
$sql = "SELECT c.book_id, c.quantity, b.price, b.stock 
        FROM cart c 
        JOIN books b ON c.book_id = b.id 
        WHERE c.user_id = $user_id";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching cart: " . $conn->error);
}

$total = 0;
$can_checkout = true;

// 2. Check stock and calculate total
while ($row = $result->fetch_assoc()) {
    if ($row['stock'] < $row['quantity']) {
        $can_checkout = false;
        break;
    }
    $total += $row['price'] * $row['quantity'];
}

if (!$can_checkout) {
    die("Cannot checkout: One or more books are out of stock or have insufficient quantity.");
}

// 3. Insert into orders table
$sql_order = "INSERT INTO orders (user_id, total_price) VALUES ($user_id, $total)";
if (!$conn->query($sql_order)) {
    die("Error inserting order: " . $conn->error);
}

$order_id = $conn->insert_id;

// 4. Insert order items and update stock
$sql_items = "SELECT book_id, quantity FROM cart WHERE user_id = $user_id";
$result_items = $conn->query($sql_items);

if (!$result_items) {
    die("Error fetching cart items: " . $conn->error);
}

while ($row = $result_items->fetch_assoc()) {
    $book_id = $row['book_id'];
    $qty = $row['quantity'];

    $conn->query("INSERT INTO order_items (order_id, book_id, quantity) VALUES ($order_id, $book_id, $qty)")
        or die("Error inserting order item: " . $conn->error);

    $conn->query("UPDATE books SET stock = stock - $qty WHERE id = $book_id")
        or die("Error updating stock: " . $conn->error);
}

// 5. Clear cart
$conn->query("DELETE FROM cart WHERE user_id = $user_id") 
    or die("Error clearing cart: " . $conn->error);

// 6. Redirect to success page
header('Location: order_success.php');
exit();
?>
