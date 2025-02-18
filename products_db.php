<?php
// backend/products_db.php

include '../config/database.php'; // Include database connection

/**
 * Adds a new product to the database.
 *
 * @param string $name The product name.
 * @param string $description The product description.
 * @param float $price The product price.
 * @param string $image_path The path to the product image.
 * @param string $category The product category.
 * @return bool True on success, false on failure.
 */
function addProduct($name, $description, $price, $image_path, $category) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_path, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $name, $description, $price, $image_path, $category);

    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

/**
 * Retrieves all products from the database.
 *
 * @return array An array of product data, or an empty array if no products are found.
 */
function getAllProducts() {
    global $conn;

    $result = $conn->query("SELECT * FROM products");
    $products = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    return $products;
}

/**
 * Retrieves a single product from the database by its ID.
 *
 * @param int $id The product ID.
 * @return array|null The product data as an associative array, or null if not found.
 */
function getProductById($id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    return $product;
}

/**
 * Updates an existing product in the database.
 *
 * @param int $id The product ID.
 * @param string $name The new product name.
 * @param string $description The new product description.
 * @param float $price The new product price.
 * @param string|null $image_path The new path to the product image (or null if not updating the image).
 * @param string $category The new product category.
 * @return bool True on success, false on failure.
 */
function updateProduct($id, $name, $description, $price, $image_path, $category) {
    global $conn;

    if ($image_path === null) {
        // No new image provided, update other fields only
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ? WHERE id = ?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $category, $id);
    } else {
        // New image provided, update all fields
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_path = ?, category = ? WHERE id = ?");
        $stmt->bind_param("ssdssi", $name, $description, $price, $image_path, $category, $id);
    }

    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

/**
 * Deletes a product from the database by its ID.
 *
 * @param int $id The product ID.
 * @return bool True on success, false on failure.
 */
function deleteProduct($id) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}
?>