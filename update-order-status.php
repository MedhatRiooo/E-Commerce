<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Your database connection code here
    $conn = new mysqli("localhost", "root", "", "e_commerce");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get data from the form
    $orderId = $_POST["orderId"];
    $orderStatus = $_POST["orderStatus"];

    // Update the order status in the database
    $updateQuery = "UPDATE orders SET order_status = '$orderStatus' WHERE order_id = $orderId";
    $conn->query($updateQuery);

    // Close the database connection
    $conn->close();

    // Redirect back to the manage-orders.php page
    header("Location: manage-orders.php");
    exit();
}
?>
