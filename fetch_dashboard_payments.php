<?php
require_once 'dbcon.php';

// Fetch outstanding payments data for the table
$sql = "SELECT CustomerName, LitreAmount AS AmountDue, SalesDate AS InvoiceDate FROM FuelSales WHERE Balance > 0";
$result = $connect->query($sql);

$outstandingPaymentsData = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $outstandingPaymentsData[] = $row;
  }
}

echo json_encode($outstandingPaymentsData);

$connect->close();
?>
