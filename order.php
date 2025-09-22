<?php
// order.php
$host = "your-rds-endpoint-or-localhost";
$user = "your_db_username";
$pass = "your_db_password";
$db   = "fashion";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

session_start();
$cart = $_SESSION['cart'] ?? [];

if (!$cart) {
  echo "<p>Your cart is empty. <a href='shop.php'>Go back</a></p>";
  exit;
}

$total = 0;
echo "<h2>Your Cart</h2>";
foreach ($cart as $id => $qty) {
  $res = $conn->query("SELECT * FROM shop WHERE id=$id");
  $p = $res->fetch_assoc();
  $discounted = $p['price'] * (1 - $p['discount_percent']/100);
  $sub = $discounted * $qty;
  $total += $sub;
  echo "<p>{$p['name']} - Qty: $qty - ₹" . number_format($sub,2) . "</p>";
}

echo "<h3>Total: ₹" . number_format($total,2) . "</h3>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  echo "<p>✅ Order placed successfully!</p>";
  $_SESSION['cart'] = []; // clear cart
  echo "<a href='shop.php'>Continue Shopping</a>";
  exit;
}

echo "<form method='post'><button>Place Order</button></form>";

$conn->close();
?>
