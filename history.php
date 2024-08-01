<!------------------------------------------ Connect, Store, and Retrieve the database ------------------------------------------>
<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // If not logged in, redirect to the login page
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

// Connect to the database
include "settings.php";

// Get user_id from the session
$user_id = $_SESSION['user_id'];

// Query booking history
$query = "SELECT * FROM TheParkingslot_Information WHERE user_id = $user_id";
$result = $conn->query($query);
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

    <!-- Script for CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!------------------------------------------ Navigation bar ------------------------------------------>
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

        <!------------------------------------------ Booking History ------------------------------------------>
        <div class="table-container">
            <h2>Booking History</h2>
            <h4 class="text-center mb-4">User name: <?php echo htmlspecialchars($username); ?></h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Number of times booked</th>
                        <th>Parking Type</th>
                        <th>Address</th>
                        <th>Vehicle Type</th>
                        <th>Slot Time</th>
                        <th>Booking Date</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!------------------------------------------ Retrieve the data from the database ------------------------------------------>
                    <?php
                    if ($result->num_rows > 0) {
                        // Hourly parking rate
                        $hourlyRate = 30;
                        // Counter for order number
                        $counter = 1;

                        // Display each row of data
                        while ($row = $result->fetch_assoc()) {
                            // Calculate total cost
                            $slotTime = isset($row['slot_time']) ? intval($row['slot_time']) : 1;
                            $totalCost = $slotTime * $hourlyRate;

                            echo "<tr>
                                <td>" . $counter++ . "</td> <!-- Order number -->
                                <td>" . htmlspecialchars($row['parking_type']) . "</td>
                                <td>" . htmlspecialchars($row['address']) . "</td>
                                <td>" . htmlspecialchars($row['car_type']) . "</td>
                                <td>" . htmlspecialchars($row['slot_time']) . "</td>
                                <td>" . htmlspecialchars($row['booking_date']) . "</td>
                                <td>$" . number_format($totalCost, 2) . "</td> 
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No bookings found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>