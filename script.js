// public/js/script.js

function displayProducts(category = null) {
    const container = document.getElementById('product-container');
    container.innerHTML = ''; // Clear existing products

    let url = '../../backend/get_products.php';
    if (category) {
        url += '?category=' + encodeURIComponent(category);
    }

    fetch(url)
        .then(response => response.json())
        .then(products => {
            if (products.length === 0) {
                container.innerHTML = '<p>No products found in this category.</p>';
                return;
            }

            products.forEach(product => {
                const card = document.createElement('div');
                card.classList.add('card');
                card.innerHTML = `
                    <a href="product_details.html?id=${product.id}">
                        <img src="../${product.image_path}" alt="${product.name}">
                        <h3>${product.name}</h3>
                        <p>$${product.price}</p>
                    </a>
                    <button class="add-to-cart-home" data-product-id="${product.id}">Add to Cart</button>
                `;
                container.appendChild(card);

                const addToCartBtn = card.querySelector('.add-to-cart-home');
                addToCartBtn.addEventListener('click', (event) => {
                    event.preventDefault();
                    const productId = event.target.dataset.productId;

                    fetch('../../backend/add_to_cart.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `product_id=${productId}&quantity=1`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            fetchCartData();
                        } else {
                            alert('Error adding to cart: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error adding to cart:', error);
                        alert('Error adding to cart. Please try again later.');
                    });
                });
            });
        })
        .catch(error => {
            console.error('Error fetching products:', error);
            container.innerHTML = '<p>Error loading products. Please try again later.</p>';
        });
}

function setupCategoryFilter() {
    document.querySelectorAll('.category-filter a').forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const category = event.target.dataset.category;
            displayProducts(category);
        });
    });
}

function updateCartLink(totalItems, totalPrice) {
    const cartLink = document.querySelector('header nav ul li a[href="cart.html"]');
    if (cartLink) {
        cartLink.textContent = `Cart (${totalItems})`;
    }
}

function fetchCartData() {
    fetch('../../backend/get_cart.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            updateCartLink(data.totalItems, data.totalPrice);
        })
        .catch(error => {
            console.error('Error fetching cart data:', error);
        });
}

function fetchProductDetails(productId) {
    fetch(`../../backend/get_product.php?id=${productId}`)
        .then(response => response.json())
        .then(product => {
            if (product.error) {
                document.querySelector('.product-details').innerHTML = `<p>${product.error}</p>`;
                return;
            }

            document.getElementById('product-name').textContent = product.name;
            document.getElementById('product-image').src = `../${product.image_path}`;
            document.getElementById('product-image').alt = product.name;
            document.getElementById('product-description').textContent = product.description;
            document.getElementById('product-price').textContent = `$${product.price}`;
            document.getElementById('product-category').textContent = `Category: ${product.category}`;
        })
        .catch(error => {
            console.error('Error fetching product details:', error);
            document.querySelector('.product-details').innerHTML = '<p>Error loading product details.</p>';
        });
}

function setupAddToCartButton() {
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const quantityInput = document.getElementById('quantity');
    const cartMessage = document.getElementById('cart-message');

    if (addToCartBtn) { // Check if button exists (only on product details page)
        addToCartBtn.addEventListener('click', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const productId = urlParams.get('id');
            const quantity = parseInt(quantityInput.value, 10);

            if (!productId || isNaN(quantity) || quantity <= 0) {
                cartMessage.textContent = 'Invalid product ID or quantity.';
                cartMessage.style.color = 'red';
                return;
            }

            fetch('../../backend/add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cartMessage.textContent = data.message;
                    cartMessage.style.color = 'green';
                    fetchCartData();
                } else {
                    cartMessage.textContent = 'Error adding to cart: ' + data.message;
                    cartMessage.style.color = 'red';
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                cartMessage.textContent = 'Error adding to cart. Please try again later.';
                cartMessage.style.color = 'red';
            });
        });
    }
}
document.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname.includes('home.html')) {
        setupCategoryFilter();
        displayProducts();

    } else if (window.location.pathname.includes('product_details.html')) {
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('id');

        if (productId) {
            fetchProductDetails(productId);
        } else {
            document.querySelector('.product-details').innerHTML = '<p>Product ID not found.</p>';
        }
        setupAddToCartButton(); // Set up "Add to Cart" on product details page

    }else if (window.location.pathname.includes('product.html')){
        setupCategoryFilter();
        displayProducts();
    }
    fetchCartData();
});