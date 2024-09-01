<?php
require_once 'dbcon.php';

// Fetch recent tanker sales data along with the DriverName
$sql = "SELECT fs.TankerID, fs.CustomerName, fs.SalesDate, fs.TotalAmount, fs.Balance, ts.DriverName, ts.FuelType
        FROM FuelSales fs
        JOIN TankerSales ts ON fs.TankerID = ts.TankerID
        ORDER BY fs.SalesDate DESC
        LIMIT 5";

$result = $connect->query($sql);

if ($result->num_rows > 0) {
  // Array to store the recent tanker sales data
  $recentTankerSales = array();

  while ($row = $result->fetch_assoc()) {
    // Extract data for each row
    $driverName = $row['DriverName'];
    $customerName = $row['CustomerName'];
    $salesDate = $row['SalesDate'];
    $totalAmount = $row['TotalAmount'];
    $balance = $row['Balance'];
    $fuelType = $row['FuelType'];

    // Add the data to the array
    $recentTankerSales[] = array(
      'DriverName' => $driverName,
      'CustomerName' => $customerName,
      'SalesDate' => $salesDate,
      'TotalAmount' => $totalAmount,
      'Balance' => $balance,
      'FuelType' => $fuelType
    );
  }

  // Return the recent tanker sales data as a JSON response
  echo json_encode($recentTankerSales);
} else {
  // No data found
  echo json_encode(array());
}

// Close the database connection
$connect->close();
?>
