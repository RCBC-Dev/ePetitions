<?php
include 'dbconnect.php';
$expireddate=date("Y-m-d", strtotime("-31 day"));

$sql = 
   "UPDATE eSignatures 
	SET 
	name = 'Anonymised', 
	email = 'Anonymised', 
	address = 'Anonymised', 
	postcode = 'Anonymised'
	FROM eSignatures AS S
	LEFT JOIN ePetition AS P ON S.petid = P.petid
	WHERE P.petitiondisabled < '$expireddate' AND p.petitionstatus != 'Archived';

	UPDATE ePetition
	SET
	petitionstatus = 'Archived'
	FROM ePetition
	WHERE petitiondisabled < '$expireddate' AND petitionstatus != 'Archived';";
	
$stmt = sqlsrv_query($conn, $sql);          

if( $stmt === false )  
{  
	echo "Error in statement preparation/execution.\n";
	die( print_r( sqlsrv_errors(), true));
}
else
{
	echo "Data Anonymised - older than ".$expireddate;
}
sqlsrv_close($conn); 
?>
