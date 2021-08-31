<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Ahmad
 * Date: 28-Apr-2021
 * Time: 8:18 PM
 */

session_start();

$_SESSION['EmpID'] = null;
header('Location: login.php');