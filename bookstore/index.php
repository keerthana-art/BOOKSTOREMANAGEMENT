<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Modern Bookstore</title>
  <link rel="stylesheet" href="styles 2.css">
</head>
<body>
  <header class="main-header">
    <h1 class="site-title">📚 Modern Bookstore</h1>
    <nav class="auth-links">
      <a href="login.php" class="btn">Login</a>
      <a href="register.php" class="btn">Register</a>
    </nav>
  </header>

  <main class="hero-section">
    <div class="hero-text">
      <h2>Welcome to the Modern Bookstore</h2>
      <p>Find your favorite books and get them delivered fast!</p>
    </div>

    <section class="ads-section">
      <div class="ad-card" onclick="location.href='best_sellers.php';">
        <h3>🔥 Best Sellers</h3>
        <p>Explore the books everyone is talking about.</p>
      </div>
      <div class="ad-card" onclick="location.href='discount_books.php';">
        <h3>🎁 Student Discount</h3>
        <p>Get up to 15% off on academic books!</p>
      </div>
      <div class="ad-card">
        <h3>🚚 Fast Delivery</h3>
        <p>Order now and receive your books in 2 days.</p>
      </div>
    </section>
  </main>
</body>
</html>
