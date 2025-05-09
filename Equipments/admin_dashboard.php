<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Your Agriculture Equipment Rental Management System</title>
    <style>
        /* Inline CSS */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
            overflow-x: hidden; /* Prevent horizontal scrolling */
            margin-top: 0; /* Remove default margin */
            margin-bottom: 0; /* Remove default margin */
        }

        header {
            background-color: rgba(0, 0, 0, 0.7);
            text-align: center;
            padding: 1em 0;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        h1 {
            font-family: 'Cinzel', serif;
            font-size: 2.5em;
            margin: 0;
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

        main {
            padding: 20px;
            color: #000;
            margin-left: 200px;
        }

        footer {
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            text-align: center;
            padding: 1em 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1000;
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

        @media (max-width: 768px) {
            main {
                margin-left: 0;
            }

            nav {
                width: 100%;
                height: auto;
                position: relative;
                margin-bottom: 20px;
            }

            .menu-toggle {
                display: block;
            }

            .menu-open {
                transform: translateX(0);
            }
        }

        /* Slideshow Styles */
        #slideshow-container {
            position: absolute;
            top: 0;/* Adjust to match the header height */
            bottom: 0;
            left: 0;
            right: 0;
            overflow: hidden;
        }

        #slideshow {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to the Admin Dashboard</h1>
</header>

<nav id="sidebar" class="menu-open">
    <ul>
        <li><a href="admin_dashboard.php" class="active">Dashboard</a></li>
        <li><a href="manage_products.php">Manage Products</a></li>
        <li><a href="order_details.php">Order Details</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="admin_logout.php">Logout</a></li>
    </ul>
</nav>

<div class="menu-toggle" id="menu-toggle"></div>

<main>
    <!-- No content for the main section -->
</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Agriculture Equipment Rental Management System</p>
</footer>

<!-- Slideshow Container -->
<div id="slideshow-container">
    <img id="slideshow" src="images/admin_dashboard.jpg" alt="Slideshow Image">
</div>

<!-- Slideshow Script -->
<script>
    const images = ['images/admin_dashboard.jpg', 'images/admin1.jpg', 'images/admin2.jpg','images/admin3.jpg']; // Add paths to your images
    let currentIndex = 0;
    const slideshow = document.getElementById('slideshow');

    function changeImage() {
        slideshow.src = images[currentIndex];
        currentIndex = (currentIndex + 1) % images.length;
        setTimeout(changeImage, 3000); // Change image every 5 seconds
    }

    changeImage();

    // Toggle menu function
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('menu-open');
    });
</script>

</body>
</html>
