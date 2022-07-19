<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-16">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
<title>ITP24 Admin Panel (Intervals)</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

</head>

<body>
    
<?php include 'nav_bar.php'; ?>

<style> #paddingDiv{ padding-top: 2%; padding-right: 2%; padding-bottom: 2%; padding-left: 2%; } </style> <div id="paddingDiv"> <!-- Padding applies to this area onwards -->
    
<?php

//Parameters
$uuid = $_POST['uuid'];

//Logging Parameters
date_default_timezone_set('Asia/Singapore');
$date_time = date('Y-m-d H:i:s');
$date = date('Y-m-d');

//SQL Connection & Credentials Set Up
$config = parse_ini_file('../../ITP_db_config.ini');
$conn = new mysqli($config['dbservername'], $config['dbusername'], $config['dbpassword'], $config['dbname']);
//$conn new mysqli("servername", "db_username", "db_password", "db_name"); // For Personal Testing Purposes

//Validate UUID
$sql = "DELETE FROM intervals WHERE uuid ='$uuid'";
    if ($conn->query($sql) == true) {
    echo '<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">Delete Success!</h4>
    <p>The selected data has been successfully deleted.</p>
    <hr>
    <p class="mb-0">Remember that deleted data cannot be recovered!</p>
    <p class="mb-0">To head back to the admin panel to edit intervals, please click <a href="/admin_interval.php" class="alert-link">here</a>.</p>
    </div>';
    } else {
    echo '<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error!</h4>
    <p>An unexpected error occured.</p>
    <hr>
    <p class="mb-0">Please check the error logs for more information.</p>
    <p class="mb-0">To head back to the admin panel to edit intervals, please click <a href="/admin_interval.php" class="alert-link">here</a>.</p>
    </div>';
    $error = "\n" . $date_time . " " . $conn -> error;
    error_log(print_r($error, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/system_error.log");
    }

?>