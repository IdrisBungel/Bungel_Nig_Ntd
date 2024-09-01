<?php
require_once 'dbcon.php';
// Assuming $connection is your established database connection
$query = "SELECT * FROM tankers";
$result = mysqli_query($connect, $query);

if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['tankerId'] . "</td>";
    echo "<td>" . $row['tanker_number'] . "</td>";
    echo "<td>" . $row['driver_name'] . "</td>";
    echo "<td>" . $row['employee_id'] . "</td>";
    echo "<td>" . number_format($row['capacity']) . "</td>";
    echo "<td>" . $row['status'] . "</td>";
   echo '<td>';
                    echo '<button type="button" class="btn btn-primary edit-btn" data-id="' . $row['tankerId'] . '"><i class="fas fa-edit"></i> Edit</button> ';
                    echo '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row['tankerId'] . '"><i class="fas fa-trash"></i> Delete</button>';
                    echo '</td>';
    echo "</tr>";
  }
} else {
  echo "<tr><td colspan='7'>No records found.</td></tr>";
}
mysqli_close($connect);
?>
