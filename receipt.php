<?php
// Start the session
session_start();

class Customer
{
  private $name;
  private $address;

  public function __construct($name, $address)
  {
    $this->name = $name;
    $this->address = $address;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getAddress()
  {
    return $this->address;
  }
}

class Reservation
{
  private $slotNumber;
  private $time;
  private $carType;
  private $parkingSpot;
  private $bookingDate;

  public function __construct($slotNumber, $time, $carType, $parkingSpot, $bookingDate)
  {
    $this->slotNumber = $slotNumber;
    $this->time = $time;
    $this->carType = $carType;
    $this->parkingSpot = $parkingSpot;
    $this->bookingDate = $bookingDate;
  }

  public function getSlotNumber()
  {
    return $this->slotNumber;
  }

  public function getTime()
  {
    return $this->time;
  }

  public function getCarType()
  {
    return $this->carType;
  }

  public function getParkingSpot()
  {
    return $this->parkingSpot;
  }

  public function getBookingDate()
  {
    return $this->bookingDate;
  }

}

class ReceiptGenerator
{
  private $customer;
  private $reservations;

  public function __construct($customer, $reservations)
  {
    $this->customer = $customer;
    $this->reservations = $reservations;

  }


  public function generateReceipt()
  {

    // Fetch total amount from the POST request
    $totalAmount = isset($_POST['totalAmount']) ? floatval($_POST['totalAmount']) : 0.00;

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
      <div id="receiptPage">
        <div class="container mt-5">
          <h2>Receipt</h2>
          <br>
          <p><strong> Customer name: </strong><?php echo htmlspecialchars($this->customer->getName()); ?></p>
          <p><strong> Customer address: </strong><?php echo htmlspecialchars($this->customer->getAddress()); ?></p>
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
              <?php foreach ($this->reservations as $reservation): ?>
                <tr>
                  <td><?php echo htmlspecialchars($reservation->getSlotNumber()); ?></td>
                  <td><?php echo htmlspecialchars($reservation->getTime()); ?></td>
                  <td><?php echo htmlspecialchars($reservation->getCarType()); ?></td>
                  <td><?php echo htmlspecialchars($reservation->getParkingSpot()); ?></td>
                  <td><?php echo htmlspecialchars($reservation->getBookingDate()); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <!-- Display the total amount for all booked slots -->
          <h4>Total Amount: $<?php echo htmlspecialchars(number_format($totalAmount, 2)); ?></h4>

          <!-- Button to book another slot -->
          <a href="bookslot.php" class="btn btn-primary">Book Another Slot</a>
          <br> <br>
          <!-- Confirmation message for successful booking -->
          <p class="success-message">Thank you, <?php echo htmlspecialchars($this->customer->getName()); ?>! Your booking
            has
            been
            successfully completed.</p>

        </div>
      </div>

    </body>

    </html>
    <?php
  }
}

// Fetch booking and customer details from session
$customerName = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : '';
$customerAddress = isset($_SESSION['customer_address']) ? $_SESSION['customer_address'] : '';
$bookingDetails = isset($_SESSION['bookingDetails']) ? $_SESSION['bookingDetails'] : [];


// Create Customer object
$customer = new Customer($customerName, $customerAddress);

// Create Reservation objects and store them in an array
$reservations = [];
foreach ($bookingDetails as $detail) {
  $reservations[] = new Reservation(
    $detail['slot_number'],
    $detail['time'],
    $detail['car_type_text'],
    $detail['parking_type'],
    $detail['booking_date']
  );
}

// Generate the receipt
$receiptGenerator = new ReceiptGenerator($customer, $reservations);
$receiptGenerator->generateReceipt();
?>