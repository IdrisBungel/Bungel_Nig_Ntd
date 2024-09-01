<?php
// Establish the database connection
require_once 'dbcon.php';

// Prepare the INSERT statement
$stmt = $connect->prepare("INSERT INTO TankerPickUps (tanker_number, driver_name, driverID, pickup_date, capacity, category, FuelType, destination, depot) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Bind the parameters with the form inputs
$stmt->bind_param("ssssissss", $_POST['tanker_number'], $_POST['driver_name'], $_POST['driverID'], $_POST['pickup_date'], $_POST['capacity'], $_POST['category'], $_POST['FuelType'], $_POST['destination'], $_POST['depot']);

// Execute the INSERT statement
if ($stmt->execute()) {
  // Success message
  echo "Record inserted successfully";
} else {
  // Error message
  echo "Error inserting record: " . $stmt->error;
}

// Close the prepared statement and database connection
$stmt->close();
$connect->close();
?>
