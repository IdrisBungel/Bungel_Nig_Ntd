<?php
require_once 'dbcon.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the customer ID from the request
  $customerId = $_POST['customerId'];

  // Prepare the SQL statement
  $query = "DELETE FROM Customers WHERE CustomerID=?";
  $stmt = mysqli_prepare($connect, $query);
  mysqli_stmt_bind_param($stmt, 'i', $customerId);

  // Execute the statement
  if (mysqli_stmt_execute($stmt)) {
    // Send a success response back to the client
    $response = array('status' => 'success', 'message' => 'Customer deleted successfully.');
  } else {
    // Send an error response back to the client
    $response = array('status' => 'error', 'message' => 'Failed to delete customer.');
  }

  // Close the statement
  mysqli_stmt_close($stmt);

  // Send the response as JSON
  header('Content-Type: application/json');
  echo json_encode($response);
}
?>
