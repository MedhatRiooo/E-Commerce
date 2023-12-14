<?php
session_start();

// Check if the user is logged in as a user
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "user") {
    header("Location: login.html");
    exit();
}

// Your database connection code here
$conn = new mysqli("localhost", "root", "", "e_commerce");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get product ID from the form
    $product_id = $_POST["product_id"];

    // Get user ID from the session
    $user_id = $_SESSION["user_id"];

    // Insert the selected product into the cart table
    $insert_query = "INSERT INTO cart (user_id, product_id) VALUES ($user_id, $product_id)";
    $conn->query($insert_query);
}

// Close the database connection
$conn->close();

// Redirect back to the products page
header("Location: cart.php");
exit();
?>
