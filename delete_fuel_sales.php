<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'dbcon.php';
include 'admin_session_check.php';
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the sales ID from the request
  $salesID = $_POST['saleID'];

  // Prepare the SQL statement
  $query = "DELETE FROM FuelSales WHERE SalesID = ?";
  $stmt = mysqli_prepare($connect, $query);
  mysqli_stmt_bind_param($stmt, 'i', $salesID);

  // Execute the statement
  if (mysqli_stmt_execute($stmt)) {
    // Send a success response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'success', 'message' => 'Fuel sales deleted successfully.'));
  } else {
    // Send an error response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Failed to delete fuel sales.'));
  }

  // Close the statement
  mysqli_stmt_close($stmt);
}
?>
