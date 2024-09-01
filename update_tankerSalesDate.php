<?php
require_once 'dbcon.php';
include 'admin_session_check.php';
// Retrieve the updated data sent from the client-side
$tankerID = $_POST['tankerID'];
$newDate = $_POST['newDate'];

// Convert the date format from MM/DD/YYYY to YYYY-MM-DD
$newDate = date('Y-m-d', strtotime($newDate));

// Prepare the update query
$stmt = $connect->prepare("UPDATE TankerSales SET ReturnDate = ? WHERE TankerID = ?");
$stmt->bind_param('si', $newDate, $tankerID);

// Execute the update query
if ($stmt->execute()) {
    // Return a success message to the client-side
    $response = array('status' => 'success', 'message' => 'Data updated successfully');
    echo json_encode($response);
} else {
    // Return an error message to the client-side
    $response = array('status' => 'error', 'message' => 'An error occurred during the update');
    echo json_encode($response);
}

// Close the statement and connection
$stmt->close();
$connect->close();
?>
