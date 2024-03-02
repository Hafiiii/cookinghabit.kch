
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    product_price DECIMAL(10,2) NOT NULL,
    product_description TEXT NOT NULL,
    product_image MEDIUMBLOB,
    status VARCHAR(20) NOT NULL DEFAULT 'cakes'
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone_number VARCHAR(20) NOT NULL,
    customer_address VARCHAR(255) NOT NULL,
    order_quantity INT NOT NULL,
    order_total DECIMAL(10, 2) NOT NULL,
    order_date DATE NOT NULL,
    order_status VARCHAR(20) NOT NULL,
    payment_status VARCHAR(20) NOT NULL
);

ALTER TABLE orders
ADD CONSTRAINT fk_orders_product_id
FOREIGN KEY (product_id)
REFERENCES products(id);