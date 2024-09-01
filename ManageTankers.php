<?php 
include 'admin_session_check.php'; 
require_once 'dbcon.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <script>
  function toggleDarkMode() {
  var body = document.querySelector('body');
  body.classList.toggle('dark-mode');
}
</script>
  <script src="js/jquery.min.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BUNGEL Tanker</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/toastr/toastr.css">
  <link rel="stylesheet" href="css/adminlte.min.css">
   <link rel="stylesheet" href="css/darkmode.css">
</head>

<?php include 'header.php';?>
<div class="content-wrapper mt-5">
      <section class="content-header">
      </section>

      <!-- Main content -->
  <section class="content">
        <!-- Add your dashboard items here -->
        
  <div class="row justify-content-center">
  <div class="col-lg-11 col-md-8 col-sm-10">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h3>Tankers List</h3>
          <div class="d-flex">
            <div class="mr-3">
              <button class="btn btn-primary mt-2" onclick="window.print()">Print</button>
            </div>
            <div>
              <!-- <label for="filter" class="mr-2">Filter:</label> -->
              <select id="filter" class="form-control mt-2">
                <option value="all">All</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <button type="button" class="btn btn-primary mt-2 ml-3" data-toggle="modal" data-target="#addModal">
       <i class='fas fa-plus'></i> Add Tanker
      </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
         <table id="tankersTable" class="table table-hover">
  <thead class="bg-light">
    <tr>
      <th>SN</th>
      <th>Tanker Number</th>
      <th>Driver Name</th>
      <th>Employee ID</th>
      <th>Capacity</th>
      <th>Status</th>
      <th>Actions</th> <!-- Add a new column for actions -->
    </tr>
  </thead>
  <tbody>
    <?php
      // Assuming $connection is your established database connection
      $query = "SELECT * FROM tankers";
      $result = mysqli_query($connect, $query);

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . $row['tankerId'] . "</td>";
          echo "<td>" . $row['tanker_number'] . "</td>";
          echo "<td>" . $row['driver_name'] . "</td>";
          echo "<td>" . $row['employee_id'] . "</td>";
           echo "<td>" . number_format($row['capacity']) . "</td>";
          echo "<td>" . $row['status'] . "</td>";
         echo '<td>';
                    echo '<button type="button" class="btn btn-primary edit-btn" data-id="' . $row['tankerId'] . '"><i class="fas fa-edit"></i> Edit</button> ';
                    echo '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row['tankerId'] . '"><i class="fas fa-trash"></i> Delete</button>';
                    echo '</td>';
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='7'>No records found.</td></tr>";
      }
    ?>
    <!-- Table data goes here -->
  </tbody>
</table>

        </div>
      </div>
    </div>
  </div>
</div> 

<!-- Add Modal -->
<div id="addModal" class="modal fade" role="dialog">
  <!-- Modal content -->
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addForm" method="POST">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Add Tanker</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="tanker_number">Tanker Number:</label>
            <input type="text" class="form-control" id="tanker_number" name="tanker_number" required>
          </div>
          <div class="form-group">
            <label for="driver_name">Driver Name:</label>
            <select class="form-control" id="driver_name" name="driver_name" required>
      <option disabled selected value="">Select Driver Name</option>
               <?php
          $query = "SELECT employee_id, surname FROM employees WHERE role = 'Driver'";
          $result = mysqli_query($connect, $query);

          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $driverName = $row['surname'];
              $employeeId = $row['employee_id'];

              echo "<option value=\"$driverName\" data-employee-id=\"$employeeId\">$driverName</option>";
            }
          }
          ?>

            </select>
          </div>
          <div class="form-group">
            <label for="capacity">Capacity:</label>
            <select class="form-control" id="capacity" name="capacity" required>
              <option value="45000">45,000</option>
              <option value="50000">50,000</option>
              <option value="55000">55,000</option>
              <option value="60000">60,000</option>
            </select>
          </div>
          <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status" required>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label for="employee_id">Employee ID:</label>
            <input type="text" class="form-control" id="employee_id" name="employee_id" readonly required >
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    // Event handler for selecting a driver name
  $('#driver_name').on('change', function() {
    var selectedOption = $(this).find(':selected');
    var employeeId = selectedOption.data('employee-id');
    // Set the tanker number, employee ID, and capacity in the corresponding input fields
    $('#employee_id').val(employeeId);
  });
</script>

        <!-- Edit Modal -->
      <div id="editModal" class="modal fade" role="dialog">
  <!-- Modal content -->
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editForm" method="POST">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Edit Tanker</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="edit_tankerId">Tanker ID:</label>
            <input type="text" class="form-control" id="editTankerId" name="tankerId" readonly required>
          </div>
          <div class="form-group">
            <label for="edit_tanker_number">Tanker Number:</label>
            <input type="text" class="form-control" id="edit_tanker_number" name="tanker_number" required>
          </div>
          <div class="form-group">
            <label for="edit_driver_name">Driver Name:</label>
            <select class="form-control" id="edit_driver_name" name="driver_name" required>
     
               <?php
          $query = "SELECT employee_id, surname FROM employees WHERE role = 'Driver'";
          $result = mysqli_query($connect, $query);

          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $driverName = $row['surname'];
              $employeeId = $row['employee_id'];

              echo "<option value=\"$driverName\" data-employee-id=\"$employeeId\">$driverName</option>";
            }
          }
          mysqli_close($connect);
          ?>

            </select>
          </div>
          <div class="form-group">
            <label for="edit_capacity">Capacity:</label>
            <select class="form-control" id="edit_capacity" name="capacity" required>
              <option value="45000">45,000</option>
              <option value="50000">50,000</option>
              <option value="55000">55,000</option>
              <option value="60000">60,000</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_status">Status:</label>
            <select class="form-control" id="edit_status" name="status" required>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_employee_id">Employee ID:</label>
            <input type="text" class="form-control" id="edit_employee_id" name="employee_id" readonly required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    // Event handler for selecting a driver name
  $('#edit_driver_name').on('change', function() {
    var selectedOption = $(this).find(':selected');
    var employeeId = selectedOption.data('employee-id');
    // Set the tanker number, employee ID, and capacity in the corresponding input fields
    $('#edit_employee_id').val(employeeId);
  });
