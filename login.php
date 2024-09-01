<?php

// Set strict session cookie parameters
session_set_cookie_params([
    'lifetime' => 0, // Session cookie lifetime
    'path' => '/', // Path for which the cookie is valid
    'domain' => $_SERVER['HTTP_HOST'], // Domain for which the cookie is valid
    'secure' => true, // Cookie will be sent over HTTPS only
    'httponly' => true, // Cookie accessible only through the HTTP protocol
    'samesite' => 'Lax' // Strict or Lax for cross-site requests
]);

include 'dbcon.php';
session_start();
// Retrieve and sanitize the posted username and password
$username = mysqli_real_escape_string($connect, $_POST['usernameInput']);
$password = mysqli_real_escape_string($connect, $_POST['passwordInput']);

// Prepare and execute the query to retrieve user details using a prepared statement
$query = "SELECT * FROM admin WHERE username = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    // Authentication successful
    $_SESSION['admin'] = true;
    $_SESSION['username'] = $user['username'];
    $response = array('success' => true, 'message' => 'Login successful.');
} else {
    // Authentication failed
    $response = array('success' => false, 'message' => 'Invalid credentials.');
}

// Convert the response array to JSON format
echo json_encode($response);
?>
