<?php 
session_start();
require('epetition.php');
userPage();

if($_SERVER["REQUEST_METHOD"] == "POST")
{
  $userid = $_SESSION['userid'];
  $title = htmlspecialchars($_POST['title'],ENT_QUOTES);
  $detail = htmlspecialchars($_POST['detail'],ENT_QUOTES);
  $duration = htmlspecialchars($_POST['duration']);
  $actiondate=date("Y-m-d H:i:s");
  $disableddate=date("Y-m-d H:i:s",strtotime($actiondate."+ $duration days"));
  
  //$arr = get_defined_vars();
  //foreach($arr as $key=>$value)
  //{
  //  echo($key.':<br /><pre>'.htmlspecialchars($value).' ('.strlen($value).')<pre><hr>');  
  //}
  
  if(strlen($title)>599)
  {
    $title=substr($title,0,599);  
  }
  
  if(strlen($detail)>3999)
  {
    $detail=substr($title,0,3999); 
  }

  // Insert new ePetition
  include 'dbconnect.php';
  $sql = "INSERT INTO ePetition (userid, title, detail, petitioncreated, petitiondisabled, petitionstatus) OUTPUT INSERTED.petid VALUES (?, ?, ?, ?, ?, 'Not Approved');";
  $stmt = sqlsrv_query($conn, $sql,array($userid,$title,$detail,$actiondate,$disableddate));
  if( $stmt === false ){die(header("Location: /epetition/error.php"));}
  $row = sqlsrv_fetch_array($stmt);
  $petid=$row['petid'];
  
  // Insert a signature for the new ePetition we need to get users details from database
  $sql = "select E.name, E.email, E.address, E.postcode, E.connection
  from ePetition as P
  left join ePetLogin as E 
  ON P.userid = E.userid
  WHERE P.petid=?
  GROUP BY E.userid, E.name, 
  E.email, E.address, E.postcode, E.connection";
  $stmt = sqlsrv_query($conn, $sql, array($petid));
  if( $stmt === false )  
  {  
    echo "Error in statement preparation/execution.\n";  
    die( print_r( sqlsrv_errors(), true));  
  }
  
  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
  {
    $fullname=$row['name'];
    $email=$row['email'];
    $address=$row['address'];
    $postcode=$row['postcode'];
    $connection=$row['connection'];
  }
  $sql = "INSERT INTO eSignatures (petid, name, email, address, postcode, connection, signeddate, verifieddate, status) VALUES 
  (?,?,?,?,?,?,?,?,'1');";

  $stmt = sqlsrv_query($conn, $sql,array($petid,$fullname,$email,$address,$postcode,$connection,$actiondate,$actiondate,$actiondate));
  if( $stmt === false ){echo "Error in statement preparation/execution.\n";die( print_r( sqlsrv_errors(), true));}
  
  //Audit
  $sql = "INSERT INTO ePetAudit (userid, action, actiondatetime) VALUES (?, ?, ?);";
  $stmt = sqlsrv_query($conn, $sql,array($userid,"Created petition ($petid)",$actiondate));
  if( $stmt === false ){die(header("Location: /epetition/error.php"));}
  
  // Prepare to sent to email.php
  $_SESSION['email']="admin@redclev.net";
  $_SESSION['title']="New ePetition :$title";
  $_SESSION['content']="Please login to approve or decline this petition:<br> $detail <br> <a href='https://shawn-dev.redclev.net/epetition/login.php'>Log in to Epetitions</a>";
  $_SESSION['success']="/epetition/successpetition.php";  // Url to forward to if we send email successfully
  header("Location: /epetition/server.sendemail.php"); // We set up required session variables and go to the sendemail page
}
else
{
  die(header("Location: /epetition/"));  //Should only be coming here with POST Request (send them to /)
}
?>
