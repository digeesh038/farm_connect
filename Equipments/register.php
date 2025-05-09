<?php
// Include the database connection code or any necessary configuration
include 'db_connection.php';

// Initialize variables
$newUsername = $newPassword = $confirmPassword = $fullName = $email = $phoneNumber = $address = "";
$registrationError = "";

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Validate form inputs (add your validation logic here)

    // Sanitize input data to prevent SQL injection
    $newUsername = mysqli_real_escape_string($conn, $_POST['newUsername']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['newPassword']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Perform additional validation if needed

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        $registrationError = "Passwords do not match.";
    } else {
        // Insert user into the database
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $insertUser = "INSERT INTO users (username, password) VALUES ('$newUsername', '$hashedPassword')";

        if ($conn->query($insertUser) === TRUE) {
            // Get the user ID of the newly registered user
            $userId = $conn->insert_id;

            // Insert user details into user_details table
            $insertUserDetails = "INSERT INTO user_details (user_id, full_name, email, phone_number, address, password) 
                                  VALUES ('$userId', '$fullName', '$email', '$phoneNumber', '$address', '$hashedPassword')";

            if ($conn->query($insertUserDetails) !== TRUE) {
                $registrationError = "Error registering user details: " . $conn->error;
            } else {
                // Redirect to login section after successful registration
                header("Location: login.php#login");
                exit();
            }
        } else {
            $registrationError = "Error registering user: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Your Agriculture Equipment Emporium</title>
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
        <h2>Register</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="newUsername" placeholder="Username" required>
            <input type="password" name="newPassword" placeholder="Password" required>
            <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
            <input type="text" name="fullName" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phoneNumber" placeholder="Phone Number">
            <textarea name="address" placeholder="Address"></textarea>
            <button type="submit" name="register">Register</button>
            <p class="error"><?php echo $registrationError; ?></p>
        </form>
        <p>Already have an account? <a href="login.php#login">Login here</a>.</p>
        <p class="back-home"><a href="index.php">Back to Home</a></p>
    </div>

</body>
</html>
