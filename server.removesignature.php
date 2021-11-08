<?php
session_start();
require('epetition.php');
adminPage();

if($_SERVER["REQUEST_METHOD"] == "POST")  // Check that we have got a POST request
{
	$id=$_SESSION['sigid'];
	$actiondate=date("Y-m-d H:i:s");
	$reason=$_POST['reason'];
	include 'dbconnect.php';
	$sql = "UPDATE eSignatures SET status = '-1', reason = ? WHERE sigid = ?;";
	$stmt = sqlsrv_query($conn, $sql, array($reason,$id));
	if( $stmt === false )  
	{  
		echo "Error in statement preparation/execution.\n";  
		die( print_r( sqlsrv_errors(), true));  
	}
	
	$userid=$_SESSION['userid'];
	$sql = "INSERT INTO ePetAudit (userid, action, actiondatetime) VALUES (?, ?, ?);";
    $stmt = sqlsrv_query($conn, $sql, array($userid,"Signature Removed ($id) for $reason",$actiondate));
    if( $stmt === false ){die(header("Location: /epetition/error.php"));}
	// No need to send an email, we just let admin user know we removed this signature.
	//return "Test";

}
else // Not POSTED so no go...
{
	die(header("Location: /epetition/"));  //Should only be coming here with POST Request	
}


?>