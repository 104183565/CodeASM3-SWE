<?php
session_start(); // Start the session

// Get booking information from session
$bookingSlots = isset($_SESSION['bookingDetails']) ? $_SESSION['bookingDetails'] : [];

// Get customer name and address from session
$customerName = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : 'Guest';

// Calculate total amount
$totalAmount = 0;
foreach ($bookingSlots as $slot) {
  $hoursBooked = isset($slot['time']) ? intval($slot['time']) : 1;
  $hourlyRate = 30; // Assuming the hourly rate is $30
  $slotPrice = $hoursBooked * $hourlyRate;
  $totalAmount += $slotPrice;
}
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

  <div class="container mt-5">
    <h2>Booking Confirmation</h2>
    <p class="success-message">Thank you, <?php echo htmlspecialchars($customerName); ?>! Your booking has been
      successfully completed.</p>

    <h4>Your Booking Details</h4>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Slot Number</th>
          <th>Time</th>
          <th>Car Type</th>
          <th>Parking Spot</th>
          <th>Booking Date</th>
          <th>Price</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookingSlots as $slot): ?>
          <?php
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
            <td>$<?php echo number_format($slotPrice, 2); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h4>Total Amount: $<?php echo number_format($totalAmount, 2); ?></h4>

    <a href="bookslot.php" class="btn btn-primary">Book Another Slot</a>
  </div>
</body>

</html>