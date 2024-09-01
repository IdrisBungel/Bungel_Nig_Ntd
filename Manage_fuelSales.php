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
  <title>BUNGEL Sales</title>
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
</head>     

     <?php include 'header.php';?>
<div class="content-wrapper mt-5">
      <section class="content-header">
     <div class="container-fluid">
  <div class="row mt-2">
    <div class="col">
      <h1 class="">Fuel Sales</h1>
    </div>
    <div class="col-auto">
      <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#addFuelSalesModal">
        <i class="fas fa-plus"></i> Add Sales
      </button>
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
      <table id="salesTable" class="table table-bordered">
      <thead>
        <tr>
          <th>Sales ID</th>
          <th>Tanker ID</th>
          <th>Customer ID</th>
          <th>Customer Name</th>
          <th>Litre Amount</th>
          <th>Litre Price</th>
          <th>Total</th> 
          <th>Balance</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Sales data will be dynamically populated here -->
      </tbody>
    </table>
<style>
    #salesTable_wrapper {
  overflow-x: hidden !important;
}
</style>
 <script>
    $(document).ready(function() {
      // Initialize DataTable
      var salesTable = $('#salesTable').DataTable({
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
          url: 'fetch_sales_data.php', // Replace with your server-side script to retrieve payment data
          dataSrc: ''
        },
        columns: [
          { data: 'SalesID' },
           { data: 'TankerID' },
          { data: 'CustomerID' },
          { data: 'CustomerName' },
           {
            data: "LitreAmount",
            render: function(data, type, row) {
              // Format the salary with comma separators and add the Naira sign
              var formattedAmount = data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              return formattedAmount;
          }
          },
          {
            data: "LitrePrice",
            render: function(data, type, row) {
              // Format the salary with comma separators and add the Naira sign
              var formattedAmount = '₦' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              return formattedAmount;
          }
          },
          {
            data: "TotalAmount",
            render: function(data, type, row) {
              // Format the salary with comma separators and add the Naira sign
              var formattedAmount = '₦' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              return formattedAmount;
          }
          },
          {
            data: "Balance",
            render: function(data, type, row) {
              // Format the salary with comma separators and add the Naira sign
              var formattedAmount = '₦' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              return formattedAmount;
          }
          },
          {
            data: "SalesDate",
            render: function(data, type, row) {
              // Reverse the date format using moment.js
              var reversedDate = moment(data).format("DD-MM-YYYY");
              return reversedDate;
            }
          },    
          {
            data: null,
            render: function(data, type, row) {
              var salesId = data.SalesID;
              var editButton = '<button type="button" class="btn btn-primary btn-edit-sales" data-toggle="modal" data-target="#editFuelSalesModal" data-sales-id="' + salesId + '"><i class="fas fa-edit"></i> Edit</button>';
              var deleteButton = '<button type="button" class="btn btn-danger btn-delete-sales" data-toggle="modal" data-target="#deleteFuelSalesModal" data-sales-id="' + salesId + '"><i class="fas fa-trash"></i> Delete</button>';
              return editButton + ' ' + deleteButton;
            }
          }
        ],
            columnDefs: [
          { targets: [1,2], visible: false }
        ],
        order: [[8, 'desc']] 
      });

$('#salesTable').on('click', '.btn-edit-sales', function() {
  var salesId = $(this).data('sales-id');

  var salesData = salesTable.row($(this).closest('tr')).data();

  // Populate the edit sales modal with the retrieved sales details 
   $('#editSalesId').val(salesData.SalesID);
  $('#editSalesDate').val(salesData.SalesDate);
  $('#editLitrePrice').val(salesData.LitrePrice);
  $('#editLitreAmount').val(addCommas(salesData.LitreAmount));
  $('#editCustomerId').val(salesData.CustomerID);

$('#editTotalAmount').val(addCommas(salesData.TotalAmount));

function addCommas(number) {
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

  $('#editTankerID').val(salesData.TankerID);

  $('#editCustomerName').val(salesData.CustomerName).trigger('change');
});


 $('#editFuelSalesBtn').on('click', function(event) {
    event.preventDefault(); // Prevent default button behavior

  var formData = $('#editFuelSalesForm').serialize();

  // Submit the form via AJAX
  $.ajax({
    url: 'edit_fuel_sales.php', // Replace with the correct path to the PHP script
    method: 'POST',
    data: formData,
    success: function(response) {
      if (response.status === 'success') {
        // Payment updated successfully
        toastr.success(response.message);

        // Close the modal
        $('#editFuelSalesModal').modal('hide');

        // Refresh the payment table
        salesTable.ajax.reload();
        fetchTankerData();
      } else if (response.status === 'error') {
        // Display each error using Toastr
        response.errors.forEach(function(error) {
          toastr.error(error);
        });
      }
    },
    error: function() {
      // Handle the error case
      toastr.error('Failed to update fuel sale.');
    }
  });
});



// Handle click event for add Sale button
$('#addFuelSalesModal').on('shown.bs.modal', function() {

  $('#addFuelSalesForm')[0].reset();
  var currentDate = moment().format('DD-MM-YYYY');
  $('#salesDate').val(currentDate);

});



$('#addFuelSalesBtn').on('click', function(event) {
  event.preventDefault(); // Prevent default button behavior

  // Gather the form data
  var formData = new FormData($('#addFuelSalesForm')[0]);
var selectedCustomerName = $('#customerName option:selected').val();
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

        // Refresh the Sale table
        salesTable.ajax.reload();
        fetchTankerData();
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



// Handle delete action for a sale
$('#salesTable tbody').on('click', '.btn-delete-sales', function() {
  var saleID = $(this).data('sales-id');
  // Show the delete confirmation modal
  $('#deleteFuelSalesModal').modal('show');
 $('#deleteFuelSalesModal').data('saleID', saleID);
});

// Handle delete confirmation
$('#deleteFuelSalesBtn').on('click', function() {
  var saleID = $('#deleteFuelSalesModal').data('saleID');
  // Send the delete request via AJAX
  $.ajax({
    url: 'delete_fuel_sales.php', // Replace with the correct path to the PHP script
    method: 'POST',
    data: { saleID: saleID },
    success: function(response) {
      if (response.status === 'success') {
        // sale deleted successfully
        toastr.success(response.message);

        // Refresh the sale table
        salesTable.ajax.reload();
      } else if (response.status === 'error') {
        // Failed to delete payment
        toastr.error(response.message);
      }
    },
    error: function() {
      // Handle the error case
      toastr.error('Failed to delete sale.');
    }
  });

  // Hide the delete confirmation modal
  $('#deleteFuelSalesModal').modal('hide');
});


    });
 </script>

 <script>
 $(document).ready(function() {
  $('#customerName, #editCustomerName,  #tankerID').select2();
});

 </script>
 <style>
  .select2-container .select2-selection {
    height: calc(1.5em + 0.75rem + 2px);
  
  }
</style>
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
            <label for="customerName">Customer Name</label>
             <select class="form-control" id="customerName" name="customerName" style="width: 100%;" required>
          <option value="" disabled selected >Select Customer Name</option>
                   <?php
          $query = "SELECT CustomerID, CustomerName FROM Customers ";
          $result = mysqli_query($connect, $query);

          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $CustomerName = $row['CustomerName'];
              $CustomerId = $row['CustomerID'];
              echo "<option value=\"$CustomerName\"data-customer-id=\"$CustomerId\">$CustomerName</option>";

            }
          }
          ?>

            </select>
          </div>
          <input type="hidden" id="customerId" name="customerId">
          <div class="form-group">
            <label for="salesDate">Sales Date</label>
            <input type="text" class="form-control" id="salesDate" name="salesDate">
          </div>
          <div class="form-group">
            <label for="litrePrice">Litre Price</label>
            <input type="text" onkeyup="formatAmount(this)" class="form-control" id="litrePrice" name="litrePrice">
          </div>
          <div class="form-group">
            <label for="litreAmount">Litre Amount</label>
            <input type="text" class="form-control" id="litreAmount" onkeyup="formatAmount(this)" name="litreAmount">
          </div>
         <!--  <div class="form-group">
            <label for="totalAmount">Total Amount</label>
            <input type="number" class="form-control" id="totalAmount" name="totalAmount">
          </div> -->
          <div class="form-group">
            <label for="Tanker">Tanker</label><br/>
           <select class="form-select bg-light text-primary" id="tankerID" style="width: 100%;" name="tankerID"></select>
           <option disabled selected value=""> Select Tanker</option>

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
<script>
  // Fetch the tanker data and populate the dropdown
  function fetchTankerData() {
    $.ajax({
      url: 'fetch_tanker_data.php', // Replace with your server-side script to retrieve the tanker data
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          var tankerData = response.tankerData;
          var dropdown = $('#tankerID');

          // Clear existing options
          dropdown.empty();

         tankerData.forEach(function(tanker) {
          // Format the capacity number
          var formattedAvailableLitre = new Intl.NumberFormat().format(tanker.availableLitre);

          var option = $('<option>').val(tanker.TankerID).text(tanker.DriverName + ' - ' + formattedAvailableLitre + ' - ' + tanker.FuelType);
          dropdown.append(option);
        });


        } else {
          console.log('Failed to fetch tanker data');
        }
      },
      error: function() {
        console.log('Failed to fetch tanker data');
      }
    });
  }

  // Call the function to fetch and populate the tanker data on page load
  fetchTankerData();
