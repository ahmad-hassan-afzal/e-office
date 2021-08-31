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
    <title>E-Office | Generate Request</title>

    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    
    <link rel="stylesheet" href="style/scrollbar.css">

    <script>
        $(function() {
            $('.confirm').click(function() {
                return window.confirm("𝗗𝗲𝗹𝗲𝘁𝗲 𝗥𝗼𝘂𝘁𝗲! Are You Sure?");
            });
        });
    </script>
</head>
<body>

<?php include('includes/sidenav.php') ?>
<div class="main-content">
    
    <?php 
        include('includes/header.php'); 
        
        if(isset($_GET["status"])){
            if ($_GET["status"] == "done"){
                echo   '<div class="alert alert-success m-3 mx-lg-8" role="alert">
                            <strong> Success! </strong> Route Updated Successfully! 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
            } else if ($_GET["status"] == "usage-found"){
                echo   '<div class="alert alert-danger m-3 mx-lg-8" role="alert">
                            <strong> Route Cannot be updated! </strong> Route is being used by some Requests! 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
            } 
        }
        if(isset($_GET["delete-status"])){
            if ($_GET["delete-status"] == "done"){
                echo   '<div class="alert alert-success m-3 mx-lg-8" role="alert">
                            <strong> Success! </strong> Route Deleted Successfully! 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
            } else if ($_GET["delete-status"] == "usage-found"){
                echo   '<div class="alert alert-danger m-3 mx-lg-8" role="alert">
                            <strong> Route Cannot be deleted! </strong> Route is being used by some Requests! 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
            } 
        }
    ?>

   <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header">
                <h2> <i class="ni ni-sound-wave text-green"></i> Routes</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 450px;">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="number">Employee</th>
                                <th scope="col" class="sort" data-sort="request">Request Type</th>
                                <th scope="col">
                                    <span class="badge badge-dot"><i class="bg-warning"></i></span>Route
                                </th>
                                <th scope="col">Operations</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                        <?php
                        $allRoutes = $db->getAllRoutes();
                            if ($allRoutes != null){
                            foreach ($allRoutes as $i){
                                $userProfile = $db->getEmployeeProfile($i["EmpID"]); 
                                $requestType = $db->getRequestTypeDescirption($i["request_type_id"]);
                                
                                echo '<tr>
                                        <td>'.$userProfile["EmpID"].' - '.$userProfile["department"].' - '.$userProfile["fullName"].'</td>
                                        <td class="h3">'.$requestType["request_description"].'</td>
                                        <td style="overflow-x: scroll; max-width: 350px;">';
                                            $routeMembers = explode(',', $i["route"]);
                                            if ($routeMembers != null){
                                                foreach ($routeMembers as $j){
                                                    $userInfo = $db->getEmployeeProfile($j); 
                                                    echo '<div class="badge bg-gray border text-xs text-white text-capitalize">'.$userInfo["EmpID"].' - '.$userInfo["department"].'<br>'.$userInfo['fullName'].'</div>';
                                                    if ($routeMembers[sizeof($routeMembers)-1] != $j)
                                                        echo '<i class="ni ni-bold-right mx-1" aria-hidden="true"></i>';
                                                }
                                            }
                                            echo '</td>';
                                            echo 
                                            '<td>
                                            <div class="d-flex align-center">
                                                <div class="mx-2" data-toggle="tooltip" data-original-title="Modify Route">
                                                    <a href="update-route.php?id='.$i["route_id"].'&type='.$requestType["request_description"].'&emp='.$userProfile["EmpID"].'-'.$userProfile["department"].'-'.$userProfile["fullName"].'&route='.$i["route"].'">
                                                        <h2 class="text-primary"> <i class="fa fa-pencil-square"></i> </h2>
                                                    </a>
                                                </div>
                                                <div class="mx-2" data-toggle="tooltip" data-original-title="Delete Route">
                                                    <a class="confirm" href="deleteRoute.php?id='.$i["route_id"].'">
                                                        <h2 class="text-warning"> <i class="fa fa-trash"></i> </h2>
                                                    </a>
                                                </div>
                                            </div>
                                            </td>';
                                echo '</tr>';
                                }
                            } else {
                                echo '<tr>
                                    <th scope="row" class="text-center" colspan="4"> Nothing to show here.. </th>
                                </tr>';
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
<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/js-cookie/js.cookie.js"></script>
<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<!-- Argon JS -->
<script src="assets/js/argon.js?v=1.2.0"></script>

</body>
</html>