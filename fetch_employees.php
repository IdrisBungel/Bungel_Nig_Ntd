<?php
// Database connection details
require_once 'dbcon.php';


// Fetch the employee records from the database
$sql = "SELECT * FROM employees";
$result = $connect->query($sql);

$employees = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Close the database connection
$connect->close();

// Return the employee data as a JSON response
header('Content-Type: application/json');
echo json_encode($employees);
?>
