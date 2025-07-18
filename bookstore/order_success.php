
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            padding-top: 50px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>
    <h1>✅ Order Placed Successfully!</h1>
    <p>Thank you for shopping with us.</p>
    <a href="index.php">Back to Bookstore</a>
</body>
</html>

