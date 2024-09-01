<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'dbcon.php';
include 'admin_session_check.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the form data
$salesID = $_POST['editSalesId'];
$tankerID = $_POST['editTankerID'];
$customerName = $_POST['editCustomerName'];
$customerID = $_POST['editCustomerId'];
$salesDate = $_POST['editSalesDate'];
$litrePrice = str_replace(',', '', $_POST['editLitrePrice']);
$litreAmount = str_replace(',', '', $_POST['editLitreAmount']);
$totalAmount = str_replace(',', '', $_POST['editTotalAmount']);

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
} elseif (!is_numeric($litrePrice)) {
  $errors[] = 'Invalid litre price format.';
}

// Validate litre amount
if (empty($litreAmount)) {
  $errors[] = 'Litre amount is required.';
} elseif (!is_numeric($litreAmount)) {
  $errors[] = 'Invalid litre amount format.';
}

// Validate total amount
if (empty($totalAmount)) {
  $errors[] = 'Total amount is required.';
} elseif (!is_numeric($totalAmount)) {
  $errors[] = 'Invalid total amount format.';
}

// Retrieve the tanker capacity and available litres
$query = "SELECT Capacity, availableLitre FROM TankerSales WHERE TankerID = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'i', $tankerID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $tankerCapacity, $availableLitres);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Calculate the sum of litres sold for the tanker
$query = "SELECT COALESCE(SUM(LitreAmount), 0) FROM FuelSales WHERE TankerID = ? AND SalesID <> ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'ii', $tankerID, $salesID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $sumLitresSold);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Calculate the new available litres after subtracting the sum of litres sold and adding the new litres amount
$newAvailableLitres = $tankerCapacity - ($sumLitresSold + $litreAmount);

// Compare the litre amount with the tanker's capacity
if ($litreAmount > $tankerCapacity) {
  $errors[] = 'Litre amount cannot exceed the tanker capacity.';
}

// Compare the new available litres with 0
if ($newAvailableLitres < 0) {
  $errors[] = 'Litre amount exceeds the available tanker capacity.';
}

if (!empty($errors)) {
  // Send an error response back to the client
  header('Content-Type: application/json');
  echo json_encode(array('status' => 'error', 'message' => 'Failed to update fuel sales.', 'errors' => $errors));
  exit;
}

  // Disable autocommit and start the transaction
  mysqli_autocommit($connect, false);

  try {
    // Retrieve the previous total amount and outstanding payment (balance)
    $getPreviousAmountQuery = "SELECT TotalAmount, Balance FROM FuelSales WHERE SalesID = ?";
    $stmtGetPreviousAmount = mysqli_prepare($connect, $getPreviousAmountQuery);
    mysqli_stmt_bind_param($stmtGetPreviousAmount, 'i', $salesID);
    mysqli_stmt_execute($stmtGetPreviousAmount);
    mysqli_stmt_bind_result($stmtGetPreviousAmount, $previousTotalAmount, $previousOutstandingPayment);
    mysqli_stmt_fetch($stmtGetPreviousAmount);
    mysqli_stmt_close($stmtGetPreviousAmount);

    // Calculate the difference between the new and previous total amounts
    $difference = $totalAmount - $previousTotalAmount;

    // Update the outstanding payment (balance) by adding the difference to the previous outstanding payment
    $newOutstandingPayment = $previousOutstandingPayment + $difference;

    // Prepare the SQL statement for updating fuel sales
    $query = "UPDATE FuelSales SET TankerID = ?, CustomerName = ?, CustomerID = ?, SalesDate = ?, LitrePrice = ?, LitreAmount = ?, TotalAmount = ?, Balance = ? WHERE SalesID = ?";
    $stmtUpdateFuelSales = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmtUpdateFuelSales, 'isissdddi', $tankerID, $customerName, $customerID, $salesDate, $litrePrice, $litreAmount, $totalAmount, $newOutstandingPayment, $salesID);

    if (!mysqli_stmt_execute($stmtUpdateFuelSales)) {
      throw new Exception('Failed to update fuel sales.');
    }

    // Update the available fuel in the TankerSales table
    $updateTankerQuery = "UPDATE TankerSales SET availableLitre = (Capacity - (SELECT SUM(LitreAmount) FROM FuelSales WHERE TankerID = ?)) WHERE TankerID = ?";
    $stmtUpdateTanker = mysqli_prepare($connect, $updateTankerQuery);
    mysqli_stmt_bind_param($stmtUpdateTanker, 'ii', $tankerID, $tankerID);
    mysqli_stmt_execute($stmtUpdateTanker);
    mysqli_stmt_close($stmtUpdateTanker);

    // Commit the transaction
    mysqli_commit($connect);

    // Send a success response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'success', 'message' => 'Fuel sales updated successfully.'));

  } catch (Exception $e) {
    // Rollback the transaction
    mysqli_rollback($connect);

    // Send an error response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
  }

  // Enable autocommit
  mysqli_autocommit($connect, true);
}
?>
