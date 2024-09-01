<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'dbcon.php'; 


// Get the salesID from the AJAX request
if (isset($_GET['salesID'])) {
    $salesID = $_GET['salesID'];

    // Prepare the SQL query to fetch payment data based on the provided salesID
    $sql = "SELECT * FROM Payments WHERE FuelSalesID = $salesID";

    // Execute the query
    $result = $connect->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Fetch data and store it in an array
        $paymentData = array();
        while ($row = $result->fetch_assoc()) {
            $paymentData[] = $row;
        }

        // Return the payment data as JSON
        echo json_encode($paymentData);
    } else {
        // No payment data found for the provided salesID
        echo json_encode(array());
    }
} else {
    // salesID not provided in the request
    echo json_encode(array());
}

// Close the database connectection
$connect->close();
?>

