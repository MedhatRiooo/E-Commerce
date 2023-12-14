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

// Get user ID from the session
$user_id = $_SESSION["user_id"];

// Retrieve products in the user's cart
$cart_query = "SELECT products.* FROM products 
               INNER JOIN cart ON products.id = cart.product_id
               WHERE cart.user_id = $user_id";
$result = $conn->query($cart_query);

// Calculate total price
$total_price = 0;
while ($row = $result->fetch_assoc()) {
    $total_price += $row['price'];
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/user.css">
    <title>Checkout</title>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">E-commerce Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="home_user.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user-profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Checkout Form -->
    <div class="container mt-5">
        <h2>Order Confirmation</h2>

        <form action="order-confirmation.php" method="post">
            <!-- Display products in the cart -->
            <?php
            $result->data_seek(0); // Reset result set pointer
            while ($row = $result->fetch_assoc()) {
                echo '<input type="hidden" name="product_ids[]" value="' . $row['id'] . '">';
                echo '<div class="form-group">';
                echo '<label for="product_name_' . $row['id'] . '">Product Name:</label>';
                echo '<input type="text" class="form-control" id="product_name_' . $row['id'] . '" name="product_names[]" value="' . $row['name'] . '" readonly>';
                echo '</div>';
            }
            ?>
            <div class="form-group">
                <label for="total_price">Total Price:</label>
                <input type="text" class="form-control" id="total_price" name="total_price" value="<?php echo $total_price; ?>" readonly>
            </div>
            <!-- Additional fields for order confirmation -->
            <div class="form-group">
                <label for="shipping_address">Shipping Address:</label>
                <input type="text" class="form-control" id="shipping_address" name="shipping_address" required>
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <!-- Add more payment options as needed -->
                </select>
            </div>
            <!-- Add other form fields as needed -->
            <button type="submit" class="btn btn-primary">Confirm Order</button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your E-commerce Store</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <!-- Your custom JavaScript file -->
    <script src="/js/main.js"></script>

</body>
</html>

