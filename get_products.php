<?php
// backend/get_products.php

include 'products_db.php'; // Include database functions and connection

$category = isset($_GET['category']) ? $_GET['category'] : null;

if ($category) {
    // Use prepared statements for security.
    $stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");

    if ($stmt) { // Check if prepare was successful
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $stmt->close();
    } else {
        // Handle prepare error (e.g., log it, show a user-friendly message)
        $products = []; // Return empty array on error
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

} else {
    $products = getAllProducts();
}

header('Content-Type: application/json');
echo json_encode($products);
?>