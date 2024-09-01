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
  <title>BUNGEL Payments</title>
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
      <h1 class="">Payments</h1>
    </div>
    <div class="col-auto">
      <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#addPaymentModal">
        <i class="fas fa-plus"></i> Add Payment
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
     <table id="paymentTable" class="table table-bordered">
      <thead>
        <tr>
          <th>Payment ID</th>
          <th>Fuel Sales ID</th>
          <th>Tanker Sales ID</th>
          <th>Customer ID</th>
          <th>Customer Name</th>
          <th>Amount</th>
          <th>Payment Date</th>
          <th>Bank Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Payment data will be dynamically populated here -->
      </tbody>
    </table>
<style>
    #paymentTable_wrapper {
  overflow-x: hidden !important;
}

  .select2-container .select2-selection {
    height: calc(1.5em + 0.75rem + 2px);
  
  }
</style>


<div class="modal fade" id="addPaymentModal"  role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary ">
        <h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
        <form id="addPaymentForm">
      
        <div class="form-group">
  <label for="fuelSale">Fuel Sale</label>
  <select class="form-control select2" style="width: 100%;" id="fuelSalesID" name="fuelSalesID">
    <option disabled selected value="">Select Fuel Sale</option>
    <?php
    $query = "SELECT SalesID, SalesDate, CustomerName, TankerID FROM FuelSales WHERE Balance > 0";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
  $fuelSaleID = $row['SalesID'];
  $saleDate = $row['SalesDate'];
  $tankerSalesID = $row['TankerID'];
  $customerName = $row['CustomerName'];
  echo "<option value=\"$fuelSaleID\" data-tanker-id=\"$tankerSalesID\" data-customer-name=\"$customerName\">$fuelSaleID - $customerName - $saleDate</option>";
}

    }
    ?>
  </select>
</div>


 <div class="form-group">
            <label for="customerName">Customer Name</label>
            <select class="form-control" id="customerName" name="customerName" style="width: 100%;" >
          <option disabled selected value="">Select Customer Name</option>
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
  

  <div class="form-group">
  <label for="fuelSaleAmount">Balance</label>
  <input type="text" class="form-control" name="fuelSaleAmount" id="fuelSaleAmount" readonly>
  <small id="balanceText" class="form-text text-muted"></small> <!-- Add a new element to display the balance -->
</div>
 <div class="form-group">
            <label for="paymentAmount">Payment Amount</label>
            <input type="text" class="form-control" id="paymentAmount" onkeyup="formatAmount(this)" name="paymentAmount" step="0.01" >
          </div>

 
          <div class="form-group">
            <label for="paymentDate">Payment Date</label>
            <input type="text" class="form-control" id="paymentDate" name="paymentDate" >
          </div>
          <div class="form-group">
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
  <label for="tankerSalesID">Tanker Sales ID</label>
  <input type="text" class="form-control" id="tankerSalesID" readonly name="tankerSalesID" >
  </div>
            <input  type="hidden" class="form-control" id="customerNameId" name="customerID" required>
          <!-- Submit button -->
          <button type="submit" class="btn btn-primary">Add Payment</button>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="deletePaymentModal" tabindex="-1" role="dialog" aria-labelledby="deletePaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="deletePaymentModalLabel">Delete Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this payment?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeletePayment">Delete</button>
      </div>
       <input type="hidden" id="deletePaymentID" value="">
    </div>
  </div>
</div>


<div class="modal fade" id="editPaymentModal" role="dialog" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editPaymentForm" method="post">
          <div class="form-group">
            <label for="editCustomerName">Customer Name:</label>
              <select class="form-control" id="editCustomerName" name="editCustomerName" style="width: 100%;" required>
          <option disabled selected value="">Select Customer Name</option>
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
           mysqli_close($connect);
          ?>

            </select>
          </div>
          <div class="form-group">
            <label for="editPaymentDate">Payment Date:</label>
            <input type="text" class="form-control" id="editPaymentDate" name="editPaymentDate">
          </div> 
          <div class="form-group">
            <label for="editBankName">Bank Name:</label>
            <select class="form-control select2" style="width: 100%;" id="editBankName" name="editBankName">
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
          <option value="Standard Chartered Bank">Standard Chartered Bank</option>
          <option value="Polaris Bank">Polaris Bank</option>
          <option value="Wema Bank">Wema Bank</option>
          <option value="Heritage Bank">Heritage Bank</option>
          <option value="Unity Bank">Unity Bank</option>
          <option value="Jaiz Bank">Jaiz Bank</option>
          <option value="Providus Bank">Providus Bank</option>
        </select>
          </div>
          <div class="form-group">
            <label for="editPaymentAmount">Payment Amount:</label>
            <input type="text" class="form-control" id="editPaymentAmount" onkeyup="formatAmount(this)" name="editPaymentAmount">
          </div>
          <input type="hidden" id="editPaymentID" name="editPaymentID">
           <input type="hidden" id="editFuelSalesID" name="editFuelSalesID">
            <input type="hidden" id="editCustomerID" name="editCustomerID">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" id="updatePayment">Save Changes</button>
      </div>
    </div>
  </div>
</div>

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
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/select2/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script src="js/adminlte.js"></script>
</body>
</html>