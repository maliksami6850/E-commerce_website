<?php
header('Content-Type: application/json');
require 'dbConfig.php';

$userId = 1; // Assuming user ID is fixed for now

// Fetch cart items
$query = $conn->prepare("
    SELECT cart.product_id, cart.quantity, products.stock 
    FROM cart 
    JOIN products ON cart.product_id = products.id 
    WHERE cart.user_id = ?
");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

// Check stock availability and update products table
$allInStock = true;
foreach ($cartItems as $item) {
    if ($item['stock'] < $item['quantity']) {
        $allInStock = false;
        break;
    }
}

if ($allInStock) {
    foreach ($cartItems as $item) {
        $updateStockQuery = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $updateStockQuery->bind_param("ii", $item['quantity'], $item['product_id']);
        $updateStockQuery->execute();
    }

    // Clear cart
    $clearCartQuery = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $clearCartQuery->bind_param("i", $userId);
    $clearCartQuery->execute();

    echo json_encode(["success" => true, "message" => "Order submitted successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Some items are out of stock."]);
}
?>