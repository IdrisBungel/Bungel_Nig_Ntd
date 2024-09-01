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
  <title>BUNGEL Customers</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/adminlte.min.css">
   <!-- <link rel="stylesheet" href="plugins/toastr.min.css"> -->
   <link rel="stylesheet" href="css/darkmode.css">
</head>     

<?php include 'header.php';?>
<div class="content-wrapper mt-5">
      <section class="content-header">
      </section>

      <!-- Main content -->
  <section class="content">   
  <div class="row justify-content-center">
  <div class="col-lg-11 col-md-8 col-sm-10">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="">Customers</h3>
          <div class="d-flex">
            <div>
              <!-- <label for="filter" class="mr-2">Filter:</label> -->
              <select id="filter" class="form-control mt-2 mr-2">
                <option value="all">All</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <div class="mr-2">
              <button class="btn btn-primary mt-2 ml-2" onclick="window.print()"><i class='fas fa-print'></i> Print</button>
            </div>
              <button type="button" class="btn btn-primary mt-2 ml-3" data-toggle="modal" data-target="#addCustomerModal">
               <i class='fas fa-plus'></i> Add Customer
              </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="customersTable" class="table table-striped">
            <thead class="bg-light">
              <tr>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Account</th>
                <th>Actions</th>
              </tr>
            </thead>
           <tbody>
   <?php
// Fetch customers from the database
$query = "SELECT Customers.CustomerID, Customers.CustomerName, Customers.Email, Customers.Phone, Customers.Address, SUM(FuelSales.Balance) AS AccountBalance
          FROM Customers
          LEFT JOIN FuelSales ON Customers.CustomerID = FuelSales.CustomerID
          GROUP BY Customers.CustomerID";
$result = mysqli_query($connect, $query);

if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr id='customerRow_{$row['CustomerID']}'>";
    echo "<td>{$row['CustomerID']}</td>";
    echo "<td>{$row['CustomerName']}</td>";
    echo "<td>{$row['Email']}</td>";
    echo "<td>{$row['Phone']}</td>";
    echo "<td>{$row['Address']}</td>";
    echo "<td> ₦" . number_format($row['AccountBalance']) . "</td>";
    echo "<td>";
    echo "<button class='btn btn-primary mr-2' data-toggle='modal' data-target='#editCustomerModal' data-customer-id='{$row['CustomerID']}' data-customer-name='{$row['CustomerName']}' data-email='{$row['Email']}' data-phone='{$row['Phone']}' data-address='{$row['Address']}' data-balance='{$row['AccountBalance']}'><i class='fas fa-edit'></i> Edit</button>";
    echo "<button class='btn btn-danger ml-2 deleteCustomerButton' data-toggle='modal' data-target='#deleteCustomerModal' data-customer-id='{$row['CustomerID']}'><i class='fas fa-trash'></i> Delete</button>";
    echo "</td>";
    echo "</tr>";
  }
  mysqli_free_result($result);
}
?>

  </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div> 
</section>


<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCustomerModalLabel">Add Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addCustomerForm">
          <div class="form-group">
            <label for="customerName">Customer Name</label>
            <input type="text" class="form-control" id="customerName" name="customerName" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email">
          </div>
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone">
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address">
          </div>
          <div class="form-group">
            <label for="balance">Balance</label>
            <input type="text"  onkeyup="formatAmount(this)" class="form-control" id="balance" name="balance">
          </div>
          <button type="submit" class="btn btn-primary">Add Customer</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // Add Customer form submit event
    $("#addCustomerForm").on("submit", function(e) {
      e.preventDefault(); // Prevent form submission
      var balanceInput = $('#balance');
    var balanceValue = balanceInput.val();
    var sanitizedBalance = balanceValue.replace(/,/g, '');
    balanceInput.val(sanitizedBalance);
      // Get form data
      var formData = $(this).serialize();

      // Perform Ajax request
      $.ajax({
        url: "add_customer.php",
        type: "post",
        data: formData,
        dataType: "json",
        success: function(response) {
          // Handle successful response
          if (response.status === "success") {
            toastr.success(response.message);
            $("#addCustomerModal").modal("hide");

            // Reload the page after 5 seconds
            setTimeout(function() {
              location.reload();
            }, 2000);
          } else {
            toastr.error(response.message);
          }

        },
        error: function(xhr, status, error) {
          // Handle error response
          toastr.error("An error occurred while adding the customer.");
          console.log(xhr.responseText);
        }
      });
    });

  });
</script>



<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updateCustomerForm">
          <div class="form-group">
            <label for="editCustomerId">Customer ID</label>
            <input type="text" class="form-control" id="editCustomerId" name="editCustomerId" readonly>
          </div>
          <div class="form-group">
            <label for="editCustomerName">Customer Name</label>
            <input type="text" class="form-control" id="editCustomerName" name="editCustomerName" required>
          </div>
          <div class="form-group">
            <label for="editEmail">Email</label>
            <input type="email" class="form-control" id="editEmail" name="editEmail" required>
          </div>
          <div class="form-group">
            <label for="editPhone">Phone</label>
            <input type="text" class="form-control" id="editPhone" name="editPhone" >
          </div>
          <div class="form-group">
            <label for="editAddress">Address</label>
            <input type="text" class="form-control" id="editAddress" name="editAddress" >
          </div>
          <div class="form-group">
  <label for="editBalance">Balance</label>
  <input type="text" class="form-control" id="editBalance" name="editBalance"  onkeyup="formatAmount(this)" required>
