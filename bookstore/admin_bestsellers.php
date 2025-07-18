<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Handle toggle bestseller status
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $conn->query("UPDATE books SET is_best_seller = NOT is_best_seller WHERE id = $id");
    header('Location: admin_bestsellers.php');
    exit();
}

$books = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Best Sellers</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>🔥 Best Sellers Management</h1>
        <nav><a href="admin_dashboard.php">← Back to Dashboard</a></nav>
    </header>

    <div class="container">
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Is Bestseller?</th>
                <th>Action</th>
            </tr>
            <?php while ($book = $books->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><?= $book['is_best_seller'] ? '✅ Yes' : '❌ No' ?></td>
                    <td>
                        <a href="?toggle=1&id=<?= $book['id'] ?>">
                            <?= $book['is_best_seller'] ? 'Remove from Best Sellers' : 'Mark as Best Seller' ?>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
