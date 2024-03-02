<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cookinghabitDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get orders based on status
function getOrders($conn, $status) {
    if ($status === 'all') {
        $sql = "SELECT * FROM orders";
    } else {
        $sql = "SELECT * FROM orders WHERE order_status = '$status'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return array();
    }
}

// Fetch orders from the database based on the status
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $orders = getOrders($conn, $status);
} else {
    // Default to 'all' if no status is specified
    $status = 'all';
    $orders = getOrders($conn, $status);
}

// Fetch products from the database
$product_query = "SELECT * FROM products";
$product_result = $conn->query($product_query);

if ($product_result->num_rows > 0) {
    $products = $product_result->fetch_all(MYSQLI_ASSOC);
} else {
    // If no products are found, set $products to an empty array
    $products = array();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="orders.css">
</head>
<body>
    <div class="main-content">
        <h1>Orders</h1>
        <div class="order-count">
            <p>Total Orders: <?php echo count($orders); ?></p>
        </div>
        <div class="categories">
            <button onclick="filterOrders('all')">All Orders</button>
            <button onclick="filterOrders('dispatched')">Dispatched</button>
            <button onclick="filterOrders('pending')">Pending</button>
            <button onclick="filterOrders('completed')">Completed</button>
        </div>
        <div class="order-table">
            <div class="table-header">
                <div>Customer Name</div>
                <div>Product(s)</div>
                <div>Order Total (RM)</div>
                <div>Date</div>
                <div>Action</div>
            </div>
            <!-- Populate the table with orders using JavaScript -->
            <script src="orders.js"></script>
        </div>
        <script>
            // Fetch orders and products from PHP to JavaScript
            var orders = <?php echo json_encode($orders); ?>;
            var products = <?php echo json_encode($products); ?>;
            // Populate the table with actual orders and products
            populateOrderTable(orders, products);
        </script>
    </div>
    <script>
        // Fetch the sidebar content and append it to the body
        fetch('sidebar.html')
            .then(response => response.text())
            .then(data => {
                console.log('Sidebar content:', data); // Debugging statement
                document.body.insertAdjacentHTML('afterbegin', data);
            })
            .catch(error => console.error('Error fetching sidebar:', error));
    </script>
</body>
</html>
