<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-16">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>ITP24 Admin Panel (Intervals)</title>

</head>

<body>
    
<?php include 'nav_bar.php'; ?>

<style> #paddingDiv{ padding-top: 2%; padding-right: 2%; padding-bottom: 2%; padding-left: 2%; } </style> <div id="paddingDiv"> <!-- Padding applies to this area onwards -->
    
<?php

//=============================================
//                  POST Data
//=============================================
$AWD = $_POST['AWD'];
$AMD = $_POST['AMD'];
$PL = $_POST['PL'];
$OW = $_POST['OW'];

//=============================================
//             Logging Parameters
//=============================================
date_default_timezone_set('Asia/Singapore');
$date_time = date('d-m-Y H:i:s');
$date = date('d-m-Y');

//=============================================
//     SQL Connection & Credentials Set Up
//=============================================
$config = parse_ini_file('../../ITP_db_config.ini');
$conn = new mysqli($config['dbservername'], $config['dbusername'], $config['dbpassword'], $config['dbname']);
//$conn new mysqli("servername", "db_username", "db_password", "db_name"); // For Personal Testing Purposes

//=============================================
//        Update 'defaults' Database
//=============================================
$sql = "UPDATE `defaults` SET AWD='$AWD', AMD='$AMD', PL='$PL', OW='$OW' WHERE name='intervals'";
if (mysqli_query($conn, $sql)) {
    echo '<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">Success!</h4>
    <p>Default values for intervals has been updated.</p>
    <hr>
    <p class="mb-0">To head back to the admin panel to view intervals defaults, please click <a href="/admin_interval_defaults.php" class="alert-link">here</a>.</p>
    </div>';
} else {
    echo '<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error!</h4>
    <p>Invalid UUID.</p>
    <hr>
    <p class="mb-0">Please check the error logs for more information.</p>
    <p class="mb-0">To head back to the admin panel to view intervals defaults, please click <a href="/admin_interval_defaults.php" class="alert-link">here</a>.</p>
    </div>';
    $error = "\n" . $date_time . " " . $conn -> error;
    error_log(print_r($error, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/system_error.log");
}

?>