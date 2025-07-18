<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (!isset($_GET['id'])) {
    echo "Book ID is missing.";
    exit();
}

$id = intval($_GET['id']);
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    $sql = "UPDATE books SET title='$title', author='$author', price=$price, stock=$stock WHERE id=$id";
    if ($conn->query($sql)) {
        $message = "Book updated successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

$sql = "SELECT * FROM books WHERE id=$id";
$result = $conn->query($sql);
if ($result->num_rows !== 1) {
    echo "Book not found.";
    exit();
}
$book = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Edit Book</h2>
    <form method="post">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required><br>
        <label>Author:</label><br>
        <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>"><br>
        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" value="<?php echo $book['price']; ?>" required><br>
        <label>Stock:</label><br>
        <input type="number" name="stock" value="<?php echo $book['stock']; ?>" required><br><br>
        <input type="submit" value="Update Book">
    </form>
    <p><?php echo $message; ?></p>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
