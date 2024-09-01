<?php
require 'dbcon.php'; // Ensure your database connection file is correctly included
include 'admin_session_check.php';

// Check if the required data is present
if (isset($_POST['notification_id']) && isset($_SESSION['admin'])) {
    $notification_id = $_POST['notification_id'];
    $admin_id = $_SESSION['admin']; // Assuming you store admin ID in session upon login

    // SQL to update the notification's read status, only if it belongs to the logged-in admin
    $query = "UPDATE notifications SET read_status = 1 WHERE id = ? AND admin_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $notification_id, $admin_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['success' => true]);
    } else {
        // This can happen if no rows match the condition - i.e., either the notification ID was wrong or the admin doesn't own it
        echo json_encode(['error' => 'Failed to mark as read - no matching notification found for this admin']);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['error' => 'Notification ID or admin session missing']);
}
mysqli_close($connect);
?>
