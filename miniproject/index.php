<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shein Style - Fashion Store</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php">YMS.FASHION</a>
        </div>
        <div class="nav-links">
            <a href="index.php" class="active">Home</a>
            <a href="women.php">Women</a>
            <a href="men.php">Men</a>
            <a href="kids.php">Kids</a>
            <!-- <a href="shop.php">Shop</a> -->
        </div>
        <div class="header-right">
            <div class="search-bar">
                <form action="search.php" method="GET">
                    <input type="text" name="query" placeholder="Search products..." required>
                    <button type="submit">Search</button>
                </form>
            </div>
            <div class="user-actions">
                <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION['user_name'])):
                ?>
                    <span>Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span> | <a href="login1.php">Logout</a>
                <?php else: ?>
                    <a href="login1.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <section class="hero">
        <h1>NEW SEASON STYLES</h1>
        <p>Discover the latest trends in women's, men's & kids fashion</p>
        <a href="shop.php" class="shop-now-btn">Shop Now</a>
    </section>
    <div class="container">
        <!-- <h2>Trending Now</h2> -->
        <!-- <div class="product-grid">
            <?php

            include 'db.php';

            $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 4";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="product-img">
                        <div class="product-info">
                            <div class="product-title"><?php echo htmlspecialchars($row['title']); ?></div>
                            <div class="product-price"><?php echo htmlspecialchars($row['price']); ?></div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No products available yet.</p>";
            }
            ?>
        </div> -->
        <?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Fashion Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
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
            max-width: 1200px;
            margin: auto;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .product-card {
            border: 1px solid #eaeaea;
            border-radius: 6px;
            overflow: hidden;
            text-align: center;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .product-img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
            background-color: #ffffff;
        }
        .product-info {
            padding: 15px;
            text-align: left;
            background-color: #ffffff;
        }
        .product-title {
            font-size: 14px;
            color: #222222;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .product-price {
            font-size: 15px;
            color: #000000;
            font-weight: bold;
        }
        .btn {
            padding: 8px 12px;
            background: #e84757;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }
        .btn:hover {
            background: #d13848;
        }
    </style>
</head>
<body>

<!-- <header>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="shop.php" class="active">Shop</a>
        <a href="women.php">Women</a>
        <a href="men.php">Men</a>
        <a href="kids.php">Kids</a>
    </div>
    <div class="user-actions">
        <?php if (isset($_SESSION['user_name'])): ?>
            <span>Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span> |
            <a href="login1.php">Logout</a>
        <?php else: ?>
            <a href="login1.php">Login</a>
        <?php endif; ?>
    </div>
</header> -->

<div class="container">
    <h2>All Products</h2>
    <div class="product-grid">
        <?php
        $sql = "SELECT * FROM products ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Database ထဲမှာ column နာမည်က 'name' ဖြစ်နေလို့ title လို့သုံးထားရင် name ကိုပြောင်းပေးလိုက်ပါတယ်
                $product_name = isset($row['name']) ? $row['name'] : (isset($row['title']) ? $row['title'] : 'Product');
        ?>
                <div class="product-card">
                    <div>
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($product_name); ?>" class="product-img">
                        <div class="product-info">
                            <div class="product-title"><?php echo htmlspecialchars($product_name); ?></div>
                            <div class="product-price">$<?php echo htmlspecialchars($row['price']); ?></div>
                        </div>
                    </div>
                    <div style="padding: 0 15px 15px 15px;">
                        <form action="buy.php" method="POST" onsubmit="return confirm('Are you sure you want to buy this item?');">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" name="buy_now" class="btn">Buy Now</button>
                        </form>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p>No products available yet.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
    </div>
    <footer>
        <div class="footer-content">
            <p>© 2026 YMS.FASHION. All rights reserved.</p>
            <p>Designed with style and care for your e-commerce project.</p>
        </div>
    </footer>
</body>

</html>