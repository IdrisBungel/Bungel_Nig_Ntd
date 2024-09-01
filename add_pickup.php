<?php
// Establish the database connection
require_once 'dbcon.php';

// Validate the input fields
$errors = [];

if (empty($_POST['tankerNumber'])) {
    $errors[] = "Tanker Number is required.";
}

if (empty($_POST['driverName'])) {
    $errors[] = "Driver Name is required.";
}

if (empty($_POST['employeeId'])) {
    $errors[] = "Employee ID is required.";
}

if (empty($_POST['pickupDate'])) {
    $errors[] = "Pickup Date is required.";
} else {
    // Convert the date format to 'YYYY-MM-DD'
    $convertedDate = date('Y-m-d', strtotime($_POST['pickupDate']));
}

if (empty($_POST['capacity'])) {
    $errors[] = "Capacity is required.";
} elseif (!is_numeric($_POST['capacity'])) {
    $errors[] = "Capacity must be a number.";
}

// Add more validation checks for other fields

// Check if there are any validation errors
if (!empty($errors)) {
    // Display the errors as JSON response
    echo json_encode(['success' => false, 'message' => $errors]);
} else {
    // Prepare the INSERT statement
    $stmt = $connect->prepare("INSERT INTO TankerPickUps (tanker_number, driver_name, driverID, pickup_date, capacity, category, FuelType, destination, depot) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind the parameters with the form inputs
    $stmt->bind_param("ssssissss", $_POST['tankerNumber'], $_POST['driverName'], $_POST['employeeId'], $convertedDate, $_POST['capacity'], $_POST['category'], $_POST['fuelType'], $_POST['destination'], $_POST['depot']);

    // Execute the INSERT statement
    if ($stmt->execute()) {
        // Success message
        echo json_encode(['success' => true, 'message' => 'Record inserted successfully']);
    } else {
        // Error message
        echo json_encode(['success' => false, 'message' => 'Error inserting record: ' . $stmt->error]);
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$connect->close();
?>
