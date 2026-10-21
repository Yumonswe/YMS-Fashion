...</div>


<?php
session_start();
include 'db.php';
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Results - YMS.FASHION</title>
<style>
body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
header { display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background: white; border-bottom: 1px solid #ddd; }
.nav-links a { margin-right: 20px; text-decoration: none; color: #333; font-weight: bold; }
.container { padding: 40px; max-width: 1000px; margin: auto; }
.product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-top: 20px; }
.product-card { border: 1px solid #ddd; padding: 15px; border-radius: 6px; text-align: center; background: #fff; }
.product-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 4px; }
.btn { padding: 8px 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
</style>
</head>
<body>
<header>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="shop.php">Shop</a>
<a href="women.php">Women</a>
<a href="men.php">Men</a>
<a href="kids.php">Kids</a>
</div>
</header>
<div class="container">
<h2>Search Results for: "<?php echo htmlspecialchars($search_query); ?>"</h2>
<div class="product-grid">
<?php
if ($search_query !== '') {
$stmt =$conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
$searchTerm = "%" . $search_query . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);$stmt->execute();
$result =$stmt->get_result();
if ($result->num_rows > 0) {
while ($row =$result->fetch_assoc()) {
echo '<div class="product-card">';
echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="Product">';
echo '<h4>' . htmlspecialchars($row['name']) . '</h4>';
echo '<p>' . htmlspecialchars($row['price']) . '</p>';
echo '<form action="buy.php" method="POST" onsubmit="return confirm(\'ဒီပစ္စည်းကို ဝယ်ယူမှာ သေချာပါသလား?\');">';
echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
echo '<input type="hidden" name="quantity" value="1">';
echo '<button type="submit" name="buy_now" class="btn">Buy Now</button>';
echo '</form>';
echo '</div>';
}
} else {
echo '<p>No products found matching your search.</p>';
}
$stmt->close();
} else {
echo '<p>Please enter a search term.</p>';
}
?>
</div>
</div>
</body>
</html>