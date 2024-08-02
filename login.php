<!------------------------------------------ Connect, Store, and Retrieve the database ------------------------------------------>
<?php
session_start(); // Start the session

// Initialize message variables
$error_msg = '';
$success_msg = '';

// Initialize variable to store login information
$username = '';

include "settings.php";

// Check data from POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to get the password and user_id from the database
    $query = "SELECT id, password FROM TheParkingslot_User WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Compare hashed password
        if (password_verify($password, $row['password'])) {
            // Save user_id to session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $success_msg = "Login successful! Redirecting to home page...";
            // Redirect to home.php
            header("Location: home.php");
            exit();
        } else {
            $error_msg = "Invalid password. Please try again.";
        }
    } else {
        $error_msg = "User not found. Please check your username.";
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Team 2">
    <meta name="description" content="Assignment 3 - SWE30003">
    <title>Smart Parking System - Login</title>

    <!-- Bootstrap CSS & JS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Script for CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!------------------------------------------ Login Form ------------------------------------------>
    <div id="loginPage">
        <div class="form-container">
            <form id="loginForm" class="loginForm" method="post" action="">
                <h2 class="text-center mb-4">Login</h2>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" class="form-control" name="username"
                        value="<?php echo htmlspecialchars($username, ENT_QUOTES); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" name="password" required>
                </div>
                <div class="form-group text-center">
                    <div id="error-msg" class="text-danger">
                        <?php if ($error_msg)
                            echo $error_msg; ?>
                    </div>
                    <div id="success-msg" class="text-success">
                        <?php if ($success_msg)
                            echo $success_msg; ?>
                    </div>
                </div>
                <br>
                <div class="form-group btn-container">
                    <button type="submit" class="btn btn-success">Log In</button>
                </div>
                <div class="form-group text-center mt-2">
                    <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
                </div>
            </form>
        </div>
    </div>

</body>

</html>