</script>
        <!-- Delete Modal -->
        <div id="deleteModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content -->
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title">Confirm Delete</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this tanker?</p>
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" method="POST">
                            <input type="hidden" id="delete_tankerId" name="tankerId">
                            <button type="submit" class="btn btn-danger">Delete</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



 <script>
   $(document).ready(function() {
  // Add tanker form submit event
$(document).on("submit", "#addForm", function(e) {
  e.preventDefault();
  var form = this; // Store the form reference
  var formData = $(this).serialize();

  // Submit the form using AJAX
  $.ajax({
    url: "add_tanker.php",
    type: "POST",
    data: formData,
    success: function(response) {
      // Display success notification
      $("#addModal").modal("hide");
      toastr.success("Tanker added successfully.");

      // Clear the form fields and close the modal
      form.reset();
      refreshTable();
    },
    error: function(xhr, status, error) {
      if (xhr.status === 400) {
        // Display error notification
        toastr.error(xhr.responseText);
      } else {
        // Handle other error cases if needed
        // ...
      }
    }
  });
});



  // Delete tanker button click event
  $(document).on("click", ".delete-btn", function() {
    var tankerId = $(this).data("id");

    // Set the tankerId in the delete form
    $("#delete_tankerId").val(tankerId);

    // Show the delete modal
    $("#deleteModal").modal("show");
  });

  // Delete tanker form submit event
  $(document).on("submit", "#deleteForm", function(e) {
    e.preventDefault();
    var formData = $(this).serialize();

    // Submit the form using AJAX
    $.ajax({
      url: "delete_tanker.php",
      type: "POST",
      data: formData,
      success: function(response) {
        // Display success notification
        toastr.success("Tanker deleted successfully.");

        // Close the modal
        $("#deleteModal").modal("hide");
        refreshTable();
      },
      error: function(xhr, status, error) {
        // Display error notification
        toastr.error("Failed to delete tanker (User Have a Record) " + xhr.responseText);
      }
    });
  });




$(document).on("click", ".edit-btn", function() {
  var tankerId = $(this).data("id");
console.log(tankerId);
  // Reset the form and clear any previous error messages
  $("#editForm")[0].reset();
  $(".error-message").text("");

  // Fetch the tanker data from the server using AJAX
  $.ajax({
    url: "fetch_tanker.php",
    type: "POST",
    data: { tankerId: tankerId },
    dataType: "json",
    success: function(response) {
      if (response.status === "success") {
        // Set the values in the edit modal
        $("#editTankerId").val(response.data.tankerId);
        $("#edit_tanker_number").val(response.data.tankerNumber);
        $("#edit_driver_name").val(response.data.driverName);
        $("#edit_capacity").val(response.data.capacity);
        $("#edit_status").val(response.data.status);
        $("#edit_employee_id").val(response.data.employeeId);

        // Show the edit modal
        $("#editModal").modal("show");
      } else {
        // Show an error message
        toastr.error(response.message);
      }
    },
    error: function(xhr, status, error) {
      // Show an error message
      toastr.error("Failed to fetch tanker data: " + xhr.responseText);
    }
  });
});

$(document).on("submit", "#editForm", function(e) {
  e.preventDefault();
  var form = this; // Store the form reference
  var formData = $(this).serialize();

  // Submit the form using AJAX
  $.ajax({
    url: "edit_tanker.php",
    type: "POST",
    data: formData,
    success: function(response) {
      if (response === "Tanker updated successfully.") {
        // Display success notification
        $("#editModal").modal("hide");
        toastr.success(response);

        // Clear the form fields and close the modal
        form.reset();
        refreshTable();
      } else {
        // Display error notification
        toastr.error(response);
      }
    },
    error: function(xhr, status, error) {
      // Display error notification
      toastr.error("Failed to update tanker: " + xhr.responseText);
    }
  });
});


  // Function to refresh the table
  function refreshTable() {
    // Fetch and update the table content using AJAX
    $.ajax({
      url: "fetch_tanker_table.php", // Replace with the URL to fetch the updated table content
      type: "GET",
      success: function(response) {
        // Update the table with the new content
        $("#tankersTable tbody").html(response);
      },
      error: function(xhr, status, error) {
        // Display error notification
        toastr.error("Failed to fetch table data: " + xhr.responseText);
      }
    });
  }
});

 </script>





</section>
    </div>
    <?php include 'footer.html'; ?>
  </div>

<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- jQuery -->

<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/toastr/toastr.min.js"></script>
<script src="js/adminlte.js"></script>

</body>
</html>