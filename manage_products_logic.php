<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.html");
    exit();
}

// Your database connection code here
$conn = new mysqli("localhost", "root", "", "e_commerce");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to retrieve all products from the database
function getProducts($conn) {
    $query = "SELECT * FROM products";
    $result = $conn->query($query);

    $products = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    return $products;
}

// Function to get a product by its ID
function getProductById($conn, $productId) {
    $query = "SELECT * FROM products WHERE id = '$productId'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

// Get all products from the database
$products = getProducts($conn);

// Handle form submissions for adding, editing, and deleting products
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                // Handle form submission for adding a new product
                $newProductName = $_POST['newProductName'];
                $newProductCategory = $_POST['newProductCategory'];
                $newProductPrice = $_POST['newProductPrice'];

                // Upload and save the image (adjust the path based on your server configuration)
                $targetDirectory = "uploads/";
                $targetFile = $targetDirectory . basename($_FILES["newProductImage"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Check if the image file is a actual image or fake image
                if (isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["newProductImage"]["tmp_name"]);
                    if ($check !== false) {
                        echo "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        echo "File is not an image.";
                        $uploadOk = 0;
                    }
                }

                // Check if file already exists
                if (file_exists($targetFile)) {
                    echo "Sorry, file already exists.";
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES["newProductImage"]["size"] > 500000) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif") {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                } else {
                    // if everything is ok, try to upload file
                    if (move_uploaded_file($_FILES["newProductImage"]["tmp_name"], $targetFile)) {
                        echo "The file " . htmlspecialchars(basename($_FILES["newProductImage"]["name"])) . " has been uploaded.";

                        // Insert new product into the database
                        $insertQuery = "INSERT INTO products (name, category, img_path, price) VALUES ('$newProductName', '$newProductCategory', '$targetFile', '$newProductPrice')";
                        if ($conn->query($insertQuery) === true) {
                            echo "New product added successfully.";
                            header("Location: manage_products.php");
                        } else {
                            echo "Error adding new product: " . $conn->error;
                        }
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }

                break;

            case 'edit':
                // Handle form submission for editing a product
                $productId = $_POST['productId'];
                $productName = $_POST["productName"];
                $productCategory = $_POST["productCategory"];
                $productPrice = $_POST["productPrice"];

                $updateQuery = "UPDATE products SET name = '$productName', category = '$productCategory', price = '$productPrice' WHERE id = '$productId'";
                if ($conn->query($updateQuery) === true) {
                    echo "Product updated successfully.";
                    header("Location: manage_products.php");
                } else {
                    echo "Error updating product: " . $conn->error;
                }
                break;

            case 'delete':
                // Handle form submission for deleting a product
                $productId = $_POST['productId'];

                // Delete product image from the server
                $product = getProductById($conn, $productId);
                if ($product !== null) {
                    unlink($product['img_path']);
                }

                $deleteQuery = "DELETE FROM products WHERE id = '$productId'";
                if ($conn->query($deleteQuery) === true) {
                    echo "Product deleted successfully.";
                    header("Location: manage_products.php");
                } else {
                    echo "Error deleting product: " . $conn->error;
                }
                break;
        }
    }
}

// Close the database connection
$conn->close();
?>

