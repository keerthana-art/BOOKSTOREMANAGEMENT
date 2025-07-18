<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$order_id = intval($_GET['order_id']);

// Get order items
$sql = "SELECT b.title, oi.quantity, b.price 
        FROM order_items oi 
        JOIN books b ON oi.book_id = b.id 
        WHERE oi.order_id = $order_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        h2 { text-align: center; }
        table { width: 80%; margin: auto; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 10px; text-align: center; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h2>📚 Order #<?= $order_id ?> - Details</h2>

<table>
    <tr>
        <th>Book Title</th>
        <th>Quantity</th>
        <th>Price per Unit (₹)</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= $row['price'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
