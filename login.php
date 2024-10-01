<?php

session_start();
include_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "Login successful!";
            header("Location: welcome.html"); 
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="navbar">
        <div class = "small-navbar">
            <a href="signup.php"> Sign Up</a>
            <a href="login.php"> Login</a>
            <a href="contactus.php">Contact Us</a>
        </div>

        <div class="big-navbar">
            <a href = "home.html"> Home</a>
            <a href = "catalogue.php"> Catalogue</a>
            <a href="cart.php">Cart</a>
            <a href="account.php"> Account</a>
        </div>
    </div>
    <div class="sbox">
        <div class="left">
            <p>Don't have an account?</p>
            <a href="signup.php" class="lbox" >Sign Up here</a>
        </div>
        <div class="right">
        <h1>Login</h1>
            <p>Please fill in your credentials to log in.</p>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="username" class="user" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="password" class="pass"required>
                </div>
                <div class="form-group">
                    <input type="submit"  name="submit" value="Login">
                    <input type="reset"  value="Reset">
                </div>
            </form>
        </div>
    </div>
</body>
</html>



