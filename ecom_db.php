<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bismart_ecommerce');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create tables if they don't exist
$sql = "
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    old_price DECIMAL(10,2),
    image VARCHAR(255),
    category VARCHAR(100),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
";

if ($conn->multi_query($sql) {
    do {
        // Empty loop to process all queries
    } while ($conn->more_results() && $conn->next_result());
}

// Sample data insertion (run once)
function initializeSampleData($conn) {
    // Check if products table is empty
    $result = $conn->query("SELECT COUNT(*) as count FROM products");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $sampleProducts = [
            ["Smart Watch Pro X9", "Advanced smart watch with health monitoring", 129.99, 199.99, "watch.jpg", "Electronics"],
            ["Wireless Headphones", "Noise cancelling wireless headphones", 89.99, 129.99, "headphones.jpg", "Electronics"],
            ["Running Sneakers", "Comfortable running shoes", 79.99, NULL, "sneakers.jpg", "Fashion"],
            ["Travel Backpack", "Durable backpack for travelers", 49.99, 69.99, "backpack.jpg", "Fashion"]
        ];
        
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, old_price, image, category) VALUES (?, ?, ?, ?, ?, ?)");
        
        foreach ($sampleProducts as $product) {
            $stmt->bind_param("ssddss", $product[0], $product[1], $product[2], $product[3], $product[4], $product[5]);
            $stmt->execute();
        }
    }
}

initializeSampleData($conn);
?>