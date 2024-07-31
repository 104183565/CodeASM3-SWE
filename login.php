<?php
session_start(); // Bắt đầu phiên làm việc

// Khởi tạo biến thông báo
$error_msg = '';
$success_msg = '';

// Khởi tạo biến lưu trữ thông tin đăng nhập
$username = '';

// Truy cập vào database
$host = "localhost";
$user = "root";
$pswd = "";
$dbnm = "test";

// Mở kết nối
$conn = new mysqli($host, $user, $pswd, $dbnm);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kiểm tra dữ liệu từ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn để lấy mật khẩu và user_id từ cơ sở dữ liệu
    $query = "SELECT id, password FROM TheParkingslot_User WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // So sánh mật khẩu đã mã hóa
        if (password_verify($password, $row['password'])) {
            // Lưu user_id vào session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['logged_in'] = true;
            $success_msg = "Login successful! Redirecting to home page...";
            // Chuyển hướng tới trang home.php
            header("Location: home.php");
            exit();
        } else {
            $error_msg = "Invalid password. Please try again.";
        }
    } else {
        $error_msg = "User not found. Please check your username.";
    }
}

// Đóng kết nối
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

    <!-- Bootstrap CSS & JS  -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

     <!-- Script for CSS -->
     <link rel="stylesheet" href="css/style.css">

    <style>

    </style>
</head>

<body>
    
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