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

// Handle DELETE request for deleting a product
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $cartId = $_GET['id'];
    $sql = "DELETE FROM cart WHERE id=$cartId";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting cart item: ' . $conn->error]);
    }

    exit;
}

?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cookinghabit.kch - View Cart</title>
    <link rel="icon" href="image/square-logo.png">
    <link rel="stylesheet" href="cart.css">  
</head>

<body>

    <!-- NAVIGATION BAR -->
    <script id="replace_with_navbar" src="navbar.js"></script>

    <div class="cart-container">
        <div class="top-section">
            <div class="header-title">
                <p>From our oven to your door, delivering all over Kuching and Kota Samarahan</p>
            </div>

            <div class="button-title">
                <div class="cart-title"><h1>Cart</h1></div>
                <div class="continue-title"><a href="menu.php">Continue to Shopping</a></div>
            </div>
        </div>

        <?php
        // Fetch cart items from the database
        $cartSql = "SELECT * FROM cart";
        $cartResult = $conn->query($cartSql);

        if ($cartResult->num_rows > 0) {
            echo '<table>';
            echo "<tr class='container-header'><th>&nbsp&nbsp&nbspProduct</th><th></th><th>Quantity</th><th>Price</th><th>Subtotal</th><th>Action</th></tr>";

            $totalPrice = 0;

            while ($row = $cartResult->fetch_assoc()) {
                $productName = $row['cart_name'];
                $quantity = $row['cart_quantity'];
                $productPrice = $row['cart_price'];
                $productImage = $row['cart_image'];
                $cartId = $row['id'];

                // Calculate subtotal for each item
                $subtotal = $quantity * $productPrice;

                // Display cart item details, subtotal, and delete button
                echo "<tr class='cart-content'>
                        <td class='product-img'><img src='$productImage' alt='$productName' style='max-width: 100px; max-height: 100px;'></td>
                        <td class='productname'>$productName</td>  
                        <td>$quantity</td>
                        <td>RM $productPrice</td>
                        <td>RM $subtotal</td>
                        <td class='delete-btn'><a href='#' onclick='deleteCart($cartId)'><img src='image/delete-cart.png'></a></td>
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

            echo "
                    <div class='button-cell'>
                        <button id='checkoutButton' onclick='proceedToCheckout()'>Proceed to Checkout</button>
                    </div";
        } else {
            echo '<p>Your cart is empty.</p>';
        }
        ?>
    </div>

    <!-- FOOTER -->
    <script id="replace_with_footer" src="footer.js"></script>
    <script src="menu.js"></script>

    <script>
        function proceedToCheckout() {
            // Add any additional logic for the checkout process
            window.location.href = 'checkout.php';
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>

