<?php
session_start();
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
}

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

$sessionId = $_SESSION['session_id'];
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$quantity = isset($_GET['cart_quantity']) ? intval($_GET['cart_quantity']) : 1;

// Validate the product ID and quantity
if ($productId <= 0 || $quantity <= 0) {
    // Invalid input, handle accordingly (e.g., show an error message)
    echo 'Invalid product ID or quantity.';
    exit;
}

// Fetch product details from the database
$sql = "SELECT * FROM products WHERE id = $productId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $productName = $row['product_name'];
    $productPrice = $row['product_price'];
    $productImage = $row['product_image'];

    // Check if the product is already in the database cart
    $cartSql = "SELECT * FROM cart WHERE product_id = $productId";
    $cartResult = $conn->query($cartSql);

    if ($cartResult->num_rows > 0) {
        // Product is already in the database cart, update the quantity
        $updateSql = "UPDATE cart SET cart_quantity = cart_quantity + $quantity WHERE product_id = $productId";
        $conn->query($updateSql);
    } else {
        // Product is not in the database cart, add it as a new item
        $insertSql = "INSERT INTO cart (product_id, cart_quantity, cart_price, cart_image, cart_name) 
                VALUES ($productId, $quantity, $productPrice, '$productImage', '$productName')";
        $conn->query($insertSql);
    }

    
// Check if form has been submitted


    // Provide feedback to the user
    echo 'Product added to cart successfully!';

    // Debugging: Add some debugging statements
    echo '<br>Product Name: ' . $productName;
    echo '<br>Product Price: ' . $productPrice;
    echo '<br>Product Image: ' . $productImage;
    echo '<br>Updated Cart: ';
    print_r($_SESSION['cart']);
} else {
    echo 'Product not found.';
}

$conn->close();
?>
