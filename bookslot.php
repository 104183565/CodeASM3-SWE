<?php
session_start(); // Bắt đầu phiên làm việc
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Team 2">
    <meta name="description" content="Assignment 3 - SWE30003">
    <title>Smart Parking System - Book Slot</title>

    <!-- Bootstrap CSS & JS  -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Script for CSS -->
    <link rel="stylesheet" href="css/style.css">

    <style>

    </style>
</head>

<body>
    <div id="bookslotPage">
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
                            <a class="nav-link" href="history.php">History</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Log out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-5">
            <div class="form-container"> <!-- Thêm class form-container -->
                <h2 class="text-center mb-4">Book a Parking Slot</h2>
                <form action="bookslot.php" method="post" class="mt-4">
                    <div class="mb-3">
                        <label for="numberOfSlots" class="form-label">Number of Slots:</label>
                        <input type="number" id="numberOfSlots" name="numberOfSlots" class="form-control"
                            placeholder="Please enter your number of slot" required oninput="updateTotalPrice()">
                    </div>
                    <div class="mb-3">
                        <label for="carType" class="form-label">Vehicle Type:</label>
                        <select id="carType" name="carType" class="form-select" required>
                            <option value="" disabled selected>Select a vehicle type</option>
                            <option value="SUV">SUV</option>
                            <option value="sedan">Sedan</option>
                            <option value="supercar">Super Car</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="hoursToRent" class="form-label">Rental Hours:</label>
                        <input type="number" id="hoursToRent" name="hoursToRent" class="form-control"
                            placeholder="Please enter your rental hours" required oninput="updateTotalPrice()">
                    </div>
                    <div class="mb-3">
                        <label for="dateOfBooking" class="form-label">Booking Date:</label>
                        <input type="date" id="dateOfBooking" name="dateOfBooking" class="form-control"
                            placeholder="Please enter your booking date" required>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label class="me-2 mb-0">Total Price (30K/hour):</label>
                        <p id="calculatedPrice" class="mb-0">0 VND</p>
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn btn-success">Book Slot</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateTotalPrice() {
            const numberOfSlots = document.getElementById('numberOfSlots').value;
            const hoursToRent = document.getElementById('hoursToRent').value;
            const pricePerHour = 30000; // giá mỗi giờ

            // Tính tổng giá
            const totalPrice = numberOfSlots * hoursToRent * pricePerHour;

            // Cập nhật giá vào phần tử hiển thị
            document.getElementById('calculatedPrice').innerText = totalPrice + " VND";
        }
    </script>

    <?php
    // Kiểm tra dữ liệu từ POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        // Tạo bảng nếu chưa tồn tại
        $create_table_query = "
        CREATE TABLE IF NOT EXISTS TheParkingslot_Information (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            number_of_slots INT NOT NULL,
            vehicle_type VARCHAR(50) NOT NULL,
            rental_hours INT NOT NULL,
            booking_date DATE NOT NULL,
            total_price INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES TheParkingslot_User(id) ON DELETE CASCADE
        )";

        if ($conn->query($create_table_query) !== TRUE) {
            die("Error creating table: " . $conn->error);
        }

        // Lấy user_id từ session
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if ($user_id === null) {
            die("You must be logged in to book a slot."); // Hiển thị thông báo nếu chưa đăng nhập
        }

        // Lấy dữ liệu từ form
        $numberOfSlots = $_POST['numberOfSlots'];
        $carType = $_POST['carType'];
        $hoursToRent = $_POST['hoursToRent'];
        $dateOfBooking = $_POST['dateOfBooking'];
        $totalPrice = $hoursToRent * $numberOfSlots * 30000; // Tính tổng giá
    
        // Chuẩn bị câu truy vấn để lưu thông tin
        $sql = "INSERT INTO TheParkingslot_Information (user_id, number_of_slots, vehicle_type, rental_hours, booking_date, total_price)
                VALUES ('$user_id', '$numberOfSlots', '$carType', '$hoursToRent', '$dateOfBooking', '$totalPrice')";

        // Sau khi bạn đã lưu thông tin hóa đơn
        if ($conn->query($sql) === TRUE) {
            // Lưu thông tin vào session
            $_SESSION['invoice'] = [
                'user_id' => $user_id,
                'number_of_slots' => $numberOfSlots,
                'vehicle_type' => $carType,
                'rental_hours' => $hoursToRent,
                'booking_date' => $dateOfBooking,
                'total_price' => $totalPrice
            ];

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Đóng kết nối
        $conn->close();
    }
    ?>
</body>

</html>