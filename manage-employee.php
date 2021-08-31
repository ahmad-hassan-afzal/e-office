<?php

  session_start();
  if (!isset($_SESSION["EmpID"]))
      header("Location: logout.php");

  require_once("dbconn.php");
  $db = new dbconn(); 
  
  if (!($_SESSION['emp_mngr'] || $_SESSION['admin'])){
    header("Location: 404.php");
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Office | Generate Request</title>

    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>

    <script>
        $(function() {
            $('.confirm').click(function() {
                return window.confirm("𝗗𝗲𝗹𝗲𝘁𝗲! Employee Profile?");
            });
        });
    </script>

    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>

</head>
<body>

<?php include('includes/sidenav.php') ?>
<div class="main-content">
  <?php include('includes/header.php'); ?>

    <div class="container-fluid mt-4">
     
    <?php 
    if (isset($_GET["status"])){
      if ($_GET["status"] == "error" && isset($_SESSION["error"])){
        echo '<div class="alert bg-gradient-warning m-3 mx-lg-8 text-white" role="alert">
            <strong> Validation Failed! </strong> '.$_SESSION["error"].' 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
      }

      if ($_GET["status"]=="user-added"){
        echo '<div class="alert bg-gradient-success m-3 mx-lg-8 text-white" role="alert">
            <strong> Success! </strong> Employee Profile Created Successfully! 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
      }else if ($_GET["status"] == "user-deleted"){
        echo '<div class="alert bg-gradient-success m-3 mx-lg-8 text-white" role="alert">
            <strong> Success! </strong> Employee Profile Deleted Successfully! 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
      } else if ($_GET["status"] == "user-updated"){
        echo '<div class="alert bg-gradient-success m-3 mx-lg-8 text-white" role="alert">
            <strong> Success! </strong> Employee Profile Updated Successfully! 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
      } 
      
    }
    ?>


      <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <i class="fa fa-user-circle"></i> &nbsp; Create New Employee Profile
                </h2>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
              <div class="card-body">
                <form action="addEmployee.php" method="post" >
                  <div class="container">
                    <h2>Employee Details</h2>
                    <div class="row mb-0 pb-0">
                      <div class="form-group col-md-4">
                        <label for="inputCity" class="text-sm">Employee ID</label>
                        <input type="number" name="EmpID" class="form-control form-control-sm" id="inputCity" placeholder="e.g. 1210" required>
                      </div>
                      <div class="form-group col-md-8">
                        <label for="inputCity" class="text-sm">Employee Name</label>
                        <input type="text" name="name" class="form-control form-control-sm" id="inputCity" placeholder="e.g. Jhon Doe" required>
                        <?Php 
                        if (isset($_SESSION["error"])) {
                          echo '<span class="text-danger">*'.$_SESSION["error"].'</span>';
                          unset($_SESSION["error"]);
                        }
                        ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-4">
                        <label for="inputCity" class="text-sm">Department</label>
                        <input type="text" name="department" class="form-control form-control-sm" id="inputCity" placeholder="e.g. Shipping" required>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="inputCity" class="text-sm">Contact</label>
                        <input type="text" name="contact" class="form-control form-control-sm" id="inputCity" placeholder="e.g. (+92) 301 234 56 78" required>
                      </div>
                      <div class="form-group col-md-5">
                        <label for="inputCity" class="text-sm">Email</label>
                        <input type="text" name="email" class="form-control form-control-sm" id="inputCity" placeholder="e.g. jhondoe@email.com">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-8">
                        <label for="inputCity" class="text-sm">Password</label>
                        <input type="password" name="password" class="form-control form-control-sm" id="inputCity" placeholder="Use Strong Password " required>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="inputCity" class="text-sm">Passcode</label>
                        <input type="text" name="passcode" class="form-control form-control-sm" id="inputCity" placeholder="default: 123">
                      </div>
                    </div>

                    <h2>Employee Access Control</h2>

                    <div class="row container h3">

                      <div class="form-group col">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="app_auth" name="app_auth">
                          <label class="custom-control-label" for="app_auth">Approval Authority 
                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip" data-original-title="User will be able to Forward / Return / Approve / Disapprove requests "></i>
                        </label>
                        </div>
                      </div>
                      <div class="form-group col">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="route_mngr" name="route_mngr">
                          <label class="custom-control-label" for="route_mngr">Routes Management 
                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip" data-original-title="User will be able to Generate, Modify, Delete Routes"></i>
                          </label>
                        </div>
                      </div>
                      <div class="form-group col">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="emp_mngr" name="emp_mngr">
                          <label class="custom-control-label" for="emp_mngr">User Accounts & Access Control 
                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip" data-original-title="User will be able to Create, Modify Access, Delete Employee profiles"></i>
                          </label>
                        </div>
                      </div>
                      <div class="form-group col">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="admin" name="admin">
                          <label class="custom-control-label" for="admin">Administrator
                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip" data-original-title="Admin will be able to do everything"></i>
                          </label>
                        </div>
                      </div>
                      
                    </div>

                    <div class="inline-form row justify-content-center">
                      <div class="col-md-6">
                        <button type="submit" class="btn btn-primary form-control"> Create New Employee Profile</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
        </div>
      </div>
      <div class="card">
        <!-- Card header -->
        <div class="card-header border-1">
          <h2> <i class="ni ni-bullet-list-67"></i> &nbsp; Manage Existing Employees</h2>
        </div>
        <div class="card-body mx-4">
          <div class="table-responsive" style="max-height: 450px;">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                  <tr>
                      <th scope="col">Emp ID</th>
                      <th scope="col">Name</th>
                      <th scope="col">Contact</th>
                      <th scope="col">Email</th>
                      <th scope="col">Department</th>
                      <th scope="col">Operations</th>
                  </tr>
              </thead>
              <tbody class="list">
                <?php
                  $allRoutes = $db->getAllEmployees();
                  if ($allRoutes != null){
                    foreach ($allRoutes as $i){
                      if ($i["EmpID"] != $_SESSION["EmpID"]){
                        echo "<tr>";
                          echo '<td>'.$i["EmpID"].'</td>';
                          echo '<th>'.$i["fullName"].'</th>';
                          echo '<td>'.$i["Contact"].'</td>';
                          echo '<td>'.$i["email"].'</td>';
                          echo '<th>'.$i["department"].'</th>';
                          echo '<td>
                                <div class="d-flex align-center">
                                    <div class="mx-2" data-toggle="tooltip" data-original-title="Modify Employee Profile">
                                        <a href="modify-employee.php?user-id='.$i["EmpID"].'">
                                            <h2 class="text-primary"> <i class="fa fa-pencil-square"></i> </h2>
                                        </a>
                                    </div>
                                    <div class="mx-2" data-toggle="tooltip" data-original-title="Delete Employee Profile">
                                        <a class="confirm" href="deleteEmployee.php?user-id='.$i["EmpID"].'" >
                                            <h2 class="text-warning"> <i class="fa fa-trash"></i> </h2>
                                        </a>
                                    </div>
                                </div>
                                </td>';
                        echo "</tr>";
                      }
                    }
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <?php include('includes/footer.php'); ?>

</div>

<!-- Argon Scripts -->
<!-- Core -->
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/js-cookie/js.cookie.js"></script>
<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<!-- Argon JS -->
<script src="assets/js/argon.js?v=1.2.0"></script>

</body>
</html>