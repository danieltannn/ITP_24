<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

///////////////////////////////////////////////
//     SQL Connection & Credentials Set Up
///////////////////////////////////////////////
$config = parse_ini_file('../../ITP_db_config.ini');
$conn = new mysqli($config['dbservername'], $config['dbusername'], $config['dbpassword'], $config['dbname']);
//$conn new mysqli("servername", "db_username", "db_password", "db_name"); // For Personal Testing Purposes

//=============================================
//             Logging Parameters
//=============================================
date_default_timezone_set('Asia/Singapore');
$date_time = date('d-m-Y H:i:s');
$date = date('d-m-Y');

////////////////////////////////////////////////////////////////////////////
//    Select and process all Data from `ping` Database before logging
////////////////////////////////////////////////////////////////////////////

echo $date_time . "<br>";

$sql = "SELECT * FROM ping";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $uuid = $row["uuid"];
        $last_connect = $row["last_connect"];
        $last_connect_formatted = strtotime($last_connect);
        $last_connect_formatted = date('d-m-Y H:i:s',$last_connect_formatted);
        $now_time = strtotime($date_time);
        $last_connect_time = strtotime($last_connect_formatted);
        $time_difference = $now_time - $last_connect_time;
        
        $logfilelocation = "/Heartbeat/" . $uuid . ".log";
        $logfilelocation2 = $_SERVER['DOCUMENT_ROOT'] . "/Heartbeat/" . $uuid . ".log";
        
        
        if ($time_difference >= 10 && $time_difference < 30) {
            
            $file = escapeshellarg($logfilelocation2); // For Security Purposes
            $lastline = `tail -n 1 $file`; //Second Last Line
            $compare = mb_substr($lastline, 20); //Trim Timestamp
            $forcompare = $uuid . " is experiencing connectivity issues.\n";
            if ($compare == $forcompare) {
                echo $forcompare . "<br>";
                //No need to log the same message again.
            } else {
                $heartbeat_logs = $date_time . " " . $uuid . " is experiencing connectivity issues.\n";
                echo $forcompare . "<br>";
                error_log(print_r($heartbeat_logs, true), 3, $_SERVER['DOCUMENT_ROOT'] . $logfilelocation);
            }
            
        }
        elseif ($time_difference < 10) {
            $file = escapeshellarg($logfilelocation2); // For Security Purposes
            $lastline = `tail -n 1 $file`; //Second Last Line
            $compare = mb_substr($lastline, 20); //Trim Timestamp
            $forcompare = $uuid . " has initiated connection.\n";
            if ($compare == $forcompare) {
                echo $forcompare . "<br>";
                //No need to log the same message again.
            } else {
                $heartbeat_logs = $date_time . " " . $uuid . " has initiated connection.\n";
                echo $forcompare . "<br>";
                error_log(print_r($heartbeat_logs, true), 3, $_SERVER['DOCUMENT_ROOT'] . $logfilelocation);
            }
        
        }
        else {
            $file = escapeshellarg($logfilelocation2); // For Security Purposes
            $lastline = `tail -n 1 $file`; //Second Last Line
            $compare = mb_substr($lastline, 20); //Trim Timestamp
            $forcompare = $uuid . " has been disconnected.\n";
            
            if ($compare == $forcompare) {
                echo $forcompare . "<br>";
                //No need to log the same message again.
            } else {
                $heartbeat_logs = $date_time . " " . $uuid . " has been disconnected.\n";
                echo $forcompare . "<br>";
                error_log(print_r($heartbeat_logs, true), 3, $_SERVER['DOCUMENT_ROOT'] . $logfilelocation);
            }
        }
    } 
}

else {
    //Do Nothing
}

///////////////////////////////////////////////
//             Close SQL Connection
///////////////////////////////////////////////

$conn->close();

?>

