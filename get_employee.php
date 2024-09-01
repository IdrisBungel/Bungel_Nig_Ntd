<?php
require_once 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employeeId'])) {
    $employeeId = $_POST['employeeId'];

    // Prepare the select statement
    $query = "SELECT * FROM employees WHERE employee_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 's', $employeeId);

    // Execute the select statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $employeeData = mysqli_fetch_assoc($result);

        // Return the employee data as JSON
        echo json_encode($employeeData);
    } else {
        echo "Failed to retrieve employee data.";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request.";
}
?>
