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
  <title>BUNGEL CUSTOMERS</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/adminlte.min.css">
   <link rel="stylesheet" href="css/darkmode.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
   <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
</head>     

     <?php include 'header.php';?>
<div class="content-wrapper">
      
      <!-- Main content -->
  <section class="content">
        <!-- Add your dashboard items here -->
        
  <div class="row justify-content-center">
  <div class="col-lg-11 col-md-8 col-sm-10">
    <div class="card">
   <!-- HTML table structure -->
<table id="data-table" class="table table-hover">
  <thead>
    <tr>
      <th>Pickup ID</th>
      <th>Tanker Number</th>
      <th>Driver Name</th>
      <th>Driver ID</th>
      <th>Pickup Date</th>
      <th>Capacity</th>
      <th>Category</th>
      <th>Fuel Type</th>
      <th>Destination</th>
      <th>Depot</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <!-- Existing rows will be dynamically added here -->
  </tbody>
</table>

<!-- Form to add a new record -->
<form id="add-record-form">
  <input type="text" name="tanker_number" placeholder="Tanker Number" required>
  <input type="text" name="driver_name" placeholder="Driver Name" required>
  <input type="text" name="driverID" placeholder="Driver ID">
  <input type="date" name="pickup_date" required>
  <input type="number" name="capacity" placeholder="Capacity" required>
  <input type="text" name="category" placeholder="Category" required>
  <input type="text" name="FuelType" placeholder="Fuel Type" required>
  <input type="text" name="destination" placeholder="Destination" required>
  <input type="text" name="depot" placeholder="Depot" required>

  <button type="submit">Add Record</button>
</form>

<!-- JavaScript to handle insertion and refresh using DataTables -->



<script>
 $(document).ready(function() {
  // Initialize DataTable
  var dataTable = $('#data-table').DataTable({
    dom: 'Blfrtip', // Display buttons along with the table
    buttons: [
      'copy', 'csv', 'excel', 'pdf', 'print', 'colvis' // Add desired buttons
    ],
    responsive: true, // Enable responsive design
    scrollX: true, // Enable horizontal scrolling
    fixedHeader: true, // Enable fixed header
    lengthChange: false, // Enable the ability to change the number of rows displayed
    autoWidth: false, // Disable automatic column width calculation

    ajax: {
      url: "fetch_pickupsTable.php", // Replace with your server-side script to retrieve existing data
      dataSrc: "" // Use an empty string if the response is an array of objects
    },
    columns: [
      { data: "pickup_id" },
      { data: "tanker_number" },
      { data: "driver_name" },
      { data: "driverID" },
      { data: "pickup_date" },
      { data: "capacity" },
      { data: "category" },
      { data: "FuelType" },
      { data: "destination" },
      { data: "depot" },
      { data: "status" },
      {
       // New column for action buttons
        data: null,
        render: function(data, type, row) {
          // Render delete and edit buttons
          return '<button class="delete-button btn btn-danger" data-id="' + data.pickup_id + '">Delete</button>' +
                 '<button class="edit-button btn btn-primary" data-id="' + data.pickup_id + '">Edit</button>';
        }
      }
    ],
    columnDefs: [
      { targets: [7, 8], visible: false } // Hide "Destination" and "Category" columns by default
    ]
  });

  // Append DataTable buttons to a specific element
  dataTable.buttons().container().appendTo('#datatable-buttons');



    // Event handler for form submission (adding a new record)
    $("#add-record-form").submit(function(event) {
      event.preventDefault(); // Prevent form submission

      // Capture form inputs
      var formData = $(this).serialize();

      $.ajax({
        url: "testing_add.php", // Replace with your server-side script to insert the new record
        method: "POST",
        data: formData,
        success: function(response) {
          // Refresh the datatable after a successful insertion
          dataTable.ajax.reload(null, false);
        }
      });
    });
  });



</script>

      <div class="card-body">
        <div class="table-responsive">
        
        </div>
      </div>
    </div>
  </div>
</div> 
</section>
    </div>
    <?php include 'footer.html'; ?>
  </div>


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

<!-- AdminLTE App -->
<script src="js/adminlte.js"></script>
  </body>
</html>