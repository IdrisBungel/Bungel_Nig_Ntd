<?php
session_start();
session_destroy();
header("location:mm_login.php");

?>