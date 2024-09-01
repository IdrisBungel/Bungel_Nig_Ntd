<?php
require_once 'dbcon.php';

// Fetch the tanker data from the TankerSales table with status "Available" or "In Progress"
$query = "SELECT TankerID, DriverName, availableLitre, FuelType FROM TankerSales WHERE availableLitre > 0";
$result = mysqli_query($connect, $query);

if ($result) {
  $tankerData = array();

  // Fetch each row from the result set
  while ($row = mysqli_fetch_assoc($result)) {
    // Add the row data to the tanker data array
    $tankerData[] = $row;
  }

  // Prepare the response as an associative array
  $response = array(
    'status' => 'success',
    'tankerData' => $tankerData
  );
} else {
  // Failed to fetch tanker data
  $response = array(
    'status' => 'error',
    'message' => 'Failed to fetch tanker data'
  );
}

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Close the database connection
mysqli_close($connect);
?>
