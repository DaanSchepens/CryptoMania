<?php
$host = 'localhost';
$db   = 'crypto_app';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Database-verbinding mislukt: " . $conn->connect_error);
}

$id = $_POST['id'] ?? null;
$amount = $_POST['amount'] ?? null;
$total_value = $_POST['total_value'] ?? null;

if ($id && $amount !== null) {
  $stmt = $conn->prepare("UPDATE wallet SET amount = ?, total_value = ? WHERE id = ?");
  $stmt->bind_param("ddi", $amount, $total_value, $id);
  $stmt->execute();
  echo "success";
} else {
  echo "error";
}

$conn->close();
?>
