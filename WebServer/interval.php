<?php

//For Troubleshooting Purposes
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

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

//Display Interval via UUID (GET METHOD)
if ($_GET["uuid"] != null && $_GET["category"] != null) {
    
    $uuid = $_GET["uuid"];
    $UUID = mb_convert_encoding(base64_decode($uuid), "UTF-16"); //Base64 (UTF-16) Decode
    $UUID = preg_replace('/[[:^print:]]/', '', $UUID); //Removing Non Printable Characters
    
    $category = $_GET["category"];
    $CATEGORY = mb_convert_encoding(base64_decode($category), "UTF-16"); //Base64 (UTF-16) Decode
    $CATEGORY = preg_replace('/[[:^print:]]/', '', $CATEGORY); //Removing Non Printable Characters
    
    $sql = "SELECT * FROM intervals WHERE uuid = '$UUID'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
        $AWD = $row["AWD"];
        $AMD = $row["AMD"];
        $PL = $row["PL"];
        $OW = $row["OW"];
        }
        
        //Place the specified interval into $interval_data
        if ($CATEGORY == "AWD") { $interval_data = $AWD; } 
        if ($CATEGORY == "AMD") { $interval_data = $AMD; }
        if ($CATEGORY == "PL") { $interval_data = $PL; }
        if ($CATEGORY == "OW") { $interval_data = $OW; }
        echo $interval_data;
    }
    else {
        echo 300; //Default value  (IF SQL FINDS NO DATA)
    }
}
else {
    //Do Nothing (IF NO GET PARAMETERS)
}

//Close SQL Connection
$conn->close();

?>