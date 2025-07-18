<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
include 'db.php';

// Get books and orders for display
$books = $conn->query("SELECT * FROM books");
$orders = $conn->query("SELECT o.id, u.username, o.total_price, o.status, o.created_at FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Admin Dashboard</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<header>
    <h1>Admin Dashboard - <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
   <nav>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="add_book.php">Add Book</a>
    <a href="admin_view_orders.php">🛒 View All Orders</a>
    <a href="admin_bestsellers.php">🔥 Manage Best Sellers</a>
    <a href="admin_discounts.php">🎁 Manage Discount Books</a>
    <a href="logout.php">Logout</a>
</nav>

</header>

<div class="container">
    <h2>Books</h2>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr><th>Title</th><th>Author</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
        <?php while ($book = $books->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($book['title']); ?></td>
            <td><?php echo htmlspecialchars($book['author']); ?></td>
            <td>₹<?php echo $book['price']; ?></td>
            <td><?php echo $book['stock']; ?></td>
            <td>
                <a href="edit_book.php?id=<?php echo $book['id']; ?>">Edit</a> | 
                <a href="delete_book.php?id=<?php echo $book['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>

            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    
</div>
</body>
</html>
