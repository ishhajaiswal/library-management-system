<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: home.html");
    exit();
} else {
    echo "You are not logged in.";
}


