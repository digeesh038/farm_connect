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
}

// Function to add a product to the user cart table
function addToUserCart($user_id, $product_id, $duration) {
    global $conn;
    $query = "INSERT INTO user_cart (user_id, product_id, duration_hours) VALUES ($user_id, $product_id, $duration)";
    mysqli_query($conn, $query);
}

// Check if add to cart button is clicked
if(isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $duration = $_POST['duration'];

    // Fetch product details from the database
    $query = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['productName'];
        $price = $row['price'];

        // Add product to cart
        addToCart($product_id, $name, $price, $duration);

        // Store product in user_cart table if user is logged in
        if(isset($_SESSION['user_id'])) {
            addToUserCart($_SESSION['user_id'], $product_id, $duration);
        }

        // Display success message
        echo "<script>alert('Product added to cart successfully!');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        /* Inline CSS */
        /* Your styles here */
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

        .product-container {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .product-card {
            background-color: #f2f2f2;
            border-radius: 10px;
            margin: 10px;
            padding: 10px;
            width: 180px; /* Reduced box size */
            text-align: center;
        }

        .product-image {
            max-width: 100%;
            max-height: 120px;
            margin-bottom: 10px;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-price {
            font-size: 1em;
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .product-select {
            margin-top: 10px;
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .add-to-cart {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            width: 100%;
        }

        .add-to-cart:hover {
            background-color: #45a049;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>

<header>
    <h2>Products</h2>
</header>

<nav id="sidebar" class="menu-open">
    <ul>
        <li><a href="user_dashboard.php">User Dashboard</a></li>
        <li><a href="products.php" class="active">Products</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="payment.php">Payment</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="menu-toggle" id="menu-toggle" onclick="toggleMenu()"></div>

<div class="product-container">
    <?php
    // Include database connection file
    include_once "db_connection.php";

    // Pagination
    $results_per_page = 4;
    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }
    $start_from = ($page - 1) * $results_per_page;

    // Fetch products from the database with pagination
    $query = "SELECT * FROM products LIMIT $start_from, $results_per_page";
    $result = mysqli_query($conn, $query);

    // Display products
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product-card'>";
            echo "<img class='product-image' src='product_images/" . $row['image'] . "' alt='Product Image'>";
            echo "<p class='product-name'>" . $row['productName'] . "</p>";
            echo "<p class='product-price'>₹" . $row['price'] . " per hour</p>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $row['product_id'] . "'>";
            echo "<input class='product-select' type='number' name='duration' min='0.5' step='0.5' placeholder='Enter duration (hours)' required>";
            echo "<button type='submit' name='add_to_cart' class='add-to-cart'>Add to Cart</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
</div>

<div class="pagination">
    <?php
    // Pagination links
    $sql = "SELECT COUNT(product_id) AS total FROM products";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_pages = ceil($row["total"] / $results_per_page);

    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='products.php?page=" . $i . "' class='" . ($page == $i ? 'active' : '') . "'>" . $i . "</a>";
    }
    ?>
</div>

<script>
    function toggleMenu() {
        var sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("menu-open");
    }
</script>

</body>
</html>
