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

//====================================
// Processing POST Request (JSON) here
//====================================

// Takes raw data from the request
$json = file_get_contents('php://input');
//var_dump($json); //Testing & Troubleshooting Purposes

// Converts it into a PHP object
$rawdata = json_decode($json, true); //String To JSON Format
//var_dump($data); //Testing & Troubleshooting Purposes
$array = json_decode($rawdata, true); // JSON Format to Array
//var_dump($array); //Testing & Troubleshooting Purposes

//====================================
//               FERNET
//====================================

/*
* JSON Data Breakdown
*
* Object        Description        Value       Remarks
*
* Object 1      Trigger Count      Integer     Encrypted with Fernet
*
* Object 2      Increase Interval  Boolean     Encrypted with Fernet
*
* Object 3      Category           String      Encrypted with Fernet (AWD/AMD/PL/OW)
*
* Object 4      Data/Information   String      Encrypted with Fernet
*
* Object 5      Fernet Key         String      Not Encrypted/Encoded
*
* Object 6      UUID               String      Encoded in BASE64 (UTF-16)
*/

require_once("Fernet.php"); //Execution will stop if the script contains or process meets an error. Alternatively, you may use "include_once" for this portion.

use Fernet\Fernet;

$key = $array[5]; //Fernet Key
$fernet = new Fernet($key);

$trigger_count = $fernet->decode($array[1]);
$trigger = $fernet->decode($array[2]);
$category = $fernet->decode($array[3]);
	foreach ($array['4'] as $raw_data) {
		//var_dump($raw_data);
		$data_inside_list = $fernet->decode($raw_data);
		$data .= $data_inside_list . ", ";
	}
$data = substr($data, 0, -2);

$object6 = $array[6]; // UUID Encoded in BASE64 (UTF-16LE)
$UUID = mb_convert_encoding(base64_decode($object6), "UTF-16"); //Decoding
$UUID = preg_replace('/[[:^print:]]/', '', $UUID); //Removing Non Printable Characters

//====================================
//             PROCESSING
//====================================

//Logging Parameters
date_default_timezone_set('Asia/Singapore');
$date_time = date('Y-m-d H:i:s');
$date = date('Y-m-d');

//Check if the UUID exists in the interval database
$sql = "SELECT * FROM intervals WHERE uuid = '$UUID'";
$result = $conn->query($sql);
if ($result->num_rows > 0) { 
    $uuid_exist = 1;
    while($row = $result->fetch_assoc()) {
        //Retrieve current intervals for the specified UUID
        $AWD = $row["AWD"]; 
        $AMD = $row["AMD"]; 
        $PL = $row["PL"]; 
        $OW = $row["OW"]; 
        $admin_override = $row["admin_override"];
    }
    //Place the interval values of the specified category into $interval_value
    if ($category == "AWD") { $interval_data = $AWD; } 
    elseif ($category == "AMD") { $interval_data = $AMD; }
    elseif ($category == "PL") { $interval_data = $PL; }
    elseif ($category == "OW") { $interval_data = $OW; }
}
else { 
    $uuid_exist = 0; 
}

//Insert default values if UUID does not exist yet in the interval database
if ($uuid_exist == 0) {
    
    //DEFAULT VALUES
    $interval_default = 300;
    $admin_override_default = 0;
    
    $sql_interval = "INSERT INTO intervals (uuid, AWD, AMD, PL, OW, admin_override) VALUES ('$UUID', '$interval_default', '$interval_default', '$interval_default', '$interval_default', '$admin_override_default')"; 
    
    if (mysqli_query($conn, $sql_interval)) {
        echo "Default Interval Initialized. \n";
    } else {
        echo "An Error occured \n";
        $error = "\n" . $conn -> error;
        error_log(print_r($error, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/system_error.log");
    }
}
else {
    //Do nothing if UUID already exists in the interval database
}

//Process Trigger (If True/1)
if ($trigger == 1 && $admin_override == 0) {
    
    if ($interval_data > 60) {
        
        $interval_data -= 60; //Minus 1 minute if trigger activated and interval value is more than 60
        
        //Update interval data in interval database
        $sql = "UPDATE intervals SET $category='$interval_data' WHERE uuid='$UUID'";
        if (mysqli_query($conn, $sql)) {
            echo "Interval Updated\n";
        } else {
            echo "An Error occured.\n";
            $error = "\n" . $date_time . " " . $conn -> error;
            error_log(print_r($error, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/system_error.log");
        }
        
    }
    elseif ($interval_data > 5 && $interval_data <= 60 && $admin_override == 0) {
        
        $interval_data -= 5; //Minus 5 seconds if trigger activated and interval value is below 60 (Minimum Value: 5)
        
        //Update interval data in interval database
        $sql = "UPDATE intervals SET $category='$interval_data' WHERE uuid='$UUID'";
        if (mysqli_query($conn, $sql)) {
            echo "Interval Updated\n";
        } else {
            echo "An Error occured.\n";
            $error = "\n" . $date_time . " " . $conn -> error;
            error_log(print_r($error, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/system_error.log");
        }
        
    }
    else {
        echo "Interval at minimum value.\n";
    }
}
elseif ($admin_override == 1) {
    echo "Admin Override activated. Interval Value is currently defined by the Administrator.\n";
}

//Insert data into proctoring database
$sql = "INSERT INTO proctoring (uuid, trigger_count, category, data, date_time) VALUES ('$UUID', '$trigger_count', '$category', '$data', '$date_time')";
if (mysqli_query($conn, $sql)) {
    echo "Proctoring Data inserted successfully.\n";
} else {
    echo "An Error occured.\n";
    $error = "\n" . $date_time . " " . $conn -> error;
    error_log(print_r($error, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/system_error.log");
}

//=====================
// Close SQL Connection
//=====================

$conn->close();

?>