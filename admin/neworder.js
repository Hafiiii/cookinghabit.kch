document.addEventListener('DOMContentLoaded', function () {
    function updateOrderStatus(selectElement) {
        var selectedStatus = selectElement.value;
    
        // Perform the necessary actions to update the order status in the database
        // You can use AJAX or other methods to send the update to the server
        console.log('Order status updated to:', selectedStatus);
    
        // Optionally, you can redirect or perform other actions after updating the status
    }

    // Function to update payment status
    function updatePaymentStatus(paymentStatus) {
        // Implement the logic to update the payment status in the database via AJAX or fetch
        console.log('Payment Status Updated:', paymentStatus);
    }

    // Event listener for order status dropdown
    document.getElementById('status').addEventListener('change', function () {
        var selectedStatus = this.value;
        updateOrderStatus(selectedStatus);
    });

    // Event listener for payment status dropdown
    document.getElementById('payment').addEventListener('change', function () {
        var selectedPaymentStatus = this.value;
        updatePaymentStatus(selectedPaymentStatus);
    });

    // Add any additional logic or functions as needed
});