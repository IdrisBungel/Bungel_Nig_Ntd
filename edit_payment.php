<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'dbcon.php';
include 'admin_session_check.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the form data
  $paymentID = $_POST['editPaymentID'];
  $customerName = $_POST['editCustomerName'];
  $paymentDate = $_POST['editPaymentDate'];
  $bankName = $_POST['editBankName'];
  $paymentAmount = str_replace(',', '', $_POST['editPaymentAmount']);
  $customerID = $_POST['editCustomerID'];
  $fuelSalesID = $_POST['editFuelSalesID'];

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

  // Validate payment amount
  if (empty($paymentAmount)) {
    $errors[] = 'Payment amount is required.';
  } elseif (!is_numeric($paymentAmount)) {
    $errors[] = 'Invalid payment amount format.';
  }

  // Validate customer ID
  if (empty($customerID)) {
    $errors[] = 'Customer ID is required.';
  } elseif (!is_numeric($customerID)) {
    $errors[] = 'Invalid customer ID format.';
  }

  // Validate fuel sales ID
  if (empty($fuelSalesID)) {
    $errors[] = 'Fuel Sales ID is required.';
  } elseif (!is_numeric($fuelSalesID)) {
    $errors[] = 'Invalid fuel sales ID format.';
  }

  // Check if there are any validation errors
  if (!empty($errors)) {
    // Send an error response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Failed to update payment.', 'errors' => $errors));
    exit;
  }

  mysqli_autocommit($connect, false);

  try {
    // Retrieve the previous payment amount
    $getPreviousPaymentQuery = "SELECT PaymentAmount FROM Payments WHERE PaymentID = ?";
    $stmtGetPreviousPayment = mysqli_prepare($connect, $getPreviousPaymentQuery);
    mysqli_stmt_bind_param($stmtGetPreviousPayment, 'i', $paymentID);
    mysqli_stmt_execute($stmtGetPreviousPayment);
    mysqli_stmt_bind_result($stmtGetPreviousPayment, $previousPaymentAmount);
    mysqli_stmt_fetch($stmtGetPreviousPayment);
    mysqli_stmt_close($stmtGetPreviousPayment);

    // Calculate the difference between the new and previous payment amounts
    $difference = $paymentAmount - $previousPaymentAmount;

    // Prepare the SQL statement to update the payment
    $updatePaymentQuery = "UPDATE Payments SET CustomerName = ?, CustomerID = ?, PaymentDate = ?, BankName = ?, PaymentAmount = ? WHERE PaymentID = ?";
    $stmtUpdatePayment = mysqli_prepare($connect, $updatePaymentQuery);
    mysqli_stmt_bind_param($stmtUpdatePayment, 'sissdi', $customerName, $customerID, $paymentDate, $bankName, $paymentAmount, $paymentID);

    // Insert a new log entry for payment edit
    $logType = 'Payment Edit';
    $logQuery = "INSERT INTO PaymentLog (PaymentID, CustomerID, FuelSalesID, PaymentAmount, LogType) VALUES (?, ?, ?, ?, ?)";
    $logStmt = mysqli_prepare($connect, $logQuery);
    mysqli_stmt_bind_param($logStmt, 'iiids', $paymentID, $customerID, $fuelSalesID, $paymentAmount, $logType);
    mysqli_stmt_execute($logStmt);
    mysqli_stmt_close($logStmt);

    // Execute the payment update statement
    if (!mysqli_stmt_execute($stmtUpdatePayment)) {
      throw new Exception('Failed to update payment.');
    }

    // Update the fuel sales balance based on the difference
    $updateFuelSalesQuery = "UPDATE FuelSales SET Balance = Balance - ? WHERE SalesID = ?";
    $stmtUpdateFuelSales = mysqli_prepare($connect, $updateFuelSalesQuery);
    mysqli_stmt_bind_param($stmtUpdateFuelSales, 'di', $difference, $fuelSalesID);

    // Execute the fuel sales update statement
    if (!mysqli_stmt_execute($stmtUpdateFuelSales)) {
      throw new Exception('Failed to update fuel sales balance.');
    }

    // Commit the transaction
    mysqli_commit($connect);

    // Send a success response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'success', 'message' => 'Payment updated successfully.'));

  } catch (Exception $e) {
    // Rollback the transaction
    mysqli_rollback($connect);

    // Send an error response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
  }

  // Close the statements
  mysqli_stmt_close($stmtUpdatePayment);
  mysqli_stmt_close($stmtUpdateFuelSales);

  // Enable autocommit for subsequent database operations
  mysqli_autocommit($connect, true);
}
?>
