<!-- Proof of Concept for Dynamic Interval Value Change -->
<!--
Proctoring Script to continually poll https://24.jubilian.one/delay.txt for the variable.
This page will allow the admin to change the value at any given point in time, which would then affect the protoring script's frequency of retrieving data from the student's PC.
-->

<!-- This portion allows the change of the value via the GET parameter. E.g. "https://24.jubilian.one?delay=1" -->

<?php

if ($_GET["delay"] != null) {
	
$delay = $_GET["delay"];

$myfile = fopen("delay.txt", "w") or die("Unable to open file!");
fwrite($myfile, $delay);
fclose($myfile);
}

?>

<!-- Opens delay.txt and retrieves the value inside for display and edit purposes later -->

<?php
$myfile = fopen("delay.txt", "r") or die("Unable to open file!");

while(!feof($myfile)) {
	$value .= fgets($myfile);
}
fclose($myfile);
?>

<!-- Main Body of the page -->
  
<html>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<div class="container">
  <div class="row">
    <div class="col text-center">

        <form action="" method="post">
            <div class="form-group justify-content-center">
                <label for="delay">Simple Dashboard to Change Delay</label>
                <input type="number" class="form-control" id="delay" name="delay" value="<?php echo $value; ?>">
            </div>
            <button type="submit" class="btn btn-primary" style="justify-content-center">Change Data</button>
        </form>

    </div>
  </div>
</div>

</html>

<!-- When this form receives the 'delay' variable as a POST form request, it will change the value inside delay.txt and refreshes the page immediately. -->

<?php

if ($_POST['delay'] != NULL) {
$delay = $_POST['delay'];
$myfile = fopen("delay.txt", "w") or die("Unable to open file!");
fwrite($myfile, $delay);
fclose($myfile);
header("Refresh:0");
}
    
?>