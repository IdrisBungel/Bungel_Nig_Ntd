<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'dbcon.php';
include 'admin_session_check.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the form data
  $customerName = $_POST['customerName'];
  $paymentDate = $_POST['paymentDate'];
  $bankName = $_POST['bankName'];
  $tankerSalesID = $_POST['tankerSalesID'];
  $fuelSalesID = $_POST['fuelSalesID'];
  // $fuelSaleAmount = $_POST['fuelSaleAmount'];
  $customerID = $_POST['customerID'];
  $paymentAmount = str_replace(',', '', $_POST['paymentAmount']);

  // Perform validation and error handling
  $errors = array();

  // Validate customer name
  if (empty($customerName)) {
    $errors[] = 'Customer name is required.';
  }

  // Validate payment date
  if (empty($paymentDate)) {
    $errors[] = 'Payment date is required.';
  }

  // Validate bank name
  if (empty($bankName)) {
    $errors[] = 'Bank name is required.';
  }

  if (empty($fuelSalesID)) {
    $errors[] = 'Fuel Sales ID is required.';
  }

  if (empty($tankerSalesID)) {
    $errors[] = 'Tanker Sales ID is required.';
  }

  // Validate customer ID
  if (empty($customerID)) {
    $errors[] = 'Customer ID is required.';
  } elseif (!is_numeric($customerID)) {
    $errors[] = 'Invalid customer ID format.';
  }

  // Validate payment amount
  if (empty($paymentAmount)) {
    $errors[] = 'Payment amount is required.';
  } elseif (!is_numeric($paymentAmount)) {
    $errors[] = 'Invalid payment amount format.';
  }

  // Check if there are any validation errors
  if (!empty($errors)) {
    // Send an error response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Failed to add payment.', 'errors' => $errors));
    exit;
  }

  // Start a transaction
mysqli_autocommit($connect, false);

try {
  // Prepare the SQL statement to insert the payment
  $paymentQuery = "INSERT INTO Payments (CustomerName, PaymentDate, BankName, PaymentAmount, CustomerID, FuelSalesID, TankerSalesID) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $paymentStmt = mysqli_prepare($connect, $paymentQuery);
  mysqli_stmt_bind_param($paymentStmt, 'ssssdii', $customerName, $paymentDate, $bankName, $paymentAmount, $customerID, $fuelSalesID, $tankerSalesID);

  // Execute the payment statement
  if (!mysqli_stmt_execute($paymentStmt)) {
    throw new Exception('Failed to add payment.');
  }

  // Retrieve the generated payment ID
  $paymentID = mysqli_insert_id($connect);

  // Insert a new log entry for payment addition
  $logType = 'Payment Addition';
  $logQuery = "INSERT INTO PaymentLog (PaymentID, CustomerID, FuelSalesID, PaymentAmount, LogType) VALUES (?, ?, ?, ?, ?)";
  $logStmt = mysqli_prepare($connect, $logQuery);
  mysqli_stmt_bind_param($logStmt, 'iiids', $paymentID, $customerID, $fuelSalesID, $paymentAmount, $logType);
  mysqli_stmt_execute($logStmt);
  mysqli_stmt_close($logStmt);

  // Prepare the SQL statement to update the fuel sale balance
  $fuelSaleBalanceQuery = "UPDATE FuelSales SET Balance = Balance - ? WHERE SalesID = ?";
  $fuelSaleBalanceStmt = mysqli_prepare($connect, $fuelSaleBalanceQuery);
  mysqli_stmt_bind_param($fuelSaleBalanceStmt, 'di', $paymentAmount, $fuelSalesID);

  // Execute the fuel sale balance update statement
  $fuelSaleBalanceSuccess = mysqli_stmt_execute($fuelSaleBalanceStmt);

  if ($fuelSaleBalanceSuccess) {
    mysqli_commit($connect); // Commit the transaction
    // Send a success response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'success', 'message' => 'Payment added successfully.'));
  } else {
    mysqli_rollback($connect); // Rollback the transaction
    // Send an error response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Failed to add payment.'));
  }

  // Close the statements
  mysqli_stmt_close($paymentStmt);
  mysqli_stmt_close($fuelSaleBalanceStmt);

  // Enable autocommit for subsequent database operations
  mysqli_autocommit($connect, true);
} catch (Exception $e) {
  // Rollback the transaction
  mysqli_rollback($connect);

  // Send an error response back to the client
  header('Content-Type: application/json');
  echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}
}
?>
