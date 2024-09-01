<?php
require_once 'dbcon.php';
if(isset($_GET['pickupId'])) {
  $pickupId = $_GET['pickupId'];

  // Fetch pickup data from the database based on the pickupId
  $query = "SELECT * FROM TankerPickUps WHERE pickup_id = $pickupId";
  $result = mysqli_query($connect, $query);

  if(mysqli_num_rows($result) > 0) {
    $pickup = mysqli_fetch_assoc($result);
    echo json_encode($pickup);
  } else {
    echo json_encode(null);
  }
}

// Don't forget to close the database connection when you're done
mysqli_close($connect);
?>
