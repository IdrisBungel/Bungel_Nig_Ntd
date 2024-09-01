<?php
require_once 'dbcon.php';
// Fetch payment data from the database
$sql = 'SELECT * FROM Payments';
$result = $connect->query($sql);

$payments = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
}

// Close the database connection
$connect->close();

// Return the payment data as JSON
header('Content-Type: application/json');
echo json_encode($payments);
?>
