<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Toggle discounted status
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $conn->query("UPDATE books SET is_discounted = NOT is_discounted WHERE id = $id");
    header('Location: admin_discounts.php');
    exit();
}

$books = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🎁 Manage Discounted Books</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>🎁 Discounted Books Management</h1>
        <nav>
            <a href="admin_dashboard.php">← Back to Dashboard</a>
        </nav>
    </header>

    <div class="container">
        <h2>Books List</h2>
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Is Discounted?</th>
                <th>Action</th>
            </tr>
            <?php while ($book = $books->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td>₹<?= $book['price'] ?></td>
                <td><?= $book['stock'] ?></td>
                <td><?= $book['is_discounted'] ? '✅ Yes' : '❌ No' ?></td>
                <td>
                    <a href="?toggle=1&id=<?= $book['id'] ?>" style="color: <?= $book['is_discounted'] ? 'red' : 'green' ?>;">
                        <?= $book['is_discounted'] ? 'Remove Discount' : 'Apply Discount' ?>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
