// public/js/checkout.js

// Function to update the cart display
function updateCartDisplay(cart) {
    const cartContent = document.getElementById('cart-content');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const checkoutButton = document.getElementById('checkout-button');

    cartContent.innerHTML = ''; // Clear existing content

    if (Object.keys(cart).length === 0) {
        emptyCartMessage.style.display = 'block';
        checkoutButton.style.display = 'none';
        return;
    } else {
        emptyCartMessage.style.display = 'none';
        checkoutButton.style.display = 'block';
    }

    // Create table for displaying the cart items
    const table = document.createElement('table');
    table.innerHTML = `
        <thead>
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody></tbody>
    `;

    const tbody = table.querySelector('tbody');
    let total = 0;

    for (const product_id in cart) {
        if (cart.hasOwnProperty(product_id)) {
            const item = cart[product_id];
            const subtotal = item.price * item.quantity;
            total += subtotal;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name}</td>
                <td><img src="../product_images/${item.image_path}" alt="${item.name}" width="50"></td>
                <td>$${item.price}</td>
                <td>${item.quantity}</td>
                <td>$${subtotal.toFixed(2)}</td>
            `;
            tbody.appendChild(row);
        }
    }
    table.id = 'cart-table';
    cartContent.appendChild(table);
    // Update the total
    document.getElementById('checkout-total').textContent = `$${total.toFixed(2)}`;
}

// Function to fetch cart data
function fetchCartData() {
    fetch('../../backend/get_cart.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            updateCartDisplay(data.cart);
        })
        .catch(error => {
            console.error('Error fetching cart data:', error);
            document.getElementById('cart-content').innerHTML = '<p>Error loading cart. Please try again later.</p>';
        });
}

// --- Initial Fetch ---
document.addEventListener('DOMContentLoaded', () => {
    fetchCartData(); // Fetch cart data when the page loads
});