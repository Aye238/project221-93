<?php
// backend/remove_from_cart.php
session_start();

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($product_id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
    exit();
}

if (!isset($_SESSION['cart']) || !isset($_SESSION['cart'][$product_id])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Product not found in cart.']);
    exit();
}

// Remove item from cart
unset($_SESSION['cart'][$product_id]);

header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Product removed from cart!']);

?>