<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';
$success_message = "";
$error_message = "";
$edit_mode = false;
$edit_product = [
    'id' => '',
    'category_id' => '',
    'name' => '',
    'description' => '',
    'price' => '',
    'stock_quantity' => '',
    'image_url' => ''
];
// Fetch categories for dropdown
$categories_result = $conn->query("SELECT * FROM categories");
// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $category_id   = $_POST['category_id'];
    $name          = $_POST['name'];
    $description   = $_POST['description'];
    $price         = $_POST['price'];
    $stock_quantity = $_POST['stock']; // Form က name="stock" ကနေ လာတာပါ
    
    // Handle File Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $target_dir = __DIR__ . "/images/";
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($image_name);
        $image_url = "images/" . basename($image_name); // Store the relative path to the image

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // SQL Query တွင် Database ထဲကအတိုင်း stock_quantity ကို သုံးပေးထားပါသည်
            $sql = "INSERT INTO products (category_id, name, description, price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                // types: i = int, s = string, d = double/float
                // 1:category_id(i), 2:name(s), 3:description(s), 4:price(d), 5:stock_quantity(i), 6:image_url(s)
                $stmt->bind_param("issdis", $category_id, $name, $description, $price, $stock_quantity, $image_url);
                
                if ($stmt->execute()) {
                    $success_message = "Product added successfully!";
                } else {
                    $error_message = "Database Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error_message = "Prepare Error: " . $conn->error;
            }
        } } else {
        // PHP ကနေ ဘာ Error ကုဒ် ပေးလိုက်လဲဆိုတာကို အတိအကျ ထုတ်ပြခိုင်းခြင်း
        $php_upload_error = $_FILES['image']['error'];
        $error_message = "Upload failed with PHP Error Code: " . $php_upload_error;
    
    } }else {
        $error_message = "Please select a valid image file.";
    }

// Handle Update Product
if (isset($_POST['update_product'])) {
    $edit_id = $_POST['id'];
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = __DIR__ . "/images/" . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "UPDATE products SET category_id = ?, name = ?, description = ?, price = ?, stock_quantity = ?, image_url = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isdsssi", $category_id, $name, $description, $price, $stock, $target, $edit_id);
        }
    } else {
        $sql = "UPDATE products SET category_id = ?, name = ?, description = ?, price = ?, stock_quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isdssi", $category_id, $name, $description, $price, $stock, $edit_id);
    }
    if (isset($stmt) && $stmt->execute()) {
        $success_message = "Product updated successfully!";
    } else {
        $error_message = "Error updating product: " . $conn->error;
    }
    if (isset($stmt)) $stmt->close();
}
// Handle Edit Request (Load data into form)
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $result_edit = $conn->query("SELECT * FROM products WHERE id = $edit_id");
    if ($result_edit->num_rows > 0) {
        $edit_mode = true;
        $edit_product = $result_edit->fetch_assoc();
    }
}
// Handle Delete Product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: admin.php");
    exit();
}
// Fetch all products with category names
$result = $conn->query("SELECT products.*, categories.name as category_name FROM products JOIN categories ON products.category_id = categories.id");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-links {
            float: right;
        }

        .nav-links a {
            text-decoration: none;
            color: #e84757;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 15px;
            background: #e84757;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #d13848;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }


        th {
            background-color: #f8f8f8;
        }

        .action-btn {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            margin-right: 10px;
        }

        .action-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="nav-links">
            <a href="index.php">Go to Website</a> |
            <a href="login1.php">Logout</a>
        </div>
        <h2>Admin Dashboard</h2>
        <p>Welcome, Admin Yu Mon</p>
        <hr>
        <?php if (!empty($success_message)): ?>
            <div style="color: green; margin-bottom: 15px; font-weight: bold;"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div style="color: red; margin-bottom: 15px; font-weight: bold;"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <h3><?php echo $edit_mode ? 'Edit Product' : 'Add New Product'; ?></h3>
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php
                    if ($categories_result->num_rows > 0) {
                        while ($cat = $categories_result->fetch_assoc()) {
                            $selected = ($edit_product['category_id'] == $cat['id']) ? 'selected' : '';
                            echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($edit_product['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" required><?php echo htmlspecialchars($edit_product['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($edit_product['price']); ?>" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($edit_product['stock_quantity']); ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Choose Image File</label>
                <input type="file" id="image" name="image" <?php echo $edit_mode ? '' : 'required'; ?>>
            </div>
            <?php if ($edit_mode): ?>
                <button type="submit" name="update_product">Update Product</button>
                <a href="admin.php" style="margin-left: 10px; text-decoration: none; color: #555; font-weight: bold;">Cancel</a>
            <?php else: ?>
                <button type="submit" name="add_product">Add Product</button>
            <?php endif; ?>
        </form>
        <hr style="margin: 30px 0;">
        <h3>Manage Products</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" width="50" alt="Product"></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td>$<?php echo htmlspecialchars($row['price']); ?></td>
                            <td><?php echo htmlspecialchars($row['stock_quantity']); ?></td>
                            <td>
                                <a href="admin.php?edit=<?php echo $row['id']; ?>" class="action-btn" style="color: #007bff;">Edit</a>
                                <a href="admin.php?delete=<?php echo $row['id']; ?>" class="action-btn" style="color: #dc3545;" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>