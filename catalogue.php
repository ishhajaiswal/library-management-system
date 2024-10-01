<?php
session_start();
include_once "config.php";

// Handles cart as well
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {

    if (!isset($_SESSION['username'])) {
        echo'please login to purchase items';
        header('location: pleaselogin.html');
        exit();
    }
    else {
        //getting book id (needed for purchasing)
        $book_id = intval($_POST['book_id']);

    
    // Fetching available copies for the selected book
    $sql = "SELECT availablecopies FROM books WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $book_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);    // binding steps till here
    $book = mysqli_fetch_assoc($result);
    
    if ($book && $book['availablecopies'] > 0) {
        // Reduce available copies by 1
        $new_available_copies = $book['availablecopies'] - 1;
        $update_sql = "UPDATE books SET availablecopies = ? WHERE id = ?";  //preparing for updation
        $update_stmt = mysqli_prepare($link, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "ii", $new_available_copies, $book_id);
        mysqli_stmt_execute($update_stmt); // updates available copies

        //creates and checks cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        //adds book to cart
        if (!in_array($book_id, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $book_id;
        }

        echo "Book added to cart successfully!";
    } else {
        echo "Sorry, no copies available.";
    
}

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($update_stmt);
}}

// Add a new book
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $totalcopies = $_POST['totalcopies'];
    $availablecopies = $_POST['availablecopies'];

    $sql = "INSERT INTO books (title, author, price, totalcopies, availablecopies) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sssii", $title, $author, $price, $totalcopies, $availablecopies);
    if (mysqli_stmt_execute($stmt)) {
        echo "Book was added!";
    } else {
        echo "Error: " . mysqli_error($link);
    }

    mysqli_stmt_close($stmt);
}

// Fetching the books to display
$sql = "SELECT * FROM books LIMIT 10";
$result = mysqli_query($link, $sql);

if (!$result) {
    echo "Error fetching books: " . mysqli_error($link);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Catalogue</title>
    <link rel="stylesheet" href="css/catalogue.css">
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
        <!-- Add a Book Form -->
        <div class="form-container">
            <h4>Add a Book to the Library:</h4>
            <form action="catalogue.php" method="post">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label for="author">Author:</label>
                    <input type="text" name="author" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" name="price" required>
                </div>
                <div class="form-group">
                    <label for="totalcopies">Total Copies:</label>
                    <input type="number" name="totalcopies" required>
                </div>
                <div class="form-group">
                    <label for="availablecopies">Available Copies:</label>
                    <input type="number" name="availablecopies" required>
                </div>
                <button type="submit" name="add_book" class="submit">Add Book</button>
            </form>
        </div>

        <div class="displaybooks">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="book-card">
                    <img src="images/book.jpg" alt="Book Image" class="book-image">
                    <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                    <p>Author: <?php echo htmlspecialchars($row['author']); ?></p>
                    <p>Total Copies: <?php echo htmlspecialchars($row['totalcopies']); ?></p>
                    <p>Available Copies: <?php echo htmlspecialchars($row['availablecopies']); ?></p>
                    
                    <form action="catalogue.php" method="post">
                        <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="add_to_cart" class="addtocart">Add to Cart</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>

        <div>
            <a href="cart.php" class=" view">View Cart</a>
        </div>
    </div>

</body>
</html>



