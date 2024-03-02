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

// Query the database for product data
$sql = "SELECT * FROM products WHERE status = 'breakies'";
$result = $conn->query($sql);

// Define an empty array to store the product data
$data = array();

// Loop through the query result and add each product to the data array
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "0 results";
}


$conn->close();
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cookinghabit.kch</title>
    <link rel="icon" href="image/square-logo.png">
    <link rel="stylesheet" href="menu.css">  
</head>

<body>

    <!-- NAVIGATION BAR -->
    <script id="replace_with_navbar" src="navbar.js"></script>
    <div class="banner">  
        <div class="banner-text"><p>BREAKIES</p></div>
        <img src="image/breakkie-banner.png">
    </div>
    
    <div class="categories-btn">
        <a href="menu-cakes.php">Cakes</a>
        <a href="menu-desserts.php">Desserts</a>
        <a href="menu-breakies.php">Breakies</a>
    </div>

    <?php foreach ($data as $product) : ?>
    <div class="menu-row">
        <div class="menu-column">
            <a onclick="viewProduct(<?php echo $product['id']; ?>)"><div class="menu-card">
                <div><img src="<?php echo $product['product_image']; ?>"></div>
                <div><h3><?php echo $product['product_name']; ?></h3></div>
                <div><p><?php echo 'RM ' . $product['product_price']; ?></p></div>
            </div></a>
        </div>
    </div>  
    <?php endforeach; ?>

    <!-- FOOTER -->
    <script id="replace_with_footer" src="footer.js"></script>
    <script src="menu.js"></script>
</body>
</html>