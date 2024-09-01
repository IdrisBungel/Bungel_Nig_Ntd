<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'dbcon.php';
include 'admin_session_check.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the form data
  $tankerID = $_POST['tankerID'];
  $customerID = $_POST['customerId'];
  $customerName = $_POST['customerName'];
  $salesDate = $_POST['salesDate'];
  $litrePrice = $_POST['litrePrice'];
  $litreAmount = str_replace(',', '', $_POST['litreAmount']);

  // Perform validation and error handling
  $errors = array();

  // Validate customer name
  if (empty($customerName)) {
    $errors[] = 'Customer name is required.';
  }

  // Validate customer ID
  if (empty($customerID)) {
    $errors[] = 'Customer ID is required.';
  }

  // Validate sales date
  if (empty($salesDate)) {
    $errors[] = 'Sales date is required.';
  }

  // Validate litre price
  if (empty($litrePrice)) {
    $errors[] = 'Litre price is required.';
  } elseif (!is_numeric($litrePrice) || $litrePrice < 0) {
    $errors[] = 'Invalid litre price format.';
  }

  // Validate litre amount
  if (empty($litreAmount)) {
    $errors[] = 'Litre amount is required.';
  } elseif (!is_numeric($litreAmount) || $litreAmount < 0) {
    $errors[] = 'Invalid litre amount format.';
  }

  $query = "SELECT Capacity, availableLitre FROM TankerSales WHERE TankerID = ?";
  $stmt = mysqli_prepare($connect, $query);
  mysqli_stmt_bind_param($stmt, 'i', $tankerID);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $tankerCapacity, $availableLitre);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  // Compare the litre amount with the tanker's capacity
  if ($litreAmount > $tankerCapacity) {
    $errors[] = 'Litre amount cannot exceed the tanker capacity.';
  }

  if ($litreAmount > $availableLitre) {
    $errors[] = 'Litre amount exceed the available litre.';
  }

  // Check if there are any validation errors
  if (!empty($errors)) {
    // Send an error response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Failed to add fuel sales.', 'errors' => $errors));
    exit;
  }

  // Disable autocommit to start the transaction
  mysqli_autocommit($connect, false);
$total =$litreAmount * $litrePrice;
  // Prepare the SQL statement
  $query = "INSERT INTO FuelSales (TankerID, CustomerName, CustomerID, SalesDate, LitrePrice, LitreAmount, TotalAmount, Balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($connect, $query);
  mysqli_stmt_bind_param($stmt, 'isissdii', $tankerID, $customerName, $customerID, $salesDate, $litrePrice, $litreAmount, $total, $total);

  // Execute the insert statement
  if (mysqli_stmt_execute($stmt)) {
    // Prepare the SQL statement to update the TankerSales table
    $updateTankerQuery = "UPDATE TankerSales SET availableLitre = availableLitre - ? WHERE TankerID = ?";
    $stmtUpdateTanker = mysqli_prepare($connect, $updateTankerQuery);
    mysqli_stmt_bind_param($stmtUpdateTanker, 'di', $litreAmount, $tankerID);

    // Execute the update statement for TankerSales
    if (mysqli_stmt_execute($stmtUpdateTanker)) {
      // Commit the transaction
      mysqli_commit($connect);

      // Send a success response back to the client
      header('Content-Type: application/json');
      echo json_encode(array('status' => 'success', 'message' => 'Fuel sales added successfully.'));
    } else {
      // Rollback the transaction
      mysqli_rollback($connect);

      // Send an error response back to the client
      header('Content-Type: application/json');
      echo json_encode(array('status' => 'error', 'message' => 'Failed to update tanker sales.'));
    }

    // Close the update statement for TankerSales
    mysqli_stmt_close($stmtUpdateTanker);
  } else {
    // Rollback the transaction
    mysqli_rollback($connect);

    // Send an error response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Failed to add fuel sales.'));
  }

  // Close the insert statement
  mysqli_stmt_close($stmt);

  // Enable autocommit for subsequent database operations
  mysqli_autocommit($connect, true);
}
?>
