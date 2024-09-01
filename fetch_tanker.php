<?php
require_once 'dbcon.php';
// Check if tankerId is provided in the request
if (isset($_POST['tankerId'])) {
  $tankerId = $_POST['tankerId'];
  // Prepare the SQL statement
  $query = "SELECT * FROM tankers WHERE tankerId = ?";
  $stmt = mysqli_prepare($connect, $query);
  mysqli_stmt_bind_param($stmt, 's', $tankerId);

  // Execute the statement
  if (mysqli_stmt_execute($stmt)) {
    // Fetch the tanker data
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    // Check if the tanker exists
    if ($row) {
      // Prepare the response data
      $response = array(
        'status' => 'success',
        'data' => array(
          'tankerId' => $row['tankerId'],
          'tankerNumber' => $row['tanker_number'],
          'driverName' => $row['driver_name'],
          'capacity' => $row['capacity'],
          'status' => $row['status'],
          'employeeId' => $row['employee_id']
        )
      );
    } else {
      // Tanker not found
      $response = array(
        'status' => 'error',
        'message' => 'Tanker not found.'
      );
    }
  } else {
    // Failed to execute the statement
    $response = array(
      'status' => 'error',
      'message' => 'Failed to fetch tanker data.'
    );
  }

  // Close the statement
  mysqli_stmt_close($stmt);

  // Send the response as JSON
  header('Content-Type: application/json');
  echo json_encode($response);
} else {
  // Invalid request
  $response = array(
    'status' => 'error',
    'message' => 'Invalid request.'
  );

  // Send the response as JSON
  header('Content-Type: application/json');
  echo json_encode($response);
}
?>
