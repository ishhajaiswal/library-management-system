<?php

include_once "config.php";

// Get info, check if empty and correct, then enter into database
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        echo 'All fields are required!';
        exit();
    }

    // Prepare
    $sql = "INSERT INTO contactus (name, email, message) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        echo "Preparation failed: " . mysqli_error($link);
        exit();
    }

    // Bind
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
    if (mysqli_stmt_execute($stmt)) {
        // Redirect after successful insertion
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header("Location: sent.html");
        exit();
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/contactus.css">
    <title>Contact us</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <form action="contactus.php" method="POST">
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

        <div class="contactbox" >
            <h3> Have a query or complaint? Leave a message </h3>
            <hr>
            <div class="form-group">
                <label>Name</label>
                <br>
                <input type="text" class="name" name= "name" placeholder="name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <br>
                <input type="email" class="email" name= "email" placeholder="email" required>    
            </div>
            <div class="form-group">
                <input type="textbox" class="message" name= "message" placeholder="message" required>   
            </div>
            <div class="form-group" >
                <form action="sent.html"method="POST">
                    <input type="submit" class="submit" name="submit">
                </form>
                <input type="reset" class="reset" name="Reset">
            </div>
        </div>
    </form>
    
    
</body>
</html>