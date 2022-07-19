<?php

//Hide PHP Errors
error_reporting(0);
ini_set('display_errors', 0);

?>
        
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

<?php include 'nav_bar.php'; ?>

<style> #paddingDiv{ padding-top: 2%; padding-right: 2%; padding-bottom: 2%; padding-left: 2%; } </style> <div id="paddingDiv"> <!-- Padding applies to this area onwards -->

<?php

	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
	
	}
	
	// Check if file already exists
	if (file_exists($target_file)) {
	$errorMsg .= "Another file with the same name exists! Overwriting it...<br>";
	$uploadOk = 1;
	}
	
	// Allow certain file formats
	if($imageFileType != "ps1") {
	$errorMsg .= "Sorry, only selected types of file are allowed. <br>";
	$uploadOk = 0;
	}
	
	if(htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])) != "proctoring_script.ps1") {
		$errorMsg .= "Sorry, this file is not valid<br>";
		$uploadOk = 0;
	}
	
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo '<div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Error!</h4>
        <p>Upload Failed</p>
        <hr>
        <p class="mb-0">' . $errorMsg . 'Sorry, your file was not uploaded. <br></p>
        <p class="mb-0">To head back to the upload page, please click <a href="/upload.php" class="alert-link">here</a>.</p>
        </div>';
	// if everything is ok, try to upload file
	} else {
	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		//header( "refresh:3;url=reload.php" );
		echo '<div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Success!</h4>
        <p>Upload Successful</p>
        <hr>
        <p class="mb-0">The file ' . htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])) . ' has been uploaded.<br></p>
        <p class="mb-0">To head back to the upload page, please click <a href="/upload.php" class="alert-link">here</a>.</p>
        </div>';

	} else {
		echo '<div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Error!</h4>
        <p>Upload Failed</p>
        <hr>
        <p class="mb-0">Sorry, there was an error uploading your file.</p>
        <p class="mb-0">To head back to the upload page, please click <a href="/upload.php" class="alert-link">here</a>.</p>
        </div>';
        $error = "\n" . $date_time . " Error uploading " . htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
        error_log(print_r($error, true), 3, $_SERVER['DOCUMENT_ROOT'] . "/system_error.log");
    
	}
	}

?>

</div>