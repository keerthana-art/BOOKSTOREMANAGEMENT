<?php
session_start();
include 'db.php';

// Fetch only books marked as best sellers
$books = $conn->query("SELECT * FROM books WHERE is_best_seller = 1 ORDER BY price DESC LIMIT 5");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Best Sellers</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    .book-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      padding: 20px;
    }

    .book-card {
      background: #fff;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }

    .book-card:hover {
      transform: translateY(-5px);
    }

    .book-card h3 {
      margin-top: 0;
      color: #444;
    }

    .book-card p {
      color: #666;
    }

    .book-card button {
      background: #007bff;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 8px;
      cursor: pointer;
    }

    .book-card button:hover {
      background: #0056b3;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
      background-color: #fff;
      margin: 15% auto;
      padding: 30px;
      border-radius: 10px;
      width: 300px;
      text-align: center;
    }

    .modal-content a {
      display: inline-block;
      margin: 10px;
      color: #007bff;
      text-decoration: none;
    }

    .modal-content a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<h2>🔥 Best Selling Books</h2>

<div class="book-grid">
  <?php while ($book = $books->fetch_assoc()): ?>
    <div class="book-card">
      <h3><?php echo htmlspecialchars($book['title']); ?></h3>
      <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
      <p><strong>Price:</strong> ₹<?php echo $book['price']; ?></p>
      <p><strong>Stock:</strong> <?php echo $book['stock']; ?></p>

      <?php if (isset($_SESSION['user_id'])): ?>
        <form method="POST" action="add_to_cart.php">
          <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
          <input type="number" name="quantity" value="1" min="1" max="<?php echo $book['stock']; ?>" required>
          <button type="submit">Add to Cart</button>
        </form>
      <?php else: ?>
        <button onclick="showModal()">Buy Now</button>
      <?php endif; ?>
    </div>
  <?php endwhile; ?>
</div>

<!-- Modal -->
<div id="loginModal" class="modal">
  <div class="modal-content">
    <h3>Please Login or Register</h3>
    <a href="login.php">🔐 Login</a> | 
    <a href="register.php">📝 Register</a>
    <br><br>
    <button onclick="hideModal()">Close</button>
  </div>
</div>

<script>
function showModal() {
  document.getElementById("loginModal").style.display = "block";
}
function hideModal() {
  document.getElementById("loginModal").style.display = "none";
}
window.onclick = function(event) {
  const modal = document.getElementById("loginModal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
};
</script>

</body>
</html>
