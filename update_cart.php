<?php
// backend/update_cart.php
session_start();

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

if ($product_id <= 0 || $quantity < 0) { // Allow quantity of 0 to remove
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity.']);
    exit();
}

if (!isset($_SESSION['cart']) || !isset($_SESSION['cart'][$product_id])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Product not found in cart.']);
    exit();
}

if ($quantity == 0) {
    // Remove item if quantity is 0 (treat as remove)
    unset($_SESSION['cart'][$product_id]);
} else {
    // Update quantity
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Cart updated!']);

?>