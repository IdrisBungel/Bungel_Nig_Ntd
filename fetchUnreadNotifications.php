<?php
session_start();
require 'dbcon.php';

header('Content-Type: application/json');

$admin_id = $_SESSION['admin'];  // assuming admin ID is stored in session

$query = "SELECT id, message, created_at FROM notifications WHERE admin_id = ? AND read_status = 0 ORDER BY created_at DESC";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'i', $admin_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$notifications = [];
while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}

echo json_encode($notifications);
mysqli_stmt_close($stmt);
mysqli_close($connect);
?>
