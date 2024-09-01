<?php
require_once 'dbcon.php';

// Fetch fuel sales data for each month
$sql = "SELECT 
            MONTH(SalesDate) AS month,
            SUM(LitreAmount) AS totalSales
        FROM FuelSales
        GROUP BY MONTH(SalesDate)";

$result = $connect->query($sql);

if ($result->num_rows > 0) {
    // Array to store the fuel sales data
    $fuelSalesData = array();

    // Loop through each row of the result set
    while ($row = $result->fetch_assoc()) {
        // Extract month and total sales for each row
        $month = intval($row['month']); // Convert month to integer
        $totalSales = intval($row['totalSales']);

        // Determine the fuel type based on TankerID in the FuelSales table
        $fuelType = '';
        $sqlFuelType = "SELECT FuelType FROM TankerSales WHERE TankerID IN (SELECT TankerID FROM FuelSales WHERE MONTH(SalesDate) = $month)";
        $resultFuelType = $connect->query($sqlFuelType);
        if ($resultFuelType->num_rows > 0) {
            $rowFuelType = $resultFuelType->fetch_assoc();
            $fuelType = $rowFuelType['FuelType'];
        }

        // Add the data to the fuel sales array
        $fuelSalesData[] = array(
            'month' => $month,
            'fuelType' => $fuelType,
            'totalSales' => $totalSales
        );
    }

    // Return the fuel sales data as a JSON response
    echo json_encode($fuelSalesData);
} else {
    // No data found, return an empty JSON array
    echo json_encode(array());
}

// Close the database connection
$connect->close();
?>
