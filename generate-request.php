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
    <title>E-Office | Generate Request</title>

    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

    <script>
        $(document).ready(function() {
            $('select').selectize({
                sortField: 'text'
            });
        });
        updateApplicationName = function() {
            var input = document.getElementById('customFile');
            var output = document.getElementById('ApplicationName');
            output.innerHTML = '<strong>Selected File:</strong> '+input.files[0].name;
        }
        updateList = function() {
            var input = document.getElementById('customFiles');
            var output = document.getElementById('fileList');
            var children = "";
            for (var i = 0; i < input.files.length; ++i) {
                children += '<li>' + input.files.item(i).name + '</li>';
            }
            output.innerHTML = '<strong>Selected Files:</strong> <ol>'+children+'</ol><br>';
        }
    </script>

    <style>
        .selectize-input {
            padding: 10px 12px;
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
                        <div class='alert bg-gradient-success text-center align-middle py-7' role='alert'> 
                        <p class='h1 text-white'><strong>Request Sent Successfully!</strong></p>
                        <p class='h3 text-white'>All the files are attached to the request and sent to specified manager(s) successfully </p><br>
                        <a class='btn btn-primary text-white' href='my-requests.php'><i class='ni ni-bold-left'></i> &nbsp; Go back to My Requests</a>
                        <a class='btn btn-default text-white' href='generate-request.php'><i class='ni ni-send'></i> &nbsp; Generate another Request</a>
                        </div>
                       </div>";

                include('includes/footer.php');
                exit;
            } else {
                echo "<div class='container mt-4 mb-2'>
                            <div class='alert bg-gradient-success text-center align-middle py-7' role='alert'> 
                            <p class='h1 text-white'><strong>Request Sent Successfully!</strong></p>
                            <p class='h3 text-white'>All the files are attached to the request and sent to specified manager(s) successfully </p><br>
                            <a class='btn btn-primary text-white' href='my-requests.php'><i class='ni ni-bold-left'></i> &nbsp; Go back to My Requests</a>
                            <a class='btn btn-default text-white' href='generate-request.php'><i class='ni ni-send'></i> &nbsp; Generate another Request</a>
                            </div>
                           </div>";
            }
        }
    ?>
   <div class="container-fluid mt-4">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-1">
              <h3> <i class="ni ni-send text-green"></i> &nbsp; Generate Requests</h3>
            </div>
            <div class="card-body mx-4">
                <form action='postRequest.php' method='post' enctype='multipart/form-data'>
                    <div class="form-group">
                        <label for="FormControlInputSubject"> Request Type </label>
                        <select class="form-control" id="request-type" name="request-route" required placeholder="Select Request Type ..">
                        <option></option>
                            <?php
                                $res = $db->getAllRequestTypes($_SESSION["EmpID"]);
                                for ($i=0; $i<sizeof($res); $i++){
                                    echo '<option value='.$res[$i]["route_id"].'|'.$res[$i]["route"].'>'.$res[$i]["request_description"].'</option>';
                                }
                            ?>
                        </select>
                    </div>

                    Attach Application
                    <div class="custom-file mb-4">
                        <input type="file" class="custom-file-input" id="customFile" lang="en" style="visibility: hidden;" onchange="javascript:updateApplicationName()" name="file" draggable required>
                        <label class="custom-file-label small" id="custom-file-label" for="customFile">Browse for Application..</label>
                        <div id="ApplicationName" class="text-muted mt-0"></div>
                    </div>

                    Attach Supporting Document(s)
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="customFiles" lang="en" name="files[]"  style="visibility: hidden;" onchange="javascript:updateList()" multiple draggable>
                        <label class="custom-file-label small" for="customFiles">Browse for Supporting Documents..</label>
                        <div id="fileList" class="text-muted"></div>
                    </div>
                    
                    <div class="card-footer">
                        <button class='btn btn-default col-12' type='submit' name='submit'>Post Request</button>
                    </div>
                </form>
            </div>
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