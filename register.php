<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Your database connection code here

    $conn = new mysqli("localhost", "root", "", "e_commerce");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username already exists
    $checkUsername = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($checkUsername);

    if ($result->num_rows > 0) {
        echo "<script>alert('Username already exists. Please choose a different username.');</script>";
    } else {
        // Hash the password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $insertUser = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
        if ($conn->query($insertUser) === TRUE) {
            echo "<script>alert('Registration successful. You can now login.');</script>";
            header("Location: login.html");
        } else {
            echo "Error: " . $insertUser . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>