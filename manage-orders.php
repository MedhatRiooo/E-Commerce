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

// Function to retrieve all orders from the database
function getOrders($conn) {
    $query = "SELECT * FROM orders";
    $result = $conn->query($query);

    $orders = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }

    return $orders;
}

// Get all orders from the database
$orders = getOrders($conn);

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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


 <!-- Manage Orders Section -->
 <div class="container mt-5">
        <h2>Manage Orders</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Product Names</th>
                    <th>Total Price</th>
                    <th>Order Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['user_id']; ?></td>
                        <td><?php echo $order['product_names']; ?></td>
                        <td><?php echo $order['total_price']; ?></td>
                        <td><?php echo $order['order_status']; ?></td>
                        <td>
                            <button class="btn btn-primary" onclick="updateOrderStatus(<?php echo $order['order_id']; ?>)">Update Status</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Update Order Status Modal -->
<div id="updateOrderStatusModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeUpdateOrderStatusModal()">&times;</span>
        <h3>Update Order Status</h3>
        <form id="updateOrderStatusForm" action="update-order-status.php" method="post">
            <input type="hidden" id="orderIdInput" name="orderId" value="">
            <label for="orderStatus">Select Order Status:</label>
            <select id="orderStatus" name="orderStatus" required>
                <option value="complete">Complete</option>
                <option value="on_hold">On Hold</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <br>
            <button type="submit" class="btn btn-primary">Update Status</button>
        </form>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2023 Your E-commerce Store</p>
</div>

<script src="assets/js/script.js"></script>
<!-- Bootstrap JS and Popper.js (required for Bootstrap components) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

</body>
</html>