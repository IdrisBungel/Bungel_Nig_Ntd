<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'dbcon.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the form data
  $customerName = $_POST['customerName'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $account = str_replace(',', '', $_POST['balance']);


  // Perform validation and error handling
  $errors = array();

  // Validate customer name
  if (empty($customerName)) {
    $errors[] = 'Customer name is required.';
  }

  // Validate email
  // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  //   $errors[] = 'Invalid email format.';
  // }

  // // Validate phone number
  // if (empty($phone)) {
  //   $errors[] = 'Phone number is required.';
  // } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
  //   $errors[] = 'Invalid phone number format.';
  // }

  // Validate address
  if (empty($address)) {
    $errors[] = 'Address is required.';
  }

  // Validate account
  if (!is_numeric($account)) {
    $errors[] = 'Invalid account format.';
  }

  // Check if there are any validation errors
  if (!empty($errors)) {
    // Send an error response back to the client
    echo json_encode(array('status' => 'error', 'message' => implode(' ', $errors)));
    exit;
  }

  // Prepare the SQL statement
  $query = "INSERT INTO Customers (CustomerName, Email, Phone, Address, Balance) VALUES (?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($connect, $query);
  mysqli_stmt_bind_param($stmt, 'sssss', $customerName, $email, $phone, $address, $account);

  // Execute the statement
  if (mysqli_stmt_execute($stmt)) {
    // Send a success response back to the client
    $response = array('status' => 'success', 'message' => 'Customer added successfully.');
  } else {
    // Send an error response back to the client
    $response = array('status' => 'error', 'message' => 'Failed to add customer.');
  }

  // Close the statement
  mysqli_stmt_close($stmt);

  // Send the response as JSON
  header('Content-Type: application/json');
  echo json_encode($response);
}
?>
