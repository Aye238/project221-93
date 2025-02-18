<?php
// backend/add_product.php

include_once 'products_db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['product_name'];
    $description = $_POST['product_description'];
    $price = $_POST['product_price'];
    $category = $_POST['product_category'];

    $uploadOk = 1;
    $target_dir = "../public/product_images/";

    // --- Improved File Upload Handling ---
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $imageFileType = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));

        // Generate a unique filename
        $unique_filename = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $unique_filename;

        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check === false) {
            $message = "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["product_image"]["size"] > 5000000) {
            $message = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                $image_path = "product_images/" . $unique_filename; // Use unique name
                if (addProduct($name, $description, $price, $image_path, $category)) {
                    $message = "The product has been added successfully.";
                } else {
                    $message = "There was an error adding the product to the database.";
                }
            } else {
                $message = "Sorry, there was an error uploading your file.";
                $uploadOk = 0; // Ensure no DB update if upload fails
            }
        }
    } else {
        $message = "No image file uploaded or an error occurred.";
        $uploadOk = 0; // Prevent DB update if no image is uploaded
    }

     if ($uploadOk == 0 && !empty($message)) {
        echo "<script>alert('" . addslashes($message) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
     <link rel="stylesheet" href="../public/css/style.css"> <!-- Correct relative path -->
</head>
<body>

<?php if (!empty($message)): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

</body>
</html>