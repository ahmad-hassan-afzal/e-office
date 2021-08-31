<?php 

require_once ('dbconn.php');
$db = new dbconn();

if (isset($_GET["user-id"])){
    $userID = $_GET["user-id"];
    if ($db->deleteUser($userID)){
        header("Location:manage-employee.php?status=user-deleted");
    }
}

?>