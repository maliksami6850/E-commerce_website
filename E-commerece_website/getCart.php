<?php
header('Content-Type: application/json');
require 'dbConfig.php';

$query = $conn->prepare("
    SELECT cart.product_id, cart.quantity, products.name, products.price 
    FROM cart 
    JOIN products ON cart.product_id = products.id 
    WHERE cart.user_id = 1
");
$query->execute();
$result = $query->get_result();
$cartItems = [];

while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

echo json_encode(["cart" => $cartItems]);
?>