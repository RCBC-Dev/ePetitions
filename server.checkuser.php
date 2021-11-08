<?php 
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST") //Should only be coming here with POST Request
{
  $email = htmlspecialchars($_POST['email']);
  $password = ($_POST['password']);  //Unhashed password
  $actiondate=date("Y-m-d H:i:s");
  include 'dbconnect.php';
  $sql = "select userid, password, userlevel, accountstatus, logonattempts, safelock from ePetLogin where email=?;"; 
  //We grab the hashed password from DB
  $stmt = sqlsrv_query($conn, $sql, array($email));
  if( $stmt === false )  
  {  
	echo "Error first select.\n";  
    die( print_r( sqlsrv_errors(), true));  
  }  
  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
  {
      $userid=$row['userid'];
      $passdb=$row['password'];
      $userlevel=$row['userlevel'];
      $accountstatus=$row['accountstatus'];
      $logonattempts=$row['logonattempts'];
      $safelock=$row['safelock'];
  }
  if($safelock=='1'){
    die(header("Location: /epetition/login.php?email=$email&error=Account Locked (too many failed attempts) Please reset your password"));  //Account safe locked (too many failed attempts)
  }
  if($accountstatus=='Disabled'){
    die(header("Location: /epetition/login.php?email=$email&error=Account Disabled"));  //Account disabled (by admin)
  }
  if($accountstatus=='Not Activated'){
    die(header("Location: /epetition/login.php?email=$email&error=Account Not active (Please click on the link you have been emailed)"));  //Account disabled (by admin)  
  }
  $passcheck = password_verify($password, $passdb);
  if($passcheck) //If password is correct update the lastloggedin column in ePetLogin and reset the logonattempts to 0
  {
    $sql = "UPDATE ePetLogin SET lastloggedin = ?, logonattempts = 0 WHERE userid = ?;";
    $stmt = sqlsrv_query($conn, $sql, array($actiondate,$userid));
    if( $stmt === false )  
    {  
      echo "Error Update ePetLogin preparation/execution.\n";  
      die( print_r( sqlsrv_errors(), true));  
    }
    $sql = "INSERT INTO ePetAudit (userid, action, actiondatetime) VALUES (?, 'Logged in successfully', ?);";
    $stmt = sqlsrv_query($conn, $sql, array($userid,$actiondate));
    if( $stmt === false )  
    {  
      echo "Error in statement preparation/execution.\n";  
      die( print_r( sqlsrv_errors(), true));  
    }
  }
  if($passcheck && $userlevel==2)
  {
    // Logged in as admin - set up session and key
    $_SESSION['userid']=$userid;
    $_SESSION['key'] = password_hash($userid."s3cr3t!", PASSWORD_DEFAULT);
    // Here we are setting up a key in a GLOBAL SUPER VARIABLE which is unique to each user - even if this was copied - it would only work for user with same ID
    // It's just another level of security we can add without much fuss
    // This key is just a hashed $id - but could also contain the day and month or something to make it even more secure
    header("Location: /epetition/admin.approvals.php");
  }
  elseif ($passcheck && $userlevel==1)
  {
    $_SESSION['userid']= $userid;
	header("Location: /epetition/home.php");
  }
  else
  { // Password incorrect - check if this is a valid user, update the logon attempts
    $sql = "select userid, logonattempts from ePetLogin where email=?;"; //We grab the id for the email address
    $stmt = sqlsrv_query($conn, $sql, array($email));
    if( $stmt === false )  
    {  
      echo "Error in statement preparation/execution.\n";  
      die( print_r( sqlsrv_errors(), true));  
    }
    $action = "Login Failed - Incorrect username or password";
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
    {
      $userid=$row['userid'];
      $logonattempts=$row['logonattempts'];
      echo $logonattempts;
      if($logonattempts==3)
      {
        $action="Account locked (multiple failed login)";
        $sql = "UPDATE ePetLogin SET safelock = '1' WHERE userid = ?";
      }
      if($logonattempts==2)
      {
        $action="Failed login (3)";
        $sql = "UPDATE ePetLogin SET logonattempts = 3 WHERE userid = ?"; 
      }
      if($logonattempts==1)
      {
        $action="Failed login (2)";
        $sql = "UPDATE ePetLogin SET logonattempts = 2 WHERE userid = ?"; 
      }
      if(is_null($logonattempts)||$logonattempts==0)
      {
        $action="Failed login (1)";
        $sql = "UPDATE ePetLogin SET logonattempts = '1' WHERE userid = ?";   
      }
      $stmt = sqlsrv_query($conn, $sql, array($userid));
      if( $stmt === false )  
      {  
        echo "Error here\n";  
        die( print_r( sqlsrv_errors(), true));  
      }
      
      $sql = "INSERT INTO ePetAudit (userid, action, actiondatetime) VALUES (?, ?, ?);";
      $stmt = sqlsrv_query($conn, $sql,array($userid,$action,$actiondate));
      if( $stmt === false )  
      {  
        echo "Error here 2\n";  
        die( print_r( sqlsrv_errors(), true));  
      }
    }  
    // Incorrect login or password
    die(header("Location: /epetition/login.php?email=$email&error=$action"));  //Send them back to login page. 
  }
}
elseif (isset($_SESSION['userid']))
{
    if(isset($_SESSION['key']))
    {
      header("Location: /epetition/approvals.php");
    }
}
else
{   // Attempting to come here without using the login form (from a favourite or copied the url)
    die(header("Location: /epetition/"));  //Should only be coming here with POST Request
}
?>