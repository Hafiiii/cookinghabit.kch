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
    $productId = $_GET['id'];
    $sql = "DELETE FROM products WHERE id=$productId";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting product: ' . $conn->error]);
    }

    exit;
}

// Fetch the status filter value
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Modify SQL query to filter by status
$sql = "SELECT * FROM products";

// Add a WHERE clause if the status filter is not 'all'
if ($statusFilter !== 'all') {
    $sql .= " WHERE status = '$statusFilter'";
}

$result = $conn->query($sql);

// Fetch data into an array
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Fetch the count before closing the connection
$countSql = "SELECT COUNT(*) as total FROM products";
$countResult = $conn->query($countSql);
$totalCount = $countResult->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="products.css">
</head>
<body>
    <div class="main-content">
        <h1>Products</h1>
        <div class="search-bar">
            <label for="product-search">Product Name</label>
            <input type="text" id="product-search">
        </div>
        <div class="categories">
            <button onclick="filterProducts('all')">All</button>
            <button onclick="filterProducts('cakes')">Cakes</button>
            <button onclick="filterProducts('desserts')">Desserts</button>
            <button onclick="filterProducts('breakies')">Breakies</button>
        </div>
        <div class="info-bar">
            <div class="product-count">
                <p>Total Products: <?php echo $totalCount; ?></p>
            </div>
            <a href="addproduct.php"><button id="add-product-btn">+ Add New Product</button></a>
        </div>
        <div class="product-table">
            <!-- Product table content will be dynamically loaded using JavaScript -->
            <div class="table-header">
                <div>ID</div>
                <div>Product Name</div>
                <div>Product Image</div>
                <div>Product Price</div>
                <div>Action</div>
            </div>
            <?php foreach ($data as $product) : ?>
                <div class="table-row">
                    <div><?php echo $product['id']; ?></div>
                    <div><?php echo $product['product_name']; ?></div>
                    <div><img src="<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>" class="product-image"></div>
                    <div><?php echo $product['product_price']; ?></div>
                    <div>
                        <button onclick="editProduct(<?php echo $product['id']; ?>)">Edit</button>
                        <button onclick="deleteProduct(<?php echo $product['id']; ?>)">Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
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
    <script src="products.js"></script>
</body>
</html>
