<?php
require_once 'dbcon.php';

if (isset($_POST['pickupId'])) {
  $pickupId = $_POST['pickupId'];

  // Delete the pickup from the database based on the pickupId
  $query = "DELETE FROM TankerPickUps WHERE pickup_id = $pickupId";
  $result = mysqli_query($connect, $query);

  if ($result) {
    // Log success message
    error_log("Pickup with ID $pickupId deleted successfully");
    echo "success";
  } else {
    // Log error message
    error_log("Error deleting pickup with ID $pickupId: " . mysqli_error($connect));
    echo "error";
  }
}

// Don't forget to close the database connection when you're done
// mysqli_close($connect);
?>
