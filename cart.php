<?php
session_start();
include_once "config.php";

// Check if the user is trying to remove an item
if (isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);
    
    // Update available copies in the database
    $updateSql = "UPDATE books SET availablecopies = availablecopies + 1 WHERE id = $book_id";
    if (mysqli_query($link, $updateSql)) {
        // Removes the item from the cart
        if (($key = array_search($book_id, $_SESSION['cart'])) !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
        }
    } else {
        echo "Error updating available copies: " . mysqli_error($link);
    }
}

// Check if the cart is not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_ids = implode(',', array_map('intval', $_SESSION['cart']));
    
    // Fetch the books from the database
    $sql = "SELECT * FROM books WHERE id IN ($cart_ids)";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        echo "Error fetching cart items: " . mysqli_error($link);
        exit();
    }

    // Initialize the total price
    $totalPrice = 0;
} else {
    echo "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <link rel="stylesheet" href="css/cart.css">
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

<div class="cartcontainer">
    <h1>Your Cart</h1>
    <div class="cartitems">
        <?php if (isset($result) && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="cart-item">
                    <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                    <p>Author: <?php echo htmlspecialchars($row['author']); ?></p>
                    <p>Price: Rs.<?php echo htmlspecialchars($row['price']); ?></p>

                    <!-- Remove Button -->
                    <form action="cart.php" method="POST" style="display:inline;">
                        <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="remove-button">Remove</button>
                    </form>
                </div>
                <?php
                // Add the price to the total
                $totalPrice += $row['price'];
                ?>
            <?php endwhile; ?>
            <div class="cart-total">
                <h3>Total Price: Rs.<?php echo number_format($totalPrice, 2); ?></h3>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
    <form action="purchase.php" method="POST">
        <button type="submit" class="cp">Complete Purchase</button>
    </form>
</div>
</body>
</html>

<?php
mysqli_close($link);
?>


