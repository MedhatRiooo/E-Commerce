<?php
session_start();

// Check if the user is logged in as an user
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "user") {
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
    $query = "SELECT name, category, img_path, price FROM products WHERE category = 'featured-products'";
    $result = $conn->query($query);

    $products = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    return $products;
}

// Get all products from the database
$products = getProducts($conn);
$user_username = $_SESSION["username"];

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Use Bootstrap v5.0.2 instead of v4.0.0 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!-- Link to your custom style.css file -->
    <link rel="stylesheet" href="assets/css/user.css">
    <title>Home - Welcome User</title>
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

    <!-- Welcome Section -->
    <!-- Hero Section -->
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4">Welcome, <?php echo $user_username; ?>!</h1>
            <p class="lead">This is your personalized home page. Check out all products below:</p>
            <hr class="my-4">
            <p>Explore our featured products and find great deals.</p>
            <a class="btn btn-light btn-lg" href="products.php" role="button">Explore Products</a>
        </div>
    </div>

    <!-- All Products Section -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row">
            <?php
            // Display all products
            foreach ($products as $product) {
            ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?php echo $product['img_path']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text">Price: $<?php echo $product['price']; ?></p>
                        <button class="btn btn-success">Add to Cart</button>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
        <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your E-commerce Store</p>
    </div>
    <!-- Bootstrap JS and Popper.js (required for Bootstrap components) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <!-- Your custom JavaScript file -->
    <script src="/js/main.js"></script>

</body>

</html>
