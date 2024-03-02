<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cookinghabitDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest order ID
$sqlLatestOrder = "SELECT MAX(order_id) AS latest_order_id FROM orders";
$resultLatestOrder = $conn->query($sqlLatestOrder);

if ($resultLatestOrder->num_rows > 0) {
    $rowLatestOrder = $resultLatestOrder->fetch_assoc();
    $latestOrderId = $rowLatestOrder['latest_order_id'];
} else {
    $latestOrderId = 0; // Default value if no orders are present
}

// Fetch information from orders table
$sqlOrders = "SELECT * FROM orders WHERE order_id = ?";
$stmtOrders = $conn->prepare($sqlOrders);
$stmtOrders->bind_param("i", $latestOrderId);
$stmtOrders->execute();
$resultOrders = $stmtOrders->get_result();

if ($resultOrders->num_rows > 0) {
    $orderData = $resultOrders->fetch_assoc();

    // Fetch information from products table based on product_id from orders table
$productIds = explode(",", $orderData['product_id']); // Assuming it's named 'product_ids'
$productInfo = array();

foreach ($productIds as $productId) {
    $sqlProducts = "SELECT * FROM products WHERE id = ?"; // Change 'id' to the correct column name
    $stmtProducts = $conn->prepare($sqlProducts);
    $stmtProducts->bind_param("i", $productId);
    $stmtProducts->execute();
    $resultProducts = $stmtProducts->get_result();

    if ($resultProducts->num_rows > 0) {
        $productInfo[] = $resultProducts->fetch_assoc();
    }

    $stmtProducts->close();
}


    $stmtOrders->close();
} else {
    echo "Order not found.";
    $conn->close();
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>
    <link rel="stylesheet" href="notification.css">
</head>
<body>
    <div class="header">
        <img src="logo.png" alt="logo">
        <p>New Order: #<?php echo $latestOrderId; ?></p>
    </div>
    <div class="main-content">
        <h1>Notification</h1>
        <div class="info-box">
            <p>Date: <?php echo $orderData['order_date']; ?></p>
        </div>
        <div class="info-box">
            <p>Name: <?php echo $orderData['customer_name']; ?></p>
        </div>
        <div class="info-box">
            <p>Phone Number: <?php echo $orderData['customer_phone_number']; ?></p>
        </div>
        <div class="info-box">
            <p>Address: <?php echo $orderData['customer_address']; ?></p>
        </div>
        <div class="info-box">
            <p>Product(s): <?php
                foreach ($productInfo as $product) {
                    echo $product['product_name'] . ', ';
                }
            ?></p>
        </div>
        <div class="info-box">
            <p>Total Price(RM): <?php echo $orderData['order_total']; ?></p>
        </div>
        <div class="redirect">
            <a href="orders.php">click here to see all orders</a>
        </div>
    </div>
</body>
</html>
