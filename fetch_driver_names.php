<?php
require_once 'dbcon.php';
// Fetch driver names and employee IDs from the employees table where role is Driver
$query = "SELECT surname, employee_id FROM employees WHERE role = 'Driver'";
$result = mysqli_query($connect, $query);

if ($result) {
    $driverNames = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $driverNames[] = array(
            'name' => $row['surname'],
            'employee_id' => $row['employee_id']
        );
    }
    // Return the driver names as a JSON response
    echo json_encode($driverNames);
} else {
    // Handle the database query error
    echo "Failed to fetch driver names: " . mysqli_error($connect);
}

// Remember to close the database connection if required
mysqli_close($connect);
?>
