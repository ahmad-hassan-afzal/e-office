<?php

    require_once ('dbconn.php');
    $db = new dbconn();

    $db->addNewRequestType($_POST["request-type"]);

    header("Location:generate-route.php?type-status=done");

?>

