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
  <title>BUNGEL Tanker Pickups</title>
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

</head>     

     <?php include 'header.php';?>
<div class="content-wrapper mt-5">
      <section class="content-header">
     <div class="container-fluid">
  <div class="row mt-2">
    <div class="col">
      <h1 class="">Tanker Pickups</h1>
    </div>
    <div class="col-auto">
      <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#addPickupModal">
        <i class="fas fa-plus"></i> Add Pickups
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
     <table id="tankerPickupsTable" class="table table-hover">
  <thead class="bg-light">
    <tr>
      <th>Pickup ID</th>
      <th>Tanker Number</th>
      <th>Driver Name</th>
      <th>Date</th>
      <th>Capacity</th>
      <th>Category</th>
      <th>Fuel</th>
      <th>Destination</th>
      <th>Depot</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
 
  </tbody>
</table>

  <script>
$(document).ready(function() {
  // Initialize DataTable
var dataTable = $('#tankerPickupsTable').DataTable({
  dom: '<"top"<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>>rt<"bottom"lip><"clear">',
  buttons: [
    'print', 'copy', 'pdf', 'colvis'
  ],
  responsive: true,
  scrollX: false,
  scrollY: '405px', // Set a fixed height for scrolling
  scrollCollapse: true,
  fixedHeader: true,
  lengthChange: false,
  autoWidth: false,
  ajax: {
    url: "fetch_pickupsTable.php",
    dataSrc: function(data) {
      // Modify the response data to include the hidden employeeId property
      return data.map(function(pickup) {
        return {
          pickup_id: pickup.pickup_id,
          tanker_number: pickup.tanker_number,
          driver_name: pickup.driver_name,
          pickup_date: pickup.pickup_date,
          capacity: pickup.capacity,
          category: pickup.category,
          FuelType: pickup.FuelType,
          destination: pickup.destination,
          depot: pickup.depot,
          status: pickup.status,
          employeeId: pickup.driverID 
        };
      });
    }
  },
  columns: [
    { data: "pickup_id" },
    { data: "tanker_number" },
    { data: "driver_name" },
    {
  data: "pickup_date",
  render: function(data, type, row) {
    // Reverse the date format using moment.js
    var reversedDate = moment(data).format("DD-MM-YYYY");
    return reversedDate;
  }
},
 {
  data: "capacity",
  render: function(data, type, row) {
    // Format the capacity with comma separators
    var formattedCapacity = data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return formattedCapacity;
  }
}
,
    { data: "category" },
    { data: "FuelType" },
    { data: "destination" },
    { data: "depot" },
 {
  data: "status",
  render: function(data, type, row) {
    // Render the checkbox based on the status value
    var checked = data == 1 ? "checked" : "";
    return '<input type="checkbox" class="status-checkbox custom-checkbox" data-id="' + row.pickup_id + '" ' + checked + '>';
  }
}
,
    {
      data: null,
      render: function(data, type, row) {
        return '<button class="delete-button btn btn-sm mr-2 btn-danger" data-toggle="modal" data-target="#deletePickupModal" data-id="' + data.pickup_id + '"><i class="fas fa-trash"></i> Delete</button>' +
               '<button class="edit-button btn btn-sm btn-primary" data-toggle="modal" data-target="#editPickupModal" data-id="' + data.pickup_id + '" data-employee-id="' + data.employeeId + '"><i class="fas fa-edit"></i> Edit</button>';
      }
    }
  ],
  columnDefs: [
    { targets: [1, 7], visible: false }
  ]
});

// Event handler for status checkbox change
$('#tankerPickupsTable').on('change', '.status-checkbox', function() {
  var pickupId = $(this).data('id');
  var status = this.checked ? 1 : 0;

  // Check if the status is being changed from 1 to 0 (undo)
  if (status === 0) {
    // Display an alert to confirm the undo action
    if (!confirm('Are you sure you want to change the status from cleared to pending?')) {
      // Restore the checkbox state
      this.checked = true;
      return;
    }
  }

  // Send an AJAX request to update the status
  $.ajax({
    url: 'update_pickup_status.php', // Replace with your PHP script to update the status
    type: 'POST',
    data: { pickupId: pickupId, status: status },
    success: function(response) {
      // Handle success response
      toastr.success('Status updated successfully');
      // Refresh the table or perform any other necessary actions
      dataTable.ajax.reload(null, false);
    },
    error: function(error) {
      // Handle error response
      toastr.error('Unable to update status');
    }
  });
});


// Event handler for Add Pickup button click
$('#addPickupBtn').on('click', function() {
  var formData = $('#addPickupForm').serialize();

  $.ajax({
    url: 'add_pickup.php', // Replace with your PHP script to add the pickup
    type: 'POST',
    data: formData,
    success: function(response) {
      var data = JSON.parse(response);
      if (data.success) {
        // Handle success response
        toastr.success(data.message);
        // Refresh the table or perform any other necessary actions
        dataTable.ajax.reload(null, false);
        // Clear the form inputs
        $('#addPickupForm')[0].reset();
        // Close the modal
        $('#addPickupModal').modal('hide');
      } else {
        // Handle error response
        toastr.error(data.message);
      }
    },
    error: function(error) {
      // Handle error response
      toastr.error('Unable to add pickup');
    }
  });
});

$('#addPickupModal').on('show.bs.modal', function() {
  var currentDate = new Date().toLocaleDateString('en-NG');
  $('#pickupDate').val(currentDate);
});
 
  $('#pickupDate').daterangepicker({
    singleDatePicker: true,
    dateFormat: "d-m-Y",
    // Add more configuration options as needed
  });


  // Event handler for delete button click
  $('#tankerPickupsTable tbody').on('click', '.delete-button', function() {
    var pickupId = $(this).data('id');
    $('#confirmDeleteBtn').data('id', pickupId); // Set the pickup ID in the delete confirmation modal

    // Open the delete confirmation modal
    $('#deletePickupModal').modal('show');
  });

  // Event handler for confirm delete button click
  $('#confirmDeleteBtn').on('click', function() {
    var pickupId = $(this).data('id');

    // Send an AJAX request to delete the record
    $.ajax({
      url: "delete_pickup.php", // Replace with your server-side script to delete the record
      method: "POST",
      data: { pickupId: pickupId },
      success: function(response) {
        // Refresh the datatable after a successful deletion
        dataTable.ajax.reload(null, false);
        toastr.success('Pickup deleted successfully');
      },
      error: function(error) {
        // Handle error response
        toastr.error('Unable to delete pickup');
      }
    });

    // Close the delete confirmation modal
    $('#deletePickupModal').modal('hide');
  });



// Event handler for Edit button click
$('#tankerPickupsTable tbody').on('click', '.edit-button', function() {
 var pickupId = $(this).data('id');
  var employeeId = $(this).data('employee-id');

  // Set the employeeId in the Edit Pickup Modal
  $('#editEmployeeId').val(employeeId);
  
  $.ajax({
    url: 'fetch_pickup_data.php', // Replace with your PHP script to fetch pickup data
    type: 'GET',
    data: { pickupId: pickupId },
    success: function(response) {
      // Assuming the response is a JSON object containing the pickup data
      var pickup = JSON.parse(response);
      
      // Populate the form fields in the Edit Pickup Modal with the retrieved data
      $('#editPickupId').val(pickup.pickup_id);
      $('#editTankerNumber').val(pickup.tanker_number);
      $('#editDriverName').val(pickup.driver_name);
      $('#editDate').val(pickup.pickup_date);
      $('#editCapacity').val(pickup.capacity);
      $('#editCategory').val(pickup.category);
      $('#editFuelType').val(pickup.FuelType);
      $('#editDestination').val(pickup.destination);
      $('#editDepot').val(pickup.depot);
      $('#editStatus').val(pickup.status);
      
      // Open the Edit Pickup Modal
      $('#editPickupModal').modal('show');
    },
    error: function(error) {
      // Handle error response
    }
  });
});

 
     $('#editDate').daterangepicker({
    singleDatePicker: true,
    dateFormat: "d-m-Y",
  });


// Event handler for Update button click in the Edit Pickup Modal
$('#updatePickupBtn').on('click', function() {
  var pickupId = $('#editPickupId').val();
  var tankerNumber = $('#editTankerNumber').val();
  var driverName = $('#editDriverName').val();
  var pickupDate = $('#editDate').val();
  var capacity = $('#editCapacity').val();
  var category = $('#editCategory').val();
  var fuelType = $('#editFuelType').val();
  var destination = $('#editDestination').val();
  var depot = $('#editDepot').val();
  var employeeId = $('#editEmployeeId').val();
  var status = $('#editStatus').val();
  
  // Code to update the pickup data using AJAX
  $.ajax({
    url: 'update_pickup.php', // Replace with your PHP script to update the pickup
    type: 'POST',
    data: {
      pickupId: pickupId,
      tankerNumber: tankerNumber,
      driverName: driverName,
      pickupDate: pickupDate,
      capacity: capacity,
      category: category,
      fuelType: fuelType,
      destination: destination,
      depot: depot,
      employeeId: employeeId,
      status: status
    },
    success: function(response) {
  var data = JSON.parse(response);
  if (data.success) {
    // Handle success response
    toastr.success(data.message);
    // Refresh the table or perform any other necessary actions
    dataTable.ajax.reload(null, false);
    // Close the modal
    $('#editPickupModal').modal('hide');
  } else {
    // Handle error response
    toastr.error(data.message);
  }
  }
});
  });
});
 </script>



