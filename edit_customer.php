<?php
require_once 'dbcon.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the form data
  $customerId = $_POST['editCustomerId'];
  $customerName = $_POST['editCustomerName'];
  $email = $_POST['editEmail'];
  $phone = $_POST['editPhone'];
  $address = $_POST['editAddress'];
  $balance = $_POST['editBalance'];
  $balance = $_POST['editBalance'];
  $balance = str_replace(',', '', $balance);


  // Perform validation and error handling
  $errors = array();

  // Validate customer name
  if (empty($customerName)) {
    $errors[] = 'Customer name is required.';
  }

  // Validate email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
  }

  // Validate phone number
  if (empty($phone)) {
    $errors[] = 'Phone number is required.';
  }

  // Validate address
  if (empty($address)) {
    $errors[] = 'Address is required.';
  }

  // Validate balance
  if (!is_numeric($balance)) {
    $errors[] = 'Invalid balance format.';
  }

  // Check if there are any validation errors
  if (!empty($errors)) {
    // Send an error response back to the client
    echo json_encode(array('status' => 'error', 'message' => implode(' ', $errors)));
    exit;
  }

  // Prepare the SQL statement
  $query = "UPDATE Customers SET CustomerName=?, Email=?, Phone=?, Address=?, Balance=? WHERE CustomerID=?";
  $stmt = mysqli_prepare($connect, $query);
  mysqli_stmt_bind_param($stmt, 'sssssi', $customerName, $email, $phone, $address, $balance, $customerId);

  // Execute the statement
  if (mysqli_stmt_execute($stmt)) {
    // Send a success response back to the client
    echo json_encode(array('status' => 'success', 'message' => 'Customer updated successfully.'));
  } else {
    // Send an error response back to the client
    echo json_encode(array('status' => 'error', 'message' => 'Failed to update customer.'));
  }

  // Close the statement
  mysqli_stmt_close($stmt);
}
?>
