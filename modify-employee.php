<?php

session_start();
    if (!isset($_SESSION["EmpID"]))
        header("Location: logout.php");

    require_once("dbconn.php");
    $db = new dbconn(); 

    $emp_details = $db->getEmployeeProfile($_GET["user-id"]);

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
        $('#admin').on('change', function() {
          $('.child').prop('checked', this.checked);
        });
        $('.child').on('change', function() {
          $('#admin').prop('checked', $('.child:checked').length===$('.child').length);
        });
      });
    </script>

</head>
<body>

<?php include('includes/sidenav.php') ?>
<div class="main-content">
  <?php include('includes/header.php'); ?>

   <div class="container-fluid mt-4">
   <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0" >
                    <i class="fa fa-user-circle"></i> &nbsp; Modify Employee Profile
                </h2>
            </div>
            <div class="card-body">
              <form action="updateEmployee.php" method="post" >
                <div class="container">
                  <h2>Employee Details</h2>
                  <div class="row mb-0 pb-0">
                    <div class="form-group col-md-3">
                      <label for="inputCity" >Employee ID</label>
                      <input type="number" name="EmpID" class="form-control" id="inputCity" placeholder="e.g. 1210" readonly value="<?php echo $emp_details["EmpID"]; ?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="inputCity" >Employee Name</label>
                      <input type="text" name="name" class="form-control" id="inputCity" placeholder="e.g. Jhon Doe" required value="<?php echo $emp_details["fullName"]; ?>">
                    </div>
                    <div class="form-group col-md-3">
                      <label for="inputCity" >Department</label>
                      <input type="text" name="department" class="form-control" id="inputCity" placeholder="e.g. Shipping" required value="<?php echo $emp_details["department"]; ?>">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-4">
                      <label for="inputCity" >Contact</label>
                      <input type="text" name="contact" class="form-control" id="inputCity" placeholder="e.g. (+92) 301 234 56 78" required value="<?php echo $emp_details["Contact"]; ?>">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="inputCity" >Email</label>
                      <input type="text" name="email" class="form-control" id="inputCity" placeholder="e.g. jhondoe@email.com" required value="<?php echo $emp_details["email"]; ?>">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="inputCity" >Passcode</label>
                      <input type="text" name="passcode" class="form-control" id="inputCity" placeholder="default: 123" required value="<?php echo $emp_details["passcode"]; ?>">
                    </div>
                  </div>
                  
                  <h2>Employee Access Control</h2>
                  <div class="container">
                    <div class="row h3">
                      <div class="form-group col">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input parent" id="admin" name="admin" <?php if ($emp_details["admin"] == 1 ) echo "checked"; ?>>
                          <label class="custom-control-label" for="admin">Administrator
                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip" data-original-title="Admin will be able to do everything"></i>
                          </label>
                        </div>
                      </div>
                      <div class="form-group col">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input child" id="app_auth" name="app_auth" <?php if ($emp_details["app_auth"] == 1 ) echo "checked"; ?>>
                          <label class="custom-control-label" for="app_auth">Approval Authority 
                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip" data-original-title="User will be able to Forward / Return / Approve / Disapprove requests "></i>
                        </label>
                        </div>
                      </div>
                      <div class="form-group col">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input child" id="route_mngr" name="route_mngr" <?php if ($emp_details["route_mngr"] == 1 ) echo "checked"; ?>>
                          <label class="custom-control-label" for="route_mngr">Routes Management 
                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip" data-original-title="User will be able to Generate, Modify, Delete Routes"></i>
                          </label>
                        </div>
                      </div>
                      <div class="form-group col">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input child" id="emp_mngr" name="emp_mngr" <?php if ($emp_details["emp_mngr"] == 1 ) echo "checked"; ?>>
                          <label class="custom-control-label" for="emp_mngr">User Accounts Management 
                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip" data-original-title="User will be able to Create, Modify Access, Delete Employee profiles"></i>
                          </label>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="inline-form row justify-content-center">
                    <div class="col-md-6">
                      <button type="submit" class="btn btn-primary form-control"> Update Employee Profile</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

</div>

<!-- Argon Scripts -->
<!-- Core -->
<!-- <script src="assets/vendor/jquery/dist/jquery.min.js"></script> -->
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/js-cookie/js.cookie.js"></script>
<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<!-- Argon JS -->
<script src="assets/js/argon.js?v=1.2.0"></script>

</body>
</html>