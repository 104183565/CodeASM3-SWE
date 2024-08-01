<?php
session_start(); // Start the session

// Initialize error messages
$error_msgs = []; // Use an array to store multiple error messages
$success_msg = '';

include "settings.php";

// Check if the TheParkingslot_User table exists, if not create it
$table_check_query = "SHOW TABLES LIKE 'TheParkingslot_User'";
$table_check_result = $conn->query($table_check_query);

if ($table_check_result->num_rows == 0) {
    $create_table_query = "
    CREATE TABLE TheParkingslot_User (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fname VARCHAR(255) NOT NULL,
        lname VARCHAR(255) NOT NULL,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($create_table_query) !== TRUE) {
        die("Error creating table: " . $conn->error);
    }
}

// Initialize variables to store input values
$fname = $lname = $username = $email = $password = $confirmpassword = '';

// Check POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // Validate input patterns
    if (!preg_match("/^[a-zA-Z ]+$/", $fname)) {
        $error_msgs[] = "Invalid first name.";
    }
    if (!preg_match("/^[a-zA-Z ]+$/", $lname)) {
        $error_msgs[] = "Invalid last name.";
    }
    if (!preg_match("/^[a-zA-Z ]+$/", $username)) {
        $error_msgs[] = "Invalid username.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msgs[] = "Invalid email format.";
    }
    if (strlen($password) < 8) {
        $error_msgs[] = "Password must be at least 8 characters.";
    }
    if ($password !== $confirmpassword) {
        $error_msgs[] = "Passwords do not match.";
    }

    // Check if the username and email already exist
    $username_check_query = "SELECT * FROM TheParkingslot_User WHERE username = '$username'";
    $username_check_result = $conn->query($username_check_query);

    $email_check_query = "SELECT * FROM TheParkingslot_User WHERE email = '$email'";
    $email_check_result = $conn->query($email_check_query);

    if ($username_check_result->num_rows > 0) {
        $error_msgs[] = "Username already exists.";
    }
    if ($email_check_result->num_rows > 0) {
        $error_msgs[] = "Email already exists.";
    }

    // If there are no errors, insert data into the table
    if (empty($error_msgs)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_query = "INSERT INTO TheParkingslot_User (fname, lname, username, password, email) VALUES ('$fname', '$lname', '$username', '$hashed_password', '$email')";

        if ($conn->query($insert_query) !== TRUE) {
            $error_msgs[] = "Error inserting data: " . $conn->error;
        } else {
            $success_msg = "Sign up successful! <a href='login.php'>Click here to login</a>.";
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Team 2">
    <meta name="description" content="Assignment 3 - SWE30003">
    <title>Smart Parking System - Sign Up</title>

    <!-- Bootstrap CSS & JS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Script for CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div id="signupPage">
        <div id="signupForm" class="container">
            <div class="form-container">
                <h2 class="text-center mb-4">Registration</h2>
                <form id="signupForm" class="signupForm" method="post" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" id="firstname" name="fname"
                                value="<?php echo htmlspecialchars($fname ?? '', ENT_QUOTES); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lname"
                                value="<?php echo htmlspecialchars($lname ?? '', ENT_QUOTES); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="username">User Name</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?php echo htmlspecialchars($username ?? '', ENT_QUOTES); ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmpassword">Re-password</label>
                            <input type="password" class="form-control" id="confirmpassword" name="confirmpassword"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES); ?>" required>
                    </div>
                    <div class="form-group">
                        <div id="error-msg" class="text-danger">
                            <?php if (!empty($error_msgs)) {
                                foreach ($error_msgs as $msg) {
                                    echo $msg . "<br>";
                                }
                            } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="success-msg" class="text-success">
                            <?php if ($success_msg)
                                echo $success_msg; ?>
                        </div>
                    </div>

                    <br>
                    <div class="form-group btn-container">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                    <div class="form-group text-center">
                        <p>Have an account? <a href="login.php">Log in here</a></p>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>

</html>