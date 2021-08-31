<?php

require_once ('dbconn.php');
$db = new dbconn();
session_start();

    $route_members = $_POST["managers"][0];

    for ($i = 1; $i < sizeof($_POST["managers"]); $i++){
        $route_members = $route_members.','.$_POST['managers'][$i];
    }

    $db->addRoute($_POST["EmpID"], $_POST["request-type"], $route_members);

    header("Location:generate-route.php?status=done");

?>

