
<?php

session_start();

if (!isset($_SESSION["EmpID"]))
    header("Location: logout.php");

    require_once('dbconn.php');
    $db = new dbconn();
    $request_id = $_GET['req_id'];
    
    $majorAttachment = $db->getMajorAttachment($request_id);
    $allFiles = $db->getAllRequestFiles($request_id);
    $allComments = $db->getAllComments($request_id, "Posted");
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Office | Document Viewer</title>

    <!-- Favicon -->
    <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
    <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
    <!-- Argon CSS -->
    <link rel="stylesheet" href="assets/css/argon.css?v=1.2.0" type="text/css">

    <!-- ck-editor -->
    
    <script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/decoupled-document/ckeditor.js"></script>
    <link rel="stylesheet" href="style/editor-styles.css">

    <script>
     function printDiv(divId,title) {

        let mywindow = window.open('', 'PRINT', 'height=650,width=900,top=100,left=150');

        mywindow.document.write(`<html><head><title>${title}</title>`);
        mywindow.document.write('</head><body >');
        mywindow.document.write(document.getElementById(divId).innerHTML);
        mywindow.document.write('</body></html>');

        mywindow.document.close();
        mywindow.focus(); 
        
        mywindow.print();

        return true;
        }

    </script>


</head>
<body>
<!-- Topnav -->
  <nav class="navbar navbar-top navbar-expand navbar-light bg-white border-bottom border-default">
    <div class="container-fluid">
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Search form -->
        <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
          <a class="btn" href="/e-office" data-toggle="tooltip" data-original-title="Go Back"><i class="ni ni-bold-left"></i></a>
          <div class="form-group mb-0">
            <div class="input-group input-group-alternative input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input class="form-control" placeholder="Search" type="text">
            </div>
          </div>
          <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </form>
        <!-- Navbar links -->
        <img src="assets/img/brand/blue.png" alt="" width="200" class="justify-content-center mx-auto">
        <ul class="navbar-nav align-items-center  ml-md-auto ">
          <li class="nav-item d-sm-none">
            <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
              <i class="ni ni-zoom-split-in"></i>
            </a>
        </ul>
        <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                <i class="fas fa-user" aria-hidden="true"></i>
                </span>
                <div class="media-body  ml-2  d-none d-lg-block">
                  <span class="mb-0 text-sm  font-weight-bold"><?php echo $_SESSION['Name']; ?></span>
                </div>
              </div>
            </a>
            <div class="dropdown-menu  dropdown-menu-right ">
              <div class="dropdown-header noti-title">
                <h6 class="text-overflow m-0">Welcome!</h6>
              </div>
              <a href="Profile.php" class="dropdown-item">
                <i class="ni ni-single-02"></i>
                <span>My profile</span>
              </a>
              <a href="#!" class="dropdown-item">
                <i class="ni ni-settings-gear-65"></i>
                <span>Settings</span>
              </a>
              <a href="" class="dropdown-item">
                <i class="ni ni-support-16"></i>
                <span>Contact Support</span>
              </a>
              <a href="" class="dropdown-item">
                <i class="ni ni-badge"></i>
                <span>About Developers</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="logout.php" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Logout</span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="row m-auto">
  <div class="col-md-3" style="overflow-y: scroll;">
  <h1 class="card-title my-3 text-center">Files</h1>
    <!-- List group -->
    <div class="list-group list-group-flush">
      <div class="card mb-1">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col-auto">
              <div class="icon icon-shape bg-gradient-blue text-white rounded-circle shadow">
              <i class="fa fa-file" aria-hidden="true"></i>
              </div>
            </div>
            <div class="col mt-2">
              <?php 
              echo '<a class="h3 font-weight-bold" href="DocumentViewer.php?req_id='.$request_id.'&path='.$majorAttachment[0]["major_filepath"].$majorAttachment[0]["major_filename"].'">'.$majorAttachment[0]["major_filename"].'</a>';
              ?>
            </div>
          </div>
        </div>
      </div>
      
      <?php  
      if ($allFiles != null){
        foreach ($allFiles as $i){
          echo '<div class="card mb-1 ml-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-auto">
                      <div class="icon icon-sm icon-shape bg-gradient-orange text-white rounded-circle shadow">
                      <i class="fa fa-file" aria-hidden="true"></i>
                      </div>
                    </div>
                    <div class="col mt-2">';
              echo '<a class="h3 mylink font-weight-bold" href="DocumentViewer.php?req_id='.$request_id.'&path='.$i["path"].$i["file_name"].'">'.$i["file_name"].'</a>';
              echo '</div>
                  </div>
                </div>
              </div>';
        }
      }
      ?>
    </div>
    <!-- View all -->
  </div>

  <div class="col-md-6" style="overflow-x: scroll;" >
    <div class="document-editor">
      <div class="document-editor__toolbar"></div>
      <div class="document-editor__editable-container">
        <?php
            if (isset($_GET['path'])) {
                echo '<iframe id="editor" src="'.$_GET['path'].'.html"></iframe>';
            } else {
                echo '<div id="editor"></div>';
            }
        ?>
      </div>
    </div>
  </div>
  
  <div class="col-md-3" style="overflow-y: scroll;">
    <h1 class="card-title my-3 text-center">Comments</h1>
      <!-- List group -->
      <div class="list-group list-group-flush">
        <?php
        if ($allComments != null) {
          foreach ($allComments as $i) {
            echo '<div class="card p-3">
              <div class="row ">
                <div class="col">
                  <span class="text-sm text-muted float-right">'.$i["timestamp"].'</span>
                  <h4 class="mt-4 h4">
                    <i class="fa fa-user avatar rounded-circle" aria-hidden="true"></i>
                    <span class="m-2">'.$i["commenter"].'</span>
                  </h4>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <p class="text-sm m-2">'.$i["comment"].'</p>
                </div>
              </div>
            </div>';
          }
        } else {
          echo '<h3 class="text-center text-muted">No comments From anyone</h3>';
        }
        ?>
      </div>
      <!-- View all -->
    </div>
  </div>

<script>
DecoupledEditor
    .create( document.querySelector( '#editor' ) )
    .then( editor => {
        const toolbarContainer = document.querySelector( '#toolbar-container' );

        toolbarContainer.appendChild( editor.ui.view.toolbar.element );
    } )
    .catch( error => {
        console.error( error );
    } );
</script>

</body>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/js-cookie/js.cookie.js"></script>
  <script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  <!-- Argon JS -->
  <script src="assets/js/argon.js?v=1.2.0"></script>
</html>