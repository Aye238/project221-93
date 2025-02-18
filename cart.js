
// public/js/cart.js

// Function to update the cart display
function updateCartDisplay(cart) {
    const cartContent = document.getElementById('cart-content');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const clearCartBtn = document.getElementById('clear-cart-btn');
    const checkoutButton = document.getElementById('checkout-button');

    cartContent.innerHTML = ''; // Clear existing content

    if (Object.keys(cart).length === 0) {
        emptyCartMessage.style.display = 'block';
        clearCartBtn.style.display = 'none';
        checkoutButton.style.display = 'none'; // Hide when cart is empty
        return;
    } else {
        emptyCartMessage.style.display = 'none';
        clearCartBtn.style.display = 'block';
        checkoutButton.style.display = 'block'; // Show when cart is not empty
    }

    const table = document.createElement('table');
    table.innerHTML = `
        <thead>
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total:</td>
                <td id="cart-total">$0.00</td>
                <td></td>
            </tr>
        </tfoot>
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
                <td><img src="../${item.image_path}" alt="${item.name}" width="50"></td>
                <td>$${item.price}</td>
                <td>
                    <input type="number" class="quantity-input" data-product-id="${product_id}" name="quantity[${product_id}]" value="${item.quantity}" min="0">
                </td>
                <td>$${subtotal.toFixed(2)}</td>
                <td>
                    <button class="remove-btn" data-product-id="${product_id}">Remove</button>
                </td>
            `;
            tbody.appendChild(row);
        }
    }

    const totalFormatted = total.toFixed(2);
    table.querySelector('#cart-total').textContent = `$${totalFormatted}`;
    cartContent.appendChild(table);

    // --- Add event listeners for Remove buttons ---
    attachButtonEvents();
     // --- Attach event listeners for quantity input changes ---
    attachQuantityInputEvents();
}

// Function to attach event listeners to Update and Remove buttons
function attachButtonEvents() {
       // --- Event Delegation for Remove Buttons ---
    document.querySelectorAll('.remove-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            const productId = event.target.dataset.productId;

            fetch('../../backend/remove_from_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                      // Fetch cart data again and update display
                    fetchCartData();
                } else {
                    alert('Error removing item: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error removing item:', error);
                alert('Error removing item. Please try again later.');
            });
        });
    });

}
// Function to attach event listeners to quantity input fields
function attachQuantityInputEvents() {
    const quantityInputs = document.querySelectorAll('.quantity-input');

    quantityInputs.forEach(input => {
        input.addEventListener('input', (event) => { // 'input' event for real-time updates
            const productId = event.target.dataset.productId;
            const newQuantity = parseInt(event.target.value, 10);
             // --- Send AJAX request to update_cart.php ---
            fetch('../../backend/update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${productId}&quantity=${newQuantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cart updated successfully, refresh cart display
                    fetchCartData(); // Re-fetch and re-render the cart
                } else {
                    alert('Error updating cart: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error updating cart:', error);
                alert('Error updating cart. Please try again later.');
            });
        });
    });
}

// Function to fetch cart data (reusable - called on page load, update, and remove)
function fetchCartData() {
    fetch('../../backend/get_cart.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            updateCartDisplay(data.cart); // Update the cart display
            const clearCartButton = document.getElementById('clear-cart-btn');
            const checkoutButton = document.getElementById('checkout-button'); // ADD THIS: Get reference

            if (clearCartButton && checkoutButton) {  // ADD THIS conditional check
                clearCartButton.style.display = data.totalItems > 0 ? 'block' : 'none';
                checkoutButton.style.display = data.totalItems > 0 ? 'block' : 'none'; // ADD THIS line
            }
        })
        .catch(error => {
            console.error('Error fetching cart data:', error);
            document.getElementById('cart-content').innerHTML = '<p>Error loading cart. Please try again later.</p>';
        });
}

// --- Clear Cart Functionality ---
function setupClearCartButton() {
    const clearCartBtn = document.getElementById('clear-cart-btn');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', () => {
            fetch('../../backend/clear_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchCartData(); // Refresh cart display (will be empty)
                } else {
                    alert('Error clearing cart: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error clearing cart:', error);
                alert('Error clearing cart. Please try again later.');
            });
        });
    }
}

// --- Initial Fetch ---
document.addEventListener('DOMContentLoaded', () => {
    fetchCartData(); // Fetch cart data when the page loads
    setupClearCartButton(); // Setup "Clear Cart" button functionality
});