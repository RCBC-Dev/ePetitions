<?php
session_start();

if(isset($_SESSION['userid']) && isset($_SESSION['key']))
{
  if(!password_verify($_SESSION['userid']."s3cr3t!",$_SESSION['key']))
  {
      die(header("Location: /epetition/")); // Key check failed
  }
}
elseif (isset($_SESSION['userid']))
{  // User logged in but not admin
    die(header("Location: /epetition/home.php"));
}
else
{   // User not logged in - attempting to circumvent security
    die(header("Location: /epetition/"));  
}

if($_SERVER["REQUEST_METHOD"] == "POST")  // Check that we have got a POST request
{
	$id=$_SESSION['petid'];
	$actiondate=date("Y-m-d H:i:s");
	$category=$_POST['category'];
	$comments=$_POST['comments'];
	$pettitle=$_SESSION['pettitle'];
	include 'dbconnect.php';
	$sql = "UPDATE ePetition SET petitionstatus = 'Declined', petitionapproved = ?, disabledreason = ? WHERE petid = ?;";
	$stmt = sqlsrv_query($conn, $sql, array($actiondate,$category,$id));
	if( $stmt === false )  
	{  
		echo "Error in statement preparation/execution.\n";  
		die( print_r( sqlsrv_errors(), true));  
	}
	
	$userid=$_SESSION['userid'];
	$sql = "INSERT INTO ePetAudit (userid, action, actiondatetime) VALUES (?, ?, ?);";
    $stmt = sqlsrv_query($conn, $sql, array($userid,"Petition Declined ($id) for $category",$actiondate));
    if( $stmt === false ){die(header("Location: /epetition/error.php"));}
	
	// Prepare to sent to email.php
    $_SESSION['email']=$_SESSION['petemail'];
    $_SESSION['title']="Redcar and Cleveland - ePetition Declined";
    $_SESSION['content']="Unfortunately your ePetition titled: $pettitle has been declined for $category.<br>Additional comments: $comments<br>If you feel that this is not the case please call 01642 774774";
    $_SESSION['success']="/epetition/admin.approvals.php";  // Url to forward to if we send successfully
	echo "Success";
    header("Location: /epetition/server.sendemail.php"); // We set up required session variables and go to the sendemail page
}
else // Not POSTED so no go...
{
	die(header("Location: /epetition/"));  //Should only be coming here with POST Request	
}
?>