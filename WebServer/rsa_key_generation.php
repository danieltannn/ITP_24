<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-16">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>ITP24 Admin Panel (RSA Key Generation)</title>

</head>

<?php include 'nav_bar.php'; ?>

<style> #paddingDiv{ padding-top: 2%; padding-right: 2%; padding-bottom: 2%; padding-left: 2%; } </style> <div id="paddingDiv"> <!-- Padding applies to this area onwards -->

<div class="row">
    <div class="col">
        <div class="mb-3">
            <label for="public_key" class="form-label">Public Key:</label>
            <textarea class="form-control" id="public_key" rows="20"><?php echo file_get_contents("RSA/public_rsa.key"); ?></textarea>
        </div>
    </div>
    <div class="col">
        <div class="mb-3">
            <label for="private_key" class="form-label">Private Key:</label>
            <textarea class="form-control" id="private_key" rows="20"><?php echo file_get_contents("RSA/private_rsa.key"); ?></textarea>
        </div>
    </div>
</div>

<?php
/////////////////////////////////////////////////
// Display Last Line of RSA Key Generation Logs
/////////////////////////////////////////////////

$file = "rsa_key_generation.log";
$file = escapeshellarg($file); // For Security Purposes
$line = `tail -n 1 $file`; //Last Line
echo $line;
?>
    
<br>
<br>
<a href="rsa_key_generation_process.php" class="btn btn-warning btn-lg btn-block">Generate New Keys</a>