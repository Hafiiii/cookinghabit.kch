<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cookinghabitDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an order ID is provided in the query parameters
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Retrieve order information
    $order_query = "SELECT * FROM orders WHERE order_id = $order_id";
    $order_result = $conn->query($order_query);

    if ($order_result->num_rows > 0) {
        $order_data = $order_result->fetch_assoc();
        $order_status = $order_data['order_status'];
        $customer_email = $order_data['customer_email'];
        $customer_name = $order_data['customer_name'];
        $customer_address = $order_data['customer_address'];
        $customer_phone_number = $order_data['customer_phone_number'];
        $product_id = $order_data['product_id'];
        $order_quantity = $order_data['order_quantity'];
        $order_total = $order_data['order_total'];
        $order_date = $order_data['order_date'];
        $payment_status = $order_data['payment_status'];

        // Retrieve product information
        $product_query = "SELECT * FROM products WHERE id = $product_id";
        $product_result = $conn->query($product_query);

        if ($product_result->num_rows > 0) {
            $product_data = $product_result->fetch_assoc();
            $product_name = $product_data['product_name'];
            $product_price = $product_data['product_price'];
        } else {
            echo "Product not found";
            exit;
        }
    } else {
        echo "Order not found";
        exit;
    }
} else {
    echo "Order ID not provided";
    exit;
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order</title>
    <link rel="stylesheet" href="neworder.css">
    <script src="neworder.js" defer></script>
</head>
<body>
    <div class="main-content">
        <div class="left-side">
            <div class="image-container">
                <img src="logo.png" alt="Logo">
            </div>
            <div class="order-status">
                <label for="status">Status:</label>
                <select id="status" onchange="updateOrderStatus(this)">
                    <option value="pending" <?php echo ($order_status === 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="dispatched" <?php echo ($order_status === 'dispatched') ? 'selected' : ''; ?>>Dispatched</option>
                    <option value="completed" <?php echo ($order_status === 'completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            <div class="order-details">
                <p>Order ID: <?php echo $order_id; ?></p>
                <p>Email: <?php echo $customer_email ?></p>
                <p>Name: <?php echo $customer_name; ?></p>
                <p>Delivery Address: <?php echo $customer_address; ?></p>
                <p>Phone Number: <?php echo $customer_phone_number; ?></p>
                <label for="payment">Payment:</label>
                <select id="payment">
                    <option value="unpaid" <?php echo ($payment_status === 'unpaid') ? 'selected' : ''; ?>>Unpaid</option>
                    <option value="paid" <?php echo ($payment_status === 'paid') ? 'selected' : ''; ?>>Paid</option>
                </select>
            </div>
            <div class="order-details-table">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Product Name</th>
                            <th>Price (RM)</th>
                            <th>Quantity</th>
                            <th>Total (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><?php echo $product_name; ?></td>
                            <td><?php echo $product_price; ?></td>
                            <td><?php echo $order_quantity; ?></td>
                            <td><?php echo $order_total; ?></td>
                        </tr>
                    </tbody>
                </table>
                <p>Final Amount (RM): <?php echo $order_total; ?></p>
                <button id="printInvoice">Print Invoice</button>
            </div>
        </div>
        <div class="right-side">
            <div class="order-history">
                <p>Order History:</p>
                <ul>
                    <li>New Order (<?php echo date("d/m/y", strtotime($order_date)); ?>)</li>
                    <?php
                    if ($payment_status === 'paid') {
                        echo "<li>The payment has been successfully transferred.</li>";
                        echo "<li>The order is currently in process.</li>";
                    }
                    if ($order_status === 'dispatched') {
                        echo "<li>The order has been dispatched.</li>";
                    }
                    if ($order_status === 'completed') {
                        echo "<li>The order is completed.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
