<?php

  session_start();

  if (!isset($_SESSION["EmpID"]))
      header("Location: logout.php");

  require_once("dbconn.php");
  $db = new dbconn();

  $profileInfo = $db->getEmployeeProfile($_SESSION['EmpID']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Office | View Profile </title>

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

<body>

<?php include('includes/sidenav.php') ?>
<div class="main-content" id="panel">

    <div class="my-3 d-md-none">
      <div class="d-lg-none py-3" >
        <div class="float-left ml-3 mt--4">
          <img src="assets/img/brand/blue.png" style="max-height: 3rem;" alt="">
        </div>
        <div class="sidenav-toggler mr-3 float-right" data-target="#sidenav-main">
          <div class="sidenav-toggler-inner">
            <i class="sidenav-toggler-line"></i>
            <i class="sidenav-toggler-line"></i>
            <i class="sidenav-toggler-line"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="header pb-6 d-flex align-items-center" style="min-height: 500px; background-image: url(https://source.unsplash.com/1600x900/?nature,water); background-size: cover; background-position: center top;">  
      <!-- Mask -->
      <span class="mask bg-gradient-primary opacity-6"></span>
      <!-- Header container -->
      <div class="container-fluid d-flex align-items-center">
        <div class="row">
          <div class="col-lg-7 col-md-10">
            <h1 class="display-2 text-white">Hello <?= $profileInfo["fullName"]; ?></h1>
            <p class="text-white mt-0 mb-5">This is your profile page. You can see the progress you've made with your work and manage your profile</p>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-5 order-xl-2">
          <div class="card card-profile">
            <img src="https://source.unsplash.com/1600x900/?nature,water" alt="Image placeholder" class="card-img-top">
            <div class="row justify-content-center">
              <div class="col-lg-3 order-lg-2">
                <div class="card-profile-image">
                  <a href="#">
                    <!-- <img src="" class="rounded-circle"> -->
                  </a>
                </div>
              </div>
            </div>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col">
                  <div class="card-profile-stats d-flex justify-content-center">
                    <div>
                      <span class="heading">22</span>
                      <span class="description">Pending Requests</span>
                    </div>
                    <div>
                      <span class="heading">10</span>
                      <span class="description">Processed Requests</span>
                    </div>
                    <div>
                      <span class="heading">89</span>
                      <span class="description">Posted Comments</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <h5 class="h3">
                <?= $profileInfo["fullName"]; ?> <span class="font-weight-light">, <?= $profileInfo["department"]; ?></span>
                </h5>
                <div class="h5 font-weight-300">
                  <i class="ni location_pin mr-2"></i><?= $profileInfo["address"]; ?>
                </div>
                <div class="h5 mt-4">
                  <i class="ni business_briefcase-24 mr-2"></i><?= $profileInfo["email"]; ?>
                </div>
                <div>
                  <i class="mx-auto"></i>Sitara Chemicals Industries, Ltd.
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-7 order-xl-1">
        <?php

          if(isset($_GET["update-status"])){
            if ($_GET["update-status"] == "done"){
                echo "<div class='container '>
                        <div class='alert bg-gradient-success text-center align-middle' role='alert'> 
                          <p class='h2 text-white'><strong>Success (:</strong></p>
                          <p class='h4 text-white'>Profile Details Updated Successfully !</p>
                        </div>
                      </div>";
            }
          }
        ?>

          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <h3 class="ml-3 my-auto h2">Edit profile </h3>
              </div>
            </div>
            <div class="card-body">
              <form action='updateProfile.php' method='post' enctype='multipart/form-data'>
                <h6 class="heading-small text-muted mb-4">User information</h6>
                <div class="px-lg-4">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-last-name">Name</label>
                        <input type="text" name="fullName" id="input-last-name" class="form-control" readonly value="<?= $profileInfo['fullName']?>">
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4" />
                <!-- Address -->
                <h6 class="heading-small text-muted mb-4">Contact information</h6>
                <div class="px-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-last-name">Contact</label>
                        <input type="text" name="contact" id="input-last-name" class="form-control" placeholder="Contact" value="<?= $profileInfo['Contact']?>">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-email">Email address</label>
                        <input type="email" name="email" id="input-email" class="form-control" placeholder="jesse@example.com" value="<?= $profileInfo['email'] ?>">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Address</label>
                        <input name="address" id="input-address" class="form-control" placeholder="Address" value="<?= $profileInfo['address'] ?>" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Passcode</label>
                        <input name="passcode" id="input-address" class="form-control" placeholder="Passcode" value="<?= $profileInfo['passcode'] ?>" type="text">
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4" />
                <!-- Description -->
                <div class="px-lg-4">
                  <div class="form-group">
                    <input class="btn btn-default form-control" type="submit" value="Update Profile" ></input>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <?php require_once("includes/footer.php") ?>
    </div>
  </div>

  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/js-cookie/js.cookie.js"></script>
  <script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  <!-- Argon JS -->
  <script src="assets/js/argon.js?v=1.2.0"></script>
</body>
</html>