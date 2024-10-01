<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to see your orders.";
    exit();
}

$user_id = $_SESSION['user_id'];

// SQL query to get past orders
$sql = "SELECT o.order_date, b.title, oi.price, oi.quantity 
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        JOIN books b ON oi.book_id = b.id
        WHERE o.user_id = ? 
        ORDER BY o.order_date DESC";

// Prepare and run the query
$stmt = mysqli_prepare($link, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        echo "Error fetching orders.";
        exit();
    }
} else {
    echo "Query error.";
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<>
    <meta charset="UTF-8">
    <title>Past Orders</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <style>
        
        body {
    margin: 0;
    padding: 0;
    background-color: rgb(226, 226, 194);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.navbar {
    display: flex;
    flex-direction: column;
    background-color: black;
    border-bottom: 5px solid white;
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    font-size: large;
}

.small-navbar {
    display: flex;
    justify-content: flex-end;
    gap: 30px;
    margin-top: 10px;
}

.big-navbar {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding-left: 50px;
    padding-right: 50px;
}

.big-navbar a, .small-navbar a {
    text-decoration: none;
    color: white;
    padding: 5px;
}

.big-navbar a:hover, .small-navbar a:hover {
    text-decoration: underline;
}


        .order-container {
            margin-top: 100px;
            width: 70%;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-list {
            margin-top: 20px;
        }

        .order-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .order-item h5 {
            margin: 0;
            font-weight: bold;
        }

        .order-item p {
            margin: 5px 0;
        }
    </style>

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

    <div class="order-container">
        <h1>Your Past Orders:</h1>
        <div class="order-list">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="order-item">
                        <h5><?php echo htmlspecialchars($row['title'] ?? ''); ?></h5>
                        <p>Order Date: <?php echo htmlspecialchars($row['order_date'] ?? ''); ?></p>
                        <p>Price: Rs. <?php echo htmlspecialchars($row['price'] ?? ''); ?></p>
                        <p>Quantity: <?php echo htmlspecialchars($row['quantity'] ?? ''); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You haven't placed any orders yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
