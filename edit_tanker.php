<?php
require_once 'dbcon.php';
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are provided
    if (isset($_POST['tankerId'], $_POST['tanker_number'], $_POST['driver_name'], $_POST['capacity'], $_POST['status'], $_POST['employee_id'])) {
        $tankerId = $_POST['tankerId'];
        $tanker_number = $_POST['tanker_number'];
        $driver_name = $_POST['driver_name'];
        $capacity = $_POST['capacity'];
        $status = $_POST['status'];
        $employee_id = $_POST['employee_id'];

        // Check if the employee ID already exists in the tankers table for a different record
        $query = "SELECT COUNT(*) FROM tankers WHERE employee_id = ? AND tankerId != ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 'si', $employee_id, $tankerId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($count > 0) {
            http_response_code(400);
            echo "Employee ID already exists for another tanker record.";
        } else {
            // Prepare the update statement
            $query = "UPDATE tankers SET tanker_number = ?, driver_name = ?, capacity = ?, status = ?, employee_id = ? WHERE tankerId = ?";
            $stmt = mysqli_prepare($connect, $query);
            mysqli_stmt_bind_param($stmt, 'ssissi', $tanker_number, $driver_name, $capacity, $status, $employee_id, $tankerId);

            // Execute the update statement
            if (mysqli_stmt_execute($stmt)) {
                http_response_code(200);
                echo "Tanker updated successfully.";
            } else {
                http_response_code(400);
                echo "Failed to update tanker.";
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
