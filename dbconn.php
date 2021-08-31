<?php
class dbconn
{
    private $dbcon;
    function __construct()
    {
        $this->dbcon = new PDO("mysql: host=localhost;dbname=drs_eoffice", "root", "");
        if(!$this->dbcon){
            die("Connection failed.");
        }
    }
    public function addEmployee($EmpID, $name, $contact, $email, $department, $passcode, $password, $app_auth, $route_mngr, $emp_mngr, $admin){
        try {
            $sql = "INSERT INTO `user` (`EmpID`, `fullName`, `Contact`, `email`, `department`, `passcode`, `password`, `app_auth`, `route_mngr`, `emp_mngr`, `admin`) VALUES ('$EmpID', '$name', '$contact', '$email', '$department', '$passcode', '$password', '$app_auth', '$route_mngr', '$emp_mngr', '$admin');";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                return $res;
            else 
                return null;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function updateEmployee($EmpID, $name, $contact, $email, $department, $passcode, $app_auth, $route_mngr, $emp_mngr, $admin){
        try {
            $sql = "UPDATE `user` SET `email`='$email', `passcode`='$passcode', `fullName`='$name', `Contact`='$contact', `department` = '$department', `app_auth` = $app_auth, `route_mngr` = $route_mngr, `emp_mngr` = $emp_mngr, `admin` = $admin WHERE EmpID = '$EmpID'";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                return true;
            else 
                return false;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function updateUser($userID, $contact, $email, $address, $passcode){
        try {
            $sql = "UPDATE `user` SET `Contact`='$contact', `email`='$email', `passcode`='$passcode', `address`='$address' WHERE EmpID = '$userID'";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                return true;
            else 
                return false;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function deleteUser($userID){
        try {
            $sql = "DELETE FROM `user` WHERE `user`.`EmpID` = '$userID'";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                return true;
            else 
                return false;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function generateRequest($user_id, $user_name, $timestamp, $file_path, $file_name, $route_id, $route){
        try {
            $sql = "INSERT INTO `request` (`user_id`, `sender`, `major_filepath`, `major_filename`, `request_route_id`, `request_route`) VALUES ('$user_id','$user_name', '$file_path','$file_name', '$route_id', '$route');";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res)){
            echo "Gen-Req";
                return $this->dbcon->lastInsertId();
            } else 
                return null;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function generateAllPaths($emp_id, $req_id, $mgr_id, $approval_level){
    // Depreciated
        try {
            $sql = "INSERT INTO `request_all_paths` (`EmpID`, `request_id`, `approval_authority_id`, `approval_level`) VALUES ('$emp_id',$req_id,'$mgr_id',$approval_level)";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res)){
                print_r($res);
                return $res;
            } else 
                return null;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function addRequestTraversal($mgr_id, $req_id, $EmpID, $operation, $prev_dec){
        try {
            $sql = "INSERT INTO `request_traversal` (`request_id`, `approval_authority`, `sent_by`, `prev_dec`, `operation`, `processed`, `comment`, `datetime`) VALUES ('$req_id', '$mgr_id', $EmpID, $prev_dec, '$operation', '0', '', current_timestamp());";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                return $res;
            else 
                return null;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function getRequests($EmpID, $prev_dec)
    {
        try {
            $st = $this->dbcon->prepare("SELECT * FROM `request_traversal` WHERE approval_authority = '$EmpID' AND processed = 0 AND prev_dec = '$prev_dec';");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getPendingRequests($EmpID)
    {
        try {
            $st = $this->dbcon->prepare("SELECT * FROM `request_traversal` WHERE approval_authority = '$EmpID' 
                AND `sent_by` = 
                    (SELECT `approval_authority` FROM `request_traversal` WHERE `operation` LIKE 'Returned' GROUP BY request_id DESC);");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getEmployeeProfile($EmpID)
    {
        try {
            $st = $this->dbcon->prepare("SELECT * FROM `user` WHERE EmpID LIKE '$EmpID';");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetch();
                return $result;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getProcessedRequests($EmpID)
    {
        try {
            $st = $this->dbcon->prepare("SELECT * FROM `request_traversal` WHERE approval_authority = '$EmpID' AND processed = 1;");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getRequestType($req_id)
    {
        try {
            $st = $this->dbcon->prepare("SELECT request_type.request_description from request_type where request_type.id = 
                                            (SELECT routes.request_type_id FROM routes where routes.route_id = 
                                                (SELECT request_route_id FROM request WHERE request.id = $req_id))");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetch();
                return $result;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getSenderInfo($req_id)
    {
        try {
            $st = $this->dbcon->prepare("SELECT * FROM user WHERE EmpID = (SELECT request.user_id FROM request WHERE request.id = $req_id)");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetch();
                return $result;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getUserFullName($user_id)
    {
        try {
            $st = $this->dbcon->prepare("SELECT fullName FROM user WHERE EmpID = $user_id");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetch();
                return $result;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getRequestRoute($req_id)
    {
        try {
            $st = $this->dbcon->prepare("SELECT request_route, current_route_position FROM request WHERE request.id = $req_id");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetch();
                return $result;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function updateRoutePosition($req_id, $route_pos){
        try {
            $sql = "UPDATE `request` SET `current_route_position` = '$route_pos' WHERE `id` = '$req_id';";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                return true;
            else 
                return false;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function updateRequestTraversalProcessedStatus($req_id, $operation){
        try {
            $sql = "UPDATE `request_traversal` SET `operation`='$operation',`processed`=1, `comment_status`='Posted' WHERE request_id = $req_id AND `processed` = 0 ";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                return true;
            else 
                return false;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function generateApprovals($mgr_id, $req_id){
        try {
            $sql = "INSERT INTO `approvals` (`manager_id`, `request_id`) VALUES ('$mgr_id','$req_id');";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                return $res;
            else 
                return null;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function addFileToDatabase($file_name, $path, $req_id){
        try {
            $sql = "INSERT INTO `files` (`file_name`, `path`, `request_id` ) VALUES ('$file_name','$path', $req_id);";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                echo "Added to database Successful";
            else 
                echo "Database Addition Unsuccessful";
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    // Comments Module
    public function addComment( $cmt,  $cmt_status, $cmtr, $req_id){
        try {
            $sql = "INSERT INTO `comments` ( `comment`, `comment_status`, `commenter`, `request_id`) VALUES ('$cmt', '$cmt_status', '$cmtr', '$req_id');";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                return true;
            else 
                return false;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function updateComment($cmt, $status, $cmtr,  $req_id){
        try {
            $sql = "UPDATE `comments` SET `comment` = '$cmt', `comment_status` = '$status' WHERE `request_id` = '$req_id' AND `commenter` = '$cmtr'";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                return true;
            else 
                return false;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function getAllComments($req_id, $status)
    {
        try {
            $st = $this->dbcon->prepare("SELECT commenter, comment, timestamp FROM `comments` WHERE request_id = '$req_id' AND comment_status LIKE '$status'");
            $st->execute();
            if ($st->rowCount() > 0) {
                return $st->fetchAll();
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getMyComments($cmtr, $cmt_status)
    {
        try {
            $st = $this->dbcon->prepare("SELECT `comments`.* FROM `comments` WHERE `commenter` = '$cmtr' AND `comment_status` LIKE '$cmt_status';");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getDraftedComment($req_id)
    {
        try {
            $st = $this->dbcon->prepare("SELECT * FROM `comments` WHERE request_id = '$req_id' AND comment_status = 'Drafted' ORDER BY timestamp DESC");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetch();
                return $result;
            } else {
                return -1;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    // ---
    public function getAllRequests($data)
    {
        try {
            $st = $this->dbcon->prepare("select * from request where user_id = ?");
            $st->execute($data);
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getAllPendingFiles($data)
    {
        try {
            $st = $this->dbcon->prepare("SELECT * FROM `request` LEFT JOIN `approvals` ON `approvals`.`request_id` = `request`.`id` WHERE `approvals`.`manager_id` = ?");
            $st->execute($data);
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getMyPendingRequests($data)
    {
        try {
            $st = $this->dbcon->prepare("SELECT * FROM `request` WHERE `request`.`user_id` = ?");
            $st->execute($data);
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getPendingApprovals($status, $req_id)
    {
        // Gets request id and returns names of people eho didn't approved that request
        try {
            $st = $this->dbcon->prepare("Select fullName From user where EmpID IN (select manager_id from approvals where approval_status LIKE '$status' AND request_id = $req_id)");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function upadteRequestStatus($req_id, $mgr_id, $Status, $app_flag){
        try {
            $st = $this->dbcon->prepare("UPDATE `approvals` SET `approval_status` = '$Status', `approval_flag` = '$app_flag' WHERE `request_id` = '$req_id' AND `manager_id` = '$mgr_id';");
            if($st->execute())
                return true;
        } catch (Exception $ex) {
            echo $ex;
        }
    }

    public function authUser($data){
        $st = $this->dbcon->prepare("SELECT * FROM user WHERE EmpID = ? AND password = ?");
        $st->execute($data);

        if($st->rowCount() > 0) {
            return $st->fetch();
        } else {
            return false;
        }
    }
    public function getAllEmployees()
    {
        try {
            $st = $this->dbcon->prepare("select * from user;");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getRequestTypes()
    {
        try {
            $st = $this->dbcon->prepare("select * from request_type;");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function addNewRequestType($requst_type)
    {
        try {
            $st = $this->dbcon->prepare("INSERT INTO `request_type` ( `request_description` ) VALUES ('$requst_type');");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function addRoute($EmpID, $req_type_id, $route)
    {
        try {
            $st = $this->dbcon->prepare("INSERT INTO `routes` ( `EmpID`, `request_type_id`, `route` ) VALUES ('$EmpID', '$req_type_id', '$route');");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function updateRoute($route_id, $route)
    {
        try {
            $sql = "UPDATE `routes` SET `route` = '$route' WHERE `route_id` = '$route_id';";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                return true;
            else 
                return false;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function deleteRoute($route_id)
    {
        try {
            $sql = "DELETE FROM `routes` WHERE `route_id` = '$route_id'";
            $res = $this->dbcon->exec($sql);
            if(!is_null($res))
                return true;
            else 
                return false;
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    public function checkRouteUsages($route_id)
    {
        try {
            $st = $this->dbcon->prepare("SELECT COUNT(*) as number_of_requests FROM `request` WHERE request_route_id = '$route_id';");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetch();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getAllRoutes()
    {
        try {
            $st = $this->dbcon->prepare("SELECT `routes`.*, `request_type`.`request_description` FROM `routes` LEFT JOIN `request_type` ON `routes`.`request_type_id` = `request_type`.`id`");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getRequestTypeDescirption($type_id)
    {
        try {
            $st = $this->dbcon->prepare("SELECT `request_type`.`request_description` FROM `request_type` WHERE `request_type`.`id` = '$type_id'");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetch();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getAllRequestTypes($EmpID)
    {
        try {
            $st = $this->dbcon->prepare("SELECT `request_type`.`request_description`, `routes`.`route`, `routes`.`route_id` FROM `request_type` LEFT JOIN `routes` ON `routes`.`request_type_id` = `request_type`.`id` WHERE routes.EmpID LIKE '$EmpID'");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getAllRequestFiles($req_id)
    {
        try {
            $st = $this->dbcon->prepare("SELECT `path`, `file_name` FROM `files` WHERE request_id = $req_id");
            $st->execute();
            if ($st->rowCount() > 0) {
                return $st->fetchAll();
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getMajorAttachment($req_id)
    {
        try {
            $st = $this->dbcon->prepare("select major_filepath, major_filename from request where id = $req_id");
            $st->execute();
            if ($st->rowCount() > 0) {
                return $st->fetchAll();
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    public function getRequestsForComments($mgr_id)
    {
        try {
            $st = $this->dbcon->prepare("SELECT `approvals`.`request_id`, `request`.`subject`
                                        FROM `approvals` 
                                            LEFT JOIN `request` ON `approvals`.`request_id` = `request`.`id`
                                            WHERE `approvals`.`manager_id` = $mgr_id
                                            AND `approvals`.`comment_status` NOT LIKE 'Post';");
            $st->execute();
            if ($st->rowCount() > 0) {
                $result = $st->fetchAll();
                return $result;
            }
            else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    
//    Raw
    public function addUserDB($var)
    {

        $result = $this->conn->prepare("INSERT INTO user (fullName,userName,email,password) VALUES (?,?,?,?)");
        $result->execute($var);

        if($result->rowCount())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
}
?>