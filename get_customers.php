<?php
require_once 'dbcon.php';

// Retrieve the list of customers from the database
$query = "SELECT CustomerID, CustomerName FROM Customers";
$result = mysqli_query($connect, $query);

// Fetch the customer data and store it in an array
$customers = array();
while ($row = mysqli_fetch_assoc($result)) {
  $customer = array(
    'id' => $row['CustomerID'],
    'name' => $row['CustomerName']
  );
  $customers[] = $customer;
}

// Close the database connection
mysqli_close($connect);

// Send the customer data as a JSON response
header('Content-Type: application/json');
echo json_encode($customers);
?>
