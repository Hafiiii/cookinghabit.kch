document.addEventListener('DOMContentLoaded', function () {
    // Function to fetch product data from products.php
    function fetchProductData() {
        fetch('products.php')
            .then(response => response.json())
            .then(data => {
                // Call a function to render the product table with the retrieved data
                renderProductTable(data);
            })
            .catch(error => console.error('Error fetching product data:', error));
    }

    // Function to render the product table with the given data
    function renderProductTable(data) {
        var productTable = document.querySelector('.product-table');

        // Clear existing content in the product table
        productTable.innerHTML = '';

        // Create a table header
        var tableHeader = document.createElement('div');
        tableHeader.classList.add('table-header');
        tableHeader.innerHTML = '<div>ID</div><div>Product Name</div><div>Product Image</div><div>Product Price</div><div>No. of Sales</div><div>Action</div>';
        productTable.appendChild(tableHeader);

        // Loop through the data and create table rows
        data.forEach(product => {
            var tableRow = document.createElement('div');
            tableRow.classList.add('table-row');
            tableRow.innerHTML = `
                <div>${product.id}</div>
                <div>${product.product_name}</div>
                <div><img src="${product.product_image}" alt="${product.product_name}" class="product-image"></div>
                <div>${product.product_price}</div>
                <div>
                    <button onclick="editProduct(${product.id})">Edit</button>
                    <button onclick="deleteProduct(${product.id})">Delete</button>
                </div>`;
            productTable.appendChild(tableRow);
        });
    }

    // Function to filter products based on status
    window.filterProducts = function (status) {
        // Map the status values to the corresponding values used in products.php
        const statusMap = {
            'all': 'all',
            'cakes': 'cakes',
            'desserts': 'desserts',
            'breakies': 'breakies'
        };

        // Retrieve the corresponding status value from the map
        const mappedStatus = statusMap[status] || 'all';

        // Redirect to the same page with the selected status as a query parameter
        window.location.href = `products.php?status=${mappedStatus}`;
    };

    // Function to edit a product
    window.editProduct = function (productId) {
        // Redirect to the editproduct.php page with the product ID as a parameter
        window.location.href = `editproduct.php?id=${productId}`;
    };

    window.deleteProduct = function (productId) {
        var confirmDelete = confirm('Are you sure you want to delete this product?');

        if (confirmDelete) {
            fetch(`products.php?action=delete&id=${productId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                console.log('Product deleted successfully:', data);
                // Redirect to the same page after deletion
                window.location.href = 'products.php';
            })
            .catch(error => {
                console.error('Error deleting product:', error);
                // Redirect to the same page even if there's an error
                window.location.href = 'products.php';
            });
        }
    };

    // Fetch product data when the page is loaded
    fetchProductData();
});
