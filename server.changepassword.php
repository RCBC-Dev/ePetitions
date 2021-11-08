<?php
session_start(); 
if($_SERVER["REQUEST_METHOD"] == "POST")  // Check that we have got a POST request
{
  if(isset($_POST["password"])&& isset($_POST["userid"]) && isset($_POST["key"]))  // password has been received
  {           
    $userid=htmlspecialchars($_POST['userid']);
    $key = htmlspecialchars($_POST['key']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  //Hashed password
    
    include 'dbconnect.php';

    $sql = "select email, accountstatus from ePetLogin where userid=? and resetkey=?;"; 
    // Checking if this user already in the ePetLogin table
    $stmt = sqlsrv_query($conn, $sql, array($userid, $key));
    if( $stmt === false )  
    {  
      echo "Error in statement preparation execution.<br>";  
      die( print_r( sqlsrv_errors(), true));  
    }
    
    if( sqlsrv_fetch( $stmt ) === false){die(header("Location: /epetition/error.php"));}
    
    $email = sqlsrv_get_field( $stmt, 0); 
    $accountstatus = sqlsrv_get_field( $stmt, 1); 
    
    if (!$email)
    {
      die(header("Location: /epetition/"));  // No match - should not get here, hack attempt?
    }
    
    if($accountstatus=='Not Activated')
    {
      die(header("Location: /epetition/"));  // User already exists not activated - shouldn't get here either
    }
    
    if($accountstatus=="Disabled")
    {
      die(header("Location: /epetition/"));  // User account disabled - we can redirect to custom page if required
    }
    
    // User account is active and not disabled we can continue
      
    $actiondate=date("Y-m-d H:i:s");
    
    $sql = "UPDATE ePetLogin SET password = ?, resetkey=NULL, safelock=NULL, logonattempts=0 WHERE userid = ? AND resetkey = ?;";
    $stmt = sqlsrv_query($conn, $sql,array($password, $userid, $key));
    if( $stmt === false )  
    {  
      echo "Error in statement preparation execution.<br>";  
      die( print_r( sqlsrv_errors(), true));  
    }
    
    $sql = "INSERT INTO ePetAudit (userid, action, actiondatetime) VALUES (?, 'Reset Password', ?);";
    $stmt = sqlsrv_query($conn, $sql, array($userid,$actiondate));
    if( $stmt === false ){die(header("Location: /epetition/error.php"));}
    
    // Prepare to sent to email.php
    $_SESSION['email']=$email;
    $_SESSION['title']="Redcar and Cleveland - Password Reset";
    $_SESSION['content']="Your password was successfully reset on $actiondate.</a>";
    $_SESSION['success']="/epetition/successresetdone.php";  // Url to forward to if we send successfully
    header("Location: /epetition/server.sendemail.php"); // We set up required session variables and go to the sendemail page
  }
  else
  {
    // Something wasn't sent
    die(header("Location: /epetition/"));  //We can only access this page with a post request
  }
}
else
{
    die(header("Location: /epetition/"));  //We can only access this page with a post request
}
?>