<?php

require_once ('dbconn.php');
$db = new dbconn();
session_start();

    $route_members = $_POST["managers"][0];
    $route_id = $_POST["route-id"];

    for ($i = 1; $i < sizeof($_POST["managers"]); $i++){
        $route_members = $route_members.','.$_POST['managers'][$i];
    }

    if ($db->checkRouteUsages($route_id)["number_of_requests"] == '0'){
        if ($db->updateRoute($route_id, $route_members))
            header("Location:manage-route.php?status=done");
    } else {
        header("Location:manage-route.php?status=usage-found");
    }
    
?>

