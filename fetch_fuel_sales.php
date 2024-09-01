<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'dbcon.php';

// Get the tankerID from the AJAX request
if (isset($_GET['tankerID'])) {
    $tankerID = $_GET['tankerID'];

    // Prepare the SQL query to fetch fuel sales data based on the provided tankerID
    $sql = "SELECT * FROM FuelSales WHERE TankerID = $tankerID";

    // Execute the query
    $result = $connect->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Fetch data and store it in an array
        $fuelSalesData = array();
        while ($row = $result->fetch_assoc()) {
            $fuelSalesData[] = $row;
        }

        // Return the fuel sales data as JSON
        echo json_encode($fuelSalesData);
    } else {
        // No fuel sales data found for the provided tankerID
        echo json_encode(array());
    }
} else {
    // tankerID not provided in the request
    echo json_encode(array());
}

// Close the database connectection
$connect->close();
?>


