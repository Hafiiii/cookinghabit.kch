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

// Handle the upload of proof payment receipt
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "proof/";
    $target_file = $target_dir . basename($_FILES["receipt"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        $message = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["receipt"]["size"] > 500000) {
        $message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "pdf") {
        $message = "Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded.";
    } else {
        // Move the file to the target directory
        if (move_uploaded_file($_FILES["receipt"]["tmp_name"], $target_file)) {
            $message = "The file <strong>" . basename( $_FILES["receipt"]["name"]) . "</strong> has been uploaded.";
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cookinghabit.kch - Checkout</title>
    <link rel="icon" href="image/square-logo.png">
    <link rel="stylesheet" href="checkout.css">  
</head>

<body>

    <!-- NAVIGATION BAR -->
    <script id="replace_with_navbar" src="navbar.js"></script>

    <div class="checkout-container">
        <div class="checkout-header">
            <h1>Checkout Summary</h1>
        </div>

        <?php
        // Fetch cart items from the database
        $cartSql = "SELECT * FROM cart";
        $cartResult = $conn->query($cartSql);

        if ($cartResult->num_rows > 0) {
            echo '<table>';
            echo "<tr class='container-header'><th>&nbsp&nbsp&nbspProduct</th><th></th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>";

            $totalPrice = 0;

            while ($row = $cartResult->fetch_assoc()) {
                $productName = $row['cart_name'];
                $quantity = $row['cart_quantity'];
                $productPrice = $row['cart_price'];
                $productImage = $row['cart_image'];

                // Calculate subtotal for each item
                $subtotal = $quantity * $productPrice;

                // Display cart item details and subtotal
                echo "<tr class='cart-content'>
                        <td><img src='$productImage' alt='$productName' style='max-width: 100px; max-height: 100px;'></td>
                        <td>$productName</td>
                        <td>$quantity</td>
                        <td>RM $productPrice</td>
                        <td>RM $subtotal</td>
                      </tr>";

                // Update the total price
                $totalPrice += $subtotal;
            }

            // Display the total price row
            echo "<tr class='total-row'>
                    <td colspan='4' class='total-text'><strong>Total</strong></td>
                    <td class='totalprice-text'><strong>RM $totalPrice</strong></td>
                    <td></td>
                  </tr>";

            echo '</table>';
        } else {
            echo '<p>Your cart is empty. Please add items before proceeding to checkout.</p>';
        }
        ?>
    </div>


    <!-- CONTAINER FOR THE CHECKOUT PAGE -->
    <div class="info-container">
        <!-- RIGHT COLUMN -->
        <div class="info-row">
            <!-- BILLING INFORMATION -->
            <div class="form-container">
                <h2>Billing Information</h2>
                <form action="checkout-form" method="post">
                    <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                    </div>

                    <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="phone" id="phone" name="phone" required>
                    </div>             
                </form>
            </div>
            <!-- QR CODE PAYMENT SECTION -->
            <div class="qr-code-container">
                <h2>Scan QR Code to Pay</h2>
                <div class="qr-code-image">
                    <img src="image/qr-code.png" alt="QR Code"/>
                </div>
                <p>Use your preferred payment app to scan the QR code.
                    Once you've paid, upload the receipt below as a proof.
                    Upon confirmation of payment on our end, we will promptly proceed with processing your order.
                </p>
                
                <!-- UPLOAD PROOF OF PAYMENT SECTION -->
                <div class="proof-container">
                    <br><h2>Upload your payment receipt</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="file" id="receipt" name="receipt" required>
                        </div>
                        <button type="submit">Upload</button>
                        <p><?php echo $message; ?></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="button-checkout">
        <button type="submit">Checkout</button>  
    </div>


    <!-- FOOTER -->
    <script id="replace_with_footer" src="footer.js"></script>

</body>
</html>
