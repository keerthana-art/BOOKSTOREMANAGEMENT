<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Fetch orders and user info
$sql = "SELECT o.id AS order_id, o.total_price, o.status, o.created_at, u.username
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - View Orders</title>
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
            width: 90%;
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
    </style>
</head>
<body>

<h2>📦 All Orders</h2>

<table>
    <tr>
        <th>Order ID</th>
        <th>Username</th>
        <th>Total Price (₹)</th>
        <th>Status</th>
        <th>Placed On</th>
        <th>Details</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['order_id'] ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= $row['total_price'] ?></td>
        <td>
    <form action="update_order_status.php" method="post" style="display:inline;">
        <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
        <select name="status">
            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Shipped" <?= $row['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
            <option value="Completed" <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
        </select>
        <button type="submit">Update</button>
    </form>
</td>

        <td><?= $row['created_at'] ?></td>
        <td><a href="admin_order_details.php?order_id=<?= $row['order_id'] ?>">View</a></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
