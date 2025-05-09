<?php
include_once "db_connection.php"; 
session_start(); // Start session before any output

// Check if the session variable is not set, initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add a product to the cart
function addToCart($product_id, $name, $price, $duration) {
    $_SESSION['cart'][] = array(
        'product_id' => $product_id,
        'name' => $name,
        'price' => $price,
        'duration' => $duration
    );
    // Redirect to prevent form resubmission
    header("Location: cart.php");
    exit();
}

// Function to remove a product from the cart
function removeFromCart($product_id) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] === $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    // Redirect to prevent form resubmission
    header("Location: cart.php");
    exit();
}

// Check if add to cart button is clicked
if(isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $duration = $_POST['duration'];

    // Fetch product details from the database
    $query = "SELECT * FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['productName'];
        $price = $row['price'];

        // Add product to cart
        addToCart($product_id, $name, $price, $duration);
    }
}

// Check if remove from cart button is clicked
if(isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
}

// Redirect to payment.php if checkout button is clicked
if(isset($_POST['checkout'])) {
    header("Location: payment.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        /* Internal CSS */
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
            padding: 0.5em 0;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        h2 {
            color: #fff;
        }

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

        .cart-container {
            margin-top: 100px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            width: 80%;
            max-width: 600px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }

        .product-details {
            flex-grow: 1;
        }

        .product-image {
            max-width: 100px;
            max-height: 100px;
            margin-right: 20px;
        }

        .product-name {
            font-weight: bold;
        }

        .product-duration {
            color: #888;
        }

        .product-price {
            font-weight: bold;
        }

        .remove-btn {
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 3px;
        }

        .checkout-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .checkout-btn:hover {
            background-color: #45a049;
        }

        .back-to-products {
            margin-top: 20px;
            text-align: center;
        }

        .back-to-products a {
            text-decoration: none;
            color: #000;
            font-weight: bold;
        }

        .back-to-products a:hover {
            color: #f44336;
        }
    </style>
</head>
<body>

<header>
    <h2>Cart</h2>
</header>

<nav id="sidebar" class="menu-open">
    <ul>
        <li><a href="user_dashboard.php">User Dashboard</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="cart.php" class="active">Cart</a></li>
        <li><a href="payment.php">Payment</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="menu-toggle" id="menu-toggle" onclick="toggleMenu()"></div>

<div class="cart-container">
    <?php
    // Check if cart is not empty
    if (!empty($_SESSION['cart'])) {
        $total = 0;
        // Display cart items
        foreach ($_SESSION['cart'] as $item) {
            echo "<div class='cart-item'>";
            // Display product image if available
            $product_id = $item['product_id'];
            $query = "SELECT image FROM products WHERE product_id = $product_id";
            $result = mysqli_query($conn, $query);
            

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $image = $row['image'];
                echo "<img src='product_images/$image' alt='Product Image' class='product-image'>";
            }
            echo "<div class='product-details'>";
            echo "<p class='product-name'>" . $item['name'] . "</p>";
            echo "<p class='product-duration'>Duration: " . $item['duration'] . " hours</p>";
            echo "<p class='product-price'>₹" . $item['price'] * $item['duration'] . "</p>"; // Calculate price based on duration
            echo "</div>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $item['product_id'] . "'>";
            echo "<button type='submit' name='remove_from_cart' class='remove-btn'>Remove</button>";
            echo "</form>";
            echo "</div>";
            $total += $item['price'] * $item['duration']; // Update total price
        }
        echo "<p class='total'>Total: ₹$total</p>";
        echo "<form method='post'>";
        echo "<button type='submit' name='checkout' class='checkout-btn'>Checkout</button>";
        echo "</form>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
    
    ?>
</div>

<div class="back-to-products">
    <a href="products.php">Back to Products</a>
</div>

<script>
    function toggleMenu() {
        var sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("menu-open");
    }
</script>

</body>
</html>
