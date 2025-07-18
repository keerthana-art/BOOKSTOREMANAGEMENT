<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

$result = $conn->query("SELECT c.id AS cart_id, b.id AS book_id, b.title, b.price, c.quantity, b.stock 
    FROM cart c JOIN books b ON c.book_id = b.id WHERE c.user_id = $user_id");

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Your Cart</title>
<link rel="stylesheet" href="styles.css" />
<style>
.cart-item {
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 8px;
}
</style>
</head>
<body>
<header>
    <h1>Your Cart, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <nav>
        <a href="browse.php">Browse</a>
        <a href="cart.php">Cart</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <h2>Cart Items</h2>
    <?php if ($result->num_rows == 0): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <form action="update_cart.php" method="POST">
        <?php while ($row = $result->fetch_assoc()): 
            $subtotal = $row['price'] * $row['quantity'];
            $total += $subtotal;
        ?>
            <div class="cart-item">
                <p>
                    <strong><?php echo htmlspecialchars($row['title']); ?></strong> - ₹<?php echo $row['price']; ?> 
                    x 
                    <input type="number" name="quantities[<?php echo $row['cart_id']; ?>]" value="<?php echo $row['quantity']; ?>" min="1" max="<?php echo $row['stock']; ?>" />
                    = ₹<?php echo $subtotal; ?>
                    <a href="remove_from_cart.php?id=<?php echo $row['cart_id']; ?>">Remove</a>
                </p>
                <p>
                    <a href="checkout.php?cart_id=<?php echo $row['cart_id']; ?>&quantity=<?php echo $row['quantity']; ?>">Proceed to Checkout</a>
                </p>
            </div>
        <?php endwhile; ?>
        <p><strong>Total: ₹<?php echo $total; ?></strong></p>
        <button type="submit">Update Cart</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
