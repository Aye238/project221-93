<?php
// backend/delete_product.php

include 'products_db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$product_id = intval($_GET['id']);

// Get product info before deleting, to delete image
$product = getProductById($product_id);

if (!$product) {
    header("Location: dashboard.php");
    exit();
}

// --- Delete Product from Database ---
if (deleteProduct($product_id)) {
    // --- Delete Image File ---
    if (!empty($product['image_path'])) {
        $image_path = "../public/" . $product['image_path'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    $message = "Product deleted successfully!";
} else {
    $message = "Error deleting product.";
}
header("Location: dashboard.php"); // Redirect to dashboard
exit();

?>