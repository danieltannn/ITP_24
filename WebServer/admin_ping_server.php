<!DOCTYPE html>
<html>
    
<head>
    
<title>ITP24 Admin Panel (Heartbeat Server)</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>

</head>

<body>
    
<?php include 'nav_bar.php'; ?>

<style> #paddingDiv{ padding-top: 2%; padding-right: 2%; padding-bottom: 2%; padding-left: 2%; } </style> <div id="paddingDiv"> <!-- Padding applies to this area onwards -->

<p class="h1">Heartbeat Server</p>
<p>Keep this page open in the background when the examination is going on to accurately capture the timestamp of all of our RaspberryPi connections.</p>
<p>Remember to close this page when the examination ends to avoid inflating the log file.</p>
<p>Relevant information pertaining to our RaspberryPi connections can be found at ping.log</p>

<div class="progress">
  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
</div>
<br>
  
<script>
function ProgressBarUp() {
    // Set the width to animate the progress bar
    // Along with time duration in milliseconds
    $(".progress-bar").animate({
            width: "100%",
    }, 10000);
    ProgressBarDown();
}
function ProgressBarDown() {
    // Set the width to animate the progress bar
    // Along with time duration in milliseconds
    $(".progress-bar").animate({
            width: "10%",
    }, 10000);
    ProgressBarUp();
}
ProgressBarUp()

</script>

<!--
Auto Refresh Table
-->
<div id="auto_refresh">
<?php include 'admin_ping_server_process.php' ; ?>
</div>
<script async>
$(function() {
    setInterval(function(){
        $('#auto_refresh').load('admin_ping_server_process.php');
    },10000);
});


</script>