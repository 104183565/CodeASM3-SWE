<?php
session_start(); // Start the session

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
  <div class="container mt-5">
    <h2>Invoice Generator</h2>
    <form id="invoiceForm">
        <div class="col-sm-10">
          <p id="customerName" class="form-control-plaintext">Customer Name: <?php echo htmlspecialchars($customerName); ?></p>
        </div>
   
        <div class="col-sm-10">
          <p id="customerAddress" class="form-control-plaintext">Customer Address: <?php echo htmlspecialchars($customerAddress); ?></p>
        </div>

      <div class="mb-3 row">
        <label for="fee" class="col-sm-2 col-form-label">Fee per hour: $30</label>
      </div>

      <a href="bookslot.php" class="btn btn-secondary">Back</a>
      <button type="submit" class="btn btn-primary">Generate Invoice</button>

    </form>
    <br>
    <div class="invoice-summary" id="invoiceSummary"></div>
  </div>

  <script>
    document.getElementById('invoiceForm').addEventListener('submit', function (event) {
      event.preventDefault();

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
        const hoursBooked = slot.time ? parseInt(slot.time) : 1;
        const price = hoursBooked * hourlyRate;

        invoice.addItem(`Slot Number: ${slot.slot_number}, Slot Time: ${slot.time} hours, Car Type: ${slot.car_type_text}, Parking Spot: ${slot.parking_type}, Booking Date: ${slot.booking_date}`, price);
      });

      // Display invoice summary
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
            ${bookingSlots.map(slot => `
                <tr>
                  <td>${slot.slot_number}</td>
                  <td>${slot.time} hours</td>
                  <td>${slot.car_type_text}</td>
                  <td>${slot.parking_type}</td>
                </tr>
              `).join('')}
          </tbody>
        </table>
      ` : '<p>No booking details available.</p>'}
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
            <th>Price</th>
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
              <td>$${item.price.toFixed(2)}</td>
            </tr>
          `;
          }).join('')}
        </tbody>
        <tfoot>
          <tr class="total-row">    
            <td colspan="6">Total</td>
            <td>$${invoice.calculateTotal().toFixed(2)}</td>
          </tr>
        </tfoot>
      </table>
      <a href="receipt.php" class="btn btn-success">Check Out</a>
      `;
    });

    // Classes for Invoice
    class Item {
      constructor(description, price) {
        this.description = description;
        this.price = price;
      }

      total() {
        return this.price; // Return price, no need for quantity
      }
    }

    class Customer {
      constructor(name, address) {
        this.name = name;
        this.address = address;
      }
    }

    class Invoice {
      constructor(invoiceNumber, customer) {
        this.invoiceNumber = invoiceNumber;
        this.customer = customer;
        this.items = [];
      }

      addItem(description, price) {
        const newItem = new Item(description, price);
        this.items.push(newItem);
      }

      calculateTotal() {
        return this.items.reduce((total, item) => total + item.total(), 0);
      }
    }
  </script>

    <!-- Script for CSS
     <script src="javascript/invoice.js"></script> -->
</body>

</html>
