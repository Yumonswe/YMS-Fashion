<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
header("Location: login1.php");
exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_now'])) {$user_id = $_SESSION['user_id'];$product_id = $_POST['product_id'];$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$stmt_prod =$conn->prepare("SELECT price FROM products WHERE id = ?");
$stmt_prod->bind_param("i", $product_id);$stmt_prod->execute();
$result_prod =$stmt_prod->get_result();
if ($result_prod->num_rows > 0) {$row_prod = $result_prod->fetch_assoc();$price = $row_prod['price'];$total_amount = $price * $quantity;
$status = 'Pending';$shipping_address = 'Tokyo, Japan';
$stmt_order =$conn->prepare("INSERT INTO orders (user_id, total_amount, status, shipping_address) VALUES (?, ?, ?, ?)");
$stmt_order->bind_param("idss", $user_id,$total_amount, $status,$shipping_address);
if ($stmt_order->execute()) {
echo "<script>alert('Order placed successfully!'); window.location.href='shop.php';</script>";
} else {
echo "Error placing order: " . $conn->error;
}
$stmt_order->close();
} else {
echo "<script>alert('Product not found!'); window.location.href='shop.php';</script>";
}
$stmt_prod->close();
}
$conn->close();
?>