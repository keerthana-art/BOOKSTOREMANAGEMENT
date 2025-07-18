<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}
include 'db.php';

// Fetch book categories
$all_books = $conn->query("SELECT * FROM books ORDER BY title ASC");
$best_sellers = $conn->query("SELECT * FROM books WHERE is_best_seller = 1 ORDER BY title ASC");
$discount_books = $conn->query("SELECT * FROM books WHERE is_discounted = 1 ORDER BY title ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Dashboard</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    .book-section {
        margin: 20px;
    }
    .book-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .book-card {
        border: 1px solid #ccc;
        padding: 15px;
        width: 220px;
        border-radius: 10px;
        background-color: #f9f9f9;
    }
    .book-card h3 {
        margin: 0 0 5px;
        font-size: 1.1rem;
    }
    .book-card p {
        margin: 5px 0;
    }
    .book-card form {
        margin-top: 10px;
    }
    header {
        background: #444;
        color: white;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    header h1 {
        margin: 0;
        font-size: 1.5rem;
    }
    nav a {
        color: white;
        margin-left: 20px;
        text-decoration: none;
        font-weight: bold;
    }
    nav a:hover {
        text-decoration: underline;
    }
  </style>
</head>
<body>
<header>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <nav>
        <a href="browse.php">🏠 Dashboard</a>
        <a href="cart.php">🛒 Cart</a>
        <a href="user_orders.php">🧾 My Orders</a>
        <a href="logout.php">🚪 Logout</a>
    </nav>
</header>

<div class="container">

    <!-- Best Sellers -->
    <div class="book-section">
        <h2>🔥 Best Sellers</h2>
        <div class="book-cards">
            <?php while ($book = $best_sellers->fetch_assoc()): ?>
                <div class="book-card">
                    <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
                    <p>Price: ₹<?php echo $book['price']; ?></p>
                    <?php if ($book['stock'] > 0): ?>
                        <form method="POST" action="add_to_cart.php">
                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $book['stock']; ?>" required>
                            <button type="submit">Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <p style="color:red;">Out of stock</p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Discount Books -->
    <div class="book-section">
        <h2>💸 Discount Books</h2>
        <div class="book-cards">
            <?php while ($book = $discount_books->fetch_assoc()): ?>
                <div class="book-card">
                    <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
                    <p>Price: ₹<?php echo $book['price']; ?></p>
                    <?php if ($book['stock'] > 0): ?>
                        <form method="POST" action="add_to_cart.php">
                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $book['stock']; ?>" required>
                            <button type="submit">Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <p style="color:red;">Out of stock</p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- All Books -->
    <div class="book-section">
        <h2>📚 All Books</h2>
        <div class="book-cards">
            <?php while ($book = $all_books->fetch_assoc()): ?>
                <div class="book-card">
                    <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
                    <p>Price: ₹<?php echo $book['price']; ?></p>
                    <?php if ($book['stock'] > 0): ?>
                        <form method="POST" action="add_to_cart.php">
                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $book['stock']; ?>" required>
                            <button type="submit">Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <p style="color:red;">Out of stock</p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</div>
</body>
</html>
