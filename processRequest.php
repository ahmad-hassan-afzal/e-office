<?php 
session_start();
require_once('dbconn.php');
$db = new dbconn();

if (isset($_POST["draft"])){
    if ($db->addComment($_POST['comment'], "Drafted", $_SESSION["Name"], $_POST["request-id"])) {
        header("Location:index.php?status=drafted");
    }
    
}
if ( isset($_GET["req-id"]) && isset($_GET["withdraw"])){
    $request_id = $_GET["req-id"];
    echo $_GET["withdraw"];

    // update current route position in request table
    $db->updateRoutePosition($request_id, -2);

    // update processed field in req-traversal table
    $db->updateRequestTraversalProcessedStatus($request_id, "Withdrawn");
    $db->addComment("", "Posted", $_SESSION["Name"], $request_id);
    
    header("Location:my-request.php?status=withdrawn");
}

if (isset($_POST['operation'])){
    
// getting route and position from request table
    $request_id = $_POST['request-id']; 
    $request_route_details = $db->getRequestRoute($request_id);
    $request_route = $request_route_details["request_route"];
    $current_position = $request_route_details["current_route_position"];

    if ( $_POST['operation'] == "Forward" ){
       
        // update current route position in request table
        $db->updateRoutePosition($request_id, $current_position+1);

        // update processed field in req-traversal table
        $db->updateRequestTraversalProcessedStatus($request_id, "Forwarded");
        $db->addComment($_POST['comment'], "Posted", $_SESSION["Name"], $request_id);

        // insert new traversal from form submitted
        $next_member = explode(',', $request_route)[$current_position+1];
        $db->addRequestTraversal($next_member, $request_id, $_SESSION["EmpID"], '', 1);

        header("Location:index.php?status=forwarded");

    } else if ( $_POST['operation'] == "Return" ) {
        if ($current_position == 0){
            // set next member's postion to -1 AND generate new traversal for initiator id  
            
            // update current route position in request table
            $db->updateRoutePosition($request_id, -1);

            // update processed field in req-traversal table
            $db->updateRequestTraversalProcessedStatus($request_id, "Returned");
            $db->addComment($_POST['comment'], "Posted", $_SESSION["Name"], $request_id);

            // insert new traversal from form submitted
            $db->addRequestTraversal('Retutrned to initiator', $request_id, $_SESSION["EmpID"], '', -1);

            header("Location:index.php?status=returned");

        } else {
            // check if next member is initiator
            
            // update current route position in request table
            $db->updateRoutePosition($request_id, $current_position-1);

            // update processed field in req-traversal table
            $db->updateRequestTraversalProcessedStatus($request_id, "Returned");
            $db->addComment($_POST['comment'], "Posted", $_SESSION["Name"], $request_id);

            // insert new traversal from form submitted
            $next_member = explode(',', $request_route)[$current_position-1];
            $db->addRequestTraversal($next_member, $request_id, $_SESSION["EmpID"], '', -1);
            
            header("Location:index.php?status=returned");

        }
    } else if ( $_POST['operation'] == "Approved" ) {
        
        // update processed field in req-traversal table
        $db->updateRequestTraversalProcessedStatus($request_id, "Approved");
        $db->addComment($_POST['comment'], "Posted", $_SESSION["Name"], $request_id);
        
        header("Location:index.php?status=approved");

    } else if ( $_POST['operation'] == "Disapproved" ) {
        // update processed field in req-traversal table
        $db->updateRequestTraversalProcessedStatus($request_id, "Disapproved");
        $db->addComment($_POST['comment'], "Posted", $_SESSION["Name"], $request_id);
        
        header("Location:index.php?status=disapproved");
        
    }

}

?>