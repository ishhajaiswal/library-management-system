<?php

require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required";
        exit(); 
    }


    if ($password !== $confirm_password) {
        echo "Passwords do not match.<br>";
        exit(); 
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)";
    echo $sql ;
    $stmt = mysqli_prepare($link, $sql);

    
    if ($stmt === false) {
        echo "Error preparing the statement: " . mysqli_error($link);
        exit(); 
    }

    mysqli_stmt_bind_param( $stmt , "ssss", $name , $username , $email , $hashed_password) ; 


    $debug_sql = "INSERT INTO users (name, username, email, password) VALUES ('$name', '$username', '$email', '$hashed_password')";
    echo "Debug SQL: " . $debug_sql . "<br>"; 

    if (mysqli_stmt_execute($stmt)) {
        echo "Success";
        header("Location: login.php");
        exit(); 
    } else {
        echo "Error: " . mysqli_stmt_error($stmt); // Error reporting for execution
    }

    mysqli_stmt_close($stmt); 
    mysqli_close($link); 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/signup.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="navbar">
        <div class = "small-navbar">
            <a href="signup.php">Sign Up</a>
            <a href="login.php"> Login</a>
            <a href="contactus.php">Contact us</a>
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
                <h1>Sign Up</h1>
                <p>Please fill this form to create an account.</p>
                <form action="signup.php" method="post">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="name" required >
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="username" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" placeholder="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="confirm password" required>
                </div>
                <div class="form-group terms-group">
                    <input type="checkbox" class="terms" id="terms" name="terms" required>
                    <label for="terms">I accept the terms and conditions</label>
                </div>

                <div class="form-group">
                    <input type="submit" class="submit" name="submit" >
                    <input type="reset" class="reset" name="Reset">
                </div>
        </div>
        <div class="right">
            <p>Already have an account?</p> 
            <a href="login.php" class="rbox">Login here</a>
        </div>
        </form>
    </div>
</body>
</html>


