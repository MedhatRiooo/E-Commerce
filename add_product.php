<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Your database connection code here
    $conn = new mysqli("localhost", "root", "", "e_commerce");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get data from the form
    $productName = $_POST["productName"];
    $productCategory = $_POST["productCategory"];
    $productPrice = $_POST["productPrice"];

    // Handle file upload for the product image
    $targetDirectory = "uploads/"; // Create a directory named "uploads" in your project folder
    $targetFile = $targetDirectory . basename($_FILES["productImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["productImage"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if the file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (adjust as needed)
    if ($_FILES["productImage"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (you can customize this list)
    $allowedFormats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk === 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload the file
        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
            // Insert product data into the database
            $insertQuery = "INSERT INTO products (name, category, img, price) VALUES ('$productName', '$productCategory', '$targetFile', '$productPrice')";
            if ($conn->query($insertQuery) === true) {
                echo "Product added successfully.";
            } else {
                echo "Error: " . $insertQuery . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Close the database connection
    $conn->close();
}
?>
