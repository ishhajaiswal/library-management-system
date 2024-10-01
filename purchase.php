<?php
session_start();
include_once "config.php";

$orderId = rand(100000, 999999); 
$userId = $_SESSION['user_id']; 
$totalOrderPrice = 0; 

if (!empty($_SESSION['cart'])) { 
    // Insert the order
    $insertOrderSql = "INSERT INTO orders (user_id, total_price) VALUES ($userId, 0)";
    mysqli_query($link, $insertOrderSql);
    $orderId = mysqli_insert_id($link); 

    foreach ($_SESSION['cart'] as $book_id) {
        // Get book price
        $bookResult = mysqli_query($link, "SELECT price FROM books WHERE id = $book_id");
        $price = mysqli_fetch_assoc($bookResult)['price'];
        $totalOrderPrice += $price;

        // updates order items
        mysqli_query($link, "INSERT INTO order_items (order_id, book_id, quantity, price) VALUES ($orderId, $book_id, 1, $price)");

        // Updates the number of available books
        mysqli_query($link, "UPDATE books SET availablecopies = availablecopies - 1 WHERE id = $book_id");
    }

    // Update total price in the order
    mysqli_query($link, "UPDATE orders SET total_price = $totalOrderPrice WHERE id = $orderId");

    // Clear cart
    unset($_SESSION['cart']);
}
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Complete</title>
    <link rel="stylesheet" href="css/purchase.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="small-navbar">
            <a href="signup.php">Sign Up</a>
            <a href="login.php">Login</a>
            <a href="contactus.php">Contact Us</a>
        </div>
        <div class="big-navbar">
            <a href="home.html">Home</a>
            <a href="catalogue.php">Catalogue</a>
            <a href="cart.php">Cart</a>
            <a href="account.php">Account</a>
        </div>
    </div>
    
    <div class="container">
        <div class="message-box">
            <h1>Thank You!</h1>
            <p class="o">Your order has been placed successfully.</p>
            <img src="images/tick.jpeg" class="tick" alt="Tick Icon">
            <p>Order ID: #<?php echo $orderId; ?></p> 
            <a href="catalogue.php" class="shop">Continue Shopping</a>
        </div>
    </div>
</body>
</html>


