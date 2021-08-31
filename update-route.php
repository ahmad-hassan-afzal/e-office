<?php

session_start();
    if (!isset($_SESSION["EmpID"]))
        header("Location: logout.php");

    require_once("dbconn.php");
    $db = new dbconn(); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Office | Update Route</title>

    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    

    <script>
        $(document).ready(function() {
            $('select').selectize({
                sortField: 'text'
            });
        });
    </script>

    <style>
        .selectize-input {
            padding: 12px 12px;
        }
    </style>

</head>
<body>

<?php include('includes/sidenav.php') ?>
<div class="main-content">
    <?php 
        include('includes/header.php'); 
    ?>
   <div class="container-fluid mt-4">
        <div class="mx-md-7">

    
    <?php 
    $type = $_GET["type"];
    $employee = $_GET["emp"];
    $route = $_GET["route"];
    ?>
            <div class="card">
                <div class="card-header border-1">
                    <h3> <i class="ni ni-send text-green"></i> &nbsp; Update Route</h3>
                </div>
                <div class="card-body mx-4">
                    <form action='updateRoute.php' method='post' enctype='multipart/form-data'>
                        
                    <div class="row justify-content-between mb-4">
                        <div class="badge badge-default badge-pill text-sm text-capitalize">
                            <span>Request Type: <strong><?= $type?></strong> </span>
                        </div>
                        <div class="badge badge-default badge-pill text-sm text-capitalize">
                            <span>Related Employee: <strong><?= $employee?></strong> </span>
                        </div>
                    </div>
                    
                    <input type="text" hidden name="route-id" value="<?= $_GET["id"] ?>">
                    
                    <div class="form-group">
                        <label>Related Manager(s): </label>
                        <select class="my-2" name="managers[]" id="mgr" multiple="multiple" required placeholder="Search Manager(s) .. ">
                            <?php
                                $res = $db->getAllEmployees();
                                
                                $routeMembers = explode(',', $route);
                                for ($i = 0; $i < sizeof($res); $i++){
                                
                                    if (in_array($res[$i]["EmpID"], $routeMembers)){
                                        echo '<option selected="selected" value='.$res[$i]['EmpID'].'>'.$res[$i]['EmpID'].' - '.$res[$i]['fullName'].' - '.$res[$i]['department'].'</option>';
                                    } else {
                                        echo '<option value='.$res[$i]['EmpID'].'>'.$res[$i]['EmpID'].' - '.$res[$i]['fullName'].' - '.$res[$i]['department'].'</option>';
                                    }
                                }

                                
                            ?>
                        </select>
                    </div>

                    <div class="card-footer">
                        <button class='btn btn-primary col-12' type='submit' name='submit'>Update Route</button>
                    </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    <?php include('includes/footer.php'); ?>

</div>

  <!-- Argon JS -->
  <script src="assets/js/argon.js?v=1.2.0"></script>
  <!-- Core -->
  <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/js-cookie/js.cookie.js"></script>
  <script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  
</body>
</html>