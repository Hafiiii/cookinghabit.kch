<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cookinghabitDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle DELETE request for deleting a product
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $career_id = $_GET['id'];
    $sql = "DELETE FROM career WHERE id=$career_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting career: ' . $conn->error]);
    }

    exit;
}

// Fetch the status filter value
$statusFilter = isset($_GET['position']) ? $_GET['position'] : 'all';

// Modify SQL query to filter by status
$sql = "SELECT * FROM career";

// Add a WHERE clause if the status filter is not 'all'
if ($statusFilter !== 'all') {
    $sql .= " WHERE position = '$statusFilter'";
}

$result = $conn->query($sql);

// Fetch data into an array
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Fetch the count before closing the connection
$countSql = "SELECT COUNT(*) as total FROM career";
$countResult = $conn->query($countSql);
$totalCount = $countResult->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career</title>
    <link rel="stylesheet" href="career_list.css">
</head>
<body>
    <div class="main-content">
        <h1>Career</h1>
        <div class="search-bar">
            <label for="product-search">Name</label>
            <input type="text" id="product-search">
        </div>
        <div class="categories">
            <button onclick="filterProducts('all')">All</button>
            <button onclick="filterProducts('kitchen-helper')">Kitchen Helper</button>
            <button onclick="filterProducts('service-crew')">Service Crew</button>
        </div>
        <div class="info-bar">
            <div class="product-count">
                <p>Total Applications: <?php echo $totalCount; ?></p>
            </div>
        </div>
        <div class="product-table">
            <!-- Product table content will be dynamically loaded using JavaScript -->
            <div class="table-header">
                <div>ID</div>
                <div>Name</div>
                <div>Phone Number</div>
                <div>Position</div>
                <div>Action</div>
            </div>
            <?php foreach ($data as $career) : ?>
                <div class="table-row">
                    <div><?php echo $career['id']; ?></div>
                    <div><?php echo $career['career_name']; ?></div>
                    <div><?php printf('%010d', $career['career_phone']); ?></div>
                    <div><?php echo $career['position']; ?></div>
                    <div>
                        <button onclick="editCareer(<?php echo $career['id']; ?>)">Edit</button>
                        <button onclick="deleteCareer(<?php echo $career['id']; ?>)">Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="career.js"></script>
</body>
</html>
