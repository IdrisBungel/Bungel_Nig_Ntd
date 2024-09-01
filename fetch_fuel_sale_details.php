<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'dbcon.php';
include 'admin_session_check.php';

// Check if the fuelSaleID parameter is provided
if (isset($_GET['fuelSaleID'])) {
  // Get the fuel sale ID from the request
  $fuelSaleID = $_GET['fuelSaleID'];

  // Prepare the SQL statement to fetch the fuel sale details
  $query = "SELECT TankerID, Balance, TotalAmount FROM FuelSales WHERE SalesID = ?";
  $stmt = mysqli_prepare($connect, $query);
  mysqli_stmt_bind_param($stmt, 'i', $fuelSaleID);

  // Execute the statement
  mysqli_stmt_execute($stmt);

  // Get the result
  $result = mysqli_stmt_get_result($stmt);

  // Check if a row is returned
  if ($row = mysqli_fetch_assoc($result)) {
    // Fetch the required details
    $tankerSalesID = $row['TankerID'];
    $balance = $row['Balance'];
    $fuelSaleAmount = $row['TotalAmount'];

    // Prepare the response array
    $response = array(
      'status' => 'success',
      'tankerSalesID' => $tankerSalesID,
      'balance' => $balance,
      'fuelSaleAmount' => $fuelSaleAmount
    );

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
  } else {
    // Fuel sale not found
    $response = array(
      'status' => 'error',
      'message' => 'Fuel sale not found'
    );

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  // Close the statement
  mysqli_stmt_close($stmt);
} else {
  // Fuel sale ID not provided
  $response = array(
    'status' => 'error',
    'message' => 'Fuel sale ID not provided'
  );

  // Send the response as JSON
  header('Content-Type: application/json');
  echo json_encode($response);
}
?>