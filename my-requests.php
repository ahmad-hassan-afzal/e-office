
<?php
session_start();
if (!isset($_SESSION["EmpID"]))
    header("Location: login.php");
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Office | My Requests</title>

    <link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet"> 
    
    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
    <script>
      $(document).ready(function () {

        
      });
    </script>

    <script>
        $(function() {
            $('.confirm').click(function() {
              return window.confirm("𝗪𝗶𝘁𝗵𝗱𝗿𝗮𝘄 𝗥𝗲𝗾𝘂𝗲𝘀𝘁! Are you sure?");
            });

            $('.open-modal').on('click', function () {
              var reqID = $(this).attr('request-id');
              $("#request-id").val(reqID);
            });   
        });
    </script>

</head>

<body>
<?php
    require_once("dbconn.php");
    $db = new dbconn();    
?>

<?php include('includes/sidenav.php') ?>
<div class="main-content">

    <?php include('includes/header.php'); ?>

    <!-- status=withdrawn -->
    <?php
      if(isset($_GET["status"])){
        $msg_title = "";
        $msg = "";
        $bg = "";
        if ($_GET["status"] == "withdrawn"){
          $msg_title = "Success!";
          $msg = "Request Withdrawn Successfully!";
          $bg = "bg-gradient-success";            
        } else if ($_GET["status"] == "forwarded"){
          $msg_title = "Success!";
          $msg = "Request Forwarded Successfully!";
          $bg = "bg-gradient-gray";
        } else if ($_GET["status"] == "returned"){
          $msg_title = "Success!";
          $msg = "Request Returned!";
          $bg = "bg-gradient-info";
        } else if ($_GET["status"] == "approved"){
          $msg_title = "Success!";
          $msg = "Request Approved Successfully!";
          $bg = "bg-gradient-primary";
        } else if ($_GET["status"] == "disapproved"){
          $msg_title = "Success!";
          $msg = "Request Disapproved!";
          $bg = "bg-gradient-warning";
        } 
        echo '<div class="alert text-white '.$bg.' m-3 mx-lg-8" role="alert">
            <strong> '.$msg_title.' </strong> '.$msg.'
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>';
      }
    ?>

    <div class="container-fluid mt-3">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-1">

            <!-- Tabs -->
              <div class="row">
                <div class="col-md-8 col-sm-12 nav nav-tabs border-0" id="nav-tab" role="tabList">
                  <a class="h2 my-auto ml-2" href="#my-reqs" data-toggle="tab"><i class="ni ni-bullet-list-67"></i> &nbsp; My Requests</a>
                  <a class="h3 my-auto ml-3 btn border border-primary" data-toggle="tab" href="#returned"> Returned Requests </a>
                  <a class="h3 my-auto ml-3 btn border border-gray" data-toggle="tab" href="#withdrawn"> Withdrawn Requests </a>
                </div>
                <div class="col-md-4 col-sm-12">
                  <div class="btn-group float-right">
                    <a class="btn btn-success font-weight-bold rounded-0 mx-0" href="generate-request.php">Generate Request</a>
                    <a href="my-requests.php" class="btn btn-secondary rounded-0 mx-0" data-toggle="tooltip" data-original-title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;</a>
                  </div>
                </div>
              </div>

            </div>
            
            <div class="tab-content">

              <div class="tab-pane fade show active table-responsive" id="my-reqs" style="max-height: 450px;">
                <table class="table align-items-center table-flush">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col" class="sort" data-sort="number">Request ID</th>
                      <th scope="col" class="sort" data-sort="request">Request</th>
                      <th scope="col">
                      Request Route [
                      <span class="badge badge-dot"><i class="bg-warning"></i></span>    
                      Current Position ]</th>
                      <th scope="col">Overall Request Status</th>
                    </tr>
                  </thead>
                  <tbody class="list">
                  <?php
                    
                    $myRequests = $db->getMyPendingRequests(array($_SESSION["EmpID"]));

                      if ($myRequests != null){
                          foreach ($myRequests as $i){
                            if ($i['current_route_position'] != -1 && $i['current_route_position'] != -2 ){
                              echo '<tr>
                              <td>Req-'.$i["id"].'-'.$i["timeStamp"].'</td>
                              <td class="h4">
                                <a href="DocumentViewer.php?req_id='.$i["id"].'">'.$db->getRequestType($i["id"])['request_description'].'
                              </td>
                              <td>
                              <div class="">';
                              $requestRoute = explode(',', $i["request_route"]);
                              if ($requestRoute != null){
                                foreach ($requestRoute as $j){
                                  $app_auth = $db->getEmployeeProfile($j);
                                  echo '<a href="#" class="avatar avatar-sm rounded-circle" data-toggle="tooltip" data-original-title="'.$app_auth['EmpID'].' - '.$app_auth['fullName'].'">';
                                  if ($requestRoute[$i['current_route_position']] == $j){
                                    echo '<i class="fas fa-user text-warning" aria-hidden="true"></i>';
                                  } else {
                                    echo '<i class="fas fa-user" aria-hidden="true"></i>';
                                  }
                                  echo '</a>';
                                  if ($requestRoute[sizeof($requestRoute)-1] != $j)
                                    echo '<i class="ni ni-bold-right" aria-hidden="true"></i>';
                                }
                              }
                              echo '</div>
                              </td>';
                              if ($i['current_route_position'] == 0){
                                echo '<td class="bg-warning text-center"><span class="h4 text-white">Initiated</span></td>';
                              } else if ($i['current_route_position'] == sizeof($requestRoute)-2){
                                echo '<td class="bg-primary text-center"><span class="h4 text-white">Pending for Approval</span></td>';
                              } else if ($i['current_route_position'] == sizeof($requestRoute)-1) {
                                echo '<td class="bg-success text-center"><span class="h4 text-white">Approved</span></td>';
                              } else {
                                echo '<td class="bg-purple text-center"><span class="h4 text-white">Being Processed</span></td>';
                              }
                              
                              echo '</tr>';
                            }
                          }
                      } else {
                          echo '<tr>
                              <th scope="row" class="text-center" colspan="7"> Nothing to show here.. </th>
                          </tr>';
                      }
                    ?>
                  </tbody>
                </table>
              </div>

              <div class="tab-pane fade table-responsive" id="returned" style="max-height: 450px;">
                <table class="table align-items-center" >
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">Request ID</th>
                      <th scope="col">Request</th>
                      <th scope="col"> Returned From </th>
                      <th scope="col">Reason <span class="badge badge-dot"><i class="bg-warning"></i></span> Returned for Revision</th>
                      <th scope="col">Operations</th>
                    </tr>
                  </thead>
                  <tbody class="list">
                  <?php
                      if ($myRequests != null){
                        foreach ($myRequests as $i){
                          if ($i['current_route_position'] == -1){
                            echo '<tr>
                            <td>Req-'.$i["id"].'-'.$i["timeStamp"].'</td>
                            <td class="h4">
                              <a href="DocumentViewer.php?req_id='.$i["id"].'">'.$db->getRequestType($i["id"])['request_description'].'
                            </td>
                            <td>
                              <div class="">';
                                $returnedFrom = explode(',', $i["request_route"])[0];
                                $app_auth = $db->getEmployeeProfile($returnedFrom);
                                echo '<a href="#" class="avatar avatar-sm rounded-circle" data-toggle="tooltip" data-original-title="'.$app_auth['EmpID'].' - '.$app_auth['department'].'">';
                                echo '<i class="fas fa-user" aria-hidden="true"></i>';
                                echo '</a>';
                                echo '<span class="h5"> '.$app_auth['EmpID'].'-'.$app_auth['department'].'-'.$app_auth['fullName'].'</span>';
                                echo 
                              '</div>
                            </td>';
                            echo '<td><span class="h4 text-muted">{{reasons/comments here..}}</span></td>';
                            echo '<td>
                                <div class="d-flex">
                                    <div class="mx-1" >
                                      <a data-toggle="tooltip" data-original-title="Forward Request">
                                        <h1 class="text-success open-modal" request-id="'.$i["id"].'" data-toggle="modal" data-target="#genRequestModal"><i class="fa fa-check-square"></i></h1>
                                      </a>
                                    </div>
                                    <div class="mx-1">
                                        <a class="confirm" href="processRequest.php?req-id='.$i["id"].'&withdraw=true" data-toggle="tooltip" data-original-title="Withdraw Request">
                                            <h1 class="text-warning"> <i class="fa fa-minus-square"></i> </h1>
                                        </a>
                                    </div>
                                </div>
                                </td>';
                            echo '</tr>';
                          }
                        }
                      } else {
                          echo '<tr>
                              <th scope="row" class="text-center" colspan="7"> Nothing to show here.. </th>
                          </tr>';
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              
              <div class="tab-pane fade table-responsive" id="withdrawn" style="max-height: 450px;">
                <table class="table align-items-center" >
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">Request ID</th>
                      <th scope="col">Request</th>
                      <th scope="col"><span class="badge badge-dot"><i class="bg-warning"></i></span> Status </th>
                      <th scope="col">Request Activity</th>
                    </tr>
                  </thead>
                  <tbody class="list">
                  <?php
                      if ($myRequests != null){
                        foreach ($myRequests as $i){
                          if ($i['current_route_position'] == -2){
                            echo '<tr>
                            <td>Req-'.$i["id"].'-'.$i["timeStamp"].'</td>
                            <td class="h4">
                              <a href="DocumentViewer.php?req_id='.$i["id"].'">'.$db->getRequestType($i["id"])['request_description'].'
                            </td>
                            <td>
                              <div class="">';
                                $returnedFrom = explode(',', $i["request_route"])[0];
                                $app_auth = $db->getEmployeeProfile($returnedFrom);
                                echo '<a href="#" class="avatar avatar-sm rounded-circle" data-toggle="tooltip" data-original-title="'.$app_auth['EmpID'].' - '.$app_auth['department'].'">';
                                echo '<i class="fas fa-user" aria-hidden="true"></i>';
                                echo '</a>';
                                echo '<span class="h5"> '.$app_auth['EmpID'].'-'.$app_auth['department'].'-'.$app_auth['fullName'].'</span>';
                                echo 
                              '</div>
                            </td>';
                            echo '<td>
                                  <div>
                                    <button class="btn btn-primary btn-icon-only text-white open-modal" data-toggle="modal" data-target="#activityModal">
                                      <i class="fas fa-tasks my-auto"></i>
                                    </button>
                                  </div>
                                </td>';
                            echo '</tr>';
                          }
                        }
                      } else {
                          echo '<tr>
                              <th scope="row" class="text-center" colspan="7"> Nothing to show here.. </th>
                          </tr>';
                      }
                    ?>
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="activityModal">
      <div class="modal-dialog" >
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="genRequestModalLabel">Request Activity</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <br><br><br><br><br><br><br><br><br>
          
          </div>
        </div>
      </div>
    </div>


    
    <div class="modal fade" id="genRequestModal" tabindex="-1" role="dialog" aria-labelledby="genRequestModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="genRequestModalLabel">Forward Request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="processRequest.php" id="commentForm" method="post" enctype="multipart/form-data">
              <label for="request-id">Request ID </label>
              <input class="form-control" id="request-id" name="request-id" readonly></input>
              
              <label for="comment">Write your Comments Here.. </label>
              <div class="input-group mb-3">
                <textarea class="form-control" rows="2" id="comment" required type="textarea" name="comment"></textarea>
              </div>

              <input class="btn btn-primary font-weight-bold float-right" type="submit" id="forward" name="operation" value="Forward"></input>

            </form>
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