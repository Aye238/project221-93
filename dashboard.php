<?php
// backend/dashboard.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../public/html/login.html");
    exit();
}
include_once 'add_product.php'; // Use include_once
include_once 'products_db.php'; // Use include_once
$products = getAllProducts();

// ... (rest of your dashboard.php code) ...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>Admin Dashboard</h1>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="#">Products</a></li>
                <li><a href="#">Orders</a></li>
                <li><a href="#">Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="dashboard-content">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <p>This is your admin dashboard. You can manage your store from here.</p>

        <h2>Add New Product</h2>
        <!-- Display any messages from add_product.php -->
        <?php if (!empty($message)): ?>
            <p><?= $message ?></p>
        <?php endif; ?>
        <form action="add_product.php" method="post" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required><br><br>

            <label for="product_description">Description:</label>
            <textarea id="product_description" name="product_description"></textarea><br><br>

            <label for="product_price">Price:</label>
            <input type="number" id="product_price" name="product_price" step="0.01" required><br><br>

            <label for="product_image">Image:</label>
            <input type="file" id="product_image" name="product_image" required><br><br>

            <label for="product_category">Category:</label>
            <input type="text" id="product_category" name="product_category"><br><br>

            <button type="submit">Add Product</button>
        </form>
<h2>All Products</h2>
       <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th> <!-- Add Actions column -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['price']; ?></td>
                        <td><img src="../public/<?php echo $product['image_path']; ?>" alt="<?php echo $product['name']; ?>" width="50"></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a> | 
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </section>

    <footer>
        <div class="footer-content">
            <p>© 2024 EcoMart Admin. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>