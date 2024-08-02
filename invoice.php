<!------------------------------------------ Connect, Store, and Retrieve the database ------------------------------------------>
<?php
// Start the session
session_start();

// Get booking information from the session
$bookingSlots = isset($_SESSION['bookingDetails']) ? $_SESSION['bookingDetails'] : [];

// Get customer name and address from the session
$customerName = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : '';
$customerAddress = isset($_SESSION['customer_address']) ? $_SESSION['customer_address'] : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Team 2">
  <meta name="description" content="Assignment 3 - SWE30003">
  <title>Smart Parking System - Invoice</title>

  <!-- Bootstrap CSS & JS -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Script for CSS -->
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <!------------------------------------------ Invoice Generator ------------------------------------------>
  <div class="container mt-5">
    <h2>Invoice</h2>
    <form id="invoiceForm">
      <div class="col-sm-10">
        <!-- Display customer name -->
        <p id="customerName" class="form-control-plaintext">Customer Name:
          <?php echo htmlspecialchars($customerName); ?>
        </p>
      </div>

      <div class="col-sm-10">
        <!-- Display customer address -->
        <p id="customerAddress" class="form-control-plaintext">Customer Address:
          <?php echo htmlspecialchars($customerAddress); ?>
        </p>
      </div>

      <div class="mb-3 row">
        <label for="fee" class="col-sm-2 col-form-label">Fee per hour: $30</label>
      </div>

      <a href="bookslot.php" class="btn btn-secondary">Back</a>
      <button type="submit" class="btn btn-primary">Generate Invoice Summary</button>

    </form>
    <br>
    <div class="invoice-summary" id="invoiceSummary"></div>
  </div>

  <!-- Pass PHP data to JavaScript -->
  <script>
    const bookingSlots = <?php echo json_encode($bookingSlots); ?>;
  </script>

  <!-- Link to custom JS -->
  <script src="javascript/invoice.js"></script>
</body>

</html>