// Function to view a single product in product details page
window.viewProduct = function (productId) {
    // Redirect to the editproduct.php page with the product ID as a parameter
    window.location.href = `menudetails.php?id=${productId}`;
};

function addCart(productId, quantity) {
    // Make an AJAX request to the server to add the product to the cart
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                // Handle the response, e.g., show a success message
                alert('Product added to cart successfully!');
            } else {
                // Handle any error that occurred during the AJAX request
                alert('Error adding product to cart: ' + xhr.statusText);
            }
        }
    };

    xhr.open('GET', 'addcart.php?id=' + productId + '&cart_quantity=' + quantity, true);
    xhr.send();
}

window.deleteCart = function (cartId) {
    var confirmDelete = confirm('Are you sure?');

    if (confirmDelete) {
        fetch(`cart.php?action=delete&id=${cartId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            console.log('Cart item deleted successfully:', data);
            // Redirect to the same page after deletion
            window.location.href = 'cart.php';
        })
        .catch(error => {
            console.error('Error deleting cart item:', error);
            // Redirect to the same page even if there's an error
            window.location.href = 'crat.php';
        });
    }
};
