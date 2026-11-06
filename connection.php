7<?php
session_start();
$conn = new mysqli("localhost","root","","citizen_reporting_system");
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}
?>