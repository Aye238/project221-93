<?php
// backend/add_to_cart.php
session_start(); // Start the session

include 'products_db.php'; // Include database functions

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// Validate product ID and quantity
if ($product_id <= 0 || $quantity <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity.']);
    exit();
}

// Get product details
$product = getProductById($product_id);

if (!$product) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit();
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart or update quantity
if (isset($_SESSION['cart'][$product_id])) {
    // Product already in cart, update quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
} else {
    // Add new product to cart
    $_SESSION['cart'][$product_id] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'image_path' =>$product['image_path'],
        'quantity' => $quantity,
    ];
}

// Return success response
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Product added to cart!']);

?>