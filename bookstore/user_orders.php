<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';
$user_id = $_SESSION['user_id'];

$sql = "SELECT id, total_price, status, created_at FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 95%;
            margin: auto;
        }
        table, th, td {
            border: 1px solid #666;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #eee;
        }
        .order-items {
            text-align: left;
            font-size: 0.95em;
        }
    </style>
</head>
<body>

<h2>🧾 My Orders</h2>

<table>
    <tr>
        <th>Order ID</th>
        <th>Books Ordered</th>
        <th>Total Price (₹)</th>
        <th>Status</th>
        <th>Placed On</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td class="order-items">
            <?php
            // Fetch items for this order
            $order_id = $row['id'];
            $items = $conn->query("SELECT b.title, oi.quantity 
                                   FROM order_items oi 
                                   JOIN books b ON oi.book_id = b.id 
                                   WHERE oi.order_id = $order_id");

            while ($item = $items->fetch_assoc()) {
                echo "📖 " . htmlspecialchars($item['title']) . " - Qty: " . $item['quantity'] . "<br>";
            }
            ?>
        </td>
        <td><?= $row['total_price'] ?></td>
        <td>
            <?= $row['status'] === 'Pending' ? '🕓 Pending' :
                ($row['status'] === 'Shipped' ? '🚚 Shipped' :
                ($row['status'] === 'Completed' ? '✅ Completed' : $row['status'])) ?>
        </td>
        <td><?= $row['created_at'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
