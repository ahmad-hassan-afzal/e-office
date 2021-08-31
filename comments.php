<?php

session_start();

require_once("dbconn.php");
$db = new dbconn();

if (!isset($_SESSION["EmpID"]))
  header("Location: logout.php");

if (!($_SESSION['app_auth'] || $_SESSION['admin'])){
  header("Location: 404.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Office | Dashboard</title>

    <link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">

</head>

<body>

<?php include('includes/sidenav.php') ?>
<div class="main-content">

    <?php include('includes/header.php'); 

if (isset($_GET['Comment-Posted'])){
      $res = $_GET['Comment-Posted'];
      if ($res){
        echo '<div class="alert alert-success alert-dismissible mt-2 fade show">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Request '.$_GET["Status"].'ed Successfully!
            </div>';
      } else {
        echo '<div class="alert alert-danger alert-dismissible mt-2 fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Request could not be '.$_GET["Status"].'ed Successfully!
              </div>';
      }
    }
    ?>
    <div class="container-fluid mt-4">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-1">
              <h2 class="mb-0">My Comments | Posted
              <a href="" class="btn btn-secondary rounded-0 float-right" data-toggle="tooltip" data-original-title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a>
              </h2>
            </div>
            <!-- Light table -->
            <div class="table-responsive" style="max-height: 450px;">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="number">Request ID</th>
                    <th scope="col" class="sort" data-sort="request">Request</th>
                    <th scope="col-8" >Comment</th>
                  </tr>
                </thead>
                <tbody class="list">
                    <?php
                    $myComments = $db->getMyComments($_SESSION['Name'], 'Posted');
                    // print_r($myComments);
                    if ($myComments != null) {
                        foreach ($myComments as $i){
                            echo '<tr>';
                            echo '<th class="col-1">Req-'.$i['request_id'].'-'.$i['timestamp'].'</th>';
                            echo '<th class="col-2">'.$db->getRequestType($i['request_id'])["request_description"].'</th>';
                            echo '<td class="col-6"><textarea rows="1" class="form-control" type="text" disabled>'.$i["comment"].'</textarea></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<th class="text-center" colspan="7">Nothing to show here..</th>';
                    }
                    ?>
                    
                </tbody>
              </table>
            </div>
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