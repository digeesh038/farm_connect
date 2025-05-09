<?php
// Handle form submission and redirect
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "agriculture_equipment";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $productName = $_POST["productName"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];

    // Check if product already exists
    $sql = "SELECT * FROM products WHERE productName = '$productName'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Product already exists, update quantity and price
        $sql_update = "UPDATE products SET quantity = quantity + $quantity, price = $price WHERE productName = '$productName'";
        if ($conn->query($sql_update) === TRUE) {
            echo "<tr><td colspan='4' style='text-align:center;'>Product already exists. Quantity and price updated successfully.</td></tr>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        // Insert new product
        $image = $_FILES["image"]["name"];
        $target_dir = "product_images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        $sql = "INSERT INTO products (productName, quantity, price, image) VALUES ('$productName', '$quantity', '$price', '$image')";
        if ($conn->query($sql) === TRUE) {
            echo "<tr><td>$productName</td><td>$quantity</td><td>₹$price</td><td><img src='product_images/$image' alt='Product Image'></td></tr>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close connection
    $conn->close();

    // Redirect to avoid form resubmission
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Your Agriculture Equipment Rental Management System</title>
    <style>
        /* Inline CSS */
        body {
            font-family: 'Arial', sans-serif;
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
            padding: 1.5em 0;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        h1 {
            font-family: 'Cinzel', serif;
            font-size: 1.0em;
            margin: 0;
            color: #fff;
        }

        form {
            margin-top: 100px; /* Adjust margin to create space below header */
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            text-align: center; /* Center form elements */
        }

        label {
            font-weight: bold;
        }

        input[type="text"], input[type="number"], input[type="file"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="file"] {
            margin-top: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 80%;
            max-width: 600px;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #f2f2f2;
            border-radius: 10px;
            text-align: center; /* Center table */
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
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
    <h1>Manage Products</h1>
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

<form action="" method="post" enctype="multipart/form-data">
    <label for="productName">Product Name:</label><br>
    <input type="text" id="productName" name="productName" required><br>
    <label for="quantity">Quantity:</label><br>
    <input type="number" id="quantity" name="quantity" required><br>
    <label for="price">Price (per hour, in ₹):</label><br>
    <input type="number" id="price" name="price" step="0.01" min="0" required><br>
    <label for="image">Product Image:</label><br>
    <input type="file" id="image" name="image" required accept="image/*"><br>
    <input type="submit" value="Add Product">
</form>

<table>
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price (per hour, ₹)</th>
        <th>Image</th>
    </tr>
    <?php

    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "agriculture_equipment";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Display products
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    
    // Display products table
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["product_id"] . "</td>";
            echo "<td>" . $row["productName"] . "</td>";
            echo "<td>" . $row["quantity"] . "</td>";
            echo "<td>₹" . $row["price"] . "</td>";
            echo "<td><img src='product_images/" . $row["image"] . "' alt='Product Image'></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No products found.</td></tr>";
    }
    
    // Close connection
    $conn->close();
    ?>
</table>

<script>
    function toggleMenu() {
        var sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("menu-open");
    }
</script>

</body>
</html>