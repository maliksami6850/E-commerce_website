<?php
header('Content-Type: application/json');
require 'dbConfig.php';

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'];

if ($product_id) {
    $query = $conn->prepare("DELETE FROM cart WHERE user_id = 1 AND product_id = ?");
    $query->bind_param("i", $product_id);
    $query->execute();

    if ($query->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Product removed from cart."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to remove product."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid product ID."]);
}
?>