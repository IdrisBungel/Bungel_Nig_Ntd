<?php
require_once 'dbcon.php';

// Check if tankerId parameter is provided
if (isset($_POST['tankerId'])) {
    $tankerId = $_POST['tankerId'];

    // Prepare the delete statement
    $query = "DELETE FROM tankers WHERE tankerId = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $tankerId);

    // Execute the delete statement
    if (mysqli_stmt_execute($stmt)) {
        echo "Tanker deleted successfully.";
    } else {
        echo "Failed to delete tanker.";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request.";
}
?>
