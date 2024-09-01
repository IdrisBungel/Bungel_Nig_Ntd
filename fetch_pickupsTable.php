<?php
require_once 'dbcon.php';

// Fetch the updated data from the database
$query = "SELECT * FROM TankerPickUps";
$result = mysqli_query($connect, $query);

$data = array(); // Array to hold the fetched data

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Add each row to the data array
        $data[] = $row;
    }
}


// Close the database connection
mysqli_close($connect);

// Send the data as a JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>

