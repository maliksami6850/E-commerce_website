<?php
header('Content-Type: application/json');
require 'dbConfig.php';

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'];
$quantity = $data['quantity'] ?? 1;

if ($product_id && $quantity) {
    $query = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $query->bind_param("i", $product_id);
    $query->execute();
    $result = $query->get_result();
    $product = $result->fetch_assoc();

    if ($product && $product['stock'] >= $quantity) {
        $checkCartQuery = $conn->prepare("SELECT * FROM cart WHERE user_id = 1 AND product_id = ?");
        $checkCartQuery->bind_param("i", $product_id);
        $checkCartQuery->execute();
        $cartResult = $checkCartQuery->get_result();

        if ($cartResult->num_rows > 0) {
            $updateCartQuery = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = 1 AND product_id = ?");
            $updateCartQuery->bind_param("ii", $quantity, $product_id);
            $updateCartQuery->execute();
        } else {
            $insertCartQuery = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (1, ?, ?)");
            $insertCartQuery->bind_param("ii", $product_id, $quantity);
            $insertCartQuery->execute();
        }

        echo json_encode(["success" => true, "message" => "Product added to cart."]);
    } else {
        echo json_encode(["success" => false, "message" => "Product out of stock."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid product ID or quantity."]);
}
?>