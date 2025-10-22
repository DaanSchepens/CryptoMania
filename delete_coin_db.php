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

if ($id) {
  $stmt = $conn->prepare("DELETE FROM wallet WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    echo "success";
  } else {
    echo "error";
  }
  $stmt->close();
} else {
  echo "error";
}

$conn->close();
?>
