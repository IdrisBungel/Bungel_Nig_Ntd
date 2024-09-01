<?php
require_once 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employeeId'])) {
    $employeeId = $_POST['employeeId'];

    // Prepare the delete statement
    $query = "DELETE FROM employees WHERE employee_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 's', $employeeId);

    // Execute the delete statement
    if (mysqli_stmt_execute($stmt)) {
        echo "Employee deleted successfully.";
    } else {
        echo "Failed to delete employee.";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request.";
}
?>
