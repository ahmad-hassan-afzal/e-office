<?php
session_start();
unset($_SESSION['EmpID']);

require_once('dbconn.php');
$db = new dbconn();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>E-Office | Login</title>
  <!-- Favicon -->
  <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <!-- Argon CSS -->
  <link rel="stylesheet" href="assets/css/argon.css?v=1.2.0" type="text/css">
</head>

<body class="bg-default">
  <!-- Main content -->
  <div class="main-content">
	  
    <!-- Header -->
    <div class="header bg-gradient-info py-6 py-lg-7 pt-lg-8">
		
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
	
    <!-- Page content -->
    <div class="container mt--8 pb-5  animate__animated animate__bounce">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-7">
          <div class="card bg-secondary border-0 mb-0">
            <div class="card-body px-lg-5 py-lg-5">
				<div class="text-center text-muted mb-4">
					<img src="assets/img/brand/blue.png" alt="" width="75%">
					<hr class="m-3">  
					<?php
					if(isset($_POST['login-btn']))
					{
						$name = $_POST['uname'];
						$pass = $_POST['pass'];
						$location = "";
					
						$hash = $db->authUser(array($name, $pass));
					
						if ($hash != null) {
							$_SESSION["EmpID"] = $hash["EmpID"];
							$_SESSION["Name"] = $hash["fullName"];
							$_SESSION["ApprovalCode"] = $hash["passcode"];

							$_SESSION["app_auth"] = $hash["app_auth"];
							$_SESSION["route_mngr"] = $hash["route_mngr"];
							$_SESSION["emp_mngr"] = $hash["emp_mngr"];
							$_SESSION["admin"] = $hash["admin"];

							header('Location: index.php');
					
						} else {
							echo'<div class="alert alert-warning alert-dismissible fade show" role="alert">
								<strong>Wrong Credentials</strong> Enter Correct Credentials.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
								</div>';
						}
					}

					?>
					<h2>Document Routing System - E-Office</h2>
					<small>Sign in with your credentials</small>
				</div>
				<form class="mx-2" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="input-group input-group-merge input-group-alternative mb-3">
						<div class="input-group-prepend">
						<span class="input-group-text"><i class="ni ni-key-25"></i></span>
						</div>
						<input class="form-control" type="number" name="uname" placeholder="Employee ID" required>
					</div>

					<div class="form-group mb-3">
					<div class="input-group input-group-merge input-group-alternative">
						<div class="input-group-prepend">
						<span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
						</div>
						<input class="form-control" placeholder="Password" type="password" name="pass" placeholder="Password" required>
					</div>
					</div>

					<div class="custom-control custom-control-alternative custom-checkbox">
					<input class="custom-control-input" id=" customCheckLogin" type="checkbox">
					<label class="custom-control-label" for=" customCheckLogin">
						<span class="text-muted">Remember me</span>
					</label>
					</div>
					<div class="d-flex justify-content-center mt-3 mb-4">
						<button type="submit" name="login-btn" class="btn btn-primary w-100">Login</button>
					</div>
				</form>


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
<footer class="footer px-5 bg-default text-muted"> 
    <div class="row align-items-center justify-content-lg-between ">
        <div class="col-lg-6">
        <div class="copyright text-center text-lg-left">
            <strong> Sitara Chemicals © <?php echo date('Y'); ?> </strong>
        </div>
        </div>
        <div class="col-lg-6">
        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
            <li class="nav-item"> 
            <a href="#" class="nav-link" target="_blank">Powered By: <strong>NTU-17-BSSE-21-P-10</strong></a>
            </li>
        </ul>
        </div>
    </div>
</footer>
</body>

</html>