<!-- Add Tanker Pickup Modal -->
<div class="modal fade" id="addPickupModal" tabindex="-1" role="dialog" aria-labelledby="addPickupModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="addPickupModalLabel">Add Tanker Pickup</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Add Tanker Pickup Form -->
        <form id="addPickupForm">
               <div class="form-group">
    <label for="addDriverName">Driver Name</label>
    <select class="form-control" id="addDriverName" name="driverName">
      <option disabled selected value="">Select Driver Name</option>
      <?php
        $query = "SELECT * FROM tankers";
        $result = mysqli_query($connect, $query);

        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $driverName = $row['driver_name'];
            $employeeId = $row['employee_id'];
            $tankerNumber = $row['tanker_number'];
            $capacity = $row['capacity'];

            echo "<option value=\"$driverName\" data-tanker-number=\"$tankerNumber\" data-employee-id=\"$employeeId\" data-capacity=\"$capacity\">$driverName</option>";
          }
        }
      ?>
    </select>
  </div>
          <div class="form-group">
  <label for="pickupDate">Date</label>
  <input type="text" class="form-control" id="pickupDate" name="pickupDate" >
</div>

            <div class="form-group">
    <label for="addCategory">Category</label>
    <select class="form-control" id="addCategory" name="category">
      <option value="NNPC">NNPC</option>
      <option value="INDEPENDENT">INDEPENDENT</option>
    </select>
  </div>
  <div class="form-group">
    <label for="addFuelType">Fuel Type</label>
    <select class="form-control" id="addFuelType" name="fuelType">
      <option value="Gas">Gas</option>
      <option selected value="Petrol">Petrol</option>
    </select>
  </div>
  <div class="form-group">
    <label for="addDestination">Destination</label>
    <input type="text" class="form-control" id="addDestination" name="destination">
  </div>
  <div class="form-group">
    <label for="addDepot">Depot</label>
    <input type="text" class="form-control" id="addDepot" name="depot">
  </div>
  <div class="form-group">
            <label for="addCapacity">Capacity</label>
            <input type="text" class="form-control" id="addCapacity" name="capacity" readonly>
          </div>
   <div class="form-group">
            <label for="addTankerNumber">Tanker Number</label>
            <input type="text" class="form-control" id="addTankerNumber" name="tankerNumber" readonly>
          </div>
          <div class="form-group">
            <label for="addEmployeeId">Employee ID</label>
            <input type="text" class="form-control" id="addEmployeeId" name="employeeId" readonly>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" id="addPickupBtn" class="btn btn-primary">Add Pickup</button>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript -->
