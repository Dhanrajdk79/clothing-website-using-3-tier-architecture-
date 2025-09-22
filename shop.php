<?php
// shop.php
$host = "your-rds-endpoint-or-localhost";
$user = "your_db_username";
$pass = "your_db_password";
$db   = "fashion";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM shop");
session_start();

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['product_id'];
  $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
}

echo "<h2>Products</h2>";
while ($row = $result->fetch_assoc()) {
  $discounted = $row['price'] * (1 - $row['discount_percent']/100);
  echo "<div class='product'>";
  echo "<h3>{$row['name']}</h3>";
  echo "<p>Brand: {$row['brand']}</p>";
  echo "<p><b>₹" . number_format($discounted, 2) . "</b> <del>₹{$row['price']}</del></p>";
  echo "<form method='post'><input type='hidden' name='product_id' value='{$row['id']}'><button>Add to Cart</button></form>";
  echo "</div>";
}

echo "<hr><p><a href='order.php'>Go to Cart & Checkout (" . array_sum($_SESSION['cart']) . " items)</a></p>";

$conn->close();
?>