</script>

<script>
    // Event handler for selecting a driver name
  $('#customerName').on('change', function() {
    var selectedOption = $(this).find(':selected');
    var employeeId = selectedOption.data('customer-id');
    // Set the tanker number, employee ID, and capacity in the corresponding input fields
    $('#customerId').val(employeeId);
  });

  $(function() {
  $("#salesDate,#editSalesDate").datepicker({
    dateFormat: "dd-mm-yy", // Format the date as "yyyy-mm-dd"
    // changeMonth: true, // Allow month selection
    // changeYear: true, // Allow year selection
    yearRange: "1900:2023" // Set the range of selectable years
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
</script>

<div class="modal fade" id="deleteFuelSalesModal" tabindex="-1" role="dialog" aria-labelledby="deleteFuelSalesModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header btn-danger">
        <h5 class="modal-title" id="deleteFuelSalesModalLabel">Delete Fuel Sales</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this fuel sales record?</p>
        <input type="hidden" id="deleteSalesId" name="deleteSalesId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="deleteFuelSalesBtn">Delete</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="editFuelSalesModal" role="dialog" aria-labelledby="editFuelSalesModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="editFuelSalesModalLabel">Edit Fuel Sales</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Edit Fuel Sales Form -->
        <form id="editFuelSalesForm">
          <input type="hidden" id="editSalesId" name="editSalesId">
          <div class="form-group">
            <label for="editCustomerName">Customer Name</label>
           <select class="form-control" id="editCustomerName" name="editCustomerName" style="width: 100%;" required>
          <option disabled value="">Select Customer Name</option>
                   <?php
          $query = "SELECT CustomerID, CustomerName FROM Customers ";
          $result = mysqli_query($connect, $query);

          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $CustomerName = $row['CustomerName'];
              $CustomerId = $row['CustomerID'];
              echo "<option value=\"$CustomerName\"data-customer-id=\"$CustomerId\">$CustomerName</option>";

            }
          }
          ?>

            </select>
            </div>
          
            <input type="hidden" id="editCustomerId" readonly name="editCustomerId">
             <input type="hidden" id="editTankerID" readonly name="editTankerID">
          
          <div class="form-group">
            <label for="editSalesDate">Sales Date</label>
            <input type="text" class="form-control" id="editSalesDate" name="editSalesDate">
          </div>
          <div class="form-group">
            <label for="editLitrePrice">Litre Price</label>
            <input type="text" class="form-control" id="editLitrePrice" onkeyup="formatAmount(this)" name="editLitrePrice">
          </div>
          <div class="form-group">
            <label for="editLitreAmount">Litre Amount</label>
            <input type="text" class="form-control" id="editLitreAmount"  onkeyup="formatAmount(this)" name="editLitreAmount">
          </div>
          <div class="form-group">
            <label for="editTotalAmount">Total Amount</label>
            <input type="text" class="form-control" id="editTotalAmount" readonly name="editTotalAmount">
          </div>
         <!--  <div class="form-group">
            <label for="editBalance">Balance</label>
            <input type="text" class="form-control" id="editBalance" readonly name="editBalance">
          </div> -->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="editFuelSalesBtn">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<script>
function calculateAmounts() {
  var litrePrice = parseFloat($('#editLitrePrice').val());
  var litreAmount = parseFloat($('#editLitreAmount').val().replace(/,/g, ''));

  // Check if litre price and litre amount are valid numbers
  if (!isNaN(litrePrice) && !isNaN(litreAmount)) {
    var totalAmount = litrePrice * litreAmount;
   
    // Format total amount and balance with commas
    var formattedTotalAmount = totalAmount.toLocaleString();

    // Update the formatted values in the fields
    $('#editTotalAmount').val(formattedTotalAmount);
  }
}

// Event listener for litre price field
$('#editLitrePrice').on('input', function() {
  calculateAmounts();
});

// Event listener for litre amount field
$('#editLitreAmount').on('input', function() {
  // Format the litre amount with commas
  var formattedLitreAmount = this.value.replace(/,/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  this.value = formattedLitreAmount;

  calculateAmounts();
});


    // Event handler for selecting a driver name
  $('#editCustomerName').on('change', function() {
    var selectedOption = $(this).find(':selected');
    var employeeId = selectedOption.data('customer-id');
    // Set the tanker number, employee ID, and capacity in the corresponding input fields
    $('#editCustomerId').val(employeeId);
  });


</script>
</div>
</div>
</div>
</div>
</div>
</section>
 
</div>
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
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/select2/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script src="js/adminlte.js"></script>
</body>
</html>