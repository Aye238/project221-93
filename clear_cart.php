<?php
// backend/clear_cart.php
session_start();

// Clear the cart by unsetting the session variable
unset($_SESSION['cart']);

// Optional: You might want to re-initialize the cart as an empty array
$_SESSION['cart'] = [];

// Return success response
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Cart cleared!']);
?>