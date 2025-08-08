async function loadCart() {
    try {
        const response = await fetch('getCart.php');
        const data = await response.json();
        const cartItems = document.getElementById('cart-items');
        cartItems.innerHTML = '';

        let totalPrice = 0;

        data.cart.forEach((item) => {
            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item';
            cartItem.innerHTML = `
                <h3>${item.name}</h3>
                <p>Price: $${item.price}</p>
                <p>Quantity: ${item.quantity}</p>
                <button onclick="removeFromCart(${item.product_id})">Remove</button>
            `;
            cartItems.appendChild(cartItem);

            totalPrice += item.price * item.quantity;
        });

        const totalPriceElement = document.getElementById('total-price');
        totalPriceElement.textContent = `Total Price: $${totalPrice.toFixed(2)}`;
    } catch (error) {
        console.error('Error loading cart:', error);
    }
}

async function removeFromCart(productId) {
    try {
        const response = await fetch('removeFromCart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ product_id: productId }),
        });
        const data = await response.json();
        alert(data.message);
        loadCart(); // Refresh cart
    } catch (error) {
        console.error('Error removing product from cart:', error);
    }
}

async function placeOrder() {
    try {
        const response = await fetch('placeOrder.php', {
            method: 'POST',
        });
        const data = await response.json();
        alert(data.message);

        if (data.success) {
            loadCart(); // Refresh cart
        }
    } catch (error) {
        console.error('Error placing order:', error);
    }
}

// Load cart on page load
window.onload = loadCart;