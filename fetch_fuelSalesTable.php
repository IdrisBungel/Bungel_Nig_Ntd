<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'dbcon.php';

// Retrieve Fuel Sales data based on the tankerID
if(isset($_POST['tankerID'])){
    $tankerID = $_POST['tankerID'];


    // Your code to fetch Fuel Sales data from the database
    // Replace this with your actual code to retrieve data

    // Sample query to fetch Fuel Sales data
    $query = "SELECT SalesID, CustomerName, SalesDate FROM FuelSales WHERE TankerID = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('i', $tankerID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch the data and store it in an array
    $fuelSalesData = array();
    while($row = $result->fetch_assoc()){
        $fuelSalesData[] = $row;
    }

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($fuelSalesData);
}
?>