</div>
          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>

function formatAmount(input) {
    // Get the input value and remove any non-numeric characters
    var value = input.value.replace(/[^0-9]/g, '');
    
    // Format the value with commas separating the thousands
    var formattedValue = Number(value).toLocaleString('en');
    
    // Set the input value to the formatted value
    input.value = formattedValue;
  }

  $(document).ready(function() {
    // Edit Customer Modal
    $('#editCustomerModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget); // Button that triggered the modal
      var customerId = button.data('customer-id'); // Extract customer ID from data attribute
      var customerName = button.data('customer-name'); // Extract customer name from data attribute
      var email = button.data('email'); // Extract email from data attribute
      var phone = button.data('phone'); // Extract phone from data attribute
      var address = button.data('address'); // Extract address from data attribute
      var balance = button.data('balance'); // Extract balance from data attribute

      // Set the form fields with the customer data
      $('#editCustomerId').val(customerId);
      $('#editCustomerName').val(customerName);
      $('#editEmail').val(email);
      $('#editPhone').val(phone);
      $('#editAddress').val(address);
      $('#editBalance').val(balance);
    });

    // Handle update customer form submission
    $('#updateCustomerForm').on('submit', function(e) {
      e.preventDefault(); // Prevent form submission
    var balanceInput = $('#editBalance');
    var balanceValue = balanceInput.val();
    var sanitizedBalance = balanceValue.replace(/,/g, '');
    balanceInput.val(sanitizedBalance);
      // Get the form data
      var formData = $(this).serialize();

      // Perform AJAX request to handle form submission
      $.ajax({
        url: 'edit_customer.php',
        type: 'POST',
        data: formData,
        success: function(response) {
    var data = JSON.parse(response);
    if (data.status === 'success') {
    toastr.success(data.message);
    $('#editCustomerModal').modal('hide');
    var updatedCustomer = {
      CustomerID: $('#editCustomerId').val(),
      CustomerName: $('#editCustomerName').val(),
      Email: $('#editEmail').val(),
      Phone: $('#editPhone').val(),
      Address: $('#editAddress').val(),
      Balance: $('#editBalance').val()
    };
    updateCustomerRow(updatedCustomer); // Update the row in the table
  } else {
    toastr.error(data.message);
  }
},

        error: function(xhr, status, error) {
          toastr.error('Failed to update customer.');
        }
      });
    });

    // Function to update the customer row in the table
    function updateCustomerRow(customer) {
      var rowId = 'customerRow_' + customer.CustomerID;
      var row = $('#' + rowId);

      // Update the specific columns with new values
      row.find('td:nth-child(2)').text(customer.CustomerName);
      row.find('td:nth-child(3)').text(customer.Email);
      row.find('td:nth-child(4)').text(customer.Phone);
      row.find('td:nth-child(5)').text(customer.Address);
      row.find('td:nth-child(6)').text(customer.Balance);
    }
  });

  // Get the editBalance input element
  var editBalanceInput = document.getElementById('editBalance');

// Add event listener for input
  editBalanceInput.addEventListener('input', function() {
  // Get the input value
  var inputValue = this.value;

  // Remove any non-digit characters
  var numericValue = inputValue.replace(/[^0-9.]/g, '');

  // Update the input value with the formatted numeric value
  this.value = numericValue;
});

</script>


<!-- Delete Customer Modal -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" role="dialog" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header btn-danger">
        <h5 class="modal-title" id="deleteCustomerModalLabel">Delete Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this customer?</p>
      </div>
      <div class="modal-footer">
        <form id="deleteCustomerForm" method="post">
          <input type="hidden" id="deleteCustomerId" name="customerId">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" id="deleteCustomerButton" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // Handle delete button click
    $('.deleteCustomerButton').on('click', function() {
      var customerId = $(this).data('customer-id');
      $('#deleteCustomerId').val(customerId); // Set the customer ID in the hidden input field
    });

    // Handle delete form submission
    $('#deleteCustomerForm').on('submit', function(e) {
      e.preventDefault(); // Prevent form submission

      // Get the customer ID
      var customerId = $('#deleteCustomerId').val();

      // Perform AJAX request to handle form submission
      $.ajax({
        url: 'delete_customer.php',
        type: 'POST',
        data: { customerId: customerId },
        success: function(response) {
          if (response.status === 'success') {
            toastr.success(response.message);
            $('#deleteCustomerModal').modal('hide');
            $('#customerRow_' + customerId).remove();
          } else {
            toastr.error(response.message);
          }
        },
        error: function(xhr, status, error) {
          toastr.error('Failed to delete customer.');
        }
      });
    });
  });
</script>



</div>
<?php include 'footer.html';?>

</body>
</html>


<script src="plugins/jquery-ui/jquery-ui.min.js"></script>

<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- jQuery -->

<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.js"></script>