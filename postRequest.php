<?php

require_once ('convert2HTML.php');
require_once ('dbconn.php');
$db = new dbconn();
session_start();

$total = 0;
if ($_FILES['files']['name'][0] != "")
    $total = count($_FILES['files']['name']);

$files = $_FILES['files'];
$fileNames = $files['name'];
$fileTmpNames = $_FILES['files']['tmp_name'];
$fileSizes = $_FILES['files']['size'];
$fileErrors = $_FILES['files']['error'];

date_default_timezone_set("Asia/Karachi");
$date = date('d.m.y-h:i:s a');

//  Major Attachment Uploading

    $maj_file = $_FILES['file'];
    $maj_fileName = $maj_file['name'];
    $maj_fileTmpName = $_FILES['file']['tmp_name'];
    $maj_fileSize = $_FILES['file']['size'];
    $maj_fileError = $_FILES['file']['error'];
    
    $maj_fileExt = explode('.',$maj_fileName);
    $maj_fileActualExt = strtolower(end($maj_fileExt));

    $maj_allowed = array('docx','doc');
    if(in_array($maj_fileActualExt,$maj_allowed)) {
        if($maj_fileError === 0) {
            if($maj_fileSize < 10000000) {
                
                $date = date('dmy-hisa');

                mkdir("uploads/".$date);

                $maj_fileDestination = "uploads/$date/$maj_fileName";
                move_uploaded_file($maj_fileTmpName, $maj_fileDestination);
                
                $route = explode('|',  $_POST["request-route"]);
                
                $request_id = $db->generateRequest($_SESSION['EmpID'], $_SESSION['Name'], $date, "uploads/$date/", $maj_fileName, $route[0], $route[1]);
                // Generating Approvals of Different Managers
                if ($request_id == null)
                    header("Location:generate-request.php?status=failed");

                $route_members = explode(",", $route[1]); 

                $db->addRequestTraversal($route_members[0], $request_id, $_SESSION["EmpID"], 'Initiated', 1);

            } else
            echo"<script>alert('File size to big')</script>";
    } else
        echo"<script>alert('Please try again later')</script>";
} else
    echo"<script>alert('Only docx, doc, pdf allowed. FileType: $fileExt')</script>";


if ($request_id != null){
    // Loop through each file
    for( $i=0 ; $i < $total ; $i++ ) {

        // $managers = $_POST['managers'];
        $user = $_SESSION['EmpID'];
        
        $fileExt = explode('.',$fileNames[$i]);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('docx','doc'   /*);*/   ,'pdf', 'xlsx', 'txt', 'jpg', 'jpeg');
        if(in_array($fileActualExt,$allowed)) {
            if($fileErrors[$i] === 0) {
                if($fileSizes[$i] < 10000000) {

                    $fileDestination = "uploads/$date/".$fileNames[$i];
                    move_uploaded_file($fileTmpNames[$i],$fileDestination);
                    $db->addFileToDatabase($fileNames[$i], "uploads/$date/", $request_id); // Level-8

                    convert2html("uploads/$date/", $fileNames[$i]);
                } else
                    echo"<script>alert('Minor File size to big')</script>";
            } else
                echo"<script>alert('Please try again later: Minor Files')</script>";
        } else
            echo"<script>alert('Minor: Only docx, doc, pdf allowed. FileType: $fileExt')</script>";
    }
    // end of For Loop

    header("Location:generate-request.php?status=done");
}

?>

