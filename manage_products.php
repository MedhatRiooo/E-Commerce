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

// Get all products from the database
$products = getProducts($conn);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
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
                    <a class="nav-link" href="home_admin.html">Admin Panel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_users.php">Manage Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_products.php">Manage Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-orders.php">Manage Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.html">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Manage Products Section -->
<div class="container">
    <h2>Manage Products</h2>

    <!-- Product Table -->
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Image</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <!-- Dynamic Product Rows -->
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo $product['name']; ?></td>
                <td><?php echo $product['category']; ?></td>
                <td>
                    <img src="<?php echo $product['img_path']; ?>" alt="Product Image" class="img-preview">
                </td>
                <td><?php echo $product['price']; ?></td>
                <td>
                    <!-- Edit and Delete buttons -->
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $product['id']; ?>">Edit</button>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal<?php echo $product['id']; ?>">Delete</button>
                </td>
            </tr>

            <!-- Edit Product Modal for each product -->
            <div class="modal fade" id="editProductModal<?php echo $product['id']; ?>" tabindex="-1" aria-labelledby="editProductModalLabel<?php echo $product['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel<?php echo $product['id']; ?>">Edit Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Edit Product Form -->
                            <form action="manage_products_logic.php" method="post">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
                                <div class="mb-3">
                                    <label for="productName" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="productName" name="productName" value="<?php echo $product['name']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="productCategory" class="form-label">Product Category</label>
                                    <input type="text" class="form-control" id="productCategory" name="productCategory" value="<?php echo $product['category']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="productPrice" class="form-label">Product Price</label>
                                    <input type="text" class="form-control" id="productPrice" name="productPrice" value="<?php echo $product['price']; ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Product Modal for each product -->
            <div class="modal fade" id="deleteProductModal<?php echo $product['id']; ?>" tabindex="-1" aria-labelledby="deleteProductModalLabel<?php echo $product['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteProductModalLabel<?php echo $product['id']; ?>">Delete Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this product?</p>
                            <form action="manage_products_logic.php" method="post">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add Product Form -->
    <h3>Add Product</h3>
    <form action="manage_products_logic.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <div class="mb-3">
            <label for="newProductName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="newProductName" name="newProductName" required>
        </div>
        <div class="mb-3">
            <label for="newProductCategory" class="form-label">Product Category</label>
            <select class="form-control" id="newProductCategory" name="newProductCategory" required>
                <option value="electronics">Electronics</option>
                <option value="clothing">Clothing</option>
                <option value="books">Books</option>
                <option value="featured-products">Featured Products</option>
                <!-- Add more options as needed -->
            </select>
        </div>

        <div class="mb-3">
            <label for="newProductPrice" class="form-label">Product Price</label>
            <input type="text" class="form-control" id="newProductPrice" name="newProductPrice" required>
        </div>
        <div class="mb-3">
            <label for="newProductImage" class="form-label">Product Image</label>
            <input type="file" class="form-control" id="newProductImage" name="newProductImage" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-success">Add Product</button>
    </form>
</div>


<!-- Footer -->
<div class="footer">
    <p>&copy; 2023 Your E-commerce Store</p>
</div>

<!-- Bootstrap JS and Popper.js (required for Bootstrap components) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

<!-- Your custom JavaScript for handling dynamic content and interactions -->
</body>
</html>

