<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Your Agriculture Equipment Rental Management System</title>
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

        table {
            width: 80%;
            max-width: 600px;
            margin-top: 100px;
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

        .user-details {
            margin-top: 20px;
            background-color: #f2f2f2;
            border-radius: 10px;
            padding: 10px;
            display: none;
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
    <h1>Manage Users</h1>
</header>

<nav id="sidebar" class="menu-open">
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_products.php">Manage Products</a></li>
        <li><a href="order_details.php">Order Details</a></li>
        <li><a href="manage_users.php" class="active">Manage Users</a></li>
        <li><a href="admin_logout.php">Logout</a></li>
    </ul>
</nav>

<div class="menu-toggle" id="menu-toggle" onclick="toggleMenu()"></div>

<table>
    <tr>
        <th>Full Name</th>
        <th>Email</th>
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

    // Fetch user details from the database
    $sql = "SELECT full_name, email FROM user_details";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr class='user-row'><td>" . $row["full_name"] . "</td><td>" . $row["email"] . "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='2'>No users found.</td></tr>";
    }
    ?>
</table>

<div class="user-details">
    <h2>User Details</h2>
    <p><strong>Full Name:</strong> <span id="full-name"></span></p>
    <p><strong>Email:</strong> <span id="email"></span></p>
</div>

<script>
    // Add click event listener to user rows
    const userRows = document.querySelectorAll('.user-row');
    const userDetails = document.querySelector('.user-details');
    const fullNameSpan = document.getElementById('full-name');
    const emailSpan = document.getElementById('email');

    userRows.forEach(row => {
        row.addEventListener('click', () => {
            const fullName = row.cells[0].innerText;
            const email = row.cells[1].innerText;
            fullNameSpan.innerText = fullName;
            emailSpan.innerText = email;
            userDetails.style.display = 'block';
        });
    });

    function toggleMenu() {
        var sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("menu-open");
    }
</script>

</body>
</html>
