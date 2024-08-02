<!------------------------------------------ Connect, Store, and Retrieve the database ------------------------------------------>
<?php
// Start the session
session_start();

// Get booking information from session
$bookingSlots = isset($_SESSION['bookingDetails']) ? $_SESSION['bookingDetails'] : [];

// Get customer name and address from session
$customerName = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : 'Guest';


// Get value from form if available
$totalAmount = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Team 2">
  <meta name="description" content="Assignment 3 - SWE30003">
  <title>Smart Parking System - Receipt</title>

  <!-- Bootstrap CSS & JS -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Script for CSS -->
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <!------------------------------------------ Navigation bar ------------------------------------------>
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
            <a class="nav-link" href="bookslot.php">Book Slot</a>
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

  <!------------------------------------------ Print out the data for Receipt ------------------------------------------>
  <div class="container mt-5">
    <h2>Receipt</h2>
<br>
    <h4>Your Booking Details: <?php echo htmlspecialchars($customerName); ?></h4>
    <!-- Table displaying booking details -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Slot Number</th>
          <th>Time</th>
          <th>Car Type</th>
          <th>Parking Spot</th>
          <th>Booking Date</th>
        </tr>
      </thead>
      <tbody>
        <!-- Loop through each slot in the booking list and display the information -->
        <?php foreach ($bookingSlots as $slot): ?>
          <?php
          // Calculate the price for the slot based on the booked time
          $hoursBooked = isset($slot['time']) ? intval($slot['time']) : 1;
          $hourlyRate = 30;
          $slotPrice = $hoursBooked * $hourlyRate;
          ?>
          <tr>
            <td><?php echo htmlspecialchars($slot['slot_number']); ?></td>
            <td><?php echo htmlspecialchars($slot['time']); ?></td>
            <td><?php echo htmlspecialchars($slot['car_type_text']); ?></td>
            <td><?php echo htmlspecialchars($slot['parking_type']); ?></td>
            <td><?php echo htmlspecialchars($slot['booking_date']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Display the total amount for all booked slots -->
    <h4>Total Amount: $<?php echo number_format($totalAmount, 2); ?></h4>
    <!-- Button to book another slot -->
    <a href="bookslot.php" class="btn btn-primary">Book Another Slot</a>
    <br> <br>
    <!-- Confirmation message for successful booking -->
    <p class="success-message">Thank you, <?php echo htmlspecialchars($customerName); ?>! Your booking has been
      successfully completed.</p>
  </div>

</body>

</html>