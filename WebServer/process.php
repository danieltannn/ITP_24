<?php

//For Troubleshooting Purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//SQL Connection & Credentials Set Up
$config = parse_ini_file('../../ITP_db_config.ini');
$conn = new mysqli($config['dbservername'], $config['dbusername'], $config['dbpassword'], $config['dbname']);
//$conn new mysqli("servername", "db_username", "db_password", "db_name"); // For Personal Testing Purposes

//SQL Connection Testing
/*
if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}
*/

//Default Interval Value
$interval = 5;

//Insert UUID value (POST METHOD)
if ($_POST["uuid"] != null) {
    $uuid = $_POST["uuid"];
    $sql = "INSERT INTO proctoring (uuid, intervals) VALUES ('$uuid', '$interval')";
    //echo $sql; //For Troubleshooting purposes
    if (mysqli_query($conn, $sql)) {
        echo '<script>console.log("Successfully inserted new UUID.")</script>';
    } else {
        echo '<script>console.log("A Database Error occured.")</script>';
    }
}

//Insert UUID value (GET METHOD)
if ($_GET["uuid"] != null) {
    $uuid = $_GET["uuid"];
    $sql = "INSERT INTO proctoring (uuid, intervals) VALUES ('$uuid', '$interval')";
    //echo $sql; //For Troubleshooting purposes
    if (mysqli_query($conn, $sql)) {
        echo '<script>console.log("Successfully inserted new UUID.")</script>';
    } else {
        echo '<script>console.log("A Database Error occured.")</script>';
    }
}

//Close SQL Connection
$conn->close();

?>