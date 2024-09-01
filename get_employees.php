<?php
require_once 'dbcon.php';

// Retrieve employee data from the database
$query = 'SELECT * FROM employees';
$result = mysqli_query($connect, $query);

$data = [];

if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
}

// Close the database connection
mysqli_close($connect);

// Return the employee data as JSON
echo json_encode($data);
?>
