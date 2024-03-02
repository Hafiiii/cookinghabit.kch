<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cookinghabitDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total sales data from the orders table
$sql = "SELECT MONTH(order_date) as month, SUM(order_total) as total_sales 
        FROM orders 
        GROUP BY MONTH(order_date)";

$result = $conn->query($sql);

// Fetch data into an array
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Retrieve the actual total order data
$totalOrdersResult = $conn->query("SELECT COUNT(*) as total_orders FROM orders");
$totalOrdersData = $totalOrdersResult->fetch_assoc();
$totalOrders = $totalOrdersData['total_orders'];

// Calculate the total sales for all time
$totalSalesResult = $conn->query("SELECT SUM(order_total) as total_sales FROM orders");
$totalSalesData = $totalSalesResult->fetch_assoc();
$totalSales = $totalSalesData['total_sales'];

// Retrieve counts for each order status
$pendingOrdersResult = $conn->query("SELECT COUNT(*) as pending_orders FROM orders WHERE order_status = 'Pending'");
$pendingOrdersData = $pendingOrdersResult->fetch_assoc();
$pendingOrders = isset($pendingOrdersData['pending_orders']) ? $pendingOrdersData['pending_orders'] : 0;

$dispatchedOrdersResult = $conn->query("SELECT COUNT(*) as dispatched_orders FROM orders WHERE order_status = 'Dispatched'");
$dispatchedOrdersData = $dispatchedOrdersResult->fetch_assoc();
$dispatchedOrders = isset($dispatchedOrdersData['dispatched_orders']) ? $dispatchedOrdersData['dispatched_orders'] : 0;

$completedOrdersResult = $conn->query("SELECT COUNT(*) as completed_orders FROM orders WHERE order_status = 'Completed'");
$completedOrdersData = $completedOrdersResult->fetch_assoc();
$completedOrders = isset($completedOrdersData['completed_orders']) ? $completedOrdersData['completed_orders'] : 0;

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="main-content">
        <h1>To Do List</h1>
        <p>Things you need to deal with</p>
        <div class="info-box">
            <p><?php echo $pendingOrders; ?></p>
            <label>Pending Orders</label>
        </div>
        <div class="info-box">
            <p><?php echo $dispatchedOrders; ?></p>
            <label>Dispatched Orders</label>
        </div>
        <div class="info-box">
            <p><?php echo $completedOrders; ?></p>
            <label>Completed Orders</label>
        </div>
    </div>
    <div class="sales-chart">
        <h1>Statistics</h1>
        <p>An overview of the shop data for the paid order dimension</p>
        <canvas id="salesChart"></canvas>
    </div>
    <script>
        // Extracting data from PHP to JavaScript
        var salesData = <?php echo json_encode($data); ?>;
        
        // Extracting months and total sales from the data
        var months = salesData.map(entry => getMonthName(entry.month));
        var totalSales = salesData.map(entry => entry.total_sales);

        // Creating a line chart
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Sales (RM)',
                    data: totalSales,
                    borderColor: 'rgba(238, 102, 10, 1)',
                    borderWidth: 2,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgba(238, 102, 10, 1)',
                    fill: false
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: { autoSkip: false, maxRotation: 45, minRotation: 45 },
                        type: 'category',
                        position: 'bottom'
                    },
                    y: {
                        beginAtZero: true,
                        callback: function (value, index, values) {
                            return 'RM ' + value.toFixed(2);
                        }
                    }
                }
            }
        });

        // Function to convert month number to name
        function getMonthName(monthNumber) {
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            return months[monthNumber - 1];
        }

        // Function to format currency
        function formatCurrency(amount) {
            return 'RM ' + amount.toFixed(2);
        }
    </script>
    <script>
        // Fetch the sidebar content and append it to the body
        fetch('sidebar.html')
            .then(response => response.text())
            .then(data => {
                console.log('Sidebar content:', data);
                document.body.insertAdjacentHTML('afterbegin', data);
            })
            .catch(error => console.error('Error fetching sidebar:', error));
    </script>
</body>
</html>
