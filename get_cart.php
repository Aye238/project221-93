<?php
// backend/get_cart.php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];

// Calculate cart summary
$totalItems = 0;
$totalPrice = 0;
foreach ($cart as $item) {
    $totalItems += $item['quantity'];
    $totalPrice += $item['price'] * $item['quantity'];
}

// Prepare the response
$response = [
    'cart' => $cart,
    'totalItems' => $totalItems,
    'totalPrice' => number_format($totalPrice, 2), // Format to 2 decimal places
];

// Set the content type to JSON
header('Content-Type: application/json');

// Encode the array as JSON and output it
echo json_encode($response);
?>