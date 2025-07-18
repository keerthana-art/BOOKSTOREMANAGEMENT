<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    $sql = "INSERT INTO books (title, author, price, stock) VALUES ('$title', '$author', $price, $stock)";
    if ($conn->query($sql)) {
        $message = "Book added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Add New Book</h2>
    <form method="post">
        <label>Title:</label><br>
        <input type="text" name="title" required><br>
        <label>Author:</label><br>
        <input type="text" name="author"><br>
        <label>Price:</label><br>
        <input type="number" name="price" step="0.01" required><br>
        <label>Stock:</label><br>
        <input type="number" name="stock" required><br><br>
        <input type="submit" value="Add Book">
    </form>
    <p><?php echo $message; ?></p>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
