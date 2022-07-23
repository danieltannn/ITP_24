<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-16">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>ITP24 Admin Panel (Intervals)</title>

</head>

<?php

//=============================================
//     SQL Connection & Credentials Set Up
//=============================================
$config = parse_ini_file('../../ITP_db_config.ini');
$conn = new mysqli($config['dbservername'], $config['dbusername'], $config['dbpassword'], $config['dbname']);
//$conn new mysqli("servername", "db_username", "db_password", "db_name"); // For Personal Testing Purposes

//=============================================
//      Retrieve Defaults for `intervals`
//=============================================
$sql = "SELECT * FROM defaults WHERE name='intervals'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        //Retrieve current intervals for the specified UUID
        $AWD = $row["AWD"]; 
        $AMD = $row["AMD"]; 
        $PL = $row["PL"]; 
        $OW = $row["OW"]; 
    }
} else {
    //Do Nothing
}

?>

<body>
    
<?php include 'nav_bar.php'; ?>

<style> #paddingDiv{ padding-top: 2%; padding-right: 2%; padding-bottom: 2%; padding-left: 2%; } </style> <div id="paddingDiv"> <!-- Padding applies to this area onwards -->
    
<form action="admin_interval_defaults_process.php" method="POST">
  <div class="mb-3">
    <input type="number" class="form-control" id="AWD" name="AWD" value='<?php echo $AWD; ?>' >
    <div id="uuidhelp" class="form-text">Interval Value (Seconds) for AWD (Active Windows Detection)</div>
  </div>
  <div class="mb-3">
    <input type="number" class="form-control" id="AMD" name="AMD" value='<?php echo $AMD; ?>' >
    <div id="uuidhelp" class="form-text">Interval Value (Seconds) for AMD (Active Monitor Detection)</div>
  </div>
  <div class="mb-3">
    <input type="number" class="form-control" id="PL" name="PL" value='<?php echo $PL; ?>' >
    <div id="uuidhelp" class="form-text">Interval Value (Seconds) for PL (Process List)</div>
  </div>
  <div class="mb-3">
    <input type="number" class="form-control" id="OW" name="OW" value='<?php echo $OW; ?>' >
    <div id="uuidhelp" class="form-text">Interval Value (Seconds) for OW (Open Windows)</div>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>