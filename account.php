<?php
session_start();
include_once "config.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('location: pleaselogin.html');
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    $username = htmlspecialchars($user['username']);
    $name = htmlspecialchars($user['name']);
    $email = htmlspecialchars($user['email']);
} else {
    echo "Error fetching user details.";
    exit();
}
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account</title>
    <link rel="stylesheet" href="css/account.css">
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

    <div class="account-container">
        <h1>Welcome, <?php echo $name; ?>!</h1>
        <p>Username: <?php echo $username; ?></p>
        <p>Email: <?php echo $email; ?></p>
        <div class="links">
            <div class="linkgroup">
                <a href="logout.php" >Logout</a>
            </div>
            <div class="historygroup">
                <a href="history.php" >Purchase history</a>
            </div>
        </div>
    </div>
</body>
</html>

