    $(document).ready(function() {
      // Initialize DataTable
      var paymentTable = $('#paymentTable').DataTable({
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
          url: 'fetch_payment_data.php', // Replace with your server-side script to retrieve payment data
          dataSrc: ''
        },
        columns: [
          { data: 'PaymentID' },
           { data: 'FuelSalesID' },
          { data: 'TankerSalesID' },
          { data: 'CustomerID' },
          { data: 'CustomerName' },
          {
            data: "PaymentAmount",
            render: function(data, type, row) {
              // Format the salary with comma separators and add the Naira sign
              var formattedAmount = '₦' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              return formattedAmount;
          }
          },
          {
            data: "PaymentDate",
            render: function(data, type, row) {
              // Reverse the date format using moment.js
              var reversedDate = moment(data).format("DD-MM-YYYY");
              return reversedDate;
            }
          },
          { data: 'BankName' },
          
          {
            data: null,
            render: function(data, type, row) {
              var paymentId = data.PaymentID;
              var editButton = '<button type="button" class="btn btn-primary btn-edit-payment" data-toggle="modal" data-target="#editPaymentModal" data-payment-id="' + paymentId + '"><i class="fas fa-edit"></i> Edit</button>';
              var deleteButton = '<button type="button" class="btn btn-danger btn-delete-payment" data-toggle="modal" data-target="#deletePaymentModal" data-payment-id="' + paymentId + '"><i class="fas fa-trash"></i> Delete</button>';
              return editButton + ' ' + deleteButton;
            }
          }
        ],
            columnDefs: [
          { targets: [1,2,3], visible: false }
        ],
        order: [[5, 'desc']] 
      });

$('#paymentTable').on('click', '.btn-edit-payment', function() {
  var paymentId = $(this).data('payment-id');

  var paymentData = paymentTable.row($(this).closest('tr')).data();

  // Populate the edit payment modal with the retrieved payment details
  $('#editPaymentDate').val(paymentData.PaymentDate);
  $('#editPaymentAmount').val(paymentData.PaymentAmount);
  $('#editPaymentID').val(paymentData.PaymentID);
  $('#editCustomerID').val(paymentData.CustomerID);
$('#editFuelSalesID').val(paymentData.FuelSalesID);
  $('#editBankName').val(paymentData.BankName).trigger('change');
  $('#editCustomerName').val(paymentData.CustomerName).trigger('change');
});


 $('#updatePayment').on('click', function(event) {
    event.preventDefault(); // Prevent default button behavior

  var formData = $('#editPaymentForm').serialize();

  // Submit the form via AJAX
  $.ajax({
    url: 'edit_payment.php', // Replace with the correct path to the PHP script
    method: 'POST',
    data: formData,
    success: function(response) {
      if (response.status === 'success') {
        // Payment updated successfully
        toastr.success(response.message);

        // Close the modal
        $('#editPaymentModal').modal('hide');

        // Refresh the payment table
        paymentTable.ajax.reload();
      } else if (response.status === 'error') {
        // Display each error using Toastr
        response.errors.forEach(function(error) {
          toastr.error(error);
        });
      }
    },
    error: function() {
      // Handle the error case
      toastr.error('Failed to update payment.');
    }
  });
});


// Handle click event for add payment button
$('#addPaymentModal').on('shown.bs.modal', function() {
  // Clear the form fields
  $('#addPaymentForm')[0].reset();
  // Clear the customer name field
  $('#customerName').val('').trigger('change');
  // Clear the fuel sale field dropdown
  $('#fuelSalesID').val('').trigger('change');
  // Clear the balance text
  $('#balanceText').text('');
   var currentDate = moment().format('DD-MM-YYYY');
  $('#paymentDate').val(currentDate);

});

// Handle form submission for adding a new payment
$('#addPaymentForm').on('submit', function(event) {
  event.preventDefault();

  // Gather the form data
  var formData = new FormData($(this)[0]);
  var selectedCustomerName = $('#customerName option:selected').val();
  var selectedBankName = $('#bankName option:selected').val();
  var selectedfuelSaleID = $('#fuelSalesID option:selected').val();
  var selectedPaymentDate = moment($('#paymentDate').val(), 'DD MM YYYY').format('YYYY-MM-DD');
  formData.append('customerName', selectedCustomerName);
  formData.append('bankName', selectedBankName);
  formData.append('fuelSalesID', selectedfuelSaleID);
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

        // Refresh the payment table
        paymentTable.ajax.reload();
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

// Handle delete action for a payment
$('#paymentTable tbody').on('click', '.btn-delete-payment', function() {
  var paymentID = $(this).data('payment-id');

  // Show the delete confirmation modal
  $('#deletePaymentModal').modal('show');
 $('#deletePaymentModal').data('paymentID', paymentID);
});

// Handle delete confirmation
$('#confirmDeletePayment').on('click', function() {
  var paymentID = $('#deletePaymentModal').data('paymentID');
  // Send the delete request via AJAX
  $.ajax({
    url: 'delete_payment.php', // Replace with the correct path to the PHP script
    method: 'POST',
    data: { paymentID: paymentID },
    success: function(response) {
      if (response.status === 'success') {
        // Payment deleted successfully
        toastr.success(response.message);

        // Refresh the payment table
        paymentTable.ajax.reload();
      } else if (response.status === 'error') {
        // Failed to delete payment
        toastr.error(response.message);
      }
    },
    error: function() {
      // Handle the error case
      toastr.error('Failed to delete payment.');
    }
  });

  // Hide the delete confirmation modal
  $('#deletePaymentModal').modal('hide');
});


    });



 $(document).ready(function() {
  $('#fuelSalesID').on('change', function() {
    var selectedFuelSale = $(this).val();
    var selectedTankerID = $('option:selected', this).data('tanker-id');
    var selectedCustomerName = $('option:selected', this).data('customer-name'); // Add this line to retrieve the selected customer name
    $('#tankerSalesID').val(selectedTankerID);
    $('#customerName').val(selectedCustomerName).trigger('change'); // Set the selected customer name and trigger the 'change' event

    fetchFuelSaleDetails();
  });

  $('#bankName,#editBankName, #fuelSalesID, #customerName, #editCustomerName').select2();
  // Fetch fuel sale details when the customer name is changed
  $('#customerName').on('change', function() {
    var selectedCustomerID = $('option:selected', this).data('customer-id');
    $('#customerNameId').val(selectedCustomerID);
  });
});


 // Function to fetch fuel sale details
function fetchFuelSaleDetails() {
  var fuelSaleID = document.getElementById("fuelSalesID").value;

  // Send an AJAX request to fetch the fuel sale details
  var url = "fetch_fuel_sale_details.php?fuelSaleID=" + fuelSaleID;
  var xhr = new XMLHttpRequest();
  xhr.open("GET", url, true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.status === "success") {
        // Update the tanker sales ID, fuel sale amount, and balance fields
        document.getElementById("tankerSalesID").value = response.tankerSalesID;
        document.getElementById("fuelSaleAmount").value = "₦" + formatAmountt(response.balance); 
        document.getElementById("paymentAmount").value = formatAmountt(response.balance); 
         document.getElementById("balanceText").textContent = "Total: ₦" + formatAmountt(response.fuelSaleAmount);
      }
    }
  };
  xhr.send();
}

