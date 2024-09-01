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
  <link rel="stylesheet" href="plugins/jquery-ui/jquery-ui.css">



</head>     

<?php include 'header.php';?>
<div class="content-wrapper mt-5">
  <section class="content-header">
     <div class="container-fluid">
  <div class="row mt-2">
    <div class="col">
      <h1 class="">Employees</h1>
   </div>
   <div class="col-auto">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployeeModal">
 <i class="fas fa-plus"></i> Add Employee
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
      <table id="employeeTable" class="table table-bordered">
      <thead>
        <tr>
          <th>Employee ID</th>
          <th>Surname</th>
          <th>Othername</th>
          <th>Role</th>
          <th>Salary</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Email</th>
          <th>Gender</th>
          <th>DOB</th>
          <th>State</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Payment data will be dynamically populated here -->
      </tbody>
    </table>
<style>
    #employeeTable_wrapper {
  overflow-x: hidden !important;
}
</style>

<script>
  $(document).ready(function() {
  // DataTable initialization
  var employeeTable = $('#employeeTable').DataTable({
    dom: '<"top"<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>>rt<"bottom mt-2"lip><"clear">',
    buttons: ['print', 'copy', 'pdf', 'colvis'],
    responsive: true,
    scrollX: false,
    scrollY: '460px',
    scrollCollapse: true,
    fixedHeader: true,
    lengthChange: false,
    autoWidth: false,
    ajax: {
      url: 'fetch_employees.php',
      dataSrc: ''
    },
    columns: [
      { data: 'employee_id' },
      { data: 'surname' },
      { data: 'othername' },
      { data: 'role' },
      {
        data: "salary",
        render: function(data, type, row) {
          // Format the salary with comma separators and add the Naira sign
          var formattedSalary = '₦' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          return formattedSalary;
        }
      },

      { data: 'phonenumber'},
      { data: 'address' },
      { data: 'email' },
      { data: 'sex' },
      { data: 'dob' },
      { data: 'state_origin' },
      {
        data: null,
        render: function(data, type, row) {
          var employeeId = row.employee_id;
          return '<button class="btn btn-primary edit-btn mr-2" data-toggle="modal" data-target="#editEmployeeModal" data-id="' + employeeId + '"><i class="fas fa-edit"></i> Edit</button> ' +
                 '<button class="btn btn-danger delete-btn" data-toggle="modal" data-target="#deleteEmployeeModal" data-id="' + employeeId + '"><i class="fas fa-trash"></i> Delete</button>';
        }
      }
    ],
        columnDefs: [
      { targets: [6,7,8,9], visible: false }
    ]
  });


// Handle edit employee modal
$('#editEmployeeModal').on('show.bs.modal', function(event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var employeeId = button.data('id'); // Get the employee ID from the button data attribute

  // Find the row with the corresponding employee ID
  var selectedRow = employeeTable.rows().data().filter(function(rowData) {
    return rowData.employee_id === employeeId;
  });

  // Populate the form fields with the retrieved employee data
  $('#editEmployeeForm #editEmployeeId').val(selectedRow[0].employee_id);
  $('#editEmployeeForm #editSurname').val(selectedRow[0].surname);
  $('#editEmployeeForm #editOthername').val(selectedRow[0].othername);
  $('#editEmployeeForm #editRole').val(selectedRow[0].role);
  $('#editEmployeeForm #editSalary').val(selectedRow[0].salary);
  $('#editEmployeeForm #editPhoneNumber').val(selectedRow[0].phonenumber);
  $('#editEmployeeForm #editAddress').val(selectedRow[0].address);
  $('#editEmployeeForm #editEmail').val(selectedRow[0].email);
  $('#editEmployeeForm #editSex').val(selectedRow[0].sex);
  $('#editEmployeeForm #editDob').val(selectedRow[0].dob);
  $('#editEmployeeForm #editStateOrigin').val(selectedRow[0].state_origin);
});

$('#editEmployeeModal').on('click', '#updateEmployee', function(event) {

  event.preventDefault(); // Prevent default form submission

  // Get form data
  var formData = $('#editEmployeeForm').serialize();

  // Send data to the server for updating the employee record
  $.ajax({
    url: 'update_employee.php',
    type: 'POST',
    data: formData,
     success: function(response) {
      // Handle success response
      toastr.success('Employee updated successfully!');
      
      // Refresh the DataTable to show the updated data
      employeeTable.ajax.reload();

      // Close the modal
      $('#editEmployeeModal').modal('hide');
    },
    error: function(xhr, status, error) {
      // Handle error response
      if (xhr.status === 400) {
        // Validation errors
        var errors = JSON.parse(xhr.responseText).errors;
        errors.forEach(function(error) {
          toastr.error(error);
        });
      } else {
        toastr.error('An error occurred while updating the employee.');
      }
    }
  });
});

  // Delete button click event
  $('#employeeTable tbody').on('click', '.delete-btn', function() {
    employeeId = $(this).data('id');

    // Set the employee ID in the delete modal
    $('#deleteEmployeeModal').data('employeeId', employeeId);
  });

  // Confirm delete button click event in delete modal
$('#confirmDeleteEmployee').on('click', function() {
  // Retrieve the employee ID from the delete modal
  employeeId = $('#deleteEmployeeModal').data('employeeId');

  // Perform an AJAX request to delete the employee
  $.ajax({
    url: 'delete_employee.php',
    type: 'POST',
    data: { employeeId: employeeId },
    success: function(response) {
      // Handle the response after deleting the employee
      // Display success message
      toastr.success(response);
      employeeTable.ajax.reload();
      // Close the delete modal
      $('#deleteEmployeeModal').modal('hide');
    },
    error: function(xhr, status, error) {
      // Handle the error
      toastr.error('An error occurred while deleting the employee: ' + error);
    }
  });
});

// Handle the add employee modal
  $('#addEmployeeModal').on('show.bs.modal', function(event) {
    // Clear existing form data
    $('#employeeForm')[0].reset();
     // Generate the next Employee ID
  var nextEmployeeId = generateNextEmployeeId();
  
  // Set the generated ID as the value of the Employee ID input field
  $('#employeeId').val(nextEmployeeId);
  });
// Function to generate the next Employee ID
function generateNextEmployeeId() {
  // Get the last Employee ID from the DataTable
  var employeeIds = employeeTable.column(0).data().toArray();
  if (employeeIds.length === 0) {
    // Handle the case when there are no employee IDs in the table
    return 'E001'; // or any desired default value
  }

  var lastEmployeeId = employeeIds.pop();

  // Extract the numeric part of the last Employee ID and increment it
  var lastIdNumber = parseInt(lastEmployeeId.slice(1));
  var nextIdNumber = lastIdNumber + 1;

  // Format the next Employee ID with leading zeros
  var nextEmployeeId = 'E' + String(nextIdNumber).padStart(3, '0');

  return nextEmployeeId;
}

   // Handle "Save" button click
  $('#saveEmployee').on('click', function(event) {
    event.preventDefault(); // Prevent default button behavior

    // Get form data
    var formData = $('#employeeForm').serialize();

    // Send data to the server for processing
    $.ajax({
      url: 'add_employee.php',
      type: 'POST',
      data: formData,
       success: function(response) {
      // Handle success response
      toastr.success('Employee Added Successfully!');
      
      // Refresh the DataTable to show the updated data
      employeeTable.ajax.reload();

      // Close the modal
      $('#addEmployeeModal').modal('hide');
    },
    error: function(xhr, status, error) {
      // Handle error response
      if (xhr.status === 400) {
        // Validation errors
        var errors = JSON.parse(xhr.responseText).errors;
        errors.forEach(function(error) {
          toastr.error(error);
        });
      } else {
        toastr.error('An error occurred while adding the employee.');
      }
    }
    });
  });
});
</script>
<script>
function formatAmount(input) {
  // Get the input value and remove any non-numeric characters and commas
  var value = input.value.replace(/[^0-9]/g, '').replace(/,/g, '');
  
  // Format the value with commas separating the thousands
  var formattedValue = Number(value).toLocaleString('en');
  
  // Set the input value to the formatted value
  input.value = formattedValue;
}

