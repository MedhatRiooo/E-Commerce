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

$user_id = $_SESSION["user_id"];

// Function to retrieve order details for the user
function getOrderDetails($conn, $user_id) {
    $query = "SELECT * FROM orders WHERE user_id=$user_id";
    $result = $conn->query($query);

    $orderDetails = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orderDetails[] = $row;
        }
    }

    return $orderDetails;
}

// Get order details for the user
$orderDetails = getOrderDetails($conn, $user_id);
$user_username = $_SESSION["username"];

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/user.css">
    <title>User Profile</title>
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

    <!-- User Profile Section -->
    <div class="container mt-5">
        <h2 class="text-center">User Profile - <?php echo $user_username; ?></h2><br>

        <!-- Order Details Table -->
        <h3 class="text-center mb-4">Order Details</h3>
        <?php
        if (!empty($orderDetails)) {
            echo '<table class="table table-striped">';
            echo '<thead><tr><th>Order ID</th><th>Product Names</th><th>Total Price</th><th>Shipping Address</th><th>Payment Method</th><th>Order Date</th><th>Order Status</th></tr></thead>';
            echo '<tbody>';
            foreach ($orderDetails as $orderDetail) {
                echo '<tr>';
                echo '<td>' . $orderDetail['order_id'] . '</td>';
                echo '<td>' . $orderDetail['product_names'] . '</td>';
                echo '<td>$' . $orderDetail['total_price'] . '</td>';
                echo '<td>' . $orderDetail['shipping_address'] . '</td>';
                echo '<td>' . $orderDetail['payment_method'] . '</td>';
                echo '<td>' . $orderDetail['order_date'] . '</td>';
                echo '<td>' . $orderDetail['order_status'] . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table><br>';
        } else {
            echo '<p class="text-center">No order details available.</p><br>';
        }
        ?>

        <!-- Update Profile Form -->
        <h3 class="text-center mb-4">Update Profile</h3>
        <form action="update-profile.php" method="post">
            <div class="mb-3">
                <label for="newUsername" class="form-label">New Username:</label>
                <input type="text" class="form-control" id="newUsername" name="newUsername" required>
            </div>
            <div class="mb-3">
                <label for="newPassword" class="form-label">New Password:</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your E-commerce Store</p>
    </div>


    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

</body>
</html>

