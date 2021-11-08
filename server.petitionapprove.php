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
	include 'dbconnect.php';
	$sql = "UPDATE ePetition SET petitionstatus = 'Approved', petitionapproved = ? WHERE petid = ?;";
	$stmt = sqlsrv_query($conn, $sql, array($actiondate, $id));
	if( $stmt === false ){die(header("Location: /epetition/error.php"));}
	
	$userid=$_SESSION['userid'];
	$sql = "INSERT INTO ePetAudit (userid, action, actiondatetime) VALUES (?, 'Petition Approved ($id)', ?);";
    $stmt = sqlsrv_query($conn, $sql, array($userid,$actiondate));
    if( $stmt === false ){die(header("Location: /epetition/error.php"));}
	
	// Prepare to sent to email.php
	$pettitle=$_SESSION['pettitle'];
    $_SESSION['email']=$_SESSION['petemail'];
    $_SESSION['title']="Redcar and Cleveland - ePetition Approved";
    $_SESSION['content']="Good news!  Your ePetition titled: $pettitle has been approved.<br>You can start to share it using the following link <a href='https://shawn-dev.redclev.net/epetition/details.php?id=$id'>https://shawn-dev.redclev.net/epetition/details.php?id=$id</a>";
    $_SESSION['success']="/epetition/admin.approvals.php";  // Url to forward to if we send successfully
    header("Location: /epetition/server.sendemail.php"); // We set up required session variables and go to the sendemail page
}
else // Not POSTED so no go...
{
	die(header("Location: /epetition/"));  //Should only be coming here with POST Request	
}


?>