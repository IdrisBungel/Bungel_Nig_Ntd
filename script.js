require_once 'dbcon.php';
$(document).ready(function() {
  var tankerPickupsTable = $('#tankerPickupsTable').DataTable({
    "ajax": "fetch_pickupsTable.php", // Replace with your PHP script to retrieve pickup data
    "columns": [
      { "data": "pickup_id" },
      { "data": "tanker_number" },
      { "data": "driver_name" },
      { "data": "driverID" },
      { "data": "pickup_date" },
      { "data": "capacity" },
      { "data": "category" },
      { "data": "FuelType" },
      { "data": "destination" },
      { "data": "depot" },
      { "data": "status" }
    ]
    
  
  });

  // Open the modal when clicking the "Add Pickup" button
  $('#addPickupBtn').on('click', function() {
    $('#addPickupModal').show();
  });

  // Handle form submission when adding a pickup
  $('#addPickupForm').on('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
      url: 'add_pickup.php', // Replace with your PHP script to add the pickup
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        toastr.success('Pickup added successfully');

        // Refresh the table with the latest data
        tankerPickupsTable.ajax.reload();

        // Clear the form inputs and hide the modal
        $('#addPickupForm')[0].reset();
        $('#addPickupModal').hide();
      },
      error: function(error) {
        toastr.error('Unable to add pickup');
      }
    });
  });
});
