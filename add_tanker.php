<?php
require_once 'dbcon.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are provided
    if (isset($_POST['tanker_number'], $_POST['driver_name'], $_POST['capacity'], $_POST['status'], $_POST['employee_id'])) {
        $tanker_number = $_POST['tanker_number'];
        $driver_name = $_POST['driver_name'];
        $capacity = $_POST['capacity'];
        $status = $_POST['status'];
        $employee_id = $_POST['employee_id'];

        // Check if the employee ID already exists in the tankers table
        $query = "SELECT COUNT(*) FROM tankers WHERE employee_id = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 's', $employee_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($count > 0) {
            http_response_code(400);
            echo "Driver is already assigned with a tanker.";
        } else {
            // Prepare the insert statement
            $query = "INSERT INTO tankers (tanker_number, driver_name, capacity, status, employee_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connect, $query);
            mysqli_stmt_bind_param($stmt, 'ssiss', $tanker_number, $driver_name, $capacity, $status, $employee_id);

            // Execute the insert statement
            if (mysqli_stmt_execute($stmt)) {
                http_response_code(200);
                echo "Tanker added successfully.";
            } else {
                http_response_code(400);
                echo "Failed to add tanker.";
            }

            mysqli_stmt_close($stmt);
        }
    } else {
        http_response_code(400);
        echo "Invalid form data.";
    }
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
