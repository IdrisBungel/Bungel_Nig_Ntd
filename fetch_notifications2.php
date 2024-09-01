<?php
session_start();
require 'dbcon.php';

header('Content-Type: application/json');  // Important: Specify the response type

$admin_id = $_SESSION['admin'];  // Ensure 'admin' is the correct session key

// Get the type of notifications to fetch from a GET request
$type = isset($_GET['type']) ? $_GET['type'] : 'unread'; // Default to 'unread'

// Build the query based on the type
if ($type === 'all') {
    $query = "SELECT id, message, created_at, read_status FROM notifications WHERE admin_id = ? ORDER BY created_at DESC";
} else { // Default to fetching only unread notifications
    $query = "SELECT id, message, created_at, read_status FROM notifications WHERE admin_id = ? AND read_status = 0 ORDER BY created_at DESC";
}

$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'i', $admin_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$notifications = [];
while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}

echo json_encode($notifications);  // Encode the result as JSON
mysqli_stmt_close($stmt);
mysqli_close($connect);
?>
