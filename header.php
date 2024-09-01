
<body class="hold-transition sidebar-mini layout-fixed">





<div class="modal fade" id="fullMessageModal" tabindex="-1" role="dialog" aria-labelledby="fullMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fullMessageModalLabel">Notification Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Full message will be injected here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<style>
  .dropdown-item {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

</style>

  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark fixed-top">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item mt-2 mr-4">
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="darkModeSwitch" onchange="toggleDarkMode()">
            <label class="custom-control-label text-light" for="darkModeSwitch">Dark Mode</label>
          </div>
        </li>

 <li class="nav-item dropdown">
  <a class="nav-link" data-toggle="dropdown" href="#">
    <i class="far fa-bell"></i>
    <span class="badge badge-warning navbar-badge" id="notificationCount">0</span>
  </a>
  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
    <span class="dropdown-header" id="notificationHeader">0 Notifications</span>
    <div class="dropdown-divider"></div>
    <div id="notificationsDropdown">
      <!-- Add low fuel inventory and pending payment notifications here -->
    </div>
    <div class="dropdown-divider"></div>
    <a href="#" class="dropdown-item dropdown-footer" data-toggle="modal" data-target="#allNotificationsModal">View All Notifications</a>
  </div>
</li>


        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="AdminMainPage.php" class="brand-link">
        <img src="images/BungelLogo.webp" alt="Bungel Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">BUNGEL NIG LTD</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="images/adminLogo.jpg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">ADMIN</a>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
            <li class="nav-item">
              <a href="AdminMainPage.php" class="nav-link active">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
             <li class="nav-item">
              <a href="TankerSales.php" class="nav-link">
                <i class="nav-icon fas fa-truck-moving"></i>
                <p>
                  Tanker Sales
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="Manage_fuelSales.php" class="nav-link">
                <i class="nav-icon fas fa-gas-pump"></i>
                <p>
                  Fuel Sales
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="Payments.php" class="nav-link">
                <i class="nav-icon fas fa-money-bill"></i>
                <p>
                  Payments
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="TankerPickUps.php" class="nav-link">
                <i class="nav-icon fas fa-truck-pickup"></i>
                <p>
                  Tanker Pickups
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-building"></i>
                <p>
                  Stations
                </p>
              </a>
            </li>
             <li class="nav-item">
              <a href="ManageCustomers.php" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>
                  Customers
                </p>
              </a>
            </li>
               <li class="nav-item">
              <a href="ManageTankers.php" class="nav-link">
                <i class="nav-icon fas fa-truck"></i>
                <p>
                  Tankers
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="Employees.php" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Employees
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="admin_logout.php" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                  Logout
                </p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
   
<!-- All Notifications Modal -->
<div class="modal fade" id="allNotificationsModal" tabindex="-1" role="dialog" aria-labelledby="allNotificationsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="allNotificationsModalLabel">All Notifications</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group" id="allNotificationsList">
          <!-- All notifications will be dynamically added here -->
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="js/notificationHandlers.js"></script>

