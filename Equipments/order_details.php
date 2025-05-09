<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        /* CSS styles for formatting */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #000;
            background-image: url('images/bg1.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        header {
            background-color: rgba(0, 0, 0, 0.7);
            text-align: center;
            padding: 0.3em 0;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        h2 {
            color: #fff;
        }

        .order-details {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-top: 100px; /* Adjust margin to create space below header */
            max-width: 600px;
            width: 80%;
        }

        img {
            max-width: 100px;
            max-height: 100px;
        }

        /* Toggle Menu Styles */
        nav {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 1em;
            position: fixed;
            left: 0;
            top: 60px;
            bottom: 0;
            width: 200px;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            margin-bottom: 10px;
        }

        nav ul li a {
            display: block;
            text-decoration: none;
            color: #fff;
            font-size: 1.2em;
            padding: 10px;
            transition: all 0.3s ease;
        }

        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-toggle {
            position: fixed;
            right: 20px;
            top: 20px;
            z-index: 1100;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-toggle:before {
            content: '\2630';
            font-size: 1.2em;
            margin-right: 5px;
        }

        .menu-open {
            transform: translateX(-200px);
        }
    </style>
</head>
<body>

<header>
    <h2>Order Details</h2>
</header>

<nav id="sidebar" class="menu-open">
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_products.php" class="active">Manage Products</a></li>
        <li><a href="order_details.php">Order Details</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="admin_logout.php">Logout</a></li>
    </ul>
</nav>

<div class="menu-toggle" id="menu-toggle" onclick="toggleMenu()"></div>

<?php
// Include database connection file
include_once "db_connection.php";

// Fetch order details from the database based on order ID
if(isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Query to retrieve order details
    $query = "SELECT * FROM orders WHERE order_id = $order_id";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];
        $total_amount = $row['total_amount'];

        // Fetch product details
        $product_query = "SELECT * FROM products WHERE product_id = $product_id";
        $product_result = mysqli_query($conn, $product_query);
        if(mysqli_num_rows($product_result) > 0) {
            $product_row = mysqli_fetch_assoc($product_result);
            $product_name = $product_row['productName'];
            $product_price = $product_row['price'];
            $product_image = $product_row['image'];

            // Decrease product quantity in stock
            $new_quantity = $product_row['quantity'] - $quantity;
            if($new_quantity < 0) {
                // Product out of stock
                $out_of_stock = true;
            } else {
                // Update product quantity in the database
                $update_query = "UPDATE products SET quantity = $new_quantity WHERE product_id = $product_id";
                mysqli_query($conn, $update_query);
            }
        }
    }
}
?>

<?php if(isset($product_name)): ?>
<div class="order-details">
    <img src="product_images/<?php echo $product_image; ?>" alt="Product Image">
    <p><strong>Product Name:</strong> <?php echo $product_name; ?></p>
    <p><strong>Quantity:</strong> <?php echo $quantity; ?></p>
    <p><strong>Price per item:</strong> ₹<?php echo $product_price; ?></p>
    <p><strong>Total Amount:</strong> ₹<?php echo $total_amount; ?></p>
    <?php if(isset($out_of_stock) && $out_of_stock): ?>
    <p><strong>Status:</strong> Out of Stock</p>
    <?php endif; ?>
</div>
<?php else: ?>
<p>No order details found.</p>
<?php endif; ?>

<script>
    function toggleMenu() {
        var sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("menu-open");
    }
</script>

</body>
</html>
