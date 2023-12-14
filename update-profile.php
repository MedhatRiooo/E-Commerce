<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

// Your database connection code here
$conn = new mysqli("localhost", "root", "", "e_commerce");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from the session
$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newUsername = $_POST["newUsername"];
    $newPassword = password_hash($_POST["newPassword"], PASSWORD_DEFAULT);

    // Update user information in the database
    $updateQuery = "UPDATE users SET username='$newUsername', password='$newPassword' WHERE id=$user_id";
    $conn->query($updateQuery);

    // Redirect back to the user profile page
    header("Location: user-profile.php");
    exit();
}

// Close the database connection
$conn->close();
?>
