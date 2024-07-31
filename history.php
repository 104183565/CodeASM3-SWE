<?php
session_start(); // Bắt đầu phiên làm việc

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

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

// Lấy user_id từ session
$user_id = $_SESSION['user_id'];

// Truy vấn để lấy thông tin đặt chỗ của người dùng
$sql = "SELECT * FROM TheParkingslot_Information WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Team 2">
    <meta name="description" content="Assignment 3 - SWE30003">
    <title>Smart Parking System - History</title>

    <!-- Bootstrap CSS & JS  -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <style>
        #historyPage .table-container {
            max-width: 800px;
            margin: auto;
            margin-top: 50px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }

        #historyPage .table-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
    </style>
</head>

<body>
    <div id="historyPage">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="home.php">Smart Parking System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="bookslot.php">Book Slot</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Log out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="table-container">
            <h2>Booking History</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Number of Slots</th>
                        <th>Vehicle Type</th>
                        <th>Rental Hours</th>
                        <th>Booking Date</th>
                        <th>Total Price (VND)</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Hiển thị dữ liệu của mỗi hàng
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['number_of_slots']}</td>
                                <td>{$row['vehicle_type']}</td>
                                <td>{$row['rental_hours']}</td>
                                <td>{$row['booking_date']}</td>
                                <td>{$row['total_price']}</td>
                                <td>{$row['created_at']}</td>
                              </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No booking history found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
  

    <?php
    // Đóng kết nối
    $conn->close();
    ?>
      </div>
</body>

</html>