$(function() {
  $("#dob,#editDob").datepicker({
    dateFormat: "yy-mm-dd", // Format the date as "yyyy-mm-dd"
    changeMonth: true, // Allow month selection
    changeYear: true, // Allow year selection
    yearRange: "1900:2023" // Set the range of selectable years
  });
});

</script>

<!-- The modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="employeeForm">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="employeeId">Employee ID</label>
                <input type="text" class="form-control" id="employeeId" name="employeeId" readonly required>
              </div>
              <div class="form-group">
                <label for="surname">Surname</label>
                <input type="text" class="form-control" id="surname" name="surname" required>
              </div>
              <div class="form-group">
                <label for="othername">Other Name</label>
                <input type="text" class="form-control" id="othername" name="othername" required>
              </div>
              <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role" required>
                  <option disabled value="">Select Role</option>
                  <option value="Driver">Driver</option>
                  <option value="Fuel Attendant">Fuel Attendant</option>
                  <option value="Cleaner">Cleaner</option>
                  <option value="Station Manager">Station Manager</option>
                  <option value="Maintenance Manager">Maintenance Manager</option>
                  <option value="Junior Driver">Junior Driver</option>
                  <option value="Engineer">Engineer</option>
                  <option value="Others">Others</option>
                </select>
              </div>
              <div class="form-group">
                <label for="sex">Gender</label>
                <select class="form-control" id="sex" name="sex" required>
                  <option disabled value="">Select Gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
              <div class="form-group">
                <label for="salary">Salary</label>
                <input type="text" class="form-control" onkeyup="formatAmount(this)" id="salary" name="salary" required>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" required>
              </div>
              <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="text" class="form-control" id="dob" name="dob" required>
              </div>
              <div class="form-group">
                <label for="stateOrigin">State of Origin</label>
                <select class="form-control" id="stateOrigin" name="stateOrigin" required>
                  <option value="Abia">Abia</option>
                  <option selected value="Adamawa">Adamawa</option>
                  <option value="Akwa Ibom">Akwa Ibom</option>
                  <option value="Anambra">Anambra</option>
                  <option value="Bauchi">Bauchi</option>
                  <option value="Bayelsa">Bayelsa</option>
                  <option value="Benue">Benue</option>
                  <option value="Borno">Borno</option>
                  <option value="Cross River">Cross River</option>
                  <option value="Delta">Delta</option>
                  <option value="Ebonyi">Ebonyi</option>
                  <option value="Edo">Edo</option>
                  <option value="Ekiti">Ekiti</option>
                  <option value="Enugu">Enugu</option>
                  <option value="F C T">F C T</option>
                  <option value="Gombe">Gombe</option>
                  <option value="Imo">Imo</option>
                  <option value="Jigawa">Jigawa</option>
                  <option value="Kaduna">Kaduna</option>
                  <option value="Kano">Kano</option>
                  <option value="Katsina">Katsina</option>
                  <option value="Kebbi">Kebbi</option>
                  <option value="Kogi">Kogi</option>
                  <option value="Kwara">Kwara</option>
                  <option value="Lagos">Lagos</option>
                  <option value="Nasarawa">Nasarawa</option>
                  <option value="Niger">Niger</option>
                  <option value="Ogun">Ogun</option>
                  <option value="Ondo">Ondo</option>
                  <option value="Osun">Osun</option>
                  <option value="Oyo">Oyo</option>
                  <option value="Plateau">Plateau</option>
                  <option value="Rivers">Rivers</option>
                  <option value="Sokoto">Sokoto</option>
                  <option value="Taraba">Taraba</option>
                  <option value="Yobe">Yobe</option>
                  <option value="Zamfara">Zamfara</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveEmployee">Save</button>
      </div>
    </div>
  </div>
