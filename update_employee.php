<?php
require_once 'dbcon.php';
// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve the form data
  $employeeId = $_POST['editEmployeeId'];
  $surname = $_POST['editSurname'];
  $othername = $_POST['editOthername'];
  $role = $_POST['editRole'];
  $salary = str_replace(',', '', $_POST['editSalary']);
  $phoneNumber = $_POST['editPhoneNumber'];
  $address = $_POST['editAddress'];
  $email = $_POST['editEmail'];
  $sex = $_POST['editSex'];
  $dob = $_POST['editDob'];
  $stateOrigin = $_POST['editStateOrigin'];

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
  if (empty($phoneNumber)) {
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
  if (empty($stateOrigin)) {
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


  // Prepare the SQL statement to update the employee record
  $sql = "UPDATE employees SET
            surname = '$surname',
            othername = '$othername',
            role = '$role',
            salary = '$salary',
            phonenumber = '$phoneNumber',
            address = '$address',
            email = '$email',
            sex = '$sex',
            dob = '$dob',
            state_origin = '$stateOrigin'
          WHERE employee_id = '$employeeId'";

  // Execute the SQL statement
  if (mysqli_query($connect, $sql)) {
    // Return a success response
    http_response_code(200);
    echo json_encode(['message' => 'Employee updated successfully']);
  } else {
    // Return an error response
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update employee']);
  }
} else {
  // Return an error response for invalid request method
  http_response_code(405);
  echo json_encode(['error' => 'Invalid request method']);
}
?>
