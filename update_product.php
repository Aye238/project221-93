<?php
// backend/update_product.php

include 'products_db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $description = $_POST['product_description'];
    $price = $_POST['product_price'];
    $category = $_POST['product_category'];

    $uploadOk = 1;
    $image_path = null;

    // --- File Upload Handling (with improvements) ---
    if (isset($_FILES['new_product_image']) && $_FILES['new_product_image']['error'] == 0) {
        $target_dir = "../public/product_images/";
        $imageFileType = strtolower(pathinfo($_FILES["new_product_image"]["name"], PATHINFO_EXTENSION));

        // Generate a unique filename
        $unique_filename = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $unique_filename;

        $check = getimagesize($_FILES["new_product_image"]["tmp_name"]);
        if ($check === false) {
            $message = "File is not an image.";
            $uploadOk = 0;
        }

        // No need to check if file exists, as we are using a unique name

        if ($_FILES["new_product_image"]["size"] > 5000000) {
            $message = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["new_product_image"]["tmp_name"], $target_file)) {
                $image_path = "product_images/" . $unique_filename; // Use unique name

                $old_product = getProductById($product_id);
                if ($old_product && !empty($old_product['image_path'])) {
                    $old_image_path = "../public/" . $old_product['image_path'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
            } else {
                $message = "Sorry, there was an error uploading your file.";
                $uploadOk = 0;
            }
        }
    }

    if ($uploadOk) {
        if (updateProduct($product_id, $name, $description, $price, $image_path, $category)) {
            $message = "Product updated successfully!";
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "There was an error updating the product.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <?php if (!empty($message)): ?>
        <p style="color:red;"><?php echo $message; ?></p>
    <?php endif; ?>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>