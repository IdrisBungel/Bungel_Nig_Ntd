<?php
require_once 'dbcon.php';
include 'admin_session_check.php';
// Check if the payment ID is provided
if (isset($_POST['paymentID'])) {
  // Get the payment ID
  $paymentID = $_POST['paymentID'];

  // Retrieve the payment details before deletion
  $query = "SELECT * FROM Payments WHERE PaymentID = ?";
  $stmt = mysqli_prepare($connect, $query);
  mysqli_stmt_bind_param($stmt, 'i', $paymentID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $payment = mysqli_fetch_assoc($result);
  mysqli_stmt_close($stmt);

  // Prepare the SQL statement to delete the payment
  $deletePaymentQuery = "DELETE FROM Payments WHERE PaymentID = ?";
  $stmtDeletePayment = mysqli_prepare($connect, $deletePaymentQuery);
  mysqli_stmt_bind_param($stmtDeletePayment, 'i', $paymentID);

  // Execute the payment deletion statement
  if (mysqli_stmt_execute($stmtDeletePayment)) {
    // Insert a new log entry for payment deletion
    $logType = 'Payment Deletion';
    $logQuery = "INSERT INTO PaymentLog (PaymentID, CustomerID, FuelSalesID, PaymentAmount, LogType) VALUES (?, ?, ?, ?, ?)";
    $logStmt = mysqli_prepare($connect, $logQuery);
    mysqli_stmt_bind_param($logStmt, 'iiids', $payment['PaymentID'], $payment['CustomerID'], $payment['FuelSalesID'], $payment['PaymentAmount'], $logType);
    mysqli_stmt_execute($logStmt);
    mysqli_stmt_close($logStmt);

    // Send a success response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'success', 'message' => 'Payment deleted successfully.'));
  } else {
    // Send an error response back to the client
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Failed to delete payment.'));
  }

  // Close the statement
  mysqli_stmt_close($stmtDeletePayment);
}

?>

