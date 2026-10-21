<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kids' Collection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: white;
            border-bottom: 1px solid #ddd;
        }

        .nav-links a {
            margin-right: 20px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .nav-links a.active {
            color: #e84757;
        }

        .user-actions a {
            text-decoration: none;
            color: #e84757;
            font-weight: bold;
        }

        .container {
            padding: 40px;
            max-width: 1000px;
            margin: auto;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            background: #fff;
        }

        .product-card img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }

        .btn {
            padding: 8px 12px;
            background: #e84757;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn:hover {
            background: #d43f4d;
        }
    </style>
</head>

<body>
    <header>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="women.php">Women</a>
            <a href="men.php">Men</a>
            <a href="kids.php" class="active">Kids</a>
           

        </div>
        <div class="user-actions">
            <?php if (isset($_SESSION['user_name'])): ?>
                <span>Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span> | <a href="login.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </header>
    <div class="container">
        <h2>Kids' Collection</h2>
        <p>Explore our latest styles and trends for kids.</p>

        <div class="product-grid">
            <?php
            $result = $conn->query("SELECT * FROM products WHERE category_id = 3");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Product">
                        <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                        <p>$<?php echo htmlspecialchars($row['price']); ?></p>
                        <form action="buy.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" name="buy_now" class="btn">Buy Now</button>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo "<p>No products available in this category.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>