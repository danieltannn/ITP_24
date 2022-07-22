<?php include 'nav_bar.php'; ?>

<style> #paddingDiv{ padding-top: 2%; padding-right: 2%; padding-bottom: 2%; padding-left: 2%; } </style> <div id="paddingDiv"> <!-- Padding applies to this area onwards -->

<div class="row">
  <div class="col">
    <center><h2>Directory</h2></center>
    <hr>

    <?php
    
    //Clearing Cache
     clearstatcache();
    
    $directory = "Heartbeat/";
    
    // Open a directory, and read its contents
    if (is_dir($directory)){
      if ($opendirectory = opendir($directory)){
        while (($file = readdir($opendirectory)) !== false){
            if ($file == "." || $file == "..") { 
                //Do Nothing 
            }
            else {
                echo '<center><a href="Heartbeat/' . $file . '" target="myiframe">' . $file . '</a><center><br>';
            }
        }
        closedir($opendirectory);
      }
    }
    
    ?>

  </div>
  <div class="col">
    <center><h2>Logs Display</h2></center>
    <hr>
      <div class="container-fluid">
        <iframe style="width: 100%; height: 500%" name="myiframe"></iframe>
        </div>
  </div>
</div>



