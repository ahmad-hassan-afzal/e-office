<?php

require_once ('dbconn.php');
$db = new dbconn();
session_start();

    $userID = $_POST["EmpID"];

    $name = $_POST["name"];
    if ($name != ""){
        if ( !preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $_SESSION["error"] = "Only letters are Allowed in Name Field";
            header("Location:manage-employee.php?status=error");
            exit();
        }
    } else {
        $_SESSION["error"] = "Name Field is missing";
        header("Location:manage-employee.php?status=error");
        exit();
    }

    $contact = $_POST["contact"];
    if ($name != ""){
        if ( !preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $_SESSION["error"] = "Only letters are Allowed in Name Field";
            header("Location:manage-employee.php?status=error");
        }
    } else {
        $_SESSION["error"] = "Name Field is missing";
        header("Location:manage-employee.php?status=error");
    }

    $email = $_POST["email"];
    $department = $_POST["department"];
    
    $password = $_POST["password"];
    $passcode = $_POST["passcode"];
    
    $app_auth = 0;
    $route_mngr = 0;
    $emp_mngr = 0;
    $admin = 0;

    if (isset($_POST["app_auth"])){
        if ($_POST["app_auth"] == "on"){
            $app_auth = 1;
        }
    }
    if (isset($_POST["route_mngr"])){
        if ($_POST["route_mngr"] == "on"){
            $route_mngr = 1;
        }
    }
    if (isset($_POST["emp_mngr"])){
        if ($_POST["emp_mngr"] == "on"){
            $emp_mngr = 1;
        }
    }
    if (isset($_POST["admin"])){
        if ($_POST["admin"] == "on"){
            $admin = 1;
        }
    }
    
    if ($db->addEmployee($userID, $name, $contact, $email, $department, $passcode, $password, $app_auth, $route_mngr, $emp_mngr, $admin))
        header("Location:manage-employee.php?status=user-added");

?>

