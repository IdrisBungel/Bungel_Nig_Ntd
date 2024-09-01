<?php
require_once 'dbcon.php';
include 'admin_session_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>

<script src="js/jquery.min.js"></script>

  <script>
  function toggleDarkMode() {
  var body = document.querySelector('body');
  body.classList.toggle('dark-mode');
}
</script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BUNGEL Tanker Sales</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/adminlte.min.css">
  <link rel="stylesheet" href="css/customCss.css">
   <link rel="stylesheet" href="css/darkmode.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
   <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">

 <link rel="stylesheet" href="plugins/jquery-ui/jquery-ui.css"> 
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">


<style>
  #tankerSalesTable_wrapper {
    overflow-x: hidden !important;
  }
  #paymentTableT td:nth-child(4) {
    white-space: nowrap;
  }
  .expanded-row {
    background-color: #BDBEBE; /* Set the desired background color for the expanded rows */
  }
    .select2-container .select2-selection {
    height: calc(1.5em + 0.75rem + 2px);
  
  }
</style>
</head>     

     <?php include 'header.php';?>
<div class="content-wrapper mt-5">
      <section class="content-header">
     <div class="container-fluid">
  <div class="row mt-2">
    <div class="col">
      <h1 class="">Tanker Sales</h1>
    </div>
  </div>
</div>


      </section>

  <section class="content">
  <div class="row justify-content-center">
  <div class="col-lg-12 col-md-8 col-sm-10">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
  <table id="tankerSalesTable" class="table table-bordered  table">
  <thead class="bg-light">
    <tr>
      <th>ID</th>
      <th>Pickup ID</th>
      <th>Driver Name</th>
      <th>Tanker Number</th>
      <th>Fuel Type</th>
      <th>Return Date</th>
      <th>Capacity</th>
      <th>Available Litre</th>
      <th>Depot</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>

</tbody>
</table>
<!-- Fuel Sales Details Table -->
<div class="container mt-4" id="fuelSalesDetailsContainer">
  <!-- Small table for fuel sales details will be dynamically added here -->
</div>
<div class="modal fade" id="addPaymentModal" role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addPaymentForm">
          <div class="form-group">
            <label for="paymentAmount">Payment Amount</label>
            <input type="text" class="form-control" id="paymentAmount" onkeyup="formatAmount(this)" name="paymentAmount">
          </div>
          <div class="form-group" id="bankNameField">
            <label for="bankName">Bank Name</label>
            <select class="form-control select2" style="width: 100%;" id="bankName" name="bankName">
              <option disabled selected value="">Select Bank</option>
              <option value="Access Bank">Access Bank</option>
              <option value="Zenith Bank">Zenith Bank</option>
              <option value="Guaranty Trust Bank">Guaranty Trust Bank</option>
              <option value="United Bank for Africa">United Bank for Africa (UBA)</option>
              <option value="First Bank of Nigeria">First Bank of Nigeria</option>
              <option value="Ecobank Nigeria">Ecobank Nigeria</option>
              <option value="Stanbic IBTC Bank">Stanbic IBTC Bank</option>
              <option value="Fidelity Bank">Fidelity Bank</option>
              <option value="Union Bank of Nigeria">Union Bank of Nigeria</option>
              <option value="Sterling Bank">Sterling Bank</option>
              <option value="Keystone Bank">Keystone Bank</option>
              <option value="Polaris Bank">Polaris Bank</option>
              <option value="Wema Bank">Wema Bank</option>
              <option value="Heritage Bank">Heritage Bank</option>
              <option value="Unity Bank">Unity Bank</option>
              <option value="Jaiz Bank">Jaiz Bank</option>
              <option value="Providus Bank">Providus Bank</option>
            </select>
          </div>
          <div class="form-group">
            <label for="paymentDate">Payment Date</label>
            <input type="text" class="form-control" id="paymentDate" name="paymentDate">
          </div>

          <div class="form-group">
            <label for="CustomerName">Customer Name</label>
            <input type="text" class="form-control" readonly id="customerName" name="customerName">
          </div>

          <input type="hidden" id="fuelSalesID" name="fuelSalesID">
          <input type="hidden" id="tankerSalesID" name="tankerSalesID">
          <input type="hidden" id="customerNameId" name="customerID" required>
            <!-- Total Amount and Balance fields -->
          <div class="form-group">
            <label for="totalAmount">Total Amount</label>
            <input type="text" class="form-control" id="totalAmount" name="totalAmount" readonly>
          </div>
          <div class="form-group">
            <label for="balance">Balance</label>
            <input type="text" class="form-control" id="balance" name="balance" readonly>
          </div>
          <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Payment</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="paymentModalLabel">Payment Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="paymentTableT" class="table table-bordered table-condensed">
          <thead class="bg-light">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Bank Name</th>
            </tr>
          </thead>
          <tbody>
            <!-- Payment details will be dynamically added here -->
          </tbody>
        </table>
      </div>
          <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary"  onclick="openAddPaymentModal()"> <i class="fas fa-plus"></i> Add Payment</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addFuelSalesModal" role="dialog" aria-labelledby="addFuelSalesModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="addFuelSalesModalLabel">Add Fuel Sales</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Add Fuel Sales Form -->
        <form id="addFuelSalesForm">
          <div class="form-group">
            <label for="CustomerName">Customer Name</label>
         <select class="form-control" id="CustomerName" name="customerName" style="width: 100%;" required>
  <option value="" disabled selected >Select Customer Name</option>
  <?php
  $query = "SELECT CustomerID, CustomerName FROM Customers ";
  $result = mysqli_query($connect, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $CustomerName = $row['CustomerName'];
      $CustomerId = $row['CustomerID'];
      echo "<option value=\"$CustomerName\" data-customer-id=\"$CustomerId\">$CustomerName</option>";
    }
  }
  ?>
