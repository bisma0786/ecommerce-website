<?php
header('Content-Type: application/json');
require_once 'ecom_db.php';

// Get all products
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    echo json_encode($products);
}

// Add new product (admin functionality)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $name = $data['name'];
    $description = $data['description'];
    $price = $data['price'];
    $old_price = $data['old_price'] ?? null;
    $image = $data['image'];
    $category = $data['category'];
    
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, old_price, image, category) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddss", $name, $description, $price, $old_price, $image, $category);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding product']);
    }
}

$conn->close();
?>