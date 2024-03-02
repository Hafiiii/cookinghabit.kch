
// Function to populate the order table with data
function populateOrderTable(orders, products) {
    var tableBody = document.querySelector('.order-table');

    orders.forEach(order => {
        // Find the corresponding product for the order
        var product = products.find(product => product.id === order.product_id);

        var row = document.createElement('div');
        row.className = 'table-row';

        if (product) {
            row.innerHTML = `
                <div>${order.customer_name}</div>
                <div>${product.product_name}</div>
                <div>${order.order_total}</div>
                <div>${order.order_date}</div>
                <div>
                    <button onclick="checkOrderDetails(${order.order_id})">Check Details</button>
                </div>
            `;
        } else {
            row.innerHTML = `
                <div>${order.customer_name}</div>
                <div>Product Not Found</div>
                <div>${order.order_total}</div>
                <div>${order.order_date}</div>
                <div>
                    <button onclick="checkOrderDetails(${order.order_id})">Check Details</button>
                </div>
            `;
        }

        tableBody.appendChild(row);
    });
}
function checkOrderDetails(orderId) {
    // Redirect to the neworder.php page with the specific order ID
    window.location.href = 'neworder.php?order_id=' + orderId;
}

function filterOrders(status) {
    // Perform an AJAX request to fetch orders from the database
    fetch('get_orders.php?status=' + status)
        .then(response => response.json())
        .then(data => {
            // Call the function to repopulate the order table with the filtered orders
            populateOrderTable(data);
        })
        .catch(error => console.error('Error fetching orders:', error));
}

// Function to update the order status
function updateOrderStatus(selectElement, orderId) {
    var selectedStatus = selectElement.value;
    // Perform the necessary actions to update the order status in the database
    // You can use AJAX or other methods to send the update to the server
    console.log('Order status updated to:', selectedStatus, 'for order ID:', orderId);

    // After updating the order status, you can fetch and update the order table if needed
    // For simplicity, let's just reload the page in this example
    location.reload();
}
