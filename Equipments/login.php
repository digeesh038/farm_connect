<?php
// Database connection details
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

// Initialize variables for login
$username = "";
$password = "";
$error = "";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query the database to check credentials
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row["password"])) {
            // Check if the user is an admin
            if ($row["is_admin"] == 1) {
                header("Location: admin_dashboard.php"); // Redirect to admin page
                exit();
            } else {
                header("Location: user_dashboard.php"); // Redirect to user page
                exit();
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $newUsername = $_POST["newUsername"];
    $newPassword = $_POST["newPassword"];
    $confirmPassword = $_POST["confirmPassword"];
    $fullName = $_POST["fullName"];
    $email = $_POST["email"];
    $phoneNumber = $_POST["phoneNumber"];
    $address = $_POST["address"];

    // Validate passwords match
    if ($newPassword != $confirmPassword) {
        $registrationError = "Passwords do not match.";
    } else {
        // Check if the username is already taken
        $checkExistingUser = "SELECT * FROM users WHERE username = '$newUsername'";
        $result = $conn->query($checkExistingUser);

        if ($result->num_rows > 0) {
            $registrationError = "Username already taken. Please choose another.";
        } else {
            // Insert new user into the database
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $insertUser = "INSERT INTO users (username, password) VALUES ('$newUsername', '$hashedPassword')";

            if ($conn->query($insertUser) === TRUE) {
                // Get the user ID of the newly registered user
                $userId = $conn->insert_id;

                // Insert user details into user_details table
                $insertUserDetails = "INSERT INTO user_details (user_id, full_name, email, phone_number, address) 
                                      VALUES ('$userId', '$fullName', '$email', '$phoneNumber', '$address')";

                if ($conn->query($insertUserDetails) !== TRUE) {
                    $registrationError = "Error registering user details: " . $conn->error;
                }

                // Redirect to login section after successful registration
                header("Location: login.php#login");
                exit();
            } else {
                $registrationError = "Error registering user: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register - Your Agriculture Equipment Emporium</title>
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

    <div id="login" style="display: block;">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>#login" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
            <p class="error"><?php echo $error; ?></p>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>

        <p class="back-home"><a href="index.php">Back to Home</a></p>
    </div>

    <script>
        function showTab(tabId) {
            document.getElementById('login').style.display = 'none';
            document.getElementById('register').style.display = 'none';
            document.getElementById(tabId).style.display = 'block';
        }
    </script>

</body>
</html>
