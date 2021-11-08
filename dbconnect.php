<?php
$serverName = "SQL-SERVER\INSTANCE,1702";  // Obvious
$connectionInfo = array( "Database"=>"DatabaseName", "UID"=>"DBUsername", "PWD"=>"DBPassword");  //Using an array for the connection with details as shown
$conn = sqlsrv_connect( $serverName, $connectionInfo);  // This is the actual attempt to connect to SQL server
if( $conn === false) {
     die( print_r( sqlsrv_errors(), true)); // We could add a custom error for database connection
}
?>