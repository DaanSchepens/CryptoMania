<?php
header('Content-Type: application/json');

// Database connectie
$host = 'localhost';
$db   = 'crypto_app';
$user = 'root'; // pas aan als nodig
$pass = '';     // pas aan als nodig

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// JSON input ophalen
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$coin_id = $conn->real_escape_string($data['id']);
$symbol = $conn->real_escape_string($data['symbol']);
$name = $conn->real_escape_string($data['name']);
$price = floatval($data['price']);
$amount = floatval($data['amount']);

$sql = "INSERT INTO wallet (coin_id, symbol, name, price_usd, amount)
        VALUES ('$coin_id', '$symbol', '$name', '$price', '$amount')";

if ($conn->query($sql)) {
    echo json_encode(["success" => true, "message" => "Coin added to wallet"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to insert data"]);
}

$conn->close();
