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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize the input data
  $productName = htmlspecialchars('CUSTOMIZATION:  ' . $_POST["csize"] . ', ' . $_POST["cflavour"] . ', ' . $_POST["ctopping"] . ', ' . $_POST["cwording"]);
  $productPrice = $_POST["total-price"]; // You may want to validate or calculate the price based on your business logic
  $productImage = "image/Strawberry Shortcake 1.png"; // Replace this with the actual image path
  $productId = 6;
  $quantity = 1;

  // Prepare and execute the SQL statement
  $insertSql = "INSERT INTO cart (product_id, cart_quantity, cart_price, cart_image, cart_name) 
                VALUES ($productId, $quantity, $productPrice, '$productImage', '$productName')";
  $conn->query($insertSql);

  
}

$conn->close();
?>


<!DOCTYPE htmL>
<html Lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <title>Customize</title>
    <link rel="icon" href="image/square-logo.png">
    <link rel="stylesheet" href="customize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>

    <!-- NAVIGATION BAR -->
    <script id="replace_with_navbar" src="navbar.js"></script>
    
    <div class="banner">  
        <div class="banner-text"><p>CUSTOMIZE YOUR OWN CAKE NOW</p></div>
        <img src="image/customize-banner.png">
    </div>
    <div class="sentence">
        <p class="sentences">SHORTCAKE CUSTOMIZE</p>
    </div> 

    <main class="container">
        <!-- right Column / cakes Image -->
        <div class="left-column">
            <img data-image="black" src="image/cake1.png" alt="">
        </div>
        
        <!-- Right Column -->
        <div class="right-column">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
              <label for="csize">Size</label>
              <div class="button-group">
                  <input type="radio" name="csize" value="6 Inches" data-price="30" required id="size-6" onchange="calculateTotalPrice()">
                  <label for="size-6">6 Inches</label>
                  <input type="radio" name="csize" value="7 Inches" data-price="40" required id="size-7" onchange="calculateTotalPrice()">
                  <label for="size-7">7 Inches</label>
                  <input type="radio" name="csize" value="8 Inches" data-price="50" required id="size-8" onchange="calculateTotalPrice()">
                  <label for="size-8">8 Inches</label>
              </div>

              <label for="cflavour">Flavour</label>
              <div class="button-group">
                  <input type="radio" name="cflavour" value="Vanilla" data-price="10" required id="flavour-vanilla" onchange="calculateTotalPrice()">
                  <label for="flavour-vanilla">Vanilla</label>
                  <input type="radio" name="cflavour" value="Chocolate" data-price="10" required id="flavour-chocolate" onchange="calculateTotalPrice()">
                  <label for="flavour-chocolate">Chocolate</label>
                  <input type="radio" name="cflavour" value="Butterscotch" data-price="10" required id="flavour-butterscotch" onchange="calculateTotalPrice()">
                  <label for="flavour-butterscotch">Butterscotch</label>
                  <input type="radio" name="cflavour" value="Red Velvet" data-price="10" required id="flavour-redvelvet" onchange="calculateTotalPrice()">
                  <label for="flavour-redvelvet">Red Velvet</label>
                  <input type="radio" name="cflavour" value="Pandan Gula Melaka" data-price="15" required id="flavour-pandan" onchange="calculateTotalPrice()">
                  <label for="flavour-pandan">Pandan Gula Melaka</label>
              </div>

              <label for="ctopping">Topping</label>
              <div class="button-group">
                  <input type="radio" name="ctopping" value="Strawberry" data-price="5" required id="topping-strawberry" onchange="calculateTotalPrice()">
                  <label for="topping-strawberry">Strawberry</label>
                  <input type="radio" name="ctopping" value="Red Cherry" data-price="5" required id="topping-redcherry" onchange="calculateTotalPrice()">
                  <label for="topping-redcherry">Red Cherry</label>
                  <input type="radio" name="ctopping" value="Kiwi" data-price="5" required id="topping-kiwi" onchange="calculateTotalPrice()">
                  <label for="topping-kiwi">Kiwi</label>
                  <input type="radio" name="ctopping" value="Shine Muscat" data-price="8" required id="topping-shinemuscat" onchange="calculateTotalPrice()">
                  <label for="topping-shinemuscat">Shine Muscat</label>
              </div>

              <label for="cwording">Wording</label>
              <div class="button-group">
                  <input type="radio" name="cwording" value="No Wording" data-price="0" required id="no-wording" onchange="calculateTotalPrice()">
                  <label for="no-wording">No Wording</label>
                  <input type="radio" name="cwording" value="Wording fondant banner" data-price="7" required id="wording-fondant-banner" onchange="calculateTotalPrice()">
                  <label for="wording-fondant-banner">Wording fondant banner</label>
                  <input type="radio" name="cwording" value="Wording buttercream piping" data-price="4" required id="wording-buttercream-piping" onchange="calculateTotalPrice()">
                  <label for="wording-buttercream-piping">Wording buttercream piping</label>
              </div>

              <input type="hidden" id="total-price" name="total-price" value="0"><span>Total</span>
              <div id="total-price-display"> RM 0</div>
              <input type="hidden" name="product_id" value="0">
              
              <div class="addCart-btn">
                  <button onclick="addCartCustomize(0, 1)">ADD TO CART</button>
              </div>
          </form>

          <script>
              function calculateTotalPrice() {
                  const sizePrice = document.querySelector('input[name="csize"]:checked').dataset.price;
                  const flavourPrice = document.querySelector('input[name="cflavour"]:checked').dataset.price;
                  const toppingPrice = document.querySelector('input[name="ctopping"]:checked').dataset.price;
                  const wordingPrice = document.querySelector('input[name="cwording"]:checked').dataset.price;

                  const totalPrice = parseInt(sizePrice) + parseInt(flavourPrice) + parseInt(toppingPrice) + parseInt(wordingPrice);

                  document.getElementById('total-price').value = totalPrice;
                  document.getElementById('total-price-display').textContent = `RM ${totalPrice}`;
              }

              function addCartCustomize(productId, quantity) {
                // Make an AJAX request to the server to add the product to the cart
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                  if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                      // Display an alert when the product is added to the cart
                      alert('Product added to cart successfully!');
                    } else {
                      // Handle any error that occurred during the AJAX request
                      alert('Product added to cart successfully!');
                    }
                  }
                };

                // Pass the productId and quantity parameters to the server
                xhr.open('POST', 'customize.php', true);
                xhr.send(`product_id=${productId}&quantity=${quantity}`);
              }
          </script>

      </div>

</body>
</html>