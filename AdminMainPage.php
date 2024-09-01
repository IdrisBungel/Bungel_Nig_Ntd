<?php include 'admin_session_check.php'; 
require_once 'dbcon.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <script src="js/jquery.min.js"></script>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BUNGEL NIG LTD</title>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"> -->
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
<link rel="stylesheet" href="plugins/toastr/toastr.css">
</head>

     <?php include 'header.php';?>

  <div class="content-wrapper">
      <section class="content-header mt-5">
        <div class="container-fluid">
          <h1>Dashboard</h1>
        </div>
      </section>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- Fuel Sales Chart Card -->
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Fuel Sales Chart</h3>
                </div>
                <div class="card-body">
                  <canvas id="fuelSalesChart" style="height: 300px;"></canvas>
                </div>
              </div>
            </div>

            <!-- Outstanding Payments Card -->
            <div class="col-md-6">
              <div class="card">
                <div class="card-header bg-primary">
                  <h3 class="card-title">Outstanding Payments</h3>
                </div>
                <div class="card-body">
                  <table id="outstandingPaymentsTable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Customer Name</th>
                        <th>Payment Amount</th>
                        <th>Payment Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Data will be populated using JavaScript -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Total Debt Card -->
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Total Debt</h3>
                </div>
                <div class="card-body">
                  <table id="totalDebtTable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Customer Name</th>
                        <th>Total Debt</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Data will be populated using JavaScript -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>


    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Recent Tanker Sales</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="recentTankerSalesTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Driver</th>
                  <th>Customer</th>
                  <th>Fuel Type</th>
                  <th>Date</th>
                  <th>Total Amount</th>
                  <th>Balance</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
 

            <!-- Add more cards for other data here -->

          </div>
        </div>
      </section>
      
    </div>
  <?php include 'footer.html'; ?>

<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <script src="dashboard.js"></script>
 <script src="plugins/chart.js/Chart.min.js"></script>
 <script src="plugins/toastr/toastr.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
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