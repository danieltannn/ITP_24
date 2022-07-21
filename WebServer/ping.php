<?php

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
//     Display Interval Value via Specified UUID & Category (GET METHOD)
////////////////////////////////////////////////////////////////////////////
if ($_GET["uuid"] != null) {
    
    $uuid = $_GET["uuid"];
    $UUID = mb_convert_encoding(base64_decode($uuid), "UTF-16LE"); //Base64 (UTF-16LE) Decode
    $UUID = preg_replace('/[[:^print:]]/', '', $UUID); //Removing Non Printable Characters
    
    ////////////////////////////////////////////////////////////////////////////
    //   Verify if a record for the specified UUID exist in `ping` Database
    ////////////////////////////////////////////////////////////////////////////
    $sql = "SELECT * FROM ping WHERE uuid = '$UUID'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        $uuid_exist = 1;
        while($row = $result->fetch_assoc()) {
            $last_connect = $row["last_connect"];
            $last_connect_formatted = strtotime($last_connect);
            //echo $last_connect_time . "\n";
            //echo date('d-m-Y H:i:s', $last_connect_formatted);
            $last_connect_formatted = date('d-m-Y H:i:s',$last_connect_formatted);
            $now_time = strtotime($date_time);
            $last_connect_time = strtotime($last_connect_formatted);
            $time_difference = $now_time - $last_connect_time;
        } 
    }
    else {
        $uuid_exist = 0;
    }
    
    ////////////////////////////////////////////////////////////////////////////
    //   Verify if a record for the specified UUID exist in `ping` Database
    ////////////////////////////////////////////////////////////////////////////
    if ($uuid_exist == 0) {
        $sql_newping = "INSERT INTO ping (uuid, last_connect) VALUES ('$UUID', '$date_time')"; 
    
        if (mysqli_query($conn, $sql_newping)) {
            echo "New Connection:" . $UUID . "\n";
        } else {
            echo "An Error occured \n";
            $error = "\n" . $conn -> error;
            error_log(print_r($error, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/system_error.log");
        }
    
    } else {
        $sql_updateping = "UPDATE ping SET last_connect='$date_time' WHERE uuid='$UUID'";
        if (mysqli_query($conn, $sql_updateping)) {
            echo "Ping Received From $UUID";
        } else {
            echo "An Error occured \n";
            $error = "\n" . $conn -> error;
            error_log(print_r($error, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/system_error.log");
        }
    }
 
}

///////////////////////////////////////////////
//             Close SQL Connection
///////////////////////////////////////////////

$conn->close();

?>

