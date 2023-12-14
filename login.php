<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Your database connection code here
    $conn = new mysqli("localhost", "root", "", "e_commerce");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve the stored password and role from the database
    $retrieveInfo = "SELECT id, password, role FROM users WHERE username='$username'";
    $result = $conn->query($retrieveInfo);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $storedPassword = $row["password"];
        $role = $row["role"];
        $user_id = $row["id"];

        // Check if the user is an admin
        if ($role == "admin" && $password == $storedPassword) {
            // Admin login successful
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role;
            header("Location: home_admin.html");
        } elseif ($role == "user" && password_verify($password, $storedPassword)) {
            // User login successful
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role;
            header("Location: home_user.php");
        } else {
            // Login failed
            echo "Invalid username or password";
        }
    } else {
        // User not found
        echo "Invalid username or password";
    }

    $conn->close();
}
?>
