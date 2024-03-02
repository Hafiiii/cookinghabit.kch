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

// Fetch cart items from the database
$cartSql = "SELECT id, product_id, cart_quantity, cart_price, cart_name, cart_image FROM cart";
$cartResult = $conn->query($cartSql);

// Process the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $cus_name = $_POST["cus_name"];
    $cus_phone = $_POST["cus_phone"];
    $cus_email = $_POST["cus_email"];
    $cus_address = $_POST["cus_address"];

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert order details into the "orders" table
    if ($cartResult->num_rows > 0) {
        $orderDate = date('Y-m-d H:i:s', time());
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_phone, customer_email, customer_address, product_name, quantity, price, receipt_image, order_date, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");

        // Loop through the cart items and insert each order
        while ($row = $cartResult->fetch_assoc()) {
            $productName = $row['cart_name'];
            $quantity = $row['cart_quantity'];
            $productPrice = $row['cart_price'];

            // Upload the receipt image to a directory and get the file name
            $receipt = $_FILES['receipt']['name'];
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["receipt"]["name"]);
            move_uploaded_file($_FILES["receipt"]["tmp_name"], $target_file);

            // Bind the variables to the prepared statement
            $stmt->bind_param("ssssssdss", $cus_name, $cus_phone, $cus_email, $cus_address, $productName, $quantity, $productPrice, $receipt, $orderDate);

            // Execute the prepared statement
            $stmt->execute();
        }

        // Close the statement
        $stmt->close();
    }

    // Clear the cart
    $cartSql = "DELETE FROM cart";
    $conn->query($cartSql);

    // Close the database connection
    $conn->close();

    // Redirect to the order confirmation page
    header("Location: notification_customer.html");
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

    <div class="back-page">
        <a href="javascript:history.back()"><img src="image/back.png"></a>
    </div>

    <div class="checkout-container">
        <div class="checkout-header">
            <h1>Checkout Summary</h1>
        </div>

        <?php
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
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                    <label for="cus_name">Name </label>
                    <input type="text" id="cus_name" name="cus_name" placeholder="Enter your name" required>

                    <label for="cus_phone">Phone Number </label>
                    <input type="tel" id="cus_phone" name="cus_phone" pattern="[0-9]{10}" placeholder="Enter your phone number" required>

                    <label for="cus_email">Email </label>
                    <input type="email" id="cus_email" name="cus_email" placeholder="Enter your email" required>

                    <label for="cus_address">Address </label>
                    <textarea id="cus_address" name="cus_address" class="career_address" placeholder="Enter your address" required></textarea>

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
                        <label class="upload-resume" for="receipt">Upload your payment receipt:</label>
                        <input type="file" id="receipt" name="receipt" required>
                    </div>

                    <div class="button-checkout">
                        <button type="submit" name="send">Order</button>
                    </div>
                </form>


                <?php
                use PHPMailer\PHPMailer\PHPMailer;
                use PHPMailer\PHPMailer\Exception;

                require './phpEmail/PHPMailer/src/Exception.php';
                require './phpEmail/PHPMailer/src/PHPMailer.php';
                require './phpEmail/PHPMailer/src/SMTP.php';

                if(isset($_POST['send'])){
                $name = htmlentities($_POST['cus_name']);
                $email = htmlentities($_POST['cus_email']);
                $message = "Dear $name, <br><br>
                Thank you for choosing cookinghabit.kch. 
                We are thrilled to confirm that we have received your order.
                We appreciate your business and are committed to ensuring a smooth and timely delivery of your selected items.
                <br><br>
                Our team will process your order. 
                If you have any questions or need further assistance, 
                please do not hesitate to contact us at 
                <a href='mailto:cookinghabit.kch@gmail.com'>cookinghabit.kch@gmail.com</a>
                or 
                <a href='https://api.whatsapp.com/send/?phone=%2B60179219897&text&type=phone_number&app_absent=0' target='_blank'>+6017 921 9897</a><br>
                .
                <br>Best regards, <br>
                The cookinghabit.kch Team";

                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'cookinghabit.kch2.0@gmail.com';
                $mail->Password = 'cqajcwigokbpzgzj';
                $mail->Port = 465;
                $mail->SMTPSecure = 'ssl';
                $mail->isHTML(true);
                $mail->setFrom('cookinghabit.kch2.0@gmail.com');
                $mail->addAddress($email, $name);
                $mail->addAddress('cookinghabit.kch2.0@gmail.com');
                $mail->Subject = ("Thank you for the order!");
                $mail->Body = $message;
                $mail->send();

                }
                ?>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <script id="replace_with_footer" src="footer.js"></script>

</body>
</html>