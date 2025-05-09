<?php
// Include the database connection code or any necessary configuration
include 'db_connection.php';

// Initialize variables
$adminUsername = $adminPassword = "";
$loginError = "";

// Handle admin login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_login'])) {
    // Get admin input values
    $adminUsername = $_POST['adminUsername'];
    $adminPassword = $_POST['adminPassword'];

    // Validate admin credentials
    $query = "SELECT * FROM admins WHERE username = '$adminUsername'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $adminData = $result->fetch_assoc();
        $hashedPassword = $adminData['password'];

        // Verify password
        if ($adminPassword === "admin123") { // For demonstration purposes, directly comparing password
            // Admin login successful, redirect to admin dashboard or any admin page
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $loginError = "Invalid password";
        }
    } else {
        $loginError = "Admin not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Your Agriculture Equipment Emporium</title>
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
            align-items: center;
            justify-content: center;
        }

        div {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 20px;
            max-width: 400px;
        }

        input {
            margin: 10px 0;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            background-color: #ffcc00;
            color: #000;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #ffa500;
        }

        .error {
            color: #ff0000;
            margin-top: 10px;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .back-home {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div>
        <h2>Admin Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="adminUsername" placeholder="Admin Username" value="admin" required>
            <input type="password" name="adminPassword" placeholder="Admin Password" value="admin123" required>
            <button type="submit" name="admin_login">Login</button>
            <p class="error"><?php echo $loginError; ?></p>
        </form>
        <p class="back-home"><a href="index.php">Back to Home</a></p>
    </div>

</body>
</html>
