<?php
session_start();
if (!$_SESSION['data']){ 
    header("Location:admin.php");
    die();
}

if(!isset($_GET['tanker_number'])){
  header("location:admin.php"); 
}
require"dbcon.php";

$sql = "DELETE FROM tankers  where tanker_number='$_GET[tanker_number]'";
if(mysqli_query($connect, $sql)){
    echo "deleted successfully.";
	header("location:e_tanker.php");
} 
else{
    echo "Loading............";
}
 

mysqli_close($connect);
?>

