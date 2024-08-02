<!------------------------------------------ Connect, Store, and Retrieve the database ------------------------------------------>
<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}

// Get the username from the session
$username = $_SESSION['username'];

// Connect to the database using the settings file
include "settings.php";

// Get user_id from the session for querying user-specific data
$user_id = $_SESSION['user_id'];

// Query booking history for the logged-in user
$query = "SELECT * FROM TheParkingslot_Information WHERE user_id = $user_id";
$result = $conn->query($query); // Execute the query
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Team 2">
    <meta name="description" content="Assignment 3 - SWE30003">
    <title>Smart Parking System - History</title>

    <!-- Include Bootstrap CSS & JS for styling and responsiveness -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Link to custom CSS for additional styling -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-image: url('image/background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
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
                            <a class="nav-link" href="home.php">Home</a> <!-- Link to home page -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="bookslot.php">Book Slot</a> <!-- Link to book a parking slot -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Log out</a> <!-- Link to logout -->
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!------------------------------------------ Booking History ------------------------------------------>
        <div class="table-container">
            <h2>Booking History</h2> <!-- Page title -->
            <h4 class="text-center mb-4">User name: <?php echo htmlspecialchars($username); ?></h4>
            <!-- Display logged-in username -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Number sesion</th> <!-- Column for booking count -->
                        <th>Slot number</th> <!-- Column for slot number -->
                        <th>Slot Time</th> <!-- Column for slot time -->
                        <th>Vehicle Type</th> <!-- Column for vehicle type -->
                        <th>Parking Type</th> <!-- Column for parking type -->
                        <th>Address</th> <!-- Column for user address -->
                        <th>Booking Date</th> <!-- Column for booking date -->
                    </tr>
                </thead>
                <tbody>
                    <!------------------------------------------ Retrieve the data from the database ------------------------------------------>
                    <?php
                    if ($result->num_rows > 0) { // Check if there are any bookings
                        // Counter for order number
                        $counter = 1;

                        // Hourly rate (not used in this part, but could be for future calculations)
                        $hourlyRate = 30;

                        // Display each row of booking data
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . $counter++ . "</td> <!-- Display booking number -->
                                <td>" . htmlspecialchars($row['slot_number']) . "</td> <!-- Display slot number -->
                                <td>" . htmlspecialchars($row['slot_time']) . "</td> <!-- Display slot time -->
                                <td>" . htmlspecialchars($row['car_type_text']) . "</td> <!-- Display vehicle type -->
                                <td>" . htmlspecialchars($row['parking_type']) . "</td> <!-- Display parking type -->
                                <td>" . htmlspecialchars($row['address']) . "</td> <!-- Display user address -->
                                <td>" . htmlspecialchars($row['booking_date']) . "</td> <!-- Display booking date -->
                            </tr>";
                        }
                    } else {
                        // If no bookings found, display a message
                        echo "<tr><td colspan='8' class='text-center'>No bookings found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>