<?php
require_once 'dbcon.php';

// Fetch total debt data for the table
$sql = "SELECT CustomerName, SUM(Balance) AS TotalDebt FROM FuelSales GROUP BY CustomerName"; 
$result = $connect->query($sql);

$totalDebtData = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $totalDebtData[] = $row;
  }
}

echo json_encode($totalDebtData);

$connect->close();
?>
