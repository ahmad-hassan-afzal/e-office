<?php 

require_once ('dbconn.php');
$db = new dbconn();

    $route_id = $_GET["id"];
    if ($db->checkRouteUsages($route_id)["number_of_requests"] == '0'){
        $db->deleteRoute($route_id);
        header("Location:manage-route.php?delete-status=done");
    } else {
        header("Location:manage-route.php?delete-status=usage-found");
    }

?>