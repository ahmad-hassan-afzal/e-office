<?php

session_start();

require_once("dbconn.php");
$db = new dbconn();

if (!isset($_SESSION["EmpID"]))
  header("Location: logout.php");

if (!($_SESSION['app_auth'] || $_SESSION['admin'])){
  header("Location: my-requests.php");
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
    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
    
    <script>
      $(document).ready(function () {

        $('.open-modal').on('click', function () {
          var reqID = $(this).attr('request-id');
          $("#request-id").val(reqID);

          var cmnt = $(this).attr('drafted-comment');
          $("textarea#comment").val(cmnt.toString());

          var ops = document.getElementById("footer-buttons");
          var myEmpID = "<?= $_SESSION["EmpID"] ?>";
          var appAuth = $(this).attr('final-app-auth');
          
          if ( appAuth == myEmpID ) {
            ops.innerHTML = '<input class="btn btn-warning font-weight-bold" type="submit" id="return" name="operation" disabled value="Return"></input>'+
                            '<input class="btn btn-danger font-weight-bold" type="submit" id="disapprove" name="operation" disabled value="Disapproved"></input>'+
                            '<input class="btn btn-primary font-weight-bold float-right" type="submit" id="approve" name="operation" disabled value="Approved"></input>';
          } else {
            ops.innerHTML = '<input class="btn btn-warning font-weight-bold" type="submit" id="return" name="operation" disabled value="Return"></input>'+
                            '<input class="btn btn-primary font-weight-bold float-right" type="submit" id="forward" name="operation" disabled value="Forward"></input>';
          }
        });   
      });
      verifyPasscode = function() {
        var passcode = document.getElementById('Approval-Passcode');
        var input = document.getElementById('pass-code');
        var ops = document.getElementById("passcode-verified");

        if (input.value == passcode.value){
          ops.innerHTML = '<strong class="text-green"><i class="ni ni-check-bold"></i> Verified</strong>';
          if (document.body.contains(document.getElementById("approve")) ){
            document.getElementById("approve").disabled = false;
          }
          if (document.body.contains(document.getElementById("disapprove")) ){
            document.getElementById("disapprove").disabled = false;
          }
          if (document.body.contains(document.getElementById("forward")) ){
            document.getElementById("forward").disabled = false;
          }
          if (document.body.contains(document.getElementById("return")) ){
            document.getElementById("return").disabled = false;
          }

        } else {
          ops.innerHTML = '<strong class="text-danger"><i class="ni ni-fat-remove"></i> Invalid Passcode</strong>';
        }
      }
    </script>

</head>
<body>




<?php include('includes/sidenav.php') ?>
<div class="main-content">

  <?php include('includes/header.php'); ?>
    <div class="header pb-3">  
    </div>
    <?php 
    if (isset($_GET['Status'])){
      $res = $_GET['Status'];
      if ($res){
        echo '<div class="alert alert-success alert-dismissible mt-2 fade show">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Request '.$_GET["Status"].' Successfully!
            </div>';
      } else {
        echo '<div class="alert alert-danger alert-dismissible mt-2 fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Request could not be '.$_GET["Status"].' Successfully!
              </div>';
      }
    }

    if(isset($_GET["status"])){
      $msg_title = "";
      $msg = "";
      $bg = "";
      
      if ($_GET["status"] == "forwarded"){
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
      } else if ($_GET["status"] == "drafted"){
        $msg_title = "Success!";
        $msg = "Comment Added to Drafts!";
        $bg = "bg-gradient-warning";
      } 
      echo '<div class="alert text-white '.$bg.' mx-lg-6 fade show" role="alert">
              <strong> '.$msg_title.' </strong> '.$msg.'
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>';
    }
    ?>
   <div class="container-fluid mt-3">

   <input type="text" id="Approval-Passcode" hidden value="<?php echo $_SESSION["ApprovalCode"]?>">
   
      <div class="row">
        <div class="col">
          <div class="card">

          
          <!-- Card header -->
            <div class="card-header border-1">
              <div class="row">
                <div class="col-md-8 col-sm-12 nav nav-tabs border-0" id="nav-tab" role="tabList">
                  <a class="h2 my-auto ml-2" href="#pending" data-toggle="tab">Pending Requests</a>
                  <a class="h3 my-auto ml-3 btn border border-warning " data-toggle="tab" href="#returned"> Returned Requests </a>
                  <a class="h3 my-auto ml-3 btn border border-primary " data-toggle="tab" href="#processed"> Processed Requests </a>
                </div>
                <div class="col-md-4 col-sm-12">
                  <a href="index.php" class="btn btn-secondary rounded-0 float-right" data-toggle="tooltip" data-original-title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                </div>
              </div>
            </div>
            
            <div class="tab-content">

              <div class="tab-pane fade show active table-responsive" id="pending" style="max-height: 450px;">
                <table class="table align-items-center table-flush" id="allRequests">
                  <thead class="thead-light" style="position: sticky; top: 0; z-index: 999;">
                    <tr>
                      <th scope="col">Request </th>
                      <th scope="col">Initiated By</th>
                      <th scope="col">
                        <span class="badge badge-dot"><i class="bg-warning"></i></span>    
                        Forwarded From
                      </th>
                      <th scope="col"> Date Time </th>
                      <th scope="col">Files</th>
                      <th scope="col">Operations</th>
                    </tr>
                  </thead>
                  
                  <tbody class="list">
                    <?php
                    
                    $pendingFwdRequests = $db->getRequests($_SESSION["EmpID"], 1);
                  
                      if ($pendingFwdRequests != null){

                          foreach ($pendingFwdRequests as $i){

                            $senderInfo = $db->getSenderInfo($i['request_id']);

                            echo '<tr>
                              <td data-toggle="tooltip" data-original-title="'.$i["datetime"].'">Req-'.$i["request_id"].' - '.$db->getRequestType($i['request_id'])["request_description"].'</td>
                              <td><i class="fas fa-user" aria-hidden="true"></i> &nbsp; '.$senderInfo["EmpID"].'-'.$senderInfo["department"].'-'.$senderInfo["fullName"].'</td>
                              <td><div class="avatar-group">
                                <a href="#" class="avatar avatar-sm rounded-circle" data-toggle="tooltip" data-original-title="'.$i["sent_by"].'">
                                  <i class="fas fa-user" aria-hidden="true"></i> 
                                </a>&nbsp;'.$db->getUserFullName($i["sent_by"])["fullName"].'</div>
                              </td>
                              <td>'.$i["datetime"].'</td>
                              <td><a href="DocumentViewer.php?req_id='.$i["request_id"].'">Files</td>';
                              // Approval Flag b traversal me se ana chaheay
                                if($i['processed'] == 0){
                                  $request_route = explode(',', $db->getRequestRoute($i["request_id"])['request_route']);
                                  $final_app_auth = $request_route[sizeof($request_route)-1];
                                  $comment = "";
                                  if ($db->getDraftedComment($i["request_id"]) != -1){
                                    $comment = $db->getDraftedComment($i["request_id"])["comment"];
                                  }
                                  echo '<td class="text-center">
                                          <button class="btn btn-sm btn-primary btn-icon-only text-light open-modal" request-id="'.$i["request_id"].'" final-app-auth="'.$final_app_auth.'" drafted-comment="'.$comment.'" data-toggle="modal" data-target="#genRequestModal">
                                            <i class="fas fa-ellipsis-v my-auto"></i>
                                          </button>
                                        </td>';
                                } else {
                                  echo '<td class="text-center">
                                          <div>
                                            <button class="btn btn-sm btn-primary btn-icon-only text-light" disabled>
                                              <i class="fas fa-ellipsis-v my-auto"></i>
                                            </button>
                                          </div>
                                        </td>';
                                }
                          echo '</tr>';
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
                <table class="table align-items-center table-flush" id="allRequests">
                  <thead class="thead-light" style="position: sticky; top: 0; z-index: 999;">
                    <tr>
                      <th scope="col">Request </th>
                      <th scope="col">Initiated By</th>
                      <th scope="col">
                        <span class="badge badge-dot"><i class="bg-warning"></i></span>    
                        Returned From
                      </th>
                      <th>Reason</th>
                      <th scope="col"> Date Time </th>
                      <th scope="col">Files</th>
                      <th scope="col">Operations</th>
                    </tr>
                  </thead>
                  
                  <tbody class="list">
                    <?php
                    
                    $pendingReturnedRequests = $db->getRequests($_SESSION["EmpID"], -1);
                    // print_r($pendingReturnedRequests);
                  
                      if ($pendingReturnedRequests != null){

                          foreach ($pendingReturnedRequests as $i){
                            echo '<tr>
                              <td data-toggle="tooltip" data-original-title="'.$i["datetime"].'">Req-'.$i["request_id"].' - '.$db->getRequestType($i['request_id'])["request_description"].'</td>
                              <td><i class="fas fa-user" aria-hidden="true"></i> &nbsp; '.$db->getSenderInfo($i['request_id'])["fullName"].'</td>
                              <td><div class="avatar-group">
                                <a href="#" class="avatar avatar-sm rounded-circle" data-toggle="tooltip" data-original-title="'.$i["sent_by"].'">
                                  <i class="fas fa-user" aria-hidden="true"></i> 
                                </a>&nbsp;'.$db->getUserFullName($i["sent_by"])["fullName"].'</div>
                              </td>
                              <td>'.$i["comment"].'</td>
                              <td>'.$i["datetime"].'</td>
                              <td><a href="DocumentViewer.php?req_id='.$i["request_id"].'">Files</td>';
                              // Approval Flag b traversal me se ana chaheay
                                if($i['processed'] == 0){
                                  $request_route = explode(',', $db->getRequestRoute($i["request_id"])['request_route']);
                                  $final_app_auth = $request_route[sizeof($request_route)-1];
                                  echo '<td class="text-center">
                                          <button class="btn btn-sm btn-primary btn-icon-only text-light open-modal" request-id="'.$i["request_id"].'" final-app-auth="'.$final_app_auth.'" data-toggle="modal" data-target="#genRequestModal">
                                            <i class="fas fa-ellipsis-v my-auto"></i>
                                          </button>
                                        </td>';
                                } else {
                                  echo '<td class="text-center">
                                          <div>
                                            <button class="btn btn-sm btn-primary btn-icon-only text-light" disabled>
                                              <i class="fas fa-ellipsis-v my-auto"></i>
                                            </button>
                                          </div>
                                        </td>';
                                }
                              echo '</tr>';
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

              <div class="tab-pane fade table-responsive"  id="processed" style="max-height: 450px;">
                <table class="table align-items-center table-flush" id="allRequests">
                  <thead class="thead-light" style="position: sticky; top: 0; z-index: 999;">
                    <tr>
                      <th scope="col">Request </th>
                      <th scope="col">Initiated By</th>
                      <th scope="col">
                        <span class="badge badge-dot"><i class="bg-warning"></i></span>    
                        Forwarded / Returned to
                      </th>
                      <th scope="col"> Date Time </th>
                      <th scope="col">Files</th>
                      <th scope="col">Operations</th>
                    </tr>
                  </thead>
                  
                  <tbody class="list">
                    <?php
                    
                    $pendingReturnedRequests = $db->getProcessedRequests($_SESSION["EmpID"]);
                    
                      if ($pendingReturnedRequests != null){

                          foreach ($pendingReturnedRequests as $i){
                            echo '<tr>
                              <td data-toggle="tooltip" data-original-title="'.$i["datetime"].'">Req-'.$i["request_id"].' - '.$db->getRequestType($i['request_id'])["request_description"].'</td>
                              <td><i class="fas fa-user" aria-hidden="true"></i> &nbsp; '.$db->getSenderInfo($i['request_id'])["fullName"].'</td>
                              <td><div class="avatar-group">
                                <a href="#" class="avatar avatar-sm rounded-circle" data-toggle="tooltip" data-original-title="'.$i["sent_by"].'">
                                  <i class="fas fa-user" aria-hidden="true"></i> 
                                </a>&nbsp;'.$db->getUserFullName($i["approval_authority"])["fullName"].'</div>
                              </td>
                              <td>'.$i["datetime"].'</td>
                              <td><a href="DocumentViewer.php?req_id='.$i["request_id"].'">Files</td>';
                              // Approval Flag b traversal me se ana chaheay
                                if($i['processed'] == 0){
                                  $request_route = explode(',', $db->getRequestRoute($i["request_id"])['request_route']);
                                  $final_app_auth = $request_route[sizeof($request_route)-1];
                                  echo '<td class="text-center">
                                          <button class="btn btn-sm btn-primary btn-icon-only text-light open-modal" request-id="'.$i["request_id"].'" final-app-auth="'.$final_app_auth.'" data-toggle="modal" data-target="#genRequestModal">
                                            <i class="fas fa-ellipsis-v my-auto"></i>
                                          </button>
                                        </td>';
                                } else {
                                  echo '<td class="text-center">
                                          '.$i['operation'].'
                                        </td>';
                                }
                              echo '</tr>';
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

    <!-- Modal -->
    <div class="modal fade" id="genRequestModal" tabindex="-1" role="dialog" aria-labelledby="genRequestModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="genRequestModalLabel">Write Your Comments</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="processRequest.php" id="commentForm" method="post" enctype="multipart/form-data">
              <label for="request-id">Request ID </label>
              <input class="form-control" id="request-id" name="request-id" readonly></input>
              
              <label for="comment">Write your Comments Here.. </label>
              <div class="input-group">
                <textarea class="form-control" rows="2" id="comment" required type="textarea" name="comment"></textarea>
                <div class="input-group-append">
                  <button type="submit" name="draft" class="btn btn-warning" value="Draft">
                  <i class="ni ni-archive-2"></i> Draft</button>
                </div>
              </div>
            
              <label class="" for="pass-code">Passcode </label>
              <div class="input-group">
                <input type="password" class="form-control" placeholder="Approval Passcode" id="pass-code" >
                <div class="input-group-append">
                  <button class="btn btn-secondary" type="button" onclick="verifyPasscode()">Verify</button>
                </div>
              </div>
              <span id="passcode-verified"></span>
              <hr>
              <div id="footer-buttons">
		  <input class="btn btn-warning font-weight-bold" type="submit" id="return" name="operation" disabled value="Return"></input>
                  <input class="btn btn-primary font-weight-bold float-right" type="submit" id="forward" name="operation" disabled value="Forward"></input>
	      </div>
              
              </form>
          </div>
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