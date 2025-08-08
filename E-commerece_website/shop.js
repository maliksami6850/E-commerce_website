function addToCart(productId) {
    fetch('addToCart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: 1 }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert(`Error: ${data.message}`); // Corrected string interpolation syntax
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to add product to cart.');
        });
}
