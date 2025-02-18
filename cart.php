<?php
// public/html/cart.html
session_start();

// Initialize cart if it doesn't exist (shouldn't happen here, but good practice)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$total = 0; // Initialize total
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>EcoMart</h1>
        </div>
        <nav>
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Products</a></li>
                <li><a href="#">Categories</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="#">Payment</a></li>
            </ul>
        </nav>
    </header>

    <section class="content">
        <h1>Your Shopping Cart</h1>

        <?php if (empty($cart)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Actions</th> <!-- For Update/Remove -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $product_id => $item): ?>
                        <?php
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal; // Accumulate total
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                             <td><img src="<?php echo '../' . htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="50"></td>
                            <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $product_id; ?>]" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1">
                            </td>
                            <td>$<?php echo number_format($subtotal, 2); ?></td>
                            <td>
                                <a href="#">Update</a> |  <!-- Placeholder for Update -->
                                <a href="#">Remove</a>   <!-- Placeholder for Remove -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Total:</td>
                        <td>$<?php echo number_format($total, 2); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <a href="#">Checkout</a> <!-- Placeholder for Checkout -->

        <?php endif; ?>

         <a href="home.html">Continue Shopping</a>
    </section>

</body>
</html>