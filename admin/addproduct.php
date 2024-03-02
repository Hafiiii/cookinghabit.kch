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

// Create the products table if it doesn't exist
$tableCreationSQL = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    product_price DECIMAL(10,2) NOT NULL,
    product_description TEXT NOT NULL,
    product_image MEDIUMBLOB,  -- Assuming a path to the image
    status VARCHAR(20) NOT NULL DEFAULT 'cakes'
)";

if ($conn->query($tableCreationSQL) === TRUE) {
    // Remove the echo statement to prevent the message from being displayed
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Handle form submission
$message = ""; // Initialize an empty message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST["product_name"];
    $product_description = $_POST["product_description"];
    $product_price = $_POST["product_price"];
    $status = $_POST["status"];

    // Image upload handling
    $targetDir = "uploads/";

    // Ensure the target directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $targetFile = $targetDir . basename($_FILES["product_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the image file is a valid image
    $check = getimagesize($_FILES["product_image"]["tmp_name"]);
    if ($check === false) {
        $message = "File is not an image.";
        $uploadOk = 0;
    }

    // Check if the file already exists
    if (file_exists($targetFile)) {
        $message = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (you can adjust this value)
    if ($_FILES["product_image"]["size"] > 500000) {
        $message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (you can adjust this list)
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedExtensions)) {
        $message = "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload the file
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
            $message = "The file " . htmlspecialchars(basename($_FILES["product_image"]["name"])) . " has been uploaded.";

            // Use prepared statements to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO products (product_name, product_description, product_price, status, product_image)
                                    VALUES (?, ?, ?, ?, ?)");

            // Bind parameters
            $stmt->bind_param("ssdss", $product_name, $product_description, $product_price, $status, $targetFile);

            // Execute the statement
            if ($stmt->execute()) {
                $message .= " Product added successfully";
                // Add a JavaScript script to redirect after a successful submission
                echo '<script>window.location.href = "products.php";</script>';
            } else {
                $message = "Error: " . $stmt->error;
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="addeditproduct.css">
</head>
<body>
    <div class="logo">
        <img src="logo.png" alt="Logo">
    </div>

    <h1>Add New Product</h1>

    <?php
    // Display the message if it's not empty
    if (!empty($message)) {
        echo "<p>{$message}</p>";
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

        <label for="product_image">Product Image: </label>
        <input type="file" name="product_image" id="imageInput" accept="image/*" required>

        <label for="product_name">Product Name: </label>
        <input type="text" name="product_name" required>

        <label for="product_description">Product Description: </label>
        <textarea name="product_description" required></textarea>

        <label for="product_price">Product Price (RM): </label>
        <input type="number" name="product_price" required>

        <label for="status">Category: </label>
        <select name="status" id="product-status" required onchange="filterByStatus()">
            <option value="cakes">Cakes</option>
            <option value="desserts">Desserts</option>
            <option value="breakies">Breakies</option>
        </select>

        <button type="submit" name="save_publish">Save and Publish</button>
        <button type="submit" name="save_delist">Save and Delist</button>
        <button type="button" name="cancel" onclick="location.href='products.php'">Cancel</button>
    </form>

</body>
</html>
