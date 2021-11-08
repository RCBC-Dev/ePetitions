<?php
session_start(); 
if($_SERVER["REQUEST_METHOD"] == "POST")  // Check that we have got a POST request
{
  if(isset($_POST["email"]))  // and the email has been received from the form
  {           
    $email = htmlspecialchars($_POST["email"]);
    $emailraw = $_POST["email"];
    
    if(!filter_var($emailraw, FILTER_VALIDATE_EMAIL))
    {
        die(header("Location: /epetition/register.php?e=3&email=$email"));  // Return with error 3 (Not an email format)
    }
    
    if ($emailraw !== $email) 
    {
        die(header("Location: /epetition/register.php?e=1&email=$email"));  // Return with error 1 (special characters)
    }
    
    // We are happy we have a valid email address
    include 'dbconnect.php';

    $sql = "select userID, accountstatus from ePetLogin where email=?;"; 
    // Checking if this user already in the ePetLogin table
    $stmt = sqlsrv_query($conn, $sql, array($email));

    if( $stmt === false ){die(header("Location: /epetition/error.php"));}
    if( sqlsrv_fetch( $stmt ) === false){die(header("Location: /epetition/error.php"));}
    
    $existinguser = sqlsrv_get_field( $stmt, 0); 
    $accountstatus = sqlsrv_get_field( $stmt, 1); 
    
    if($existinguser>0 && $accountstatus=='Not Activated')
    {
      die(header("Location: /epetition/register.php?e=2&email=$email"));  // User already exists not activated
    }
    
    if($existinguser>0 && (is_null($accountstatus)==false))
    {
      die(header("Location: /epetition/register.php?e=4&email=$email"));  // User already exists activated
    }
    
    // User not already registered, we can continue
    
    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) 
        {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
  
    $randomKey = generateRandomString(10).date('siHdmy').generateRandomString(10); // Create an empty key
    $datecreated=date("Y-m-d H:i:s");
    
    $sql = "INSERT INTO ePetLogin (email, activatekey, accountcreated, accountstatus) VALUES (?, ?, ?,'Not Activated');";
    $stmt = sqlsrv_query($conn, $sql,array($email,$randomKey,$datecreated));
    if( $stmt === false ){die(header("Location: /epetition/error.php"));}
    
    $sql = "SELECT userid FROM ePetLogin where email = ?;";
    $stmt = sqlsrv_query($conn, $sql, array($email));
    if( $stmt === false ){die(header("Location: /epetition/error.php"));}
    if( sqlsrv_fetch( $stmt ) === false){die(header("Location: /epetition/error.php"));}
    
    $userid = sqlsrv_get_field( $stmt, 0);
    $signupdate=date("Y-m-d H:i:s");
    
    $sql = "INSERT INTO ePetAudit (userid, action, actiondatetime) VALUES (?, 'Signed up', ?);";
    $stmt = sqlsrv_query($conn, $sql, array($userid,$signupdate));
    if( $stmt === false ){die(header("Location: /epetition/error.php"));}
    
    // Prepare to sent to email.php
    $_SESSION['email']=$email;
    $_SESSION['title']="Redcar and Cleveland ePetitions Register";
    $_SESSION['content']="Your email address was recently used to register an account on ePetitions.redcar-cleveland.gov.uk.<br>If this was you please click this link to activate your account.<br><a href='https://shawn-dev.redclev.net/epetition/validate.php?user=$userid&key=$randomKey'>Please click this link to verify your email address</a>";
    $_SESSION['success']="/epetition/registered.php";  // Url to forward to if we send successfully
    header("Location: /epetition/server.sendemail.php"); // We set up required session variables and go to the sendemail page
  }
}
else
{
    die(header("Location: /epetition/"));  //We can only access this page with a post request
}
?>