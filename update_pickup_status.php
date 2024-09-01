<?php
require_once 'dbcon.php';

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Get the pickup ID and new status from the request
  $pickupId = $_POST["pickupId"];
  $status = $_POST["status"];
  var_dump($_POST);
  // Perform the necessary update in your database
  // Replace this with your actual update query
  // Assuming you have a database connection established in dbcon.php using mysqli
  $sql = "UPDATE TankerPickUps SET status = ? WHERE pickup_id = ?";
  $stmt = $connect->prepare($sql);
  $stmt->bind_param("ii", $status, $pickupId);
  $stmt->execute();

  // Check if the update was successful
  if ($stmt->affected_rows > 0) {
    // Return a success message or any other necessary response
    echo "Pickup status updated successfully";
  } else {
    // Return an error message or any other necessary response
    echo "Failed to update pickup status";
  }
} else {
  // Return an error message for invalid request method
  echo "Invalid request";
}
?>
