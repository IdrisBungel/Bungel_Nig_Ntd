<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'dbcon.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve the form data
  $employeeId = $_POST['employeeId'];
  $surname = $_POST['surname'];
  $othername = $_POST['othername'];
  $role = $_POST['role'];
  $salary = str_replace(',', '', $_POST['salary']);
  $phonenumber = $_POST['phoneNumber'];
  $address = $_POST['address'];
  $email = $_POST['email'];
  $sex = $_POST['sex'];
  $dob = $_POST['dob'];
  $state_origin = $_POST['stateOrigin'];

  // Perform validation on the form data
  $errors = array();

  // Validate employee ID
  if (empty($employeeId)) {
    $errors[] = 'Employee ID is required.';
  }

  // Validate surname
  if (empty($surname)) {
    $errors[] = 'Surname is required.';
  }

  // Validate othername
  if (empty($othername)) {
    $errors[] = 'Othername is required.';
  }

  // Validate role
  if (empty($role)) {
    $errors[] = 'Role is required.';
  }

  // Validate salary
  if (empty($salary)) {
    $errors[] = 'Salary is required.';
  }

  // Validate phonenumber
  if (empty($phonenumber)) {
    $errors[] = 'Phone number is required.';
  }

  // Validate address
  if (empty($address)) {
    $errors[] = 'Address is required.';
  }

  // Validate email
  if (empty($email)) {
    $errors[] = 'Email is required.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
  }

  // Validate sex
  if (empty($sex)) {
    $errors[] = 'Gender is required.';
  }

  // Validate date of birth
  if (empty($dob)) {
    $errors[] = 'Date of birth is required.';
  } elseif (!strtotime($dob)) {
    $errors[] = 'Invalid date of birth format.';
  }

  // Validate state of origin
  if (empty($state_origin)) {
    $errors[] = 'State of origin is required.';
  }

// If there are validation errors, return a JSON response with the error messages
if (!empty($errors)) {
  $response = array('success' => false, 'errors' => $errors);
  http_response_code(400); // Set the response code to indicate a bad request
  header('Content-Type: application/json'); // Set the response header to JSON
  echo json_encode($response);
  exit;
}



  // Insert the employee data into the database
  $sql = "INSERT INTO employees (employee_id, surname, othername, role, salary, phonenumber, address, email, sex, dob, state_origin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $connect->prepare($sql);
  $stmt->bind_param("sssssssssss", $employeeId, $surname, $othername, $role, $salary, $phonenumber, $address, $email, $sex, $dob, $state_origin);

  if ($stmt->execute()) {
    // Success, return a JSON response indicating success
    $response = array('success' => true);
    echo json_encode($response);
  } else {
    // Failure, return a JSON response indicating failure
    $response = array('success' => false);
    echo json_encode($response);
  }

  // Close the database connection
  $stmt->close();
  $connect->close();
}
?>