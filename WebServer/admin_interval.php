<!DOCTYPE html>
<html>
    
<head>
    
<title>ITP24 Admin Panel (Intervals)</title>

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
    
<?php
//SQL Connection & Credentials Set Up
$config = parse_ini_file('../../ITP_db_config.ini');
$conn = new mysqli($config['dbservername'], $config['dbusername'], $config['dbpassword'], $config['dbname']);
//$conn new mysqli("servername", "db_username", "db_password", "db_name"); // For Personal Testing Purposes
?>

<style> #paddingDiv{ padding-top: 2%; padding-right: 2%; padding-bottom: 2%; padding-left: 2%; } </style> <div id="paddingDiv"> <!-- Padding applies to this area onwards -->

<table id="datatable" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>UUID</th>
                <th>AWD</th>
                <th>AMD</th>
                <th>PL</th>
                <th>OW</th>
                <th>Admin Override</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            
<?php
if ($conn->connect_error) {
    $errorMsg = "Connection failed: " . $conn->connect_error;
} else {
    $sql = $conn->prepare("SELECT * FROM intervals");
    $sql->execute() or die();
    $sql->bind_result($UUID, $AWD, $AMD, $PL, $OW, $admin_override) or die();
    while ($sql->fetch()) {
    echo '<tr>'; //Declare Header of Table Row
    
    echo '<td>' . $UUID . '</td>';
    echo '<td>' . $AWD . '</td>';
    echo '<td>' . $AMD . '</td>';
    echo '<td>' . $PL . '</td>';
    echo '<td>' . $OW . '</td>';
    echo '<td>' . $admin_override . '</td>';
    
    echo '<td><form action="admin_interval_edit.php" method="POST">';
    echo '<input type="hidden" name="uuid" id="uuid" value="' . $UUID . '">';
    echo '<input type="hidden" name="AWD" id="AWD" value="' . $AWD . '">';
    echo '<input type="hidden" name="AMD" id="AMD" value="' . $AMD . '">';
    echo '<input type="hidden" name="PL" id="PL" value="' . $PL . '">';
    echo '<input type="hidden" name="OW" id="OW" value="' . $OW . '">';
    echo '<input type="hidden" name="admin_override" id="admin_override" value="' . $admin_override . '">';
    echo '<button class="btn btn-primary" type="submit">Edit</button>';
    echo '</form></td>';
    
    echo '<td><form action="admin_interval_delete.php" method="POST">';
    echo '<input type="hidden" name="uuid" id="uuid" value="' . $UUID . '">';
    echo '<button class="btn btn-danger" type="submit"> Delete </button>';
    echo '</form></td>';
    echo '</tr>';
    }
}
?>
            
        </tbody>
        <!--<tfoot>
            <tr>
                <th>UUID</th>
                <th>AWD</th>
                <th>AMD</th>
                <th>PL</th>
                <th>OW</th>
                <th>Admin Override</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </tfoot>-->
    </table>

<script>
    $(document).ready(function() {
    var table = $('#datatable').DataTable( {
        lengthChange: false,
        dom: 'Blfrtip',
        buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print', 'colvis' ],
        "pageLength":1000,
        //fixedHeader: true
        //"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
    } );
 
    table.buttons().container()
        .appendTo( '#datatable_wrapper .col-md-6:eq(0)' );
} );
</script>