</select>

          </div>
          <input type="hidden" id="customerIds" name="customerId">
          <input type="hidden" id="tankerId" name="tankerID">
          <div class="form-group">
            <label for="litrePrice">Litre Price</label>
            <input type="text" onkeyup="formatAmount(this)" class="form-control" id="litrePrice" name="litrePrice">
          </div>
          <div class="form-group">
            <label for="litreAmount">Litre Amount</label>
            <input type="text" class="form-control" id="litreAmount" onkeyup="formatAmount(this)" name="litreAmount">
          </div>
          <div class="form-group">
            <label for="salesDate">Sales Date</label>
            <input type="text" class="form-control" id="salesDate" name="salesDate">
          </div>
          <div class="form-group">
            <label for="fuelType">Fuel Type</label>
            <input type="text" readonly class="form-control" id="fuelType" name="fuelType">
          </div>
  
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" id="addFuelSalesBtn">Add Fuel Sales</button>
      </div>
    </div>
  </div>
</div>


<!-- Edit Date Modal -->
<div class="modal fade" id="editDateModal" tabindex="-1" role="dialog" aria-labelledby="editDateModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editDateModalLabel">Edit Return Date</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editTankerID"> <!-- Add this hidden input field for the tanker ID -->
        <label for="editDateInput">Select Date:</label>
        <input type="text" class="form-control" id="editDateInput">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveDateBtn">Save</button>
      </div>
    </div>
  </div>
</div>



<script>
  // Event handler for selecting a customer name
  $('#CustomerName').on('change', function() {
    var selectedOption = $(this).find(':selected');
    var customerId = selectedOption.data('customer-id');
    $('#customerIds').val(customerId);
  });


var selectedSalesID;
var selectedTankerID;
var selectedCustomerID;
var selectedCustomerName;
var selectedBalance;
var selectedTotalAmount;

$(document).on('click', '.view-payments-btn', function(event) {
  event.preventDefault();
   selectedSalesID = $(this).data('sales-id'); // Store the SalesID in the variable
  selectedTankerID = $(this).data('tanker-id'); // Store the TankerID in the variable
  selectedCustomerID = $(this).data('customer-id'); // Store the CustomerID in the variable
  selectedCustomerName = $(this).data('customer-name'); // Store the Customer Name in the variable
  selectedBalance = $(this).data('balance'); // Store the Balance in the variable
  selectedTotalAmount = $(this).data('total-amount'); // Store the Total Amount in the variable

  $('#paymentModal').modal('show'); // Show the View Payments modal
});

