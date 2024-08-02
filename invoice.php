<!------------------------------------------ Connect, Store, and Retrieve the database ------------------------------------------>
<?php
// Start the session
session_start();

// Get booking information from the session
$bookingSlots = isset($_SESSION['bookingDetails']) ? $_SESSION['bookingDetails'] : [];

// Get customer name and address from the session
$customerName = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : 'Guest';
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


  <!------------------------------------------ JS code for Generate Invoice ------------------------------------------>
  <script>
    // Event listener for the invoice form submission
    document.getElementById('invoiceForm').addEventListener('submit', function (event) {
      event.preventDefault(); // Prevent default form submission

      // Get customer details
      const customerName = document.getElementById('customerName').innerText;
      const customerAddress = document.getElementById('customerAddress').innerText;

      // Create customer instance
      const customer = new Customer(customerName, customerAddress);

      // Create invoice instance
      const invoice = new Invoice(Date.now(), customer);

      // Get booking slots from PHP
      const bookingSlots = <?php echo json_encode($bookingSlots); ?>;

      // Calculate price based on number of hours
      const hourlyRate = 30;

      // Add booking slot items to invoice
      bookingSlots.forEach(slot => {
        // Assuming slot.time contains the number of hours booked
        const hoursBooked = slot.time ? parseInt(slot.time) : 1; // Default to 1 hour if not specified
        const price = hoursBooked * hourlyRate;

        // Split time into individual hours if it contains multiple hours
        const times = slot.time.split(',');

        // Add each time as an item to the invoice
        times.forEach(time => {
          invoice.addItem(`Slot Number: ${slot.slot_number}, Slot Time: ${time} hours, Car Type: ${slot.car_type_text}, Parking Spot: ${slot.parking_type}, Booking Date: ${slot.booking_date}`, hourlyRate);
        });
      });

      //------------------------------------------------------ Display invoice summary ------------------------------------------------------//
      const summaryContainer = document.getElementById('invoiceSummary');
      summaryContainer.innerHTML = `
      <h4>Booking Details</h4>
      ${bookingSlots.length ? `
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Slot Number</th>
              <th>Time</th>
              <th>Car Type</th>
              <th>Parking Spot</th>
            </tr>
          </thead>
          <tbody>
            ${bookingSlots.map(slot => {
        const times = slot.time.split(',');
        return times.map(time => `
                <tr>
                  <td>${slot.slot_number}</td>
                  <td>${time} hours</td>
                  <td>${slot.car_type_text}</td>
                  <td>${slot.parking_type}</td>
                </tr>
              `).join(''); // Create table rows for each booking slot
      }).join('')}
          </tbody>
        </table>
      ` : '<p>No booking details available.</p>'} <!-- Show message if no booking details -->
      <h4>Invoice Details</h4>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Number of times booked</th>
            <th>Slot Number</th>
            <th>Slot Time</th>
            <th>Car Type</th>
            <th>Parking Spot</th>
            <th>Booking Date</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          ${invoice.items.map((item, index) => {
        const details = item.description.split(', '); // Split the description to get individual details
        return `
              <tr>
                <td>${index + 1}</td> 
                <td>${details[0].split(': ')[1]}</td>
                <td>${details[1].split(': ')[1]}</td>
                <td>${details[2].split(': ')[1]}</td>
                <td>${details[3].split(': ')[1]}</td>
                <td>${details[4].split(': ')[1]}</td>
                <td>$${(item.price * 3).toFixed(2)}</td> <!-- Total price for each item -->
              </tr>
            `;
      }).join('')}
        </tbody>
        <tfoot>
          <tr class="total-row">    
            <td colspan="6">Total</td>
          <td>$${(invoice.calculateTotal() * 3).toFixed(2)}</td> <!-- Total amount for all items -->
          </tr>
        </tfoot>
      </table>

<div class="text-center">
  <form action="receipt.php" method="POST" class="d-inline" id="checkoutForm">
    <input type="hidden" name="totalAmount" value="${(invoice.calculateTotal() * 3).toFixed(2)}"> <!-- Hidden input for total amount -->
    <button type="submit" class="btn btn-primary btn-lg">Check Out</button>
  </form>
</div>

      `;
      document.getElementById('checkoutForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Show alert for successful checkout
        alert('Checkout successful! Redirecting to receipt page...');

        // Submit the form immediately after the alert
        this.submit(); // Submit the form programmatically
      });
    });

    // Classes for Invoice
    class Item {
      constructor(description, price) {
        this.description = description; // Item description
        this.price = price; // Item price
      }

      total() {
        return this.price; // Return price, no need for quantity
      }
    }

    class Customer {
      constructor(name, address) {
        this.name = name; // Customer name
        this.address = address; // Customer address
      }
    }

    class Invoice {
      constructor(invoiceNumber, customer) {
        this.invoiceNumber = invoiceNumber; // Unique invoice number
        this.customer = customer; // Customer associated with the invoice
        this.items = []; // Array to store invoice items
      }

      // Method to add an item to the invoice
      addItem(description, price) {
        const newItem = new Item(description, price);
        this.items.push(newItem); // Add item to the invoice
      }

      // Method to calculate total invoice amount
      calculateTotal() {
        return this.items.reduce((total, item) => total + item.total(), 0); // Calculate total
      }
    }
  </script>
</body>

</html>