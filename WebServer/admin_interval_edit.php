<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-16">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
<title>ITP24 Admin Panel (Intervals)</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

</head>

<body>
    
<?php include 'nav_bar.php'; ?>

<style> #paddingDiv{ padding-top: 2%; padding-right: 2%; padding-bottom: 2%; padding-left: 2%; } </style> <div id="paddingDiv"> <!-- Padding applies to this area onwards -->
    
<form action="admin_interval_edit_process.php" method="POST">
  <div class="mb-3">
    <input type="text" class="form-control" id="uuid" name="uuid" value='<?php echo $_POST['uuid']; ?>'>
    <div id="uuidhelp" class="form-text">UUID</div>
  </div>
  <div class="mb-3">
    <input type="number" class="form-control" id="AWD" name="AWD" value='<?php echo $_POST['AWD']; ?>' >
    <div id="uuidhelp" class="form-text">Interval Value (Seconds) for AWD (Active Windows Detection)</div>
  </div>
  <div class="mb-3">
    <input type="number" class="form-control" id="AMD" name="AMD" value='<?php echo $_POST['AMD']; ?>' >
    <div id="uuidhelp" class="form-text">Interval Value (Seconds) for AMD (Active Monitor Detection)</div>
  </div>
  <div class="mb-3">
    <input type="number" class="form-control" id="PL" name="PL" value='<?php echo $_POST['PL']; ?>' >
    <div id="uuidhelp" class="form-text">Interval Value (Seconds) for PL (Process List)</div>
  </div>
  <div class="mb-3">
    <input type="number" class="form-control" id="OW" name="OW" value='<?php echo $_POST['OW']; ?>' >
    <div id="uuidhelp" class="form-text">Interval Value (Seconds) for OW (Open Windows)</div>
  </div>
  <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="admin_override" name="admin_override" value=1 checked>
    <label class="form-check-label" for="admin_override">Admin Override</label>
    <div id="uuidhelp" class="form-text">Checking this means that the proctoring script will note be able to modify the interval value any further.</div>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>