// Function to open the Add Payment modal and set the SalesID, TankerID, CustomerID, and Customer Name in the form
function openAddPaymentModal() {
  $('#fuelSalesID').val(selectedSalesID); // Set the SalesID in the form
  $('#tankerSalesID').val(selectedTankerID); // Set the TankerID in the form
  $('#customerNameId').val(selectedCustomerID); // Set the CustomerID in the form
  $('#customerName').val(selectedCustomerName); // Set the Customer Name in the form
   $('#totalAmount').val('₦' + selectedTotalAmount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
  $('#balance').val('₦' + selectedBalance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
   $('#paymentAmount').val(selectedBalance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

  // Set the date to the current date in the Add Payment modal using moment.js
  var currentDate = moment().format('DD-MM-YYYY');
  $('#paymentDate').val(currentDate);

  $('#addPaymentModal').modal('show'); // Show the Add Payment modal
  $('#paymentModal').modal('hide'); // Hide the View Payments modal

}

function resetAddPaymentModal() {
  $('#addPaymentForm')[0].reset(); // Reset the form fields
  $('#totalAmount').val(''); // Clear the Total Amount field
  $('#balance').val(''); // Clear the Balance field
}

  // Handle form submission for adding a new payment
  $('#addPaymentForm').on('submit', function(event) {
    event.preventDefault();

  // Gather the form data
  var formData = new FormData($(this)[0]);
  var selectedBankName = $('#bankName option:selected').val();

  formData.append('bankName', selectedBankName);

    var selectedPaymentDate = moment($('#paymentDate').val(), 'DD MM YY').format('YYYY-MM-DD');
  formData.append('paymentDate', selectedPaymentDate);

  // Submit the form via AJAX
  $.ajax({
    url: 'add_payment.php', // Replace with the correct path to the PHP script
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      if (response.status === 'success') {
        // Payment added successfully
        toastr.success(response.message);       
        // Close the modal
        $('#addPaymentModal').modal('hide');
          resetAddPaymentModal();
      } else if (response.status === 'error') {
        // Error occurred while adding payment
        if (response.errors && response.errors.length > 0) {
          // Display each error message in Toastr
          response.errors.forEach(function(error) {
            toastr.error(error);
          });
        } else {
          toastr.error(response.message);
        }
      }
    },
    error: function() {
      // Handle the error case
      toastr.error('Failed to add payment.');
    }
  });
});

  

  function formatAmount(input) {
  // Get the input value and remove any non-numeric characters and commas
  var value = input.value.replace(/[^0-9]/g, '').replace(/,/g, '');
  
  // Format the value with commas separating the thousands
  var formattedValue = Number(value).toLocaleString('en');
  
  // Set the input value to the formatted value
  input.value = formattedValue;
}

 $(function() {
  $("#paymentDate, #salesDate, #editDateInput").datepicker({
    dateFormat: "dd-mm-yy", // Format the date as "yyyy-mm-dd"
    // changeMonth: true, // Allow month selection
    // changeYear: true, // Allow year selection
    yearRange: "1900:2023", // Set the range of selectable years
     autoclose: true,
  });
});


 $(document).ready(function() {

  $('#bankName, #CustomerName').select2();
 
});
</script>

  <script>
$(document).ready(function() {
  var tankerSalesTable = $('#tankerSalesTable').DataTable({
    dom: '<"top"<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>>rt<"bottom mt-2"lip><"clear">',
    buttons: ['print', 'copy', 'pdf', 'colvis'],
    responsive: true,
    scrollX: false,
    scrollY: '405px',
    scrollCollapse: true,
    fixedHeader: true,
    lengthChange: true,
    autoWidth: false,
    ajax: {
      url: 'fetch_tankerSalesTable.php',
      dataSrc: ''
    },
    columns: [
      { data: 'TankerID' },
      { data: 'pickup_id' },
      { data: 'DriverName' },
      { data: 'TankerNumber' },
      { data: 'FuelType' },
      {
        data: 'ReturnDate',
        render: function(data, type, row) {
          if (type === 'display' || type === 'filter') {
            // Format the date for display
            return moment(data).format('DD-MM-YYYY');
          }
          return data;
        },
      },
      {
        data: 'Capacity',
        render: function(data, type, row) {
          return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
      },
       {
        data: 'availableLitre',
        render: function(data, type, row) {
   if (data !== undefined && data !== null) {
      return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
   }
   return ''; // Return an empty string or some default display text
}
      },



      { data: 'Depot' },
       {
        data: null,
        render: function(data, type, row) {
  return '<button type="button" class="btn btn-primary mr-2 add-fuel-sales-btn" data-toggle="modal" data-target="#addFuelSalesModal" data-tanker-id="' + row.TankerID + '" data-fuel-type="' + row.FuelType + '" data-available-litres="' + row.availableLitre + '">' +
    '<i class="fas fa-plus"></i> Add Sales</button>' +
    '<button class="btn btn-info view-details-btn" data-tanker-id="' + row.TankerID + '">' +
    '<i class="fa fa-folder"></i> Details</button>';
}

      },
     
    ],
    columnDefs: [
      { targets: [1, 3], visible: false }
    ],

 // Event listener for table row click
    rowCallback: function(row, data) {
      // Get the 'ReturnDate' column data for the clicked row
      var date = data.ReturnDate;
        var tankerID = data.TankerID;
      // Attach click event to the date cell
      $(row).find('td:eq(3)').click(function() {
        $('#editDateInput').val(moment(date).format('DD-MM-YYYY'));
         $('#editTankerID').val(tankerID);
        // Show the modal
        $('#editDateModal').modal('show');
      });
    }
  });


// Event listener for Save button in the modal
$('#saveDateBtn').on('click', function() {
  // Get the updated date and tanker ID from the modal input fields
  var newDate = $('#editDateInput').val();
  var tankerID = $('#editTankerID').val();

$.ajax({
  url: 'update_tankerSalesDate.php', // Replace with the URL to update_date.php
  type: 'POST',
  data: { tankerID: tankerID, newDate: newDate }, // Include the tanker ID in the data object
  dataType: 'json',
  success: function(response) {
    if (response.status === 'success') {
      // Date updated successfully
      toastr.success(response.message);
      // Close the modal
      $('#editDateModal').modal('hide');
      // Refresh the tankerSalesTable
      tankerSalesTable.ajax.reload();
    } else if (response.status === 'error') {
      // Error occurred while updating date
      toastr.error(response.message);
    }
  },
  error: function() {
    // Error occurred while making the AJAX request
    toastr.error('Failed to update date.');
  }
});

});
  // Handle click event for add Sale button
$('#addFuelSalesModal').on('shown.bs.modal', function(event) {
  var button = $(event.relatedTarget);
  var tankerID = button.data('tanker-id');
  var fuelType = button.data('fuel-type');
  var availableLitres = button.data('available-litres');
  $('#tankerId').val(tankerID);
  $('#fuelType').val(fuelType);
  $('#litreAmount').val(availableLitres.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
   var currentDate = moment().format('DD-MM-YYYY');
  $('#salesDate').val(currentDate);

});

$('#addFuelSalesBtn').on('click', function(event) {
  event.preventDefault(); // Prevent default button behavior

  // Gather the form data
  var formData = new FormData($('#addFuelSalesForm')[0]);
  var selectedCustomerName = $('#CustomerName option:selected').val();
  formData.append('customerName', selectedCustomerName);

    var selectedPaymentDate = moment($('#salesDate').val(), 'DD MM YY').format('YYYY-MM-DD');
  formData.append('salesDate', selectedPaymentDate);

  // Submit the form via AJAX
  $.ajax({
    url: 'add_fuel_sales.php', // Replace with the correct path to the PHP script
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      // Handle the success case
      if (response.status === 'success') {
        // Payment added successfully
        toastr.success(response.message);

        // Close the modal
        $('#addFuelSalesModal').modal('hide');
 $('#addFuelSalesForm')[0].reset();
        // Refresh the Sale table
        tankerSalesTable.ajax.reload();
      } else if (response.status === 'error') {
        // Error occurred while adding Sale
        if (response.errors && response.errors.length > 0) {
          // Display each error message in Toastr
          response.errors.forEach(function(error) {
            toastr.error(error);
          });
        } else {
          toastr.error(response.message);
        }
      }
    },
    error: function() {
      // Handle the error case
      toastr.error('Failed to fuel sale.');
    }
  });
});


$(document).on('click', '.view-details-btn', function() {
  var tr = $(this).closest('tr');
  var row = tankerSalesTable.row(tr);

  if (row.child.isShown()) {
    // This row is already open - close it
    row.child.hide();
    tr.removeClass('shown');
     tr.removeClass('expanded-row');
  } else {
    // Open the row and show fuel sales details
    var tankerID = $(this).data('tanker-id');
    fetchFuelSales(tankerID, function(fuelSalesData) {
      var fuelSalesDetailsTable = createFuelSalesDetailsTable(fuelSalesData);
      row.child(fuelSalesDetailsTable).show();
      tr.addClass('shown');
      tr.addClass('expanded-row');
    });
  }
});

// Function to fetch fuel sales data based on TankerID
function fetchFuelSales(tankerID, callback) {
  $.ajax({
    url: 'fetch_fuel_sales.php', // Replace with the URL to fetch fuel sales data
    type: 'GET',
    data: { tankerID: tankerID },
    dataType: 'json',
    success: function(fuelSalesData) {
      callback(fuelSalesData);
    },
    error: function(error) {
      console.log('Error fetching fuel sales data: ' + error);
    }
  });
}

 // Function to create a small table for fuel sales details
  function createFuelSalesDetailsTable(fuelSalesData) {
    var table = $('<table class="table table-bordered">');
    var tableHead = $('<thead class="bg-dark">');
    var tableBody = $('<tbody>');

    // Add header columns to the small table
    var headerRow = $('<tr>');
    headerRow.append('<th>Sales ID</th>');
    headerRow.append('<th>Customer Name</th>');
    headerRow.append('<th>Sales Date</th>');
    headerRow.append('<th>Litre Price </th>');
    headerRow.append('<th>Litre Amount </th>');
    headerRow.append('<th>Total Amount </th>');
    headerRow.append('<th>Balance</th>');
    headerRow.append('<th>View Payments</th>');
    tableHead.append(headerRow);

   // Add rows for fuel sales data to the table
  fuelSalesData.forEach(function (fuelSale) {
    var row = $('<tr>');
    row.append('<td>' + fuelSale.SalesID + '</td>');
    row.append('<td>' + fuelSale.CustomerName + '</td>');
    row.append('<td>' + formatDate(fuelSale.SalesDate) + '</td>');
    row.append('<td>' + fuelSale.LitrePrice + '</td>');
    row.append('<td>' + fuelSale.LitreAmount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + '</td>');
    row.append('<td>₦' + fuelSale.TotalAmount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + '</td>');
    row.append('<td>₦' + fuelSale.Balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + '</td>');
    row.append('<td><button class="btn btn-success view-payments-btn" data-sales-id="' + fuelSale.SalesID + '" data-tanker-id="' + fuelSale.TankerID + '" data-customer-id="' + fuelSale.CustomerID + '" data-customer-name="' + fuelSale.CustomerName + '" data-balance="' + fuelSale.Balance + '" data-total-amount="' + fuelSale.TotalAmount + '"><i class="fa fa-money-bill-alt"></i> View Payments</button></td>');

    tableBody.append(row);
  });


    table.append(tableHead);
    table.append(tableBody);
    return table;
  }

  // Function to fetch payment data based on SalesID
  function fetchPaymentData(salesID) {
    $.ajax({
      url: 'fetch_payment_details.php', // Replace with the URL to fetch payment data
      type: 'GET',
      data: { salesID: salesID },
      dataType: 'json',
      success: function(paymentData) {
        // Populate payment details for the selected fuel sale
        populatePaymentDetails(paymentData);
      },
      error: function(error) {
        console.log('Error fetching payment data: ' + error);
      }
    });
  }


// Function to populate payment details for the selected fuel sale
function populatePaymentDetails(paymentData) {
  var paymentTableT = $('#paymentTableT');
  var tableBody = paymentTableT.find('tbody');

  // Clear the payment details table
  tableBody.empty();

  // Loop through the payment data and add rows to the modal table
  paymentData.forEach(function(payment) {
    var row = $('<tr>');
    row.append('<td>' + payment.PaymentID + '</td>');
    row.append('<td>' + payment.CustomerName + '</td>');
    row.append('<td>₦' + payment.PaymentAmount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + '</td>');

    row.append('<td>' + formatDate(payment.PaymentDate) + '</td>');
    row.append('<td>' + payment.BankName + '</td>');
    tableBody.append(row);
  });

  // Show the payment details modal
  $('#paymentModal').modal('show');
}


// Add click event to the "View Payments" button in the fuel sales details table
$(document).on('click', '.view-payments-btn', function(event) {
  event.preventDefault(); // Prevent the default behavior of the button click
  var salesID = $(this).data('sales-id');
  fetchPaymentData(salesID);
});


  // Add click event to close the payment details modal
  $('#paymentModal').on('hidden.bs.modal', function() {
    // ...
  });
});

// Function to format the date to day/month/year format
function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-GB'); // 'en-GB' represents the locale for day/month/year format
}

</script>

</div>
</div>
</div>
</div>
</div>
</section>
 
</div>

<script src="payments.js"></script>
<?php include 'footer.html'; ?>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>

<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="js/moment.js"></script>
<!-- <script src="js/dataTables.editor.min.js"></script>
<script src="js/dataTables.select.min.js"></script>
<script src="js/dataTables.dateTime.min.js"></script> -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/select2/js/select2.full.min.js"></script>


<!-- AdminLTE App -->
<script src="js/adminlte.js"></script>
</body>
</html>