</div>



<!-- The modal for editing an employee -->
<div class="modal fade " id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editEmployeeForm">
           <div class="row">
            <div class="col-lg-6">
          <div class="form-group">
            <label for="editEmployeeId">Employee ID</label>
            <input type="text" class="form-control" id="editEmployeeId" readonly name="editEmployeeId" required>
          </div>
          <div class="form-group">
            <label for="editSurname">Surname</label>
            <input type="text" class="form-control" name="editSurname" id="editSurname" required>
          </div>
          <div class="form-group">
            <label for="editOthername">Other Name</label>
            <input type="text" class="form-control" id="editOthername" name="editOthername" required>
          </div>
          <div class="form-group">
            <label for="editRole">Role</label>
            <select class="form-control" id="editRole" name="editRole" required>
            <option disabled value="">Select Role</option>
            <option value="Driver">Driver</option>
            <option value="Fuel Attendant">Fuel Attendant</option>
            <option value="Cleaner">Cleaner</option>
            <option value="Station Manager">Station Manager</option>
            <option value="Maintenance Manager">Maintenance Manager</option>
            <option value="Junior Driver">Junior Driver</option>
            <option value="Engineer">Engineer</option>
            <option value="Others">Others</option>
          </select>
          </div>
          <div class="form-group">
            <label for="editSex">Gender</label>
            <select class="form-control" id="editSex" name="editSex" required>
              <option disabled value="">Select Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          <div class="form-group">
            <label for="editSalary">Salary</label>
            <input type="text" class="form-control" name="editSalary" id="editSalary" onkeyup="formatAmount(this)" required>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="editPhoneNumber">Phone Number</label>
            <input type="text" class="form-control" id="editPhoneNumber" name="editPhoneNumber" required>
          </div>
          <div class="form-group">
            <label for="editAddress">Address</label>
            <input type="text" class="form-control" id="editAddress" name="editAddress" required>
          </div>
          <div class="form-group">
            <label for="editEmail">Email</label>
            <input type="email" class="form-control" id="editEmail" name="editEmail" required>
          </div>
          <div class="form-group">
            <label for="editDob">Date of Birth</label>
            <input type="text" class="form-control" id="editDob" name="editDob" required>
          </div>
          <div class="form-group">
            <label for="editStateOrigin">State of Origin</label>
            <select class="form-control" name="editStateOrigin" id="editStateOrigin" required>
            <option value="Abia">Abia</option>
            <option selected value="Adamawa">Adamawa</option>
            <option value="Akwa Ibom">Akwa Ibom</option>
            <option value="Anambra">Anambra</option>
            <option value="Bauchi">Bauchi</option>
            <option value="Bayelsa">Bayelsa</option>
            <option value="Benue">Benue</option>
            <option value="Borno">Borno</option>
            <option value="Cross River">Cross River</option>
            <option value="Delta">Delta</option>
            <option value="Ebonyi">Ebonyi</option>
            <option value="Edo">Edo</option>
            <option value="Ekiti">Ekiti</option>
            <option value="Enugu">Enugu</option>
            <option value="F C T">F C T</option>
            <option value="Gombe">Gombe</option>
            <option value="Imo">Imo</option>
            <option value="Jigawa">Jigawa</option>
            <option value="Kaduna">Kaduna</option>
            <option value="Kano">Kano</option>
            <option value="Katsina">Katsina</option>
            <option value="Kebbi">Kebbi</option>
            <option value="Kogi">Kogi</option>
            <option value="Kwara">Kwara</option>
            <option value="Lagos">Lagos</option>
            <option value="Nasarawa">Nasarawa</option>
            <option value="Niger">Niger</option>
            <option value="Ogun">Ogun</option>
            <option value="Ondo">Ondo</option>
            <option value="Osun">Osun</option>
            <option value="Oyo">Oyo</option>
            <option value="Plateau">Plateau</option>
            <option value="Rivers">Rivers</option>
            <option value="Sokoto">Sokoto</option>
            <option value="Taraba">Taraba</option>
            <option value="Yobe">Yobe</option>
            <option value="Zamfara">Zamfara</option>
          </select>
          </div>
        </div>
      </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="updateEmployee">Save Changes</button>
      </div>
    </div>
  </div>
</div>



<!-- Delete Employee Modal -->
<div class="modal fade" id="deleteEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteEmployeeModalLabel">Delete Employee</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this employee?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteEmployee">Delete</button>
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

<!-- AdminLTE App -->
<script src="js/adminlte.js"></script>
</body>
</html>