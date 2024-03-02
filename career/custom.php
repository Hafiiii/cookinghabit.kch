<?php
session_start();

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

$custome_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$custom_quantity = isset($_GET['cart_quantity']) ? intval($_GET['cart_quantity']) : 1;

// Validate the product ID and quantity
if ($custome_id <= 0 || $custom_quantity <= 0) {
    // Invalid input, handle accordingly (e.g., show an error message)
    echo 'Invalid product ID or quantity.';
    exit;
}

// Fetch product details from the database
$sql = "SELECT * FROM customize WHERE id = $custome_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $size = $row['size'];
    $flavour = $row['flavour'];
    $custome_option = $row['custome_option'];

    // Check if the product is already in the database cart
    $cartSql = "SELECT * FROM cart WHERE custom_id = $custome_id";
    $cartResult = $conn->query($cartSql);

    if ($cartResult->num_rows > 0) {
        // Product is already in the database cart, update the quantity
        $updateSql = "UPDATE cart SET cart_quantity = cart_quantity + $custome_quantity WHERE custome_id = $custome_id";
        $conn->query($updateSql);
    } else {
        // Product is not in the database cart, add it as a new item
        $insertSql = "INSERT INTO cart (custome_id, cart_quantity, cart_price, cart_image, cart_name) 
                VALUES ($productId, $quantity, $productPrice, '$productImage', '$productName')";
        $conn->query($insertSql);
    }

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
