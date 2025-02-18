<?php
// backend/get_product.php

include 'products_db.php'; // Include database functions

// Get product ID from URL parameter
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validate ID
if ($product_id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid product ID']); // Return error as JSON
    exit();
}

// Get product by ID
$product = getProductById($product_id);

if (!$product) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Product not found']); // Return error as JSON
    exit();
}

// Return product as JSON
header('Content-Type: application/json');
echo json_encode($product);

?>