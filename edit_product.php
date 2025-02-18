<?php
// backend/edit_product.php

include 'products_db.php'; // Include database functions

// --- 1. Retrieve Product ID ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect to dashboard if no ID or invalid ID
    header("Location: dashboard.php");
    exit();
}
$product_id = intval($_GET['id']); // Ensure ID is an integer

// --- 2. Fetch Product Data ---
$product = getProductById($product_id);

if (!$product) {
    // Redirect to dashboard if product not found
    header("Location: dashboard.php");
    exit();
}

// --- 3. Display Edit Form (Initial) ---
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <h1>Edit Product</h1>

    <form action="update_product.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br><br>

        <label for="product_description">Description:</label>
        <textarea id="product_description" name="product_description"><?php echo htmlspecialchars($product['description']); ?></textarea><br><br>

        <label for="product_price">Price:</label>
        <input type="number" id="product_price" name="product_price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required><br><br>

        <label for="product_image">Current Image:</label>
        <img src="../public/<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="100"><br><br>

        <label for="new_product_image">New Image (Optional):</label>
        <input type="file" id="new_product_image" name="new_product_image"><br><br>

        <label for="product_category">Category:</label>
        <input type="text" id="product_category" name="product_category" value="<?php echo htmlspecialchars($product['category']); ?>"><br><br>

        <button type="submit">Update Product</button>
    </form>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>