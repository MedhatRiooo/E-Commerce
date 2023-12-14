<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a database connection
    $conn = new mysqli("localhost", "root", "", "e_commerce");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get user ID from the session
    $user_id = $_SESSION["user_id"];

    // Get product names, total price, shipping address, and payment method from the form
    $product_names = $_POST["product_names"];
    $total_price = $_POST["total_price"];
    $shipping_address = $_POST["shipping_address"];
    $payment_method = $_POST["payment_method"];

    // Insert order data into the orders table
    $insert_order_query = "INSERT INTO orders (user_id, product_names, total_price, shipping_address, payment_method) 
                           VALUES ('$user_id', '" . implode(", ", $product_names) . "', '$total_price', '$shipping_address', '$payment_method')";

    if ($conn->query($insert_order_query) === TRUE) {
        // Order successfully added to the orders table

        // Delete the corresponding items from the cart
        $delete_cart_items_query = "DELETE FROM cart WHERE user_id = '$user_id'";
        $conn->query($delete_cart_items_query);

        // Redirect to a confirmation page or any other page as needed
        header("Location: order_sent.html");
        exit();
    } else {
        echo "Error: " . $insert_order_query . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
