<?php
session_start();
require "dbcon.php";
if (!$_SESSION['data']){ 
    header("Location:mm_login.php");
    die();
}
require "dbcon.php";
$user=$_SESSION['data'];
$profile=mysqli_query($connect,"select * from maintenance_manager where maintenancemanager_id='$user'");
$fetch=mysqli_fetch_array($profile);  

if(isset($_POST['submit'])){

$confirmpassword=mysqli_real_escape_string($connect,$_POST['c_password']);
$confirmpassword=stripslashes($_POST['c_password']);
$password=mysqli_real_escape_string($connect,$_POST['password']);
$password=stripslashes($_POST['password']);
  if($confirmpassword!==$password){
    $error='Password does not match'; 
  }
  else{
    
    $sql="Update maintenance_manager set password='$confirmpassword' where maintenancemanager_id='$user'";
$query=mysqli_query($connect,$sql);
$sus="Password Sucessfully Changed";

  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Maintenance Manager</title> 
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/ionicons.min.css">
  <link rel="stylesheet" href="css/AdminLTE.min.css">
  <link rel="stylesheet" href="css/_all-skins.min.css">
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="mm_login_home.php" class="logo">
      <span class="logo-mini"><b>BNL</b></span>      
      <span class="logo-lg"><b>BUNGEL NIG LTD</b></span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
            <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="images/avatar.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo"$fetch[email]"; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="images/avatar.png" class="img-circle" alt="User Image">
            <p>
                 <?php echo"$fetch[surname]"; ?>
                  <small><b>Maintenance Manager</b></small>
                </p>
              </li>
              <li class="user-body">
              <li class="user-footer">
                <div class="pull-left">
                  <a href="mm_login_home.php" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="mm_logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">    
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="images/avatar.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Maintenance Manager </p>
          
          <a href="#"> <?php echo"$fetch[maintenancemanager_id]"; ?> <i class="fa fa-circle text-success"></i> Online </a>
        </div>
      </div>
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
     <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
             
        <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i> <span> Maintenance Record</span>
            <span class="pull-right-container">
              <small class="fa fa-angle-left pull-right "></small>
               </span>
          </a>
          <ul class="treeview-menu">
          <li ><a href="n_main.php"><i class="fa fa-user-plus"></i>Add New</a></li> 
          <li ><a href="main_r.php"><i class="fa fa-group"></i>Maintenance Report</a></li>            
          </ul>
        </li>

         <li >
          <a href="mm_cpassword.php">
            <i class="fa fa-key"></i> <span>Change password</span>
            <span class="pull-right-container">
             
            </span>
          </a>
        </li>
 
      <li >
          <a href="mm_logout.php">
            <i class="fa fa-sign-out"></i> <span>Logout</span>
            <span class="pull-right-container">
             
            </span>
          </a>
        </li>
    </section>
  </aside>   
  <div class="content-wrapper"> 
   <section class="content">
   <h2 align="center" style="color:red;"> Change Password</h2>


 <form class="form-horizontal" role="form" method="post" >
        
 

 <h4 style="color:red;"> <?php if(isset($error)) {echo $error;} elseif (isset($sus)) {
   echo $sus;
 }?></h4>
                <div class="form-group">
                 <label class="control-label col-sm-2" for="o_password">Old Password</label>
              <div class="col-sm-10">
                 <input type="text" class="form-control" required name="o_password" placeholder="Old Password">
       </div>
      </div>
  


                <div class="form-group">
    <label class="control-label col-sm-2" for="surname">New Password</label>
    <div class="col-sm-10"> 
      <input type="text" class="form-control" name="password" required  placeholder="New Password">
            </div>
           </div>




            <div class="form-group">
    <label class="control-label col-sm-2" for="othername">Confirm Password</label>
    <div class="col-sm-10"> 
      <input type="text" class="form-control" name="c_password" required  placeholder="Confirm Password">
    </div>
          </div>
 <center> <input type="submit" class="btn btn-success" value="Change" name="submit"></center>
       </form>

   </section>
    
    
  </div>
  
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      
    </div>
  <center>  <strong>Copyright &copy; 2022 by PPMS</strong> All rights reserved.</center>
  </footer>

      </div>
     
 
</div>

<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.sparkline.min.js"></script>
<script src="js/jquery.slimscroll.min.js"></script>
<script src="js/adminlte.min.js"></script>
</body>
</html>
