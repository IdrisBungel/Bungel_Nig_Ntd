<?php
require_once 'dbcon.php';

// Threshold value for low fuel inventory (you can adjust this based on your business needs)
$lowInventoryThreshold = 2000; // For example, 2000 liters

// Array to store low fuel inventory notifications
$lowFuelInventoryNotifications = array();

// Fetch tanker sales data from the database
$sql = "SELECT * FROM TankerSales";
$result = $connect->query($sql);

// Initialize total fuel quantity
$totalFuelQuantity = array('Petrol' => 0, 'Gas' => 0);

if ($result->num_rows > 0) {
  // Loop through each tanker sale to check available liters for petrol and gas
  while ($row = $result->fetch_assoc()) {
    if ($row['FuelType'] === 'Petrol') {
      $totalFuelQuantity['Petrol'] += $row['availableLitre'];
    } elseif ($row['FuelType'] === 'Gas') {
      $totalFuelQuantity['Gas'] += $row['availableLitre'];
    }
  }
}

// Check if total fuel quantity for petrol and gas is below the threshold
$lowFuelTypes = array();
if ($totalFuelQuantity['Petrol'] < $lowInventoryThreshold) {
  $lowFuelTypes[] = 'Petrol';
}
if ($totalFuelQuantity['Gas'] < $lowInventoryThreshold) {
  $lowFuelTypes[] = 'Gas';
}

if (!empty($lowFuelTypes)) {
  // Add a notification for low fuel inventory
  $notificationMessage = 'Total fuel inventory is low for ' . implode(' or ', $lowFuelTypes);
  $lowFuelInventoryNotifications[] = array(
    'type' => 'Low Fuel',
    'message' => $notificationMessage,
    'timestamp' => date('Y-m-d H:i:s')
  );
}

// Fetch payments data from the database
$sql = "SELECT CustomerName, MAX(PaymentDate) AS LastPaymentDate FROM Payments GROUP BY CustomerName";
$result = $connect->query($sql);

// Check if any customer has pending payments older than one week
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $customerName = $row['CustomerName'];
    $lastPaymentDate = strtotime($row['LastPaymentDate']);
    $oneWeekAgo = strtotime('-1 week');

    if ($lastPaymentDate < $oneWeekAgo) {
      // Add a notification for pending payment
      $lowFuelInventoryNotifications[] = array(
        'type' => 'Pending Payment',
        'message' => "Customer '$customerName' has pending payment older than one week.",
        'timestamp' => date('Y-m-d H:i:s')
      );
    }
  }
}

// Return the notifications as JSON response
echo json_encode($lowFuelInventoryNotifications);

// Close the database connection
$connect->close();
?>
