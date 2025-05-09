<?php
include_once "db_connection.php";
session_start();

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Calculate total amount
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['duration'];
}

// Process payment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: payment_success.php"); // Redirect to login page
        exit();
    }

    // Process payment here
    // Assuming payment is successful
    // Store order details in the order_details table
    $user_id = $_SESSION['user_id'];
    $order_id = uniqid();
    $payment_method = $_POST['payment_method'];
    $payment_status = "Success";

    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['product_id'];
        $quantity = 1; // Assuming quantity is 1 for each product in the cart
        $price = $item['price'];
        // Insert order details into order_details table
        $query = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$quantity', '$price')";
        if (!mysqli_query($conn, $query)) {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
            exit();
        }
    }

    // Clear the cart after successful payment
    unset($_SESSION['cart']);

    // Redirect to payment success page
    header("Location: payment_success.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        /* Inline CSS */
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

        /* Additional styles for payment method selection */
        h3 {
            color: #000;
            margin-top: 20px;
        }

        form {
            margin-top: 10px;
        }

        label {
            font-weight: bold;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Styles for menu and toggle bar */
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
    </style>
</head>
<body>
    <header>
        <h2>Payment</h2>
    </header>

    <nav id="sidebar" class="menu-open">
        <ul>
            <li><a href="user_dashboard.php">User Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="payment.php" class="active">Payment</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="menu-toggle" id="menu-toggle" onclick="toggleMenu()"></div>

    <div class="cart-container">
        <!-- Display the bill structure here -->
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['price']; ?></td>
                        <td><?php echo $item['duration']; ?></td>
                        <td><?php echo $item['price'] * $item['duration']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total:</td>
                    <td><?php echo $total; ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <h3>Select Payment Method</h3>
    <form method="post">
        <label>
            <input type="radio" name="payment_method" value="UPI" required> UPI
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="PhonePe" required> PhonePe
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="Other" required> Other
        </label><br>
        <button type="submit">Pay Now</button>
    </form>

    <script>
        function toggleMenu() {
            var sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("menu-open");
        }
    </script>
</body>
</html>

