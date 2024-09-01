<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // User is not logged in, redirect to login page
    header('Location: Admin.php');
    exit;
}

// Check if the session timeout has exceeded (e.g., 30 minutes)
$timeout = 30 * 60; // Timeout in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    // Session has expired, destroy the session and redirect to login page
    session_unset();
    session_destroy();
    header('Location: Admin.php');
    exit;
}

// Update the last activity time for the session
$_SESSION['last_activity'] = time();

// Check if notifications have been fetched for the current session
if (!isset($_SESSION['notifications_fetched']) || $_SESSION['notifications_fetched'] !== true) {
    // Fetch and store notifications in the session
    // ...

    // Set the variable to true to indicate that notifications have been fetched
    $_SESSION['notifications_fetched'] = true;
}
?>
