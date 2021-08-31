<?php 
  $filename = basename($_SERVER['PHP_SELF']); 
  $status = "";
?>

<!-- Sidenav -->
<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header  align-items-center">
        <a class="navbar-brand" href="javascript:void(0)">
          <img src="assets/img/brand/blue.png" class="navbar-brand-img" style="max-height: 4rem;" alt="...">
          <h4 class="text-muted">DRS - E-Office</h4>
        </a>
      </div>
      <div class="navbar-inner mt-4">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav">

            <?php
              if ($filename == "index.php") $status = 'active';
              if ($_SESSION['app_auth'] || $_SESSION['admin']){
                echo '<li class="nav-item">
                      <a class="nav-link '.$status.'" href="/e-office">
                        <i class="ni ni-tv-2 text-primary"></i>
                        <span class="nav-link-text">Dashboard</span>
                      </a>
                    </li>';
                $status = "";
              }
            ?>
            
            <li class="nav-item">
              <a class="nav-link <?php if ($filename == "my-requests.php") echo 'active'?>" href="my-requests.php">
                <i class="ni ni-bullet-list-67 text-default"></i>
                <span class="nav-link-text">My Requests</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if ($filename == "generate-request.php") echo 'active'?> " href="generate-request.php">
                <i class="ni ni-send text-green"></i>
                <span class="nav-link-text">Generate Request</span>
              </a>
            </li>

            <?php 
              if ($filename == "comments.php") $status = 'active';
              if ($_SESSION['app_auth'] || $_SESSION['admin']){
                echo '<li class="nav-item dropdown">
                        <a class="nav-link '.$status.'" href="comments.php">
                          <i class="ni ni-chat-round"></i> 
                          <span class="nav-link-text">Comments</span>
                        </a>
                      </li>';
                $status = "";
              }
            ?>

            <li class="nav-item">
              <a class="nav-link <?php if ($filename == "my-profile.php") echo 'active'?>" href="my-profile.php">
                <i class="ni ni-single-02 text-yellow"></i>
                <span class="nav-link-text">My Profile</span>
              </a>
            </li>

            <?php
              if ($filename == "manage-employee.php") $status = 'active';
              if ($_SESSION['emp_mngr'] || $_SESSION['admin']){
                echo '<li class="nav-item">
                        <a class="nav-link '.$status.'" href="manage-employee.php">
                          <i class="ni ni-books text-default"></i>
                          <span class="nav-link-text">Manage Employees</span>
                        </a>
                      </li>';
                $status = "";
              }
              if ($_SESSION['route_mngr'] || $_SESSION['admin']){
                echo '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ni ni-spaceship text-info"></i> Routes
                        </a>
                        <div class="dropdown-menu ml-3 pl-3" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="generate-route.php"> <i class="ni ni-send text-default"></i> Generate Route</a>
                          <a class="dropdown-item" href="manage-route.php"> <i class="ni ni-sound-wave text-green"></i> Manage Routes</a>
                        </div>
                      </li>';
              }
            ?>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">
                <i class="ni ni-key-25 text-danger"></i>
                <span class="nav-link-text"> <strong>Logout</strong></span>
              </a>
            </li>
          </ul>
          
          </ul>
        </div>
      </div>
    </div>
  </nav>