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

// Dummy data for visitors, page views, and number of reviews
$visitors = 500;
$pageViews = 1200;
$numberOfReviews = 30;

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link rel="stylesheet" href="statistics.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="main-content">
        <h1>Sales Chart</h1>
        
        <!-- Display sales order (RM) -->
        <div class="info-box">
            <label>Sales (RM)</label>
            <p><?php echo 'RM ' . number_format($totalSales,2); ?></p>
        </div>

        <div class="info-box">
            <label>Total Orders</label>
            <p><?php echo $totalOrders; ?></p>
        </div>

        <div class="info-box">
            <label>Visitors</label>
            <p><?php echo $visitors; ?></p>
        </div>

        <div class="info-box">
            <label>Page Views</label>
            <p><?php echo $pageViews; ?></p>
        </div>

        <div class="info-box">
            <label>Reviews</label>
            <p><?php echo $numberOfReviews; ?></p>
        </div>

        <!-- Display the sales chart -->
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
