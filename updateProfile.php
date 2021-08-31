<?php

require_once ('dbconn.php');
$db = new dbconn();
session_start();

    $userID = $_SESSION["EmpID"];
    $contact = $_POST["contact"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $passcode = $_POST["passcode"];
    

    if ($db->updateUser($userID, $contact, $email, $address, $passcode))
        header("Location:my-profile.php?update-status=done");
        
?>

