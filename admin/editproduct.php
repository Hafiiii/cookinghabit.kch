<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cookinghabitDB";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize an empty message
$message = "";

// Check if the "save_publish" button is clicked
if (isset($_POST["save_publish"])) {
    $product_id = $_POST["product_id"];
    $product_name = $_POST["product_name"];
    $product_description = $_POST["product_description"];
    $product_price = $_POST["product_price"];
    $status = $_POST["status"];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE products SET product_name=?, product_description=?, product_price=?, status=? WHERE id=?");

    // Bind parameters
    $stmt->bind_param("ssdsi", $product_name, $product_description, $product_price, $status, $product_id);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "Product updated successfully.";
        // Redirect to products.php after a successful update
        header("Location: products.php");
        exit();
    } else {
        $message = "Error updating product: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Fetch product details for editing
if (isset($_GET["id"])) {
    $product_id = $_GET["id"];
    $query = "SELECT * FROM products WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="addeditproduct.css">
</head>
<body>
    <div class="logo">
        <img src="logo.png" alt="Logo">
    </div>

    <h1>Edit Product</h1>

    <?php
    // Display the message if it's not empty
    if (!empty($message)) {
        echo "<p>{$message}</p>";
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

        <label for="product_name">Product Name: </label>
        <input type="text" name="product_name" value="<?php echo $product['product_name']; ?>" required>

        <label for="product_description">Product Description: </label>
        <textarea name="product_description" required><?php echo $product['product_description']; ?></textarea>

        <label for="product_price">Product Price (RM): </label>
        <input type="number" name="product_price" value="<?php echo $product['product_price']; ?>" required>

        <label for="status">Category: </label>
        <select name="status" id="product-status" required onchange="filterByStatus()">
            <option value="cakes" <?php if ($product['status'] == 'cakes') echo 'selected'; ?>>Cakes</option>
            <option value="desserts" <?php if ($product['status'] == 'desserts') echo 'selected'; ?>>Desserts</option>
            <option value="breakies" <?php if ($product['status'] == 'breakies') echo 'selected'; ?>>Breakies</option>
        </select>

        <button type="submit" name="save_publish">Save and Publish</button>
        <button type="button" name="cancel" onclick="location.href='products.php'">Cancel</button>
    </form>

</body>
</html>