<script>
  // Event handler for selecting a driver name
  $('#addDriverName').on('change', function() {
    var selectedOption = $(this).find(':selected');
    var tankerNumber = selectedOption.data('tanker-number');
    var employeeId = selectedOption.data('employee-id');
    var capacity = selectedOption.data('capacity');

    // Set the tanker number, employee ID, and capacity in the corresponding input fields
    $('#addTankerNumber').val(tankerNumber);
    $('#addEmployeeId').val(employeeId);
    $('#addCapacity').val(capacity);
  });
</script>



<!-- Edit Pickup Modal -->
<div class="modal fade" id="editPickupModal" tabindex="-1" role="dialog" aria-labelledby="editPickupModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="editPickupModalLabel">Edit Pickup</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editPickupForm">
          <div class="form-group">
            <label for="editPickupId">Pickup ID</label>
            <input type="text" class="form-control" id="editPickupId" readonly>
          </div>
          <div class="form-group">
            <label for="editDriverName">Driver Name</label>
            <select class="form-control" id="editDriverName" required>
              <option disabled value="">Select Driver Name</option>
              <?php
        $query = "SELECT * FROM tankers";
        $result = mysqli_query($connect, $query);

        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $driverName = $row['driver_name'];
            $employeeId = $row['employee_id'];
            $tankerNumber = $row['tanker_number'];
            $capacity = $row['capacity'];

            echo "<option value=\"$driverName\" data-tanker-number=\"$tankerNumber\" data-employee-id=\"$employeeId\" data-capacity=\"$capacity\">$driverName</option>";
          }
        }
      ?>
            </select>
          </div>
          <div class="form-group">
            <label for="editDate">Date</label>
            <input type="text" class="form-control" id="editDate" required>
          </div>
          <div class="form-group">
            <label for="editCategory">Category</label>
             <select class="form-control" id="editCategory" required >
              <option value="NNPC">NNPC</option>
              <option value="INDEPENDENT">INDEPENDENT</option>
            </select>
          </div>
          <div class="form-group">
            <label for="editFuelType">Fuel Type</label>
            <select class="form-control" id="editFuelType" required>
            <option value="Gas">Gas</option>
            <option value="Petrol">Petrol</option>
          </select>
          </div>
          <div class="form-group">
            <label for="editDestination">Destination</label>
            <input type="text" class="form-control" id="editDestination" required>
          </div>
          <div class="form-group">
            <label for="editDepot">Depot</label>
            <input type="text" class="form-control" id="editDepot" required>
          </div>
          <div class="form-group">
            <label for="editCapacity">Capacity</label>
            <input type="number" class="form-control" id="editCapacity" readonly>
          </div>
          <input type="hidden" id="editEmployeeId">
          <div class="form-group">
            <label for="editTankerNumber">Tanker Number</label>
            <input type="text" class="form-control" id="editTankerNumber" readonly>
          </div>
          <div class="form-group">
            <label for="editStatus">Status</label>
            <select class="form-control" id="editStatus" required>
              <option value="0">Pending</option>
              <option value="1">Completed</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updatePickupBtn">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<!-- JavaScript for Edit Pickup Modal -->
<script>
  // Event handler for selecting a driver name
  $('#editDriverName').on('change', function() {
    var selectedOption = $(this).find(':selected');
    var tankerNumber = selectedOption.data('tanker-number');
    var capacity = selectedOption.data('capacity');
    var employeeIdEdit = selectedOption.data('employee-id');

    // Set the tanker number and capacity in the corresponding input fields
    $('#editTankerNumber').val(tankerNumber);
    $('#editCapacity').val(capacity);
    $('#editEmployeeId').val(employeeIdEdit);
  });
</script>

<!-- Delete Pickup Modal -->
<div class="modal fade" id="deletePickupModal" tabindex="-1" role="dialog" aria-labelledby="deletePickupModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="deletePickupModalLabel">Confirm Deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this pickup?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
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


<!-- AdminLTE App -->
<script src="js/adminlte.js"></script>
</body>
</html>