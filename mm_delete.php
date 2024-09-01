<?php
session_start();
if (!$_SESSION['data']){ 
    header("Location:admin.php");
    die();
}

if(!isset($_GET['ID'])){
  header("location:admin.php"); 
}
require"dbcon.php";

$sql = "DELETE FROM maintenance_manager  where ID='$_GET[ID]'";
if(mysqli_query($connect, $sql)){
    echo "deleted successfully.";
	header("location:profile_mm.php");
} 
else{
    echo "Loading............";
}
 

mysqli_close($connect);
?>