// Function to format the amount with commas
function formatAmountt(amount) {
  return amount.toLocaleString();
}

 $(function() {
  $("#paymentDate").datepicker({
    dateFormat: "dd-mm-yy", // Format the date as "yyyy-mm-dd"
    // changeMonth: true, // Allow month selection
    // changeYear: true, // Allow year selection
    yearRange: "1900:2023", // Set the range of selectable years
     autoclose: true,
  });
});
    // Event handler for selecting a driver name
  $('#customerName').on('change', function() {
    var selectedOption = $(this).find(':selected');
    var employeeId = selectedOption.data('customer-id');
    // Set the tanker number, employee ID, and capacity in the corresponding input fields
    $('#customerNameId').val(employeeId);
  });

  $(function() {
  $("#paymentDate, #editPaymentDate").datepicker({
    dateFormat: "dd-mm-yy", // Format the date as "yyyy-mm-dd"
    // changeMonth: true, // Allow month selection
    // changeYear: true, // Allow year selection
    yearRange: "1900:2023",
    autoclose: true, // Set the range of selectable years
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

    // Event handler for selecting a driver name
  $('#editCustomerName').on('change', function() {
    var selectedOption = $(this).find(':selected');
    var employeeId = selectedOption.data('customer-id');
    // Set the tanker number, employee ID, and capacity in the corresponding input fields
    $('#editCustomerID').val(employeeId);
  });
