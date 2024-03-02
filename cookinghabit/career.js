document.addEventListener('DOMContentLoaded', function () {
    // Function to fetch product data from products.php
    function fetchProductData() {
        fetch('career_list.php')
            .then(response => response.json())
            .then(data => {
                // Call a function to render the product table with the retrieved data
                renderProductTable(data);
            })
            .catch(error => console.error('Error fetching career data:', error));
    }

    // Function to render the product table with the given data
    function renderProductTable(data) {
        var productTable = document.querySelector('.product-table');

        // Clear existing content in the product table
        productTable.innerHTML = '';

        // Create a table header
        var tableHeader = document.createElement('div');
        tableHeader.classList.add('table-header');
        tableHeader.innerHTML = '<div>ID</div><div>Name</div><div>Resume</div><div>Phone Number</div><div>Position</div><div>Action</div>';
        productTable.appendChild(tableHeader);

        // Loop through the data and create table rows
        data.forEach(career => {
            var tableRow = document.createElement('div');
            tableRow.classList.add('table-row');
            tableRow.innerHTML = `
                <div>${career.id}</div>
                <div>${career.career_name}</div>
                <div>${career.career_phone}</div>
                <div>${career.position}</div>
                <div>
                    <button onclick="editCareer(${career.id})">Edit</button>
                    <button onclick="deleteCareer(${career.id})">Delete</button>
                </div>`;
            productTable.appendChild(tableRow);
        });
    }

    // Function to filter products based on status
    window.filterProducts = function (position) {
        // Map the status values to the corresponding values used in products.php
        const statusMap = {
            'all': 'all',
            'kitchen-helper': 'kitchen-helper',
            'service-crew': 'service-crew'
        };

        // Retrieve the corresponding status value from the map
        const mappedStatus = statusMap['position'] || 'all';

        // Redirect to the same page with the selected status as a query parameter
        window.location.href = `career_list.php?position=${mappedStatus}`;
    };

    // Function to edit a product
    window.editCareer = function (career_id) {
        // Redirect to the editcareer.php page with the product ID as a parameter
        window.location.href = `editcareer.php?id=${career_id}`;
    };

    window.deleteCareer = function (career_id) {
        var confirmDelete = confirm('Are you sure you want to delete this product?');

        if (confirmDelete) {
            fetch(`career_list.php?action=delete&id=${career_id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                console.log('Career deleted successfully:', data);
                // Redirect to the same page after deletion
                window.location.href = 'career_list.php';
            })
            .catch(error => {
                console.error('Error deleting career:', error);
                // Redirect to the same page even if there's an error
                window.location.href = 'career_list.php';
            });
        }
    };

    // Fetch product data when the page is loaded
    fetchProductData();
});