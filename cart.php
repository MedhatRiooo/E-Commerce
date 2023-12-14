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

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/user.css">
    <title>Shopping Cart</title>
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

<!-- Cart Section -->
<div class="container mt-5">
    <h2>Your Shopping Cart</h2>

    <div class="row">
        <?php
        // Display products in the cart
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?php echo $row['img_path']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="card-text">Category: <?php echo $row['category']; ?></p>
                        <p class="card-text">Price: $<?php echo $row['price']; ?></p>
                        <!-- Add any other product details you want to display -->
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

    <!-- Button to proceed to checkout -->
    <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
</div>



    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your E-commerce Store</p>
    </div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

<!-- Your custom JavaScript file -->
<script src="/js/main.js"></script>

</body>
</html>

