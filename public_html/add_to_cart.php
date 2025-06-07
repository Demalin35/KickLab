<?php
session_start();
header('Content-Type: application/json');

$productId = 1;
$productName = "Adidas Terrex Anylander";
$productPrice = 135.00;
$productImage = "assets/terrex1.webp";

$size = $_POST["size"] ?? null;
$quantity = isset($_POST["quantity"]) ? (int) $_POST["quantity"] : 1;

if (!$size) {
    echo json_encode(["success" => false, "error" => "Size is required."]);
    exit;
}

$cartItem = [
    "id" => $productId,
    "name" => $productName,
    "price" => $productPrice,
    "size" => $size,
    "quantity" => $quantity,
    "image" => $productImage
];

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

$_SESSION["cart"][] = $cartItem;

echo json_encode(["success" => true, "count" => count($_SESSION["cart"])]);
