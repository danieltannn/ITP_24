<table id="datatable" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>UUID</th>
            <th>Status</th>
            <th>Last Connection</th>
        </tr>
    </thead>
    <tbody>
        
    <?php
    //=============================================
    //     SQL Connection & Credentials Set Up
    //=============================================
    $config = parse_ini_file('../../ITP_db_config.ini');
    $conn = new mysqli($config['dbservername'], $config['dbusername'], $config['dbpassword'], $config['dbname']);
    //$conn new mysqli("servername", "db_username", "db_password", "db_name"); // For Personal Testing Purposes
    
    //=============================================
    //             Logging Parameters
    //=============================================
    date_default_timezone_set('Asia/Singapore');
    $date_time = date('d-m-Y H:i:s');
    $date = date('d-m-Y');
        
    ////////////////////////////////////////////////////////////////////////////
    //                           Process Connections
    ////////////////////////////////////////////////////////////////////////////
    $sql = "SELECT * FROM ping";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $uuid = $row["uuid"];
            $last_connect = $row["last_connect"];
            $last_connect_formatted = strtotime($last_connect);
            $last_connect_formatted = date('d-m-Y H:i:s',$last_connect_formatted);
            $now_time = strtotime($date_time);
            $last_connect_time = strtotime($last_connect_formatted);
            $time_difference = $now_time - $last_connect_time;
            
            echo '<tr>'; //Declare Header of Table Row
            
            echo '<td>' . $uuid . '</td>';
            
            if ($time_difference < 10) {
                echo '<td><span class="badge bg-success">Connected</span></td>';
            } 
            elseif ($time_difference >= 10 && $time_difference < 30) {
                echo '<td><span class="badge bg-warning text-dark">Unstable Network</span></td>';
            }
            elseif ($time_difference >= 30 ) {
                echo '<td><span class="badge bg-danger">Disconnected</span></td>';
            }
            
            echo '<td>' . $last_connect . '</td>';
            echo '</tr>'; 
        } 
    }
    
    ///////////////////////////////////////////////
    //             Close SQL Connection
    ///////////////////////////////////////////////
    
    $conn->close();
    
    ?>
    
    </tbody>
</table>

<script>
$(document).ready(function() {
var table = $('#datatable').DataTable( {
    lengthChange: false,
    dom: 'Blfrtip',
    buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print', 'colvis' ],
    "pageLength":1000,
    } );
    
table.buttons().container().appendTo( '#datatable_wrapper .col-md-6:eq(0)' );
} );
</script>