<?php
require_once 'dbcon.php';

if (isset($_POST['pickupId'])) {
  $pickupId = $_POST['pickupId'];
  $tankerNumber = $_POST['tankerNumber'];
  $driverName = $_POST['driverName'];
  $pickupDate = $_POST['pickupDate'];
  $capacity = $_POST['capacity'];
  $category = $_POST['category'];
  $fuelType = $_POST['fuelType'];
  $destination = $_POST['destination'];
  $depot = $_POST['depot'];
  $status = $_POST['status'];
  $employeeId = $_POST['employeeId'];

  // Validate the input fields
  $errors = [];

  if (empty($tankerNumber)) {
    $errors[] = "Tanker Number is required.";
  }

  if (empty($driverName)) {
    $errors[] = "Driver Name is required.";
  }

  if (empty($employeeId)) {
    $errors[] = "Employee ID is required.";
  }

  if (empty($capacity)) {
      $errors[] = "Capacity is required.";
  } elseif (!is_numeric($capacity)) {
      $errors[] = "Capacity must be a number.";
  }
  // Add more validation checks for other fields

  if (empty($pickupDate)) {
    $errors[] = "Pickup Date is required.";
  } else {
    // Convert the date format to 'Y-m-d'
    $convertedDate = date('Y-m-d', strtotime($pickupDate));
  }

  // Check if there are any validation errors
  if (!empty($errors)) {
    // Display the errors as JSON response
    echo json_encode(['success' => false, 'message' => $errors]);
  } else {
    // Prepare the UPDATE statement
    $stmt = $connect->prepare("UPDATE TankerPickUps SET
                                tanker_number = ?,
                                driver_name = ?,
                                pickup_date = ?,
                                capacity = ?,
                                category = ?,
                                FuelType = ?,
                                destination = ?,
                                depot = ?,
                                driverID = ?,
                                status = ?
                              WHERE pickup_id = ?");

    // Bind the parameters with the form inputs
    $stmt->bind_param("sssissssssi", $tankerNumber, $driverName, $convertedDate, $capacity, $category, $fuelType, $destination, $depot, $employeeId, $status, $pickupId);

    // Execute the UPDATE statement
    if ($stmt->execute()) {
      // Success message
      echo json_encode(['success' => true, 'message' => 'Pickup updated successfully']);
    } else {
      // Error message
      echo json_encode(['success' => false, 'message' => 'Error updating pickup: ' . $stmt->error]);
    }

    // Close the prepared statement
    $stmt->close();
  }
}

// Don't forget to close the database connection when you're done
$connect->close();
?>
