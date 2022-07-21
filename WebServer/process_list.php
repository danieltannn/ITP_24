<?php

//====================================
//     Troubleshooting Parameters
//====================================
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//=============================================
//     SQL Connection & Credentials Set Up
//=============================================
$config = parse_ini_file('../../ITP_db_config.ini');
$conn = new mysqli($config['dbservername'], $config['dbusername'], $config['dbpassword'], $config['dbname']);
//$conn new mysqli("servername", "db_username", "db_password", "db_name");

//====================================
//     Decode POST Request (JSON)
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
//          JSON Data Format
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
* Object 5      Fernet Key         String      Encrypted with RSA & Encoded in BASE64
*
* Object 6      UUID               String      Encrypted with Fernet
*/

//========================================
// RSA Encryption (Decrypting Fernet Key)
//========================================

//Decoding Fernet Key (BASE64) & Decrypting Fernet Key with our generated Private Key (RSA)
$RSA_privatekey = file_get_contents("RSA/private_rsa.key");
$encryptedfernetkey = base64_decode($array[5]);
openssl_private_decrypt($encryptedfernetkey, $fernetkey, $RSA_privatekey);

//========================================
//     Fernet (Symmetric Encryption)
//  Decrypting the rest of the JSON Data
//========================================

require_once("Fernet/Fernet.php"); 
use Fernet\Fernet;

$fernet = new Fernet($fernetkey); //Fernet Key

$data = ""; //Define Data Variable
$trigger_count = $fernet->decode($array[1]);
$trigger = $fernet->decode($array[2]);
$category = $fernet->decode($array[3]);
foreach ($array['4'] as $raw_data) {
	//var_dump($raw_data);
	$data_inside_list = $fernet->decode($raw_data);
	$data .= $data_inside_list . ", ";
}
$data = substr($data, 0, -2); //Removing the last ", " from $data
$UUID = $fernet->decode($array[6]);

//=============================================
//             Logging Parameters
//=============================================
date_default_timezone_set('Asia/Singapore');
$date_time = date('d-m-Y H:i:s');
$date = date('d-m-Y');

//===================================================
// Validating specified UUID in `intervals` database
//===================================================

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
    //Place the interval values of the specified category into $interval_data
    if ($category == "AWD") { $interval_data = $AWD; } 
    elseif ($category == "AMD") { $interval_data = $AMD; }
    elseif ($category == "PL") { $interval_data = $PL; }
    elseif ($category == "OW") { $interval_data = $OW; }
}
else { 
    $uuid_exist = 0; 
}

//========================================================================
//  Renaming $category (To be stored inside `proctoring` database later)
//========================================================================

if ($category == "AWD") { $category = "Active Windows Detection (AWD)"; } 
elseif ($category == "AMD") { $category = "Active Monitor Detection (AMD)"; }
elseif ($category == "PL") { $category = "Process List (PL)"; }
elseif ($category == "OW") { $category = "Open Windows (OW)"; }

//========================================================================
//  Renaming $category (To be stored inside `proctoring` database later)
//========================================================================

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

//========================================================================
// Process Trigger (If $trigger is true/1 and $admin_override is false/0)
//========================================================================

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

//========================================================================
//     Inserting Data into `proctoring` database for viewing/analysis
//========================================================================

$sql = "INSERT INTO proctoring (uuid, trigger_count, category, data, date_time) VALUES ('$UUID', '$trigger_count', '$category', '$data', '$date_time')";
if (mysqli_query($conn, $sql)) {
    echo "Proctoring Data inserted successfully.\n";
} else {
    echo "An Error occured.\n";
    $error = "\n" . $date_time . " " . $conn -> error;
    error_log(print_r($error, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/system_error.log");
}

//=============================================
//             Close SQL Connection
//=============================================

$conn->close();

?>