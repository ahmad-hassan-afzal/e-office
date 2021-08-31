<?php

session_start();
    if (!isset($_SESSION["EmpID"]))
        header("Location: logout.php");

    require_once("dbconn.php");
    $db = new dbconn(); 

    
    if (!($_SESSION['route_mngr'] || $_SESSION['admin'])){
        header("Location: 404.php");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Office | Generate Route</title>

    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    

    <script>
        $(document).ready(function() {
            $('select').selectize({
                sortField: 'text'
            });
            $('#emp').on('change', function() {
                
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
        
        if(isset($_GET["status"])){
            if ($_GET["status"] == "done"){

                echo "<div class='container mt-4 mb-2'>
                        <div class='alert bg-gradient-success text-center align-middle py-4' role='alert'> 
                            <p class='h1 text-white'><strong>Success!</strong></p>
                            <p class='h3 text-white'>New Route Added Successfully!</p><br>
                            <a class='btn btn-primary text-white' href='manage-route.php'><i class='ni ni-bold-left'></i> &nbsp; Go back to Managing Routes</a>
                            <a class='btn btn-default text-white' href='generate-route.php'><i class='ni ni-send'></i> &nbsp; Generate Another Route</a>
                        </div>
                       </div>";

                include('includes/footer.php');
                ?>
                
  <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

        <?php
                exit;
            }
        } else if(isset($_GET["type-status"])){
            if ($_GET["type-status"] == "done"){
                echo   '<div class="alert alert-success m-3 mx-lg-8" role="alert">
                            <strong> Success! </strong> New Request-Type Added Successfully! 
                        </div>';
            }
        }
    ?>
   <div class="container-fluid mt-4">
        <div class="mx-md-7">

            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h3 class="mb-0" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Add New Request Type
                        </h3>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                        <form action="addRequestType.php" class="form-inline" method="post" >
                            <label for="FormControlInputSubject"> Request-Type Name:  </label>
                            <input type="text" class="form-control mx-3 col-md-8" id="inlineFormInputName2" name="request-type" placeholder="e.g. Loan Application">
                            <hr>
                            <button type="submit" class="btn btn-primary form-control"> Add</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-1">
                    <h3> <i class="ni ni-send text-green"></i> &nbsp; Generate Route</h3>
                </div>
                <div class="card-body mx-4">
                    <form action='addRoute.php' method='post' enctype='multipart/form-data'>
                        
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="req-type" >Select Request Type</label>
                                    <select class="my-2" name="request-type" id="req-type" required placeholder="Select Request Type ..">
                                        <option value=""></option>
                                        <?php
                                            $res = $db->getRequestTypes();
                                            foreach ($res as $i){
                                                echo '<option value='.$i[0].'>'.$i[1].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Select Employee</label>
                                    <select id="emp"  class="my-2" name="EmpID" required placeholder="Search Employee .. ">
                                        <option value=""></option>
                                        <?php
                                            $res = $db->getAllEmployees();
                                            foreach ($res as $i){
                                                if ($i['EmpID'] != $_SESSION["EmpID"]){
                                                    echo '<option value='.$i['EmpID'].'>'.$i['EmpID'].' - '.$i['fullName'].' - '.$i['department'].'</option>';
                                                }

                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>   
                        <div class="form-group">
                            <label>Select Related Manager(s)</label>
                            <select class="my-2" name="managers[]" id="mgr" multiple="multiple" required placeholder="Search Manager(s) .. ">
                                <?php
                                    $res = $db->getAllEmployees();
                                    foreach ($res as $i){
                                        echo '<option value='.$i['EmpID'].'>'.$i['EmpID'].' - '.$i['fullName'].' - '.$i['department'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="card-footer">
                            <button class='btn btn-primary col-12' type='submit' name='submit'>Generate Route</button>
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