<?php
session_start();

// Your database connection code here
$conn = new mysqli("localhost", "root", "", "e_commerce");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to retrieve all products from the database
function getProducts($conn) {
    $query = "SELECT id, name, price, img_path FROM products";
    $result = $conn->query($query);

    $products = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    return $products;
}

// Function to retrieve products based on category
function getProductsByCategory($conn, $category) {
    $query = "SELECT id, name, price, img_path FROM products WHERE category = '$category'";
    $result = $conn->query($query);

    $products = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    return $products;
}

// Get all unique product categories
$categoryQuery = "SELECT DISTINCT category FROM products";
$categoryResult = $conn->query($categoryQuery);
$categories = [];

if ($categoryResult->num_rows > 0) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

// Get selected category from the dropdown, default to 'all'
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// Get all products or products from a specific category
$products = ($category == 'all') ? getProducts($conn) : getProductsByCategory($conn, $category);

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include Bootstrap CSS (v5) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!-- Include User CSS -->
    <link rel="stylesheet" href="assets/css/user.css">
    <title>Home - Welcome Admin</title>
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

    <!-- Products Section -->
    <div class="container mt-5">
        <h2>Products</h2>

        <!-- Category Dropdown -->
        <form method="get" action="products.php">
            <label for="category">Filter by Category:</label>
            <select name="category" id="category">
                <option value="all">All Categories</option>
                <?php
                foreach ($categories as $cat) {
                    $selected = ($cat == $category) ? 'selected' : '';
                    echo "<option value='$cat' $selected>$cat</option>";
                }
                ?>
            </select>
            <button type="submit">Filter</button>
        </form>

        <!-- Display all products in a row -->
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
                            <form method="post" action="addToCart.php">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
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
    <!-- Bootstrap JS and Popper.js (make sure to include them at the end of the body) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <!-- Your custom JavaScript file -->
    <script src="assets/js/main.js"></script>

</body>
</html>
