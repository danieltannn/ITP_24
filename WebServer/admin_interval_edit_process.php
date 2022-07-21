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
$uuid = $_POST['uuid'];
$AWD = $_POST['AWD'];
$AMD = $_POST['AMD'];
$PL = $_POST['PL'];
$OW = $_POST['OW'];
$admin_override = $_POST['admin_override'];
if ($_POST['admin_override'] == NULL) {
    $admin_override = 0;
}

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
//                Validate UUID
//=============================================
$sql_search = "SELECT * FROM intervals WHERE uuid ='$uuid'";
$result = $conn->query($sql_search);
if ($result->num_rows > 0) {
    $valid_uuid = 1;
} else {
    $valid_uuid = 0;
}

//=============================================
//        Update 'intervals' Database
//=============================================
$sql = "UPDATE intervals SET AWD='$AWD', AMD='$AMD', PL='$PL', OW='$OW', admin_override='$admin_override' WHERE uuid='$uuid'";
if (mysqli_query($conn, $sql) && $valid_uuid == 1) {
    echo '<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">Success!</h4>
    <p>The interval values for UUID: ' . $uuid . ' has been updated.</p>
    <hr>
    <p class="mb-0">Remember that when you have the admin override feature checked, the proctoring script would not be able to update the intervals anymore.</p>
    <p class="mb-0">To head back to the admin panel to edit intervals, please click <a href="/admin_interval.php" class="alert-link">here</a>.</p>
    </div>';
} elseif($valid_uuid == 0) {
    echo '<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error!</h4>
    <p>Invalid UUID.</p>
    <hr>
    <p class="mb-0">Please check the error logs for more information.</p>
    <p class="mb-0">To head back to the admin panel to edit intervals, please click <a href="/admin_interval.php" class="alert-link">here</a>.</p>
    </div>';
    $error = "\n" . $date_time . " " . $conn -> error;
    error_log(print_r($error, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/system_error.log");
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