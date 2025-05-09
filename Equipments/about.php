<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Agriculture Equipment Rental Management System</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: url('images/agri-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: rgba(0, 0, 0, 0.7);
            text-align: center;
            padding: 2em 0;
        }

        h1 {
            font-family: 'Cinzel', serif;
            font-size: 3em;
            margin: 0;
        }

        nav {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 1em 0;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 1.2em;
            transition: all 0.3s ease;
        }

        nav ul li a:hover,
        nav ul li a:focus {
            color: #ffcc00;
        }

        main {
            flex: 1;
            padding: 20px;
            text-align: center;
        }

        section {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        section h2 {
            font-size: 2.2em;
            margin-bottom: 20px;
        }

        section p {
            font-size: 1.3em;
            line-height: 1.6;
            color: #ddd;
        }

        footer {
            background-color: rgba(0, 0, 0, 0.7);
            text-align: center;
            padding: 1em 0;
        }
    </style>
</head>
<body>

    <header>
        <h1>Agriculture Equipment Rental Management System</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">User Login</a></li>
            <li><a href="admin_login.php">Admin Login</a></li>
        </ul>
    </nav>

    <main>
        <section>
            <h2>Agriculture Equipment Rental Management System</h2>
            <p>Agricultural equipment plays a pivotal role in modern farming, significantly enhancing efficiency and productivity. Tractors stand as the workhorse of agriculture, facilitating tasks like plowing, planting, and harvesting. Various types of plows, including moldboard and disc plows, help prepare the soil for cultivation. Harvesters, such as combine harvesters and cotton pickers, streamline the gathering and processing of crops. Planters and seeders contribute to precision agriculture by ensuring optimal seed placement. Sprayers, equipped with advanced technology, efficiently apply pesticides, herbicides, and fertilizers. Irrigation equipment, ranging from drip systems to center pivot setups, plays a vital role in providing crops with essential water resources. Cultivators aid in weed control and soil management. Livestock equipment, like feeding and milking machinery, supports integrated farming practices. The incorporation of GPS technology further enhances precision and accuracy in agricultural operations. In essence, these diverse agricultural tools and machines collectively form a robust framework that empowers farmers to navigate the complexities of modern farming and meet the growing demand for food production.</p>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Agriculture Equipment Rental Management System</p>
    </footer>

</body>
</html>
