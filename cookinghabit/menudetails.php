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
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query the database for product data
$sql = "SELECT * FROM products WHERE id = $productId";
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
    <link rel="stylesheet" href="menudetails.css">  
</head>

<body>

    <!-- NAVIGATION BAR -->
    <script id="replace_with_navbar" src="navbar.js"></script>

    <div class="back-page">
        <a href="javascript:history.back()"><img src="image/back.png"></a>
    </div>

    <?php foreach ($data as $product) : ?>
    <div class="product-details">
        <div><h2><?php echo $product['product_name']; ?></h2></div>
        <div><p class="price"><?php echo 'RM ' . $product['product_price']; ?></p></div>
        <div><img src="<?php echo $product['product_image']; ?>"></div>

        <!-- Update the quantity input field to be dynamic -->
        <div class="quantity">
            <h3>QUANTITY</h3>
            <input type="number" name="cart_quantity" id="cart_quantity" required value="1">
        </div>

        <!-- Pass both product ID and quantity to the addCart function -->
        <div class="addCart-btn">
            <button onclick="addCart(<?php echo $product['id']; ?>, document.getElementById('cart_quantity').value)">ADD TO CART</button>
        </div>

        <div class="description">
            <h3>DESCRIPTION</h3>
            <p><?php echo $product['product_description']; ?></p>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- FOOTER -->
    <script id="replace_with_footer" src="footer.js"></script>
    <script src="menu.js"></script>

</body>
</html>