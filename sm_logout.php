<?php
session_start();
session_destroy();
header("location:sm_login